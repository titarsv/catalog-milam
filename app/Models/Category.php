<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Builder;
use Config;
use App;

class Category extends Entity
{
    use SoftDeletes;

    protected $fillable = [
    	'id',
    	'slug',
        'file_id',
        'parent_id',
        'sort_order',
        'status'
    ];

    protected $dates = ['deleted_at'];

    public $entity_type = 'category';
    protected $table = 'categories';

    // Автоматизация
	protected static function boot() {
		parent::boot();

		static::addGlobalScope('order', function (Builder $builder) {
			$builder->orderBy('sort_order', 'asc');
		});

		self::deleted(function($model){
			$model->localization()->delete();
		});
	}

	// Связи
	public function seo(){
		return $this->morphOne('App\Models\Seo', 'seotable');
	}

    public function image(){
        return $this->hasOne('App\Models\File', 'id', 'file_id');
    }

    public function products(){
        return $this->belongsToMany('App\Models\Product', 'product_categories', 'category_id', 'product_id');
    }

    public function attributes(){
        return $this->belongsToMany('App\Models\Attribute', 'category_attributes', 'category_id', 'attribute_id');
    }

    public function children(){
        return $this->hasMany('App\Models\Category', 'parent_id', 'id')->with('children');
    }

	public function parent(){
		return $this->belongsTo('App\Models\Category', 'parent_id');
	}

    public function galleries(){
        return $this->morphMany('App\Models\Gallery', 'parent');
    }

    public function gallery(){
        return $this->morphMany('App\Models\Gallery', 'parent')->where('field', 'gallery');
    }

	public function localization(){
		return $this->morphMany('App\Models\Localization', 'localizable');
	}

    public function saveGalleries($request){
        $gallery = new Gallery();
        $gallery->saveGalleries($request, $this, ['gallery']);
    }

	// Локализация
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

	// Сео
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

	public function link(){
		return $this->seo->getLinkAttribute();
	}

    public function get_products_count($category_id, $filter, $price = [], $limit = 0){
        $hash = md5($category_id.serialize($filter).serialize($price));

        $count = Cache::remember($hash, 480, function () use (&$category_id, $filter, $price, $limit) {
            $products = Product::select('products.*');

            $products->where('stock', 1);

            if($category_id !== null) {
                $categories = [];
                if(is_array($category_id)){
                    foreach ($category_id as $id){
                        $categories = array_merge($categories, [$id], $this->get_children_categories($id));
                    }
                }else
                    $categories = array_merge([$category_id], $this->get_children_categories($category_id));
                $products->join('product_categories AS cat', 'products.id', '=', 'cat.product_id');
                $products->whereIn('cat.category_id', $categories);
            }

            if (!empty($filter)) {

                foreach ($filter as $key => $attribute) {

                    $products->join('product_attributes AS attr' . $key, 'products.id', '=', 'attr' . $key . '.product_id');
                    $products->where('attr' . $key . '.attribute_id', $key);
                    $products->where(function($query) use($attribute, $key){

                        foreach ($attribute as $attribute_value) {
                            $query->orWhere('attr' . $key . '.attribute_value_id', $attribute_value);
                        }
                    });

                }
            }

            if(!empty($price)){
                $products->whereBetween('products.price', $price);
            }

            $products->groupBy('products.id');

            if(!empty($limit)){
                $products->limit($limit);
            }

            return $products->count();
        });

        return $count;
    }

    public function get_children_categories($cat_id){

        if(isset($this->{'children_categories_'.$cat_id})){
            $categories = $this->{'children_categories_'.$cat_id};
        }else{
            $categories = Cache::remember('children_categories_'.$cat_id, 60, function () use (&$cat_id) {
                $children_categories = $this->select('id')->where('parent_id', $cat_id)->get()->toArray();
                $categories = [];
                if(count($children_categories)) {
                    foreach ($children_categories as $cat) {
                        $categories[] = $cat['id'];
                        $categories = array_merge ($categories, $this->get_children_categories($cat['id']));
                    }
                }
                return $categories;
            });

            $this->{'children_categories_'.$cat_id} = $categories;
        }

        return $categories;
    }

