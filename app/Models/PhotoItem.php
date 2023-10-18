<?php

namespace App\Models;

use App;

class PhotoItem extends Entity
{
	protected $fillable = [
		'file_id',
		'collection_id'
	];

	protected $table = 'photo_items';
	public $timestamps = false;

    protected static function boot() {
        parent::boot();

        self::deleted(function($model){
            $model->localization()->delete();
        });
    }

	// Связи
	public function gallery(){
		return $this->belongsTo('App\Models\Photos', 'collection_id');
	}

	public function localization(){
		return $this->morphMany('App\Models\Localization', 'localizable');
	}

    public function image(){
        return $this->hasOne('App\Models\File', 'id', 'file_id');
    }

	// Локализация
	public function saveLocalization($request){
		$localization = new Localization();
		$localization->saveLocalization($request, $this, localizationFields(['name', 'description']));
	}

	public function localize($language, $field){
        $localization = $this->localization->first(function ($value, $key) use ($language, $field){
            return $value->language == $language && $value->field == $field;
        });
        if(empty($localization))
            $localization = $this->localization()->where(['language' => $language, 'field' => $field])->first();

		if(empty($localization)){
			return '';
		}else{
			return $localization->value;
		}
	}

	private function getAttributeByName($name){
		return $this->localize(App::getLocale(), $name);
	}

	public function getNameAttribute(){
		return $this->getAttributeByName('name');
	}

    public function getDescriptionAttribute(){
        return $this->getAttributeByName('description');
    }

    protected function dataMap(){
        return [
            'attributes' => [],
            'relations' => [
                'attribute' => [
                    'attributes' => [],
                    'relations' => [
                        'localization' => [
                            'attributes' => [
                                'id' => '',
                                'field' => '',
                                'language' => '',
                                'value' => ''
                            ]
                        ]
                    ]
                ],
                'localization' => [
                    'attributes' => [
                        'id' => '',
                        'field' => '',
                        'language' => '',
                        'value' => ''
                    ]
                ]
            ]
        ];
    }
}
