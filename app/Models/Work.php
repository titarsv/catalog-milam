<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Config;
use App;

class Work extends Entity
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $table = 'works';

    public $fillable = [
        'file_id',
        'confirmed',
        'visible',
        'rating',
        'review_date'
    ];

	public function image(){
		return $this->hasOne('App\Models\File', 'id', 'file_id');
	}

    public function galleries(){
        return $this->morphMany('App\Models\Gallery', 'parent');
    }

    public function gallery(){
        return $this->morphMany('App\Models\Gallery', 'parent')->where('field', 'gallery');
    }

    public function link(){
        return $this->seo->link;
    }

    public function seo(){
        return $this->morphOne('App\Models\Seo', 'seotable');
    }

	public function localization(){
		return $this->morphMany('App\Models\Localization', 'localizable');
	}

    public function saveSeo($request){
        $seo_data = $request->only(['canonical', 'robots']);
        if(!empty($request->url)){
            $seo_data['url'] = $request->url;
        }else{
            $name_key = 'name_'.Config::get('app.locale');
            $seo_data['url'] = '/'.mb_strtolower(translit($request->$name_key));
        }
        $seo_data['action'] = 'showAction';

        $seo = $this->seo;
        if(empty($seo)){
            $this->seo()->create($seo_data);
            $seo = $this->seo()->first();
        }else{
            $seo->fill($seo_data)->save();
        }

        $seo->saveLocalization($request);
    }

	public function saveLocalization($request){
		$localization = new Localization();
		$localization->saveLocalization($request, $this, localizationFields(['name', 'description', 'result', 'customer', 'review', 'answer']));
	}

	public function localize($language, $field){
        $localization = $this->localization->first(function ($value, $key) use ($language, $field){
            return $value->language == $language && $value->field == $field;
        });
        if(empty($localization))
            $localization = $this->localization()->where(['language' => $language, 'field' => $field])->first();

		if(empty($localization)) {
			return '';
		}else{
			return $localization->value;
		}
	}

    public function saveGalleries($request){
        $gallery = new Gallery();
        $gallery->saveGalleries($request, $this, ['gallery']);
    }

    public function getReviewDateAttribute($attr){
        return Carbon::parse($attr);
    }

	private function getAttributeByName($name){
		$localization = $this->localization->where('language', App::getLocale())->where('field', $name)->first();
		if(empty($localization)){
			return '';
		}else{
			return $localization->value;
		}
	}

	public function getNameAttribute(){
		return $this->getAttributeByName('name');
	}

	public function getDescriptionAttribute(){
		return $this->getAttributeByName('description');
	}

    public function getResultAttribute(){
        return $this->getAttributeByName('result');
    }

    public function getCustomerAttribute(){
        return $this->getAttributeByName('customer');
    }

    public function getReviewAttribute(){
        return $this->getAttributeByName('review');
    }

    public function getAnswerAttribute(){
        return $this->getAttributeByName('answer');
    }

    public function getExcerptAttribute(){
        return Str::words(strip_tags(htmlspecialchars_decode($this->getBodyAttribute())), 25);
    }

	public function getMetaTitleAttribute(){
		return $this->getAttributeByName('meta_title');
	}

	public function getMetaDescriptionAttribute(){
		return $this->getAttributeByName('meta_description');
	}

    /**
     * Рекомендуемые работы
     *
     * @return mixed
     */
    public function recommended(){
        return $this->where('visible', true)
            ->where('id', '!=', $this->id)
            ->inRandomOrder()
            ->take(4);
    }
}