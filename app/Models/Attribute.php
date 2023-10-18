<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use App;

class Attribute extends Entity
{
	protected $fillable = [
		'slug'
	];

	use SoftDeletes;
	protected $dates = ['deleted_at'];

    public $entity_type = 'attribute';
	protected $table = 'attributes';

	// Связи
	public function values(){
		return $this->hasMany('App\Models\AttributeValue', 'attribute_id');
	}

	public function localization(){
		return $this->morphMany('App\Models\Localization', 'localizable');
	}

	// Локализация
	protected static function boot() {
		parent::boot();

		self::deleted(function($model){
			$model->localization()->delete();
		});
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
		return $this->localize(App::getLocale(), $name);
	}

	public function getNameAttribute(){
		return $this->getAttributeByName('name');
	}

    protected function dataMap(){
        return [
            'attributes' => [
                'id' => '',
                'slug' => ''
            ],
            'relations' => [
                'localization' => [
                    'attributes' => [
                        'id' => '',
                        'field' => '',
                        'language' => '',
                        'value' => ''
                    ]
                ],
                'values' => [
                    'attributes' => [
                        'id' => '',
                        'attribute_id' => '',
                        'value' => '',
                        'file_id' => ''
                    ],
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
                ]
            ]
        ];
    }

    public function fieldsNames(){
        return [
            'relations.localization' => [
                'localization' => true,
                'fields' => [
                    [
                        'name' => 'Название',
                        'field' => 'name'
                    ]
                ]
            ],
            'attributes.slug' => [
                'name' => 'Слаг'
            ],
            'relations.values' => [
                'name' => 'Значение',
                'multiple' => true,
                'fields' => [
                    'relations.localization' => [
                        'localization' => true,
                        'fields' => [
                            [
                                'name' => 'Название',
                                'field' => 'name'
                            ]
                        ]
                    ],
                    'attributes.value' => [
                        'name' => 'Значение'
                    ],
                    'attributes.file_id' => [
                        'name' => 'Изображение',
                        'type' => 'file'
                    ]
                ]
            ]
        ];
    }
}