    /**
     * Минимальная стоимость товара в категории
     *
     * @param $category_id
     * @return int
     */
    public function min_price($category_id){

        if(isset($this->{'min_price_'.$category_id})){
            $price = $this->{'min_price_'.$category_id};
        }else{
            $price = Cache::remember('min_price_'.$category_id, 60, function () use (&$category_id) {
                $product = Product::select('products.price');
                $categories = array_merge([$category_id], $this->get_children_categories($category_id));
                $product->join('product_categories AS cat', 'products.id', '=', 'cat.product_id');
                $product->whereIn('cat.category_id', $categories)->where('products.visible', 1);
                $result = $product->orderBy('products.price', 'asc')
                    ->first();

                if(is_null($result)){
                    return 0;
                }else{
                    return $result->price;
                }
            });

            $this->{'min_price_'.$category_id} = $price;
        }

        return empty($price) ? 0 : $price;
    }

    /**
     * Максимальная стоимость товара в категории
     *
     * @param $category_id
     * @return int
     */
    public function max_price($category_id){

        if(isset($this->{'max_price_'.$category_id})){
            $price = $this->{'max_price_'.$category_id};
        }else{
            $price = Cache::remember('max_price_'.$category_id, 60, function () use (&$category_id) {
                $product = Product::select('products.price');
                $categories = array_merge([$category_id], $this->get_children_categories($category_id));
                $product->join('product_categories AS cat', 'products.id', '=', 'cat.product_id');
                $product->whereIn('cat.category_id', $categories)->where('products.visible', 1);
                $result = $product->orderBy('products.price', 'desc')
                    ->first();

                if(is_null($result)){
                    return 0;
                }else{
                    return $result->price;
                }
            });

            $this->{'max_price_'.$category_id} = $price;
        }

        return $price;
    }

    /**
     * Корневые категории
     *
     * @param int $id
     * @return mixed
     */
    public function get_root_categories($id = 2){
	    $locale = App::getLocale();
        $categories = $this->where('parent_id', $id)
            ->where('status', 1)
            ->orderBy('sort_order', 'ASC')
	        ->with(['children' => function($query) use ($locale){
		        $query->select('id', 'parent_id', 'file_id', 'slug')
                    ->where('status', 1)
                    ->with(['localization' => function($query) use($locale){
			        $query->select(['field', 'language', 'value', 'localizable_type', 'localizable_id'])->where('language', $locale);
		        }])
		        ->with('image')
		        ->with(['children' => function($query) use ($locale){
			        $query->select('id', 'parent_id', 'file_id', 'slug')
                        ->where('status', 1)
                        ->with(['localization' => function($query) use($locale){
				        $query->select(['field', 'language', 'value', 'localizable_type', 'localizable_id'])->where('language', $locale);
			        }])
	                ->with('image');
		        }])
		        ->withCount('children');
	        }])
	        ->with(['localization' => function($query) use($locale){
		        $query->select(['field', 'language', 'value', 'localizable_type', 'localizable_id'])->where('language', $locale);
	        }])
	        ->withCount('children')
            ->get();

        return $categories;
    }
    
    /**
     * Дочерние категории
     * @param int $parent_id
     * @return mixed
     */
    public function get_children($parent_id = 0){

        $children = Cache::remember('children_categories_objects_'.$parent_id, 1440, function () use (&$parent_id) {
            if(empty($parent_id))
                $parent_id = $this->id;
            $children = $this->where('parent_id', $parent_id)
                ->orderBy('name', 'DESC')
                ->get();
            return $children;
        });

        return $children;
    }

    /**
     * Наличие дочерних категорий
     * @return bool
     */
    public function hasChildren(){
        if($this->where('parent_id', $this->id)->count()){
            return true;
        }else
            return false;
    }

    /**
     * Получение массива родительских категорий
     * @param string $category
     * @return array
     */
    public function get_parent_categories($category = ''){
        $categories = [];

        if(!empty($category)){
            if(is_int($category)){
                $category = $this->where('id', $category)->first();
            }elseif(is_string($category)){
                $category = $this->where('url_alias', $category)->first();
            }
        }else{
            $category = $this;
        }

        $categories[] = $category;
        if($category->parent_id > 0)
            $categories = array_merge ($categories, $this->get_parent_categories($category->parent_id));

        return $categories;
    }

	/**
	 * Получение корневой категории от текущей
	 * @return $this|mixed
	 */
    public function get_root_category(){
        $categories = $this->get_parent_categories($this->id);

        if(count($categories) > 1)
            return $categories[count($categories) - 1];
        else
            return $this;
    }

