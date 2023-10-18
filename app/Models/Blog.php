<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Date\DateFormat;
use Config;
use App;

class Blog extends Entity
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $table = 'blog';

    public $fillable = [
        'user_id',
        'status',
        'image_id'
    ];

    public function getCreatedAtAttribute($attr){
        return DateFormat::post($attr);
    }

    public function getUpdatedAtAttribute($attr){
        return DateFormat::post($attr);
    }

    public function user(){
        return $this->belongsTo('App\Models\User');
    }

	public function image(){
		return $this->hasOne('App\Models\File', 'id', 'image_id');
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
		$localization->saveLocalization($request, $this, localizationFields(['name', 'body']));
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

	public function getBodyAttribute(){
		return $this->getAttributeByName('body');
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
     * Получение случайного поста
     * @param $exclusion
     * @return mixed
     */
    public function recommended(){
        return $this->where('status', true)
            ->where('id', '!=', $this->id)
            ->inRandomOrder()
            ->first();
    }

    public function next(){
        return $this->where('status', true)
            ->where('id', '>', $this->id)
            ->first();
    }

    public function prev(){
        return $this->where('status', true)
            ->where('id', '<', $this->id)
            ->first();
    }
}