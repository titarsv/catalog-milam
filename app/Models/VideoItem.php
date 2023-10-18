<?php

namespace App\Models;

use App;

class VideoItem extends Entity
{
	protected $fillable = [
		'link',
        'file_id',
		'collection_id'
	];

	protected $table = 'video_items';
	public $timestamps = false;

    protected static function boot() {
        parent::boot();

        self::deleted(function($model){
            $model->localization()->delete();
        });
    }

    public function image(){
        return $this->hasOne('App\Models\File', 'id', 'file_id');
    }

	// Связи
	public function gallery(){
		return $this->belongsTo('App\Models\Videos', 'collection_id');
	}

	public function localization(){
		return $this->morphMany('App\Models\Localization', 'localizable');
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

    public function getEmbedAttribute(){
        return 'http://www.youtube.com/watch?v='.$this->getVideoId();
    }

    public function getImageLinkAttribute(){
        return 'https://img.youtube.com/vi/'.$this->getVideoId().'/hqdefault.jpg';
    }

    private function getVideoId(){
        $str = $this->link;

        if(strpos($str, 'iframe')){
            $str = preg_replace('/.*src="([^"]*)".*/','$1', $str);
        }
        $str = str_replace([
            'https://youtu.be/',
            'https://www.youtube.com/embed/',
            'https://www.youtube.com/watch?'
        ], '', $str);
        if(strpos($str, '&')){
            foreach(explode('&', $str) as $param){
                $temp = explode('=', $param);
                if($temp[0] == 'v'){
                    $str = $temp[1];
                }
            }
        }elseif(strpos($str, '=')){
            $temp = explode('=', $str);
            if($temp[0] == 'v'){
                $str = $temp[1];
            }
        }

        return $str;
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