	/**
	 * @return mixed
	 */
    public function all_categories_with_parent_name(){
        return $this->select('categories.*', 'p.name AS parent_name')
            ->leftJoin('categories AS p', 'categories.parent_id', '=', 'p.id')
            ->get();
    }

    static function getSelect($exclude = null){
	    $categories = [
		    (object)[
			    'name' => 'Не выбрано',
			    'id' => null
		    ]
	    ];
	    foreach(Category::when($exclude, function($query) use ($exclude){ $query->where('id', '!=', $exclude); })->get() as $c){
		    $categories[] = (object)[
			    'name' => $c->name,
			    'id' => $c->id
		    ];
	    }

	    return $categories;
    }

    public function getChildrenCategories($cat_id){
        if(isset($this->{'children_categories_'.$cat_id})){
            $categories = $this->{'children_categories_'.$cat_id};
        }else{
            $categories = Cache::remember('children_categories_'.$cat_id, 131040, function () use (&$cat_id) {
                $children_categories = $this->select('id')->where('parent_id', $cat_id)->with(['children' => function($query){
                    $query->select(['id', 'parent_id'])->with(['children' => function($query){
                        $query->select(['id', 'parent_id']);
                    }]);
                }])->get()->toArray();
                $categories = $this->flattenCategories($children_categories);
                return $categories;
            });

            $this->{'children_categories_'.$cat_id} = $categories;
        }

        return $categories;
    }

    private function flattenCategories($arr){
        $categories = [];
        foreach($arr as $cat){
            $categories[] = $cat['id'];
            if(!empty($cat['children'])){
                $categories = array_merge($categories, $this->flattenCategories($cat['children']));
            }
        }
        return $categories;
    }

	public function getParentCategories($cat_id){
		$category = $this->select('id', 'parent_id')->where('id', $cat_id)->with(['parent' => function($query){
			$query->select(['id', 'parent_id'])->with(['parent' => function($query){
				$query->select(['id', 'parent_id']);
			}]);
		}])->first();
        if(!empty($category)){
            $category = $category->toArray();
            $categories = $this->flattenParentCategories($category);
        }else{
            $categories = [];
        }
		return $categories;
	}

	private function flattenParentCategories($category){
		$categories = [$category['id']];
		if(!empty($category['parent'])){
			$categories = array_merge($categories, $this->flattenParentCategories($category['parent']));
		}
		return $categories;
	}

    /**
     * Список категорий со вложенностью
     *
     * @param null $exclude
     * @param bool $with_empty
     * @return array
     */
	public function getTreeList($exclude = null, $with_empty = true){
        $tree = $this->get_root_categories(null);
        $list = $this->getChildrenTreeList($tree, $exclude);

        if($with_empty){
            $list = array_merge([(object)['id' => '', 'name' => '-']], $list);
        }

        return $list;
    }

    private function getChildrenTreeList($tree, $exclude = null,  $parent_name = ''){
        $list = [];

        if($tree->count()){
            foreach($tree as $category){
                if($category->id != $exclude){
                    $name = $parent_name.$category->name;
                    $list[] = (object)[
                        'id' => $category->id,
                        'name' => $name
                    ];
                    if($category->children->count()){
                        $list = array_merge($list, $this->getChildrenTreeList($category->children, $exclude, $name.' > '));
                    }
                }
            }
        }

        return $list;
    }

    protected function dataMap(){
        return [
            'attributes' => [],
            'relations' => [
                'parent' => [
                    'attributes' => [
                        'id' => ''
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
                'image' => [
                    'attributes' => [
                        'id' => '',
                        'path' => ''
                    ]
                ],
                'attributes' => [
                    'attributes' => [
                        'id' => ''
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
                        'name' => 'Описание',
                        'field' => 'description'
                    ]
                ]
            ],
            'relations.parent' => [
                'name' => '',
                'multiple' => true,
                'fields' => [
                    'relations.localization' => [
                        'localization' => true,
                        'fields' => [
                            [
                                'name' => 'Родительская категория',
                                'field' => 'name'
                            ]
                        ]
                    ]
                ]
            ],
            'relations.image[].attributes.path' => [
                'name' => 'Изображение',
                'type' => 'file'
            ],
            'relations.attributes' => [
                'name' => 'Связанные атрибуты',
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
                    ]
                ]
            ],
            'attributes.sort_order' => [
                'name' => 'Порядок сортировки'
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
