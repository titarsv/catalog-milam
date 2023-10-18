<?php

namespace App\Models;

use Chelout\RelationshipEvents\Concerns\HasBelongsToManyEvents;
use Illuminate\Pagination\LengthAwarePaginator;
use Cartalyst\Sentinel\Native\Facades\Sentinel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Redis;
use Illuminate\Pagination\Paginator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Config;
use App;

class Product extends Entity
{
    use SoftDeletes;
    use HasBelongsToManyEvents;

    protected $fillable = [
        'external_id',
	    'name',
	    'sku',
	    'gtin',
        'price',
        'original_price',
        'sale_price',
	    'sale',
	    'sale_from',
	    'sale_to',
        'file_id',
        'stock',
        'visible',
        'sort_priority',
        'rating'
    ];

    public function getFillable(){
        return $this->fillable;
    }

    // Автоматизация сохранения в Redis
    public static function boot(){
        parent::boot();

        self::created(function($model){
            if(env('REDIS_CACHE')) {
                Redis::command('setbit', ["product_visible", $model->id, empty($model->attributes['visible']) ? 0 : 1]);
                Redis::command('setbit', ["product_stock", $model->id, $model->stock > 0 ? 1 : 0]);
                Redis::command('setbit', ["product_not_stock", $model->id, $model->stock === 0 ? 1 : 0]);
                Redis::command('setbit', ["product_under_the_order", $model->id, $model->stock < 0 ? 1 : 0]);
                Redis::command('zadd', ['prices', $model->price * 100, $model->id]);
            }
        });

        self::updated(function($model){
            if(env('REDIS_CACHE')) {
                Redis::command('setbit', ["product_visible", $model->id, empty($model->attributes['visible']) ? 0 : 1]);
                Redis::command('setbit', ["product_stock", $model->id, $model->stock > 0 ? 1 : 0]);
                Redis::command('setbit', ["product_not_stock", $model->id, $model->stock === 0 ? 1 : 0]);
                Redis::command('setbit', ["product_under_the_order", $model->id, $model->stock < 0 ? 1 : 0]);
                Redis::command('zadd', ['prices', $model->price * 100, $model->id]);
            }
        });

        self::deleted(function($model){
            if(env('REDIS_CACHE')) {
                Redis::command('setbit', ["product_visible", $model->id, 0]);
                Redis::command('setbit', ["product_stock", $model->id, 0]);
                Redis::command('setbit', ["product_not_stock", $model->id, 0]);
                Redis::command('setbit', ["product_under_the_order", $model->id, 0]);
                Redis::command('zrem', ['prices', $model->id]);
            }
        });

        static::belongsToManyAttaching(function ($parent, $product, $related) {
            if(env('REDIS_CACHE')) {
                $product_id = $product->id;
                if ($parent == 'categories') {
                    $category = new Category();
                    foreach ($related as $category_id) {
                        $ids = $category->getParentCategories($category_id);
                        foreach ($ids as $id) {
                            if (!empty($id))
                                Redis::command('setbit', ["category_$id", $product_id, 1]);
                        }
                    }
                } elseif ($parent == 'values') {
                    foreach ($related as $value_id) {
                        Redis::command('setbit', ["attribute_$value_id", $product_id, 1]);
                    }
                } elseif ($parent == 'sales') {
                    foreach ($related as $value_id) {
                        Redis::command('setbit', ["sale_$value_id", $product_id, 1]);
                    }
                }
            }
        });

        static::belongsToManyDetaching(function ($parent, $product, $related) {
            if(env('REDIS_CACHE')){
                $product_id = $product->id;
                if($parent == 'values'){
                    foreach ($related as $value_id){
                        Redis::command('setbit', ["attribute_$value_id", $product_id, 0]);
                    }
                }elseif($parent == 'sales'){
                    foreach($related as $value_id){
                        Redis::command('setbit', ["sale_$value_id", $product_id, 0]);
                    }
                }
            }
        });

        static::belongsToManyDetached(function ($parent, $product, $related) {
            if(env('REDIS_CACHE')) {
                $product_id = $product->id;
                if ($parent == 'categories') {
                    $exclude = [];
                    foreach ($product->categories as $category) {
                        $category_id = $category->id;
                        $exclude = array_merge($exclude, $category->getParentCategories($category_id));
                    }

                    $category = new Category();
                    foreach ($related as $category_id) {
                        $ids = $category->getParentCategories($category_id);
                        foreach ($ids as $id) {
                            if (!in_array($id, $exclude))
                                Redis::command('setbit', ["category_$id", $product_id, 0]);
                        }
                    }
                }
            }
        });
    }

    public $entity_type = 'product';
    protected $table = 'products';
    protected $dates = ['deleted_at'];

	// Связи
	public function seo(){
		return $this->morphOne('App\Models\Seo', 'seotable');
	}

    public function categories(){
        return $this->belongsToMany('App\Models\Category', 'product_categories', 'product_id', 'category_id');
    }

    public function sales(){
        return $this->belongsToMany('App\Models\Sale', 'sale_products', 'product_id', 'sale_id')->withPivot('sale_price');
    }

    public function image(){
        return $this->hasOne('App\Models\File', 'id', 'file_id');
    }

    public function attributes(){
        return $this->hasMany('App\Models\ProductAttributes', 'product_id');
    }

    public function getBrandAttribute(){
        if(empty($this->values)){
            $attr = $this->attributes()
                ->join('attributes', 'product_attributes.attribute_id', '=', 'attributes.id')
                ->where('attributes.id', 1)
                ->first();
            if(is_object($attr))
                return $attr->value;
        }else{
            foreach($this->values as $val){
                if($val->attribute_id == 1){
                    return $val;
                }
            }
        }

        return null;
    }

    public function getCapacityAttribute(){
        if(empty($this->values)){
            $attr = $this->attributes()
                ->join('attributes', 'product_attributes.attribute_id', '=', 'attributes.id')
                ->where('attributes.id', 3)
                ->first();
            if(is_object($attr))
                return $attr->value;
        }else{
            foreach($this->values as $val){
                if($val->attribute_id == 3){
                    return $val;
                }
            }
        }

        return null;
    }

    public function getPurposesAttribute(){
        if(empty($this->values)){
            $attrs = $this->attributes()
                ->join('attributes', 'product_attributes.attribute_id', '=', 'attributes.id')
                ->where('attributes.id', 4)
                ->get();
            if($attrs->count())
                return implode(', ', $attrs->pluck('name')->toArray());
        }else{
            $purposes = $this->values->where('attribute_id', 4);

            if(!empty($purposes)){
                return implode(', ', $purposes->pluck('name')->toArray());
            }

        }

        return null;
    }

    public function values(){
        return $this->belongsToMany('App\Models\AttributeValue', 'product_attributes', 'product_id', 'attribute_value_id')->withPivot('attribute_id');
    }

    // Данные вариаций
    public function variations_attributes(){
        $max_price = $this->price;
        $variations_attrs = [];
        $variations_prices = [];

        foreach($this->variations as $variation){
            $values = $variation->attribute_values;
            $variations_prices[implode('_', $values->pluck(['id'])->sort()->values()->all())] = [
                'price' => $variation->price,
                'id' => $variation->id
            ];
            if($max_price < $variation->price){
                $max_price = $variation->price;
            }
            foreach($values as $value){
                $attr = $value->attribute;
                if(!isset($variations_attrs[$attr->id])){
                    $variations_attrs[$attr->id] = [
                        'name' => $attr->name,
                        'values' => [
                            $value->id => ['name' => $value->name, 'stock' => $variation->stock, 'image' => $value->file_id ? $value->image->webp([17, 17], ['alt' => $this->name], 'static') : null]
                        ]
                    ];
                }elseif(!isset($variations_attrs[$attr->id]['values'][$value->id])){
                    $variations_attrs[$attr->id]['values'][$value->id] =  ['name' => $value->name, 'stock' => $variation->stock, 'image' => $value->file_id ? $value->image->webp([17, 17], ['alt' => $this->name], 'static') : null];
                }
            }
        }

        $selected_variation_attributes = explode('_', array_key_first($variations_prices));

        return [
            'variations_prices' => $variations_prices,
            'variations_attrs' => $variations_attrs,
            'selected_variation_attributes' => $selected_variation_attributes
        ];
    }

    // Вариации
    public function variations(){
        return $this->hasMany('App\Models\Variation', 'product_id');
    }

    // Отзывы
    public function reviews(){
        return $this->hasMany('App\Models\Review', 'product_id');
    }

    public function wishlist(){
        return $this->hasMany('App\Models\Wishlist', 'product_id');
    }

//    public function similar(){
//        return $this->belongsToMany('App\Models\Product', 'similar_products', 'product_id', 'similar_id');
//    }

    // Связанные товары
    public function related(){
        return $this->belongsToMany('App\Models\Product', 'related_products', 'product_id', 'related_id');
    }

    public function products_cart(){
        return $this->hasOne('App\Models\ProductsCart', 'product_id');
    }

    public function galleries(){
        return $this->morphMany('App\Models\Gallery', 'parent');
    }

    public function gallery(){
        return $this->morphMany('App\Models\Gallery', 'parent')->where('field', 'gallery')->orderBy('order');
    }

    public function documents(){
        return $this->morphMany('App\Models\Gallery', 'parent')->where('field', 'documents')->orderBy('order');
    }

	public function localization(){
		return $this->morphMany('App\Models\Localization', 'localizable');
	}

	public function saveGalleries($request){
	    $gallery = new Gallery();
	    $gallery->saveGalleries($request, $this, ['gallery', 'documents']);
    }

	// Локализация
	public function saveLocalization($request){
		$localization = new Localization();
		$localization->saveLocalization($request, $this, localizationFields(['name', 'description', 'instructions', 'security', 'compound', 'shelf_life', 'storage_conditions']));
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

	public function getDescriptionAttribute(){
		return $this->getAttributeByName('description');
	}

	public function getInstructionsAttribute(){
		return $this->getAttributeByName('instructions');
	}

    public function getSecurityAttribute(){
        return $this->getAttributeByName('security');
    }

    public function getCompoundAttribute(){
        return $this->getAttributeByName('compound');
    }

    public function getShelfLifeAttribute(){
        return $this->getAttributeByName('shelf_life');
    }

    public function getStorageConditionsAttribute(){
        return $this->getAttributeByName('storage_conditions');
    }

    public function getCategoryAttribute(){
	    $category = $this->categories()->where('categories.id', '!=', 1)->orderBy('parent_id', 'desc')->first();
	    if(empty($category)){
            $category = Category::find(1);
        }
        return $category;
    }

    public function getCategoryNameAttribute(){
        $name = '';
        if(isset($this->relations['categories'])){
            if($this->categories->count()){
                $id = 0;
                foreach($this->categories as $category){
                    if($category->id != 1 && $category->id > $id){
                        $name = $category->name;
                        $id = $category->id;
                    }
                }
            }
        }else{
            $category = $this->categories()->where('categories.id', '!=', 1)->orderBy('parent_id', 'asc')->first();
            if(!empty($category)){
                $name = $category->name;
            }
        }

        return $name;
    }

    public function getCategoryLinkAttribute(){
        $link = 'javascript:void(0)';
        $category = $this->categories()->orderBy('parent_id', 'asc')->first();
        if(!empty($category)){
            $link = $category->link();
        }

        return $link;
    }

    public function getLabelsAttribute(){
	    $labels = [];
        if(isset($this->relations['values'])){
            foreach($this->values as $label){
                $labels[] = [
                    'name' => $label->name,
                    'color' => $label->value
                ];
            }
        }else{
            foreach($this->values()->where('product_attributes.attribute_id', 1)->with('localization')->get() as $label){
                $labels[] = [
                    'name' => $label->name,
                    'color' => $label->value
                ];
            }
        }

        return $labels;
    }

    public function getOriginalNameAttribute(){
	    return $this->attributes['name'];
    }

    public function getGradeAttribute(){
	    return round($this->reviews->avg('grade'));
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

    public function link(){
        if(empty($this->seo)){
            $request = new Request();
            $name = $this->name;
            $rd = [
                'url' => '/'.Str::slug(mb_strtolower(translit($name))),
                'seo_name_ru' => $name,
                'seo_name_ua' => $name,
                'meta_title_ru' => $name,
                'meta_title_ua' => $name
            ];
            $request->merge($rd);
            $this->saveSeo($request);
            $this->load('seo');

            return $this->seo->link;
        }
        return $this->seo->link;
    }

	public function getVideoAttribute(){
		$str = str_replace([
			'https://youtu.be/',
			'https://www.youtube.com/embed/',
			'https://www.youtube.com/watch?',
			'<iframe width="560" height="315" src="https://www.youtube.com/embed/',
			'" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>'
		], '', $this->attributes['video']);

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

	public function getVideoPreviewAttribute(){
		return '<img src="//img.youtube.com/vi/'.$this->video.'/mqdefault.jpg">';
	}

	public function getVideoData(){
		$api_key = env('GOOGLE_API_KEY');
		$video_id = $this->video;

		$data = json_decode($this->video_data, true);

		if(!empty($data) && time() - $data['time'] < 86400){
			return $data['data'];
		}

		try {
			$client = new \GuzzleHttp\Client([
				'headers' => [
					'Content-Type' => 'application/json',
					'debug' => true
				]
			]);
			$response = $client->request('GET', "https://www.googleapis.com/youtube/v3/videos?id=$video_id&key=$api_key&part=snippet,contentDetails,statistics,status", []);
			$body = $response->getBody();
			$status = true;
			$message = 'Data found!';
			$data = json_decode($body, true);
		} catch (RequestException $e) {
			$status = false;
			$message = $response->getMessage();
			$data = [];
		} catch (\Exception $e) {
			$status = false;
			$message = $e->getMessage();
			$data = [];
		}

		if ($status == false || !count($data['items'])) {
			return null;
		}

		$this->video_data = json_encode([
			'data' => $data['items'][0],
			'time' => time()
		]);
		$this->save();

		return $data['items'][0];
	}

	public function getVideoMetaSnippet(){
		$item = $this->getVideoData();

		if(empty($item))
			return '';

		$snippet = [
			"@context" => "http://schema.org/",
			"@type" => "VideoObject",
			"name" => $item['snippet']['title'],
			"description" => $item['snippet']['description'],
			"thumbnailUrl" => $item['snippet']['thumbnails']['maxres']['url'],
			"duration" => $item['contentDetails']['duration'],
			"@id" => $item['id'],
			"datePublished" => date('Y-m-d', strtotime($item['snippet']['publishedAt'])),
			"uploadDate" => date('Y-m-d', strtotime($item['snippet']['publishedAt'])),
			"author" => [
				"@type" => "Person",
				"name" => $item['snippet']['channelTitle']
			],
			"interactionStatistic" => [
		        [
				    "@type" => "InteractionCounter",
			        "interactionService" => [
					    "@type" => "WebSite",
				        "name" => "YouTube",
				        "@id" => "https://youtube.com"
		            ],
			        "interactionType" => "http://schema.org/WatchAction",
			        "userInteractionCount" => $item['statistics']['viewCount']
		        ],
		        [
				    "@type" => "InteractionCounter",
			        "interactionService" => [
					    "@type" => "WebSite",
				        "name" => "YouTube",
				        "@id" => "https://youtube.com"
		            ],
			        "interactionType" => "http://schema.org/LikeAction",
			        "userInteractionCount" => $item['statistics']['likeCount']
		        ]
	        ]
		];

		return '<script type="application/ld+json">'.json_encode($snippet).'</script>';
	}

    public function in_wish(){
	    if(isset($this->relations['wishlist'])){
            if($this->wishlist->count()){
                return true;
            }else{
                return false;
            }
        }else{
            $user = Sentinel::check();

            if($user){
                if($this->wishlist()->where('user_id', $user->id)->count()){
                    return true;
                }else{
                    return false;
                }
            }else{
                return false;
            }
        }
    }

    public function main_category(){
       return $this->categories()->first();
    }

    /**
     * Поиск товаров
     *
     * @param string $text Поисковый текст
     * @param int $page Номер страницы
     * @param int $count Колличество на странице
     * @param bool $only_in_stock Только товары в наличии
     * @param null $sale_id ID акции
     * @param null $news_id ID новости
     * @param null $category_id ID категории
     * @return mixed
     */
    public function search($text = '', $page = 1, $count = 8, $only_in_stock = true, $sale_id = null, $news_id = null, $category_id = null){
		Paginator::currentPageResolver(function () use ($page) {
            return $page;
        });

	    $search = explode(' ', $text);
	    $query = $this->select('products.*')
            ->join('localization', 'products.id', '=', 'localization.localizable_id')
            ->when($only_in_stock, function($query){
                $query->where('visible', 1);
            })
            ->where('localization.localizable_type', 'Products')
            ->where('localization.field', 'name')
            ->orderBy('products.id', 'desc')
            ->groupBy('products.id')
            ->with(['localization' => function($query){
                $query->select(['field', 'language', 'value', 'localizable_type', 'localizable_id'])->where('language', 'ru')->where('field', 'name');
            }])
            ->with([
                'image.images',
                'values' => function($query){
                    $query->whereIn('product_attributes.attribute_id', [1, 2, 4])
                        ->with('attribute.localization');
                }
            ]);

	    if(count($search) == 1){
            $query->where(function($query) use($text){
                $query->where('localization.value', 'like', '%'.$text.'%')
                    ->orWhere('sku', 'like', '%'.$text.'%')
                    ->orWhere('products.name', 'like', '%' . $text . '%');
            });
	    }else{
            $query->where(function($query) use($search){
                foreach($search as $s){
                    $query->where('localization.value', 'like', '%'.$s.'%')
                        ->orWhere('sku', 'like', '%'.$s.'%')
                        ->orWhere('products.name', 'like', '%' . $s . '%');
                }
            });
	    }

	    if(!empty($sale_id)){
	        $sale = Sale::find($sale_id);
	        $exclude_ids = $sale->products()->select('products.id')->pluck('id')->toArray();
	        $from = $sale->show_from;
	        $to = $sale->show_to;

            $exclude_ids = array_merge($exclude_ids, $this->select('products.id')->where('sale', 1)->where('sale_from', '<=', $from)->where('sale_to', '>=', $to)->pluck('id')->toArray());
            $exclude_ids = array_merge($exclude_ids, $this->select('products.id')
                ->join('sale_products', 'products.id', '=', 'sale_products.product_id')
                ->join('sales', 'sale_products.sale_id', '=', 'sales.id')
                ->where(function($query) use($from){
                    $query->where('sales.show_from', '>=', $from)
                        ->where('sales.show_from', '<=', $from);
                })
                ->orWhere(function($query) use( $to){
                    $query->where('sales.show_from', '>=', $to)
                        ->where('sales.show_from', '<=', $to);
                })
                ->pluck('id')
                ->toArray());

            $query->whereNotIn('products.id', $exclude_ids);
        }

        if(!empty($news_id)){
            $news = News::find($news_id);
            $exclude_ids = $news->products()->select('products.id')->pluck('id')->toArray();
            $query->whereNotIn('products.id', $exclude_ids);
        }

        if(!empty($category_id)){
	        $category = new Category();
            $query->join('product_categories', 'products.id', '=', 'product_categories.product_id')->whereIn('product_categories.category_id', $category->getChildrenCategories($category_id));
        }

        $data = $query->paginate($count);

        return $data;
    }

    public function updateAttributes($product_attributes){
        $values = [];
        if(!empty($product_attributes)) {
	        foreach($product_attributes as $attribute) {
		        $values[$attribute['value']] = ['attribute_id' => $attribute['id']];
	        }
        }

        $this->values()->sync($values);
    }

    public function getAttributesArray(){
        $attributes = [];
        foreach($this->values as $value){
            if(!isset($attributes[$value->attribute->name])){
                $attributes[$value->attribute->name] = [];
            }

            $attributes[$value->attribute->name][] = $value->name;
        }

        return $attributes;
    }

    public function similar(){
	    $locale = App::getLocale();

	    if(empty($category = $this->categories->first())){
	        return null;
        }

    	return $category->products()->limit(4)->where('products.id', '!=', $this->id)->with([
		    'image.images',
		    'values' => function($query){
			    $query->whereIn('product_attributes.attribute_id', [1, 2, 4])
			          ->with('attribute.localization');
		    },
		    'localization' => function($query) use ($locale){
			    $query->select(['field', 'language', 'value', 'localizable_type', 'localizable_id'])->where('language', $locale)->where('field', 'name');
		    },
            'seo'
	    ])->get();
    }

    public function getProducts($ids){
	    $locale = App::getLocale();
	    $id = $this->id;

	    return $this->select('*')
            ->limit(4)
            ->when(!empty($id), function($query) use ($id){
                $query->where('id', '!=', $id);
            })
            ->whereIn('products.id', $ids)->with([
		    'image.images',
		    'values' => function($query){
			    $query->whereIn('product_attributes.attribute_id', [1, 2, 4])
			          ->with('attribute.localization');
		    },
		    'localization' => function($query) use ($locale){
			    $query->select(['field', 'language', 'value', 'localizable_type', 'localizable_id'])->where('language', $locale)->where('field', 'name');
		    }
	    ])->get();
    }

    /**
     * Получение вариаций (атрибутов с дополнительной стоимостью)
     *
     * @return array
     */
    public function get_variations(){
        $variations = [];
        $attributes = $this->get_attributes->groupBy('attribute_id');

        foreach ($attributes as $attribute => $values){
            foreach ($values as $value){
                if($value->price > 0){
                    $variations[$attribute] = $values->sortBy('price')->values();
                    continue;
                }
            }
        }

        return $variations;
    }

    public function getReviews($count, $page, $paginator_options = []){
        return new LengthAwarePaginator(
            $this->reviews()
                ->where('published', 1)
                ->limit($count)
                ->offset($count*($page - 1))
                ->orderBy('created_at', 'desc')
                ->get(),
            $this->reviews()->where('published', 1)->count(),
            $count,
            $page,
            $paginator_options
        );
    }

    /**
     * Значения атрибута товара
     *
     * @param $slug
     * @return array
     */
    public function get_attribute($slug){
        $attr_val = new AttributeValue;
        $attribute = $attr_val->select('localization.value as name')
            ->join('product_attributes', 'product_attributes.attribute_value_id', '=', 'attribute_values.id')
            ->join('attributes', 'attributes.id', '=', 'attribute_values.attribute_id')
            ->join('localization', 'attribute_values.id', '=', 'localization.localizable_id')
            ->where('attributes.slug', $slug)
            ->where('product_attributes.product_id', $this->id)
            ->where('localization.localizable_type', 'Values')
            ->where('localization.language', App::getLocale())
            ->first();

        if(!empty($attribute))
            return $attribute->attributes['name'];

        return null;
    }

    protected function dataMap(){
        return [
            'attributes' => [],
            'relations' => [
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
                'categories' => [
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
                'values' => [
                    'attributes' => [
                        'id' => '',
                        'attribute_id' => ''
                    ],
                    'relations' => [
                        'attribute' => [
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
                ],
                'variations' => [
                    'attributes' => [
                        'id' => '',
                        'price' => '',
                        'stock' => ''
                    ],
                    'relations' => [
                        'attribute_values' => [
                            'attributes' => [
                                'id' => '',
                                'attribute_id' => ''
                            ],
                            'relations' => [
                                'attribute' => [
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
                        ]
                    ]
                ],
                'image' => [
                    'attributes' => [
                        'id' => '',
                        'path' => ''
                    ]
                ],
                'gallery' => [
                    'attributes' => [
                        'id' => '',
                        'field' => '',
                        'file_id' => ''
                    ],
                    'relations' => [
                        'image' => [
                            'attributes' => [
                                'id' => '',
                                'path' => ''
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
                        'name' => 'Описание товара',
                        'field' => 'description'
                    ]
                ]
            ],
            'relations.image[].attributes.path' => [
                'name' => 'Изображение',
                'type' => 'file'
            ],
            'relations.gallery' => [
                'name' => 'Галлерея',
                'multiple' => true,
                'fields' => [
                    'relations.image[].attributes.path' => [
                        'name' => 'Фото',
                        'type' => 'file'
                    ]
                ]
            ],
            'attributes.sku' => [
                'name' => 'Артикул'
            ],
            'attributes.original_price' => [
                'name' => 'Цена'
            ],
            'attributes.sale' => [
                'name' => 'Акционная цена'
            ],
            'attributes.sale_price' => [
                'name' => 'Цена со скидкой'
            ],
            'attributes.sale_from' => [
                'name' => 'Скидка с'
            ],
            'attributes.sale_to' => [
                'name' => 'Скидка до'
            ],
            'attributes.temporary' => [
                'name' => 'Временный'
            ],
            'attributes.temporary_from' => [
                'name' => 'Временный с'
            ],
            'attributes.temporary_to' => [
                'name' => 'Временный до'
            ],
            'attributes.stock' => [
                'name' => 'Наличие товара'
            ],
            'attributes.series' => [
                'name' => 'Серия'
            ],
            'attributes.certificate' => [
                'name' => 'Это сертификат'
            ],
            'relations.categories' => [
                'name' => 'Категория',
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
            'relations.values' => [
                'name' => 'Атрибут',
                'multiple' => true,
                'fields' => [
                    'relations.localization' => [
                        'localization' => true,
                        'fields' => [
                            [
                                'name_from' => 'relations.attribute[].relations.localization[].attributes.value',
                                'name' => 'Название',
                                'field' => 'name'
                            ]
                        ]
                    ]
                ]
            ],
            'relations.variations' => [
                'name' => 'Вариация',
                'multiple' => true,
                'fields' => [
                    'attributes.price' => [
                        'name' => 'Цена'
                    ],
                    'attributes.stock' => [
                        'name' => 'Наличие вариации'
                    ],
                    'relations.attribute_values' => [
                        'name' => 'Атрибут',
                        'multiple' => true,
                        'fields' => [
                            'relations.localization' => [
                                'localization' => true,
                                'fields' => [
                                    [
                                        'name_from' => 'relations.attribute[].relations.localization[].attributes.value',
                                        'name' => 'Название',
                                        'field' => 'name'
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
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