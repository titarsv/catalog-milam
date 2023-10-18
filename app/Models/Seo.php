<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Config;
use App;

class Seo extends Entity
{
	use SoftDeletes;

	public $entity_type = 'seo';
	protected $table = 'seo';
	protected $page = 1;

	protected $fillable = [
		'canonical',
		'robots',
		'url',
		'seotable_id',
		'seotable_type',
		'action'
	];

	protected $dates = ['deleted_at'];

	public static function boot(){
		parent::boot();

		self::creating(function($model){

		});

		self::created(function($model){

		});

		self::updating(function($model){
			if($model->original['url'] != $model->attributes['url']){
				$redirects = new Redirect();
				$redirects->where('new_url', $model->original['url'])->where('old_url', '!=', $model->attributes['url'])->update(['new_url' => $model->attributes['url']]);
				$redirects->where('new_url', $model->original['url'])->where('old_url', $model->attributes['url'])->delete();
				$redirects->fill(['old_url' => $model->original['url'], 'new_url' => $model->attributes['url']])->save();
			}
		});

		self::updated(function($model){

		});

		self::deleting(function($model){

		});

		self::deleted(function($model){

		});
	}

	public function seotable(){
		return $this->morphTo();
	}

	public function localization(){
		return $this->morphMany('App\Models\Localization', 'localizable');
	}

	public function saveLocalization($request){
		$localization = new Localization();
		$localization->saveLocalization($request, $this, localizationFields(['seo_name', 'meta_title', 'seo_description', 'meta_description','meta_keywords']));
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
        if(!empty($this->attributes['name'])){
            return $this->attributes['name'];
        }

        $name = $this->getAttributeByName('seo_name');

        if(empty($name) && !empty($this->seotable_type) && !empty($this->seotable))
            $name = $this->seotable->name;

		return $name;
	}

	public function getDescriptionAttribute(){
		$description = $this->getAttributeByName('seo_description');

		if(\Request::segment( 1 ) == 'admin'){
			$value = $description;
			$value = preg_replace('/<source.*?data-src="(.*?)" type="image\/(.*?)" \/>/s', '<source srcset="$1" type="image/$2" />', $value);
			return $value;
		}

		$page = $this->getCurrentPage();
		if($page > 1 || ($this->url != '/' && $this->url != preg_replace( '/\/page-\d+$/', '', '/' . \Request::path()) && '/'.App::getLocale().$this->url != preg_replace( '/\/page-\d+$/', '', '/' . \Request::path()))){
			return '';
		}

		return str_replace('editor-image', '', $description);
	}

	public function getMetaTitleAttribute(){
	    if(!empty($this->attributes['meta_title'])){
	        return $this->attributes['meta_title'];
        }

		$title = $this->getAttributeByName('meta_title');

		if(empty($title)){
            if($this->seotable_type == 'Products'){
                $settings = new Setting();
                $template = $settings->get_setting('products_meta_title_'.app()->getLocale());
                if(!empty($template)){
                    $title = $template;
                    foreach(['product_name', 'product_brand', 'product_color', 'product_category'] as $key){
                        if(strpos($title, '['.$key.']') !== false){
                            $val = '';
                            if($key == 'product_name'){
                                $val = $this->seotable->name;
                            }elseif($key == 'product_brand' && !empty($brand = $this->seotable->brand)){
                                $val = $brand->name;
                            }elseif($key == 'product_color' && !empty($color = $this->seotable->color)){
                                $val = $color->name;
                            }elseif($key == 'product_category' && !empty($category = $this->seotable->category)){
                                $val = $category->name;
                            }
                            $title = str_replace('['.$key.']', $val, $title);
                        }
                    }
                }else{
                    $title = !empty($this->name) ? $this->name : $this->seotable->name;
                }
            }elseif($this->seotable_type == 'Categories'){
                $settings = new Setting();
                $template = $settings->get_setting('categories_meta_title_'.app()->getLocale());
                if(!empty($template)){
                    $title = $template;
                    foreach(['category_name', 'parent_category_name'] as $key){
                        if(strpos($title, '['.$key.']') !== false){
                            $val = '';
                            if($key == 'category_name'){
                                $val = $this->name;
                            }elseif($key == 'parent_category_name' && !empty($parent = $this->seotable->parent)){
                                $val = $parent->name;
                            }
                            $title = str_replace('['.$key.']', $val, $title);
                        }
                    }
                }else{
                    $title = !empty($this->name) ? $this->name : $this->seotable->name;
                }
            }elseif(!empty($this->seotable_type) && !empty($this->seotable))
                $title = $this->seotable->name;
        }

		return $title;
	}

	public function getMetaDescriptionAttribute(){
        if(!empty($this->attributes['meta_description'])){
            return $this->attributes['meta_description'];
        }

        $description = $this->getAttributeByName('meta_description');

        if(empty($description)){
            if($this->seotable_type == 'Products'){
                $settings = new Setting();
                $template = $settings->get_setting('products_meta_title_'.app()->getLocale());
                if(!empty($template)){
                    $description = $template;
                    foreach(['product_name', 'product_brand', 'product_color', 'product_category'] as $key){
                        if(strpos($description, '['.$key.']') !== false){
                            $val = '';
                            if($key == 'product_name'){
                                $val = $this->seotable->name;
                            }elseif($key == 'product_brand' && !empty($brand = $this->seotable->brand)){
                                $val = $brand->name;
                            }elseif($key == 'product_color' && !empty($color = $this->seotable->color)){
                                $val = $color->name;
                            }elseif($key == 'product_category' && !empty($category = $this->seotable->category)){
                                $val = $category->name;
                            }
                            $description = str_replace('['.$key.']', $val, $description);
                        }
                    }
                }else{
                    $description = !empty($this->name) ? $this->name : $this->seotable->name;
                }
            }elseif($this->seotable_type == 'Categories'){
                $settings = new Setting();
                $template = $settings->get_setting('categories_meta_title_'.app()->getLocale());
                if(!empty($template)){
                    $description = $template;
                    foreach(['category_name', 'parent_category_name'] as $key){
                        if(strpos($description, '['.$key.']') !== false){
                            $val = '';
                            if($key == 'category_name'){
                                $val = $this->name;
                            }elseif($key == 'parent_category_name' && !empty($parent = $this->seotable->parent)){
                                $val = $parent->name;
                            }
                            $description = str_replace('['.$key.']', $val, $description);
                        }
                    }
                }else{
                    $description = !empty($this->name) ? $this->name : $this->seotable->name;
                }
            }elseif(!empty($this->seotable_id)){
                $description = $this->seotable->name;
            }
        }

		return $description;
	}

    public function getMetaKeywordsAttribute(){
        if(!empty($this->attributes['meta_keywords'])){
            return $this->attributes['meta_keywords'];
        }

        $keywords = $this->getAttributeByName('meta_keywords');

        if(empty($keywords)){
            if($this->seotable_type == 'Products'){
                $settings = new Setting();
                $template = $settings->get_setting('products_meta_title_'.app()->getLocale());
                if(!empty($template)){
                    $keywords = $template;
                    foreach(['product_name', 'product_brand', 'product_color', 'product_category'] as $key){
                        if(strpos($keywords, '['.$key.']') !== false){
                            $val = '';
                            if($key == 'product_name'){
                                $val = $this->seotable->name;
                            }elseif($key == 'product_brand' && !empty($brand = $this->seotable->brand)){
                                $val = $brand->name;
                            }elseif($key == 'product_color' && !empty($color = $this->seotable->color)){
                                $val = $color->name;
                            }elseif($key == 'product_category' && !empty($category = $this->seotable->category)){
                                $val = $category->name;
                            }
                            $keywords = str_replace('['.$key.']', $val, $keywords);
                        }
                    }
                }
            }elseif($this->seotable_type == 'Categories'){
                $settings = new Setting();
                $template = $settings->get_setting('categories_meta_title_'.app()->getLocale());
                if(!empty($template)){
                    $keywords = $template;
                    foreach(['category_name', 'parent_category_name'] as $key){
                        if(strpos($keywords, '['.$key.']') !== false){
                            $val = '';
                            if($key == 'category_name'){
                                $val = $this->name;
                            }elseif($key == 'parent_category_name' && !empty($parent = $this->seotable->parent)){
                                $val = $parent->name;
                            }
                            $keywords = str_replace('['.$key.']', $val, $keywords);
                        }
                    }
                }
            }elseif(!empty($this->seotable_id)){
                $keywords = '';
            }
        }

        return $keywords;
    }

	public function getLinkAttribute(){
		return env('APP_URL').(App::getLocale() != 'ua' ? '/'.App::getLocale() : '').$this->url;
	}

    private function getCurrentPage(){
        if($this->page === null){
            $path = \Request::path();
            $page = (int)preg_replace('/.+\/page-(\d+)/', '$1', $path);
            if(empty($page)){
                $this->page = 1;
            }else{
                $this->page = (int)$page;
            }
        }

        return $this->page;
    }

	public function setUrlAttribute($value){
		if(!isset($this->original['url']) || $this->original['url'] != $value){
			$value                  = preg_replace( '/[^a-zA-Z0-9\-_\/]/', '', str_replace(' ', '-', $value));
			$item_with_this_url     = $this->where( 'url', $value )->first();
			$redirect_with_this_url = Redirect::where( 'old_url', $value )->first();
			if(!empty( $item_with_this_url) || !empty($redirect_with_this_url)){
				if($this->seotable_type == 'Categories' && ! empty($parent_service = $this->seotable->parent()->first()) && !empty($parent_seo = $parent_service->seo)){
					$parent_parts                 = explode( '/', $parent_seo->url );
					$parts                        = explode( '/', $value );
					$parts[ count( $parts ) - 1 ] = $parent_parts[ count( $parent_parts ) - 1 ] . '-' . $parts[ count( $parts ) - 1 ];
					$value                        = implode( '/', $parts );
				}
				$value = $this->getUniqueUrl( $value );
			}

			$this->attributes['url'] = '/' . trim( $value, '/' );
		}

		$this->attributes['url'] = $value;
	}

	protected function getUniqueUrl($value){
		$redirects = new Redirect();
		$item_with_this_url = $this->where('url', $value)->first();
		$redirect_with_this_url = $redirects->where('old_url', $value)->first();
		if(!empty($item_with_this_url) || !empty($redirect_with_this_url)){
			for($i=2;!empty($item_with_this_url) || !empty($redirect_with_this_url);$i++){
				$item_with_this_url = $this->where('url', $value.'-'.$i)->first();
				$redirect_with_this_url = $redirects->where('old_url', $value.'-'.$i)->first();
			}
			$i--;
		}else{
			return $value;
		}

		return $value.'-'.$i;
	}

    protected function dataMap(){
        return [
            'attributes' => [
                'id' => '',
                'canonical' => '',
                'robots' => '',
                'url' => '',
                'seotable_id' => '',
                'seotable_type' => '',
                'action' => ''
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
        ];
    }

    public function fieldsNames(){
        return [
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
            ],
            'attributes.seotable_id' => [
                'name' => 'Тип страницы'
            ],
            'attributes.seotable_type' => [
                'name' => 'ID записи'
            ],
            'attributes.action' => [
                'name' => 'Метод отображения'
            ]
        ];
    }
}
