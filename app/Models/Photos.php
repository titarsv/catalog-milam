<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use Config;
use App;

class Photos extends Entity
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $table = 'photos';

    public $fillable = [
        'file_id',
        'count',
        'visible'
    ];

    public function getCreatedAtAttribute($attr){
        return Carbon::parse($attr);
    }

	public function image(){
		return $this->hasOne('App\Models\File', 'id', 'file_id');
	}

	public function photos(){
        return $this->hasMany('App\Models\PhotoItem', 'collection_id', 'id');
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
		$localization->saveLocalization($request, $this, localizationFields(['name']));
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
}