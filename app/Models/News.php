<?php

namespace App\Models;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Config;
use App;

class News extends Entity
{
	use SoftDeletes;

	protected $dates = ['deleted_at'];

    public $entity_type = 'news';
	protected $table = 'news';

	public $fillable = [
		'user_id',
		'published',
		'file_id'
	];

	public function getCreatedAtAttribute($attr){
//		return DateFormat::post($attr);
		return date('d.m.Y', strtotime($attr));
	}

	public function getUpdatedAtAttribute($attr){
//		return DateFormat::post($attr);
		return date('d.m.Y', strtotime($attr));
	}

	public function user(){
		return $this->belongsTo('App\Models\User');
	}

	public function image(){
		return $this->hasOne('App\Models\File', 'id', 'file_id');
	}

	public function link(){
		return $this->seo->link;
	}

    public function products(){
        return $this->belongsToMany('App\Models\Product', 'news_products', 'news_id', 'product_id');
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

    public function getSmallExcerptAttribute(){
        return Str::words(strip_tags(htmlspecialchars_decode($this->getBodyAttribute())), 10);
    }

	public function getMetaTitleAttribute(){
		return $this->getAttributeByName('meta_title');
	}

	public function getMetaDescriptionAttribute(){
		return $this->getAttributeByName('meta_description');
	}

	public function last(){
		return $this->where('published', true)
		            ->where('id', '!=', $this->id)
		            ->orderBy('created_at', 'desc')
		            ->take(4)
					->get();
	}

	/**
	 * Получение случайного поста
	 *
	 * @return mixed
	 */
	public function recommended(){
		return $this->where('published', true)
		            ->where('id', '!=', $this->id)
		            ->inRandomOrder()
		            ->first();
	}

	public function next(){
		return $this->where('published', true)
		            ->where('id', '>', $this->id)
		            ->first();
	}

	public function prev(){
		return $this->where('published', true)
		            ->where('id', '<', $this->id)
		            ->first();
	}

    public function productsList($current_page = 1){
        $limit = 20;
        $products = new LengthAwarePaginator(
            $this->products()->select(['products.id', 'products.sku', 'products.stock', 'products.original_price', 'products.file_id', 'localization.value as name'])
                ->leftJoin('localization', function($leftJoin) {
                    $leftJoin->on('products.id', '=', 'localization.localizable_id')
                        ->where('localization.localizable_type', '=', 'Products')
                        ->where('localization.language', '=', config('locale'))
                        ->where('field', 'name');
                })
                ->with(['attributes.info', 'attributes.value'])
                ->limit($limit)
                ->offset($limit * ($current_page - 1))
                ->get(),
            $this->products()->count(),
            $limit,
            $current_page,
            [
                'path' => url('/admin/news/edit/'.$this->id)
            ]
        );

        return $products;
    }

    protected function dataMap(){
        return [
            'attributes' => [
                'id' => '',
                'user_id' => '',
                'published' => '',
                'file_id' => ''
            ],
            'relations' => [
                'image' => [
                    'attributes' => [
                        'id' => '',
                        'path' => ''
                    ]
                ],
                'seo' => [
                    'attributes' => [
                        'id' => '',
                        'canonical' => '',
                        'robots' => '',
                        'url' => ''
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

    public function fieldsNames(){
        return [
            'relations.localization' => [
                'localization' => true,
                'fields' => [
                    [
                        'name' => 'Название',
                        'field' => 'name'
                    ],
                    [
                        'name' => 'Текст статьи',
                        'field' => 'body'
                    ]
                ]
            ],
            'relations.image[].attributes.path' => [
                'name' => 'Изображение',
                'type' => 'file'
            ],
            'attributes.status' => [
                'name' => 'Статус'
            ],
            'relations.seo' => [
                'name' => 'SEO',
                'multiple' => true,
                'fields' => [
                    'attributes.url' => [
                        'name' => 'Url'
                    ],
                    'relations.localization' => [
                        'localization' => true,
                        'fields' => [
                            [
                                'name' => 'Название',
                                'field' => 'seo_name'
                            ],
                            [
                                'name' => 'Описание',
                                'field' => 'seo_description'
                            ],
                            [
                                'name' => 'Title',
                                'field' => 'meta_title'
                            ],
                            [
                                'name' => 'Meta description',
                                'field' => 'meta_description'
                            ],
                            [
                                'name' => 'Meta keywords',
                                'field' => 'meta_keywords'
                            ]
                        ]
                    ],
                    'attributes.canonical' => [
                        'name' => 'Canonical'
                    ],
                    'attributes.robots' => [
                        'name' => 'Robots'
                    ]
                ]
            ]
        ];
    }
}