<?php

namespace App\Http\Controllers;

use Illuminate\Pagination\LengthAwarePaginator;
use Cartalyst\Sentinel\Native\Facades\Sentinel;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\ProductsExport;
use App\Models\ProductsImport;
use App\Models\AttributeValue;
use App\Models\Localization;
use App\Models\Attribute;
use App\Models\Variation;
use App\Models\Redirect;
use App\Models\Category;
use App\Models\Product;
use App\Models\Action;
use App\Models\Setting;
use App\Models\Filter;
use App\Models\Sale;
use App\Models\File;
use App;
use Validator;
use Config;
use Illuminate\Support\Facades\Cache;

class ProductsController extends Controller
{
    private $products;
    private $rules = [
//        'sku' => 'required|unique:products,sku',
    ];

    private $messages = [
//        'sku.required' => 'Поле должно быть заполнено!',
//        'sku.unique' => 'Артикул товара должен быть уникальным!',
    ];

    public $sort = [
        'date_added' => [
            'name'  => 'Дата создания',
            'dest'  => 'DESC',
            'sort'  => 'id'
        ],
        'date_modified' => [
            'name'  => 'Дата изменения',
            'dest'  => 'DESC',
            'sort'  => 'updated_at'
        ],
        'name_asc' => [
            'name'  => 'Имя (А-Я)',
            'dest'  => 'ASC',
            'sort'  => 'name'
        ],
        'name_desc' => [
            'name'  => 'Имя (Я-А)',
            'dest'  => 'DESC',
            'sort'  => 'name'
        ]
    ];

    public $show = [15,30,45,60];

	public function showAction($data){
        $redirect = Redirect::where('old_url', '/'.str_replace(' ', '+', urldecode($data->request->path())))->where('old_url', '!=', 'new_url')->first();
        if(!empty($redirect) && $redirect->old_url != $redirect->new_url){
            return redirect($redirect->new_url, 301);
        }

		$product = $data->seo->seotable;

		if(empty($product)){
			abort(404);
		}

		$product->load(['localization', 'gallery.image.images']);

		$viewed = json_decode($data->request->cookie('viewed'), true);

		if (!is_array($viewed)) {
			$viewed = [];
		}

		if (!in_array($product->id, $viewed)) {
			if (count($viewed) > 7) {
				array_splice($viewed, -7);
			}
			$viewed[] = $product->id;
		}

        $locale = App::getLocale();
        $settings = new Setting;

        $paginator_options = [
            'path' => url($data->request->url())
        ];
        $current_page = 1;
        if(!empty($data->params)){
            foreach($data->params as $param){
                if(strpos($param, 'page-') === 0){
                    $current_page = (int)str_replace('page-', '', $param);
                }
            }
        }

        $reviews = $product->getReviews(4,  $current_page, $paginator_options);

        $seo = $product->seo;
        if(empty($seo->meta_title) || $seo->meta_title == $product->name){
            $seo->meta_title = $product->name;
        }
        if(empty($seo->meta_description) || $seo->meta_description == $product->name){
            $category = $product->categories()->first();
            $seo->meta_description = $product->name;
        }

		return response(view('public.product')
			->with('product', $product)
			->with('gallery', $product->gallery)
			->with('documents', $product->documents)
			->with('seo', $seo)
//			->with('similar', $product->similar())
			->with('related', $product->related()->with(['values' => function($query){
                $query->where('product_attributes.attribute_id', 3)->with('localization');
            }, 'seo', 'image'])->get())
            ->with('reviews', $reviews)
            ->with('grade', $product->grade)
            ->with('delivery_information', $settings->get_setting('delivery_information_'.$locale))
			->with('viewed', !is_null($viewed) ? $product->getProducts($viewed) : null)
            ->with('current_categories', !empty($product->category) ? collect((array)$product->category->get_parent_categories())->pluck('id')->toArray() : ''))
			->withCookie(cookie()->forever('viewed', json_encode($viewed)));
	}

    /**
     * Список товаров
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function adminIndexAction(Request $request){
        $category_id = false;
        $stock = false;
        $current_sort = false;
        $selected_filters = [];
        $select = ['products.id', 'products.sku', 'products.stock', 'products.visible', 'products.original_price', 'products.sale_price', 'products.sale_to', 'products.file_id', 'products.sort_priority', 'localization.value as name'];

        if(empty($request->sort)){
            $request->sort = 'date_added';
        }

        if($request->sort) {
            $current_sort = $this->sort[$request->sort];
            $current_sort['value'] = $request->sort;
        }

        if($request->show) {
            if ($request->cookie('show_list') == null || $request->cookie('show_list') !== $request->show) {
                Cookie::queue('show_list', $request->show);
            }
            $current_show = $request->show;
        } else {
            if ($request->cookie('show_list') == null) {
                Cookie::queue('show_list', 60);
                $current_show = 60;
            } else {
                $current_show = (int)$request->cookie('show_list');
            }
        }

        if(isset($request->category)){
            $category_id = $request->category;
        }
        if(isset($request->stock)){
            $stock = $request->stock;
        }

        if($request->search){
            $products = new Product();
            $search_text = $request->search;
            $per_page = $current_show;

//            $ids_loc = Localization::select('localizable_id')
//                ->where('localizable_type', '=', 'Products')
//                ->where('field', 'name')
//                ->where('value', 'like', '%' . $search_text . '%')
//                ->get()
//                ->pluck('localizable_id')
//                ->toArray();
//
//            $ids_prod = $products->select('id')
//                ->orWhere('products.sku', 'like', '%' . $search_text . '%')
//                ->orWhere('products.external_id', 'like', '%' . $search_text . '%')
//                ->get()
//                ->pluck('id')
//                ->toArray();
//
//            $ids = array_unique(array_merge($ids_loc, $ids_prod));
            $search = explode(' ', $search_text);

            if (count($search) == 1){
                $ids = array_unique(array_merge(Localization::select('localizable_id')->where('localizable_type', 'Products')
                    ->where('value', 'like', '%' . $search_text . '%')
                    ->groupBy('localizable_id')
                    ->get()->pluck('localizable_id')->toArray(),
                    Localization::select('product_attributes.product_id')->join('product_attributes', 'localization.localizable_id', '=', 'product_attributes.attribute_value_id')
                        ->where('localizable_type', 'Values')
                        ->where('value', 'like', '%' . $search_text . '%')
                        ->groupBy('product_attributes.product_id')
                        ->get()->pluck('product_id')->toArray(),
                    Product::select('id')
                        ->where('name', 'like', '%' . $search_text . '%')
                        ->orWhere('sku', 'like', '%' . $search_text . '%')
                        ->get()->pluck('id')->toArray()
                ));
            } else {
                $ids = array_unique(array_merge(Localization::select('localizable_id')->where('localizable_type', 'Products')
                    ->where(function ($query) use ($search) {
                        foreach ($search as $s) {
                            $query->where('value', 'like', '%' . $s . '%');
                        }
                    })
                    ->groupBy('localizable_id')
                    ->get()->pluck('localizable_id')->toArray(),
                    Localization::select('product_attributes.product_id')->join('product_attributes', 'localization.localizable_id', '=', 'product_attributes.attribute_value_id')
                        ->where('localizable_type', 'Values')
                        ->where(function ($query) use ($search) {
                            foreach ($search as $s) {
                                $query->where('value', 'like', '%' . $s . '%');
                            }
                        })
                        ->groupBy('product_attributes.product_id')
                        ->get()->pluck('product_id')->toArray(),
                    Product::select('id')
                        ->where('name', 'like', '%' . $search_text . '%')
                        ->get()->pluck('id')->toArray()
                ));
            }
            asort($ids);

            // Пагинация
            $paginator_options = [
                'path' => url($request->url()),
                'query' => [
                    'sort' => $request->sort,
                    'search' => $request->search
                ]
            ];

            if(empty($per_page))
                $per_page = config('view.product_quantity');
            $current_page = $request->page ? $request->page : 1;
	        $current_page_ids = array_slice($ids, ($current_page - 1) * $per_page, $per_page);
            $products = new LengthAwarePaginator(
                $products->select($select)
                    ->leftJoin('localization', function($leftJoin) {
                        $leftJoin->on('products.id', '=', 'localization.localizable_id')
                            ->where('localization.localizable_type', '=', 'Products')
                            ->where('localization.language', '=', config('locale'))
                            ->where('field', 'name');
                    })
                    ->with(['attributes.info', 'attributes.value'])
                    ->whereIn('products.id', $current_page_ids)
                    ->when($current_sort, function($query) use ($current_sort){
                        return $query->orderBy('products.'.$current_sort['sort'], $current_sort['dest']);
                    })
                    ->get(),
                count($ids),
                $per_page,
                $current_page,
                $paginator_options
            );
            $current_search = $request->search;
        } elseif(!empty($request->filter)) {

            if(env('REDIS_CACHE')){
                $ids = [];
                $rules = [];

                // Входные данные
                foreach($request->filter as $gi => $group){
                    $group_rules = [
                        'and' => [],
                        'or' => []
                    ];

                    if(isset($group[0]) && isset($group[0]['relations'])){
                        $group_rules['relations'] = $group[0]['relations'];
                        unset($group[0]['relations']);
                    }else{
                        $group_rules['relations'] = 'AND';
                    }

                    foreach($group as $ri => $rule){
                        if($rule['criterion'] == 'category' && !empty($rule['value'])){
                            $selected_filters[] = [
                                'name' => 'Категория: '.Category::find($rule['value'])->name,
                                'link' => $this->generateFilterLink($request->filter, $gi, $ri)
                            ];
                            $key = "category_".$rule['value'];
                        }elseif($rule['criterion'] == 'attribute' && !empty($rule['value'])){
                            $val = AttributeValue::with('localization', 'attribute.localization')->find($rule['value']);
                            $selected_filters[] = [
                                'name' => $val->attribute->name.': '.$val->name,
                                'link' => $this->generateFilterLink($request->filter, $gi, $ri)
                            ];
                            $key = "attribute_".$rule['value'];
                        }elseif($rule['criterion'] == 'status'){
                            $statuses = [
                                0 => 'Ожидается',
                                -1 => 'Под заказ',
                                -2 => 'Нет в наличии'
                            ];
                            $selected_filters[] = [
                                'name' => 'Статус: '.($rule['value'] > 0 ? 'В наличии' : $statuses[$rule['value']]),
                                'link' => $this->generateFilterLink($request->filter, $gi, $ri)
                            ];
                            if($rule['value'] > 0){
                                $key = "product_stock";
                            }elseif($rule['value'] == -2){
                                $key = "product_no_stock";
                            }elseif($rule['value'] == -1){
                                $key = "product_under_the_order";
                            }elseif($rule['value'] == 0){
                                $key = "product_expected";
                            }
                        }elseif($rule['criterion'] == 'price'){
                            $key = null;
                            if($rule['condition'] == '='){
                                $selected_filters[] = [
                                    'name' => 'Цена: '.$rule['value'].'грн',
                                    'link' => $this->generateFilterLink($request->filter, $gi, $ri)
                                ];
                                $key = "prices_".($rule['value']*100);
                                $ids = Redis::command("zrangebyscore", ["prices", $rule['value']*100, $rule['value']*100]);
                            }elseif($rule['condition'] == '>'){
                                $selected_filters[] = [
                                    'name' => 'Цена от: '.$rule['value'].'грн',
                                    'link' => $this->generateFilterLink($request->filter, $gi, $ri)
                                ];
                                $key = "prices_more_".(($rule['value']*100));
                                $ids = Redis::command("zrangebyscore", ["prices", '('.($rule['value']*100), "+inf"]);
                            }elseif($rule['condition'] == '<'){
                                $selected_filters[] = [
                                    'name' => 'Цена до: '.$rule['value'].'грн',
                                    'link' => $this->generateFilterLink($request->filter, $gi, $ri)
                                ];
                                $key = "prices_less_".(($rule['value']*100));
                                $ids = Redis::command("zrangebyscore", ["prices", "-inf", '('.($rule['value']*100)]);
                            }

                            if(!empty($key)){
                                foreach(array_chunk($ids, 1000) as $ids_chunk) {
                                    $command = [$key];

                                    foreach($ids_chunk as $id) {
                                        $command[] = 'SET';
                                        $command[] = 'u1';
                                        $command[] = $id;
                                        $command[] = 1;
                                    }

                                    Redis::command('bitfield', $command);
                                }
                            }
                        }elseif($rule['criterion'] == 'description'){
                            $selected_filters[] = [
                                'name' => 'Описание содержит: '.strip_tags($rule['value']),
                                'link' => $this->generateFilterLink($request->filter, $gi, $ri)
                            ];
                            $tags = explode(' ', mb_strtolower(strip_tags($rule['value'])));
                            $params = ['search:results', count($tags)];
                            foreach($tags as $tag){
                                $params[] = "words:$tag";
                            }
                            Redis::command('zunionstore', $params);
                            $ids = array_keys(Redis::command('zrevrange', ['search:results', 0, -1, 'withscores']));
                            $key = 'search_'.md5($rule['value']);

                            foreach(array_chunk($ids, 1000) as $ids_chunk) {
                                $command = [$key];

                                foreach($ids_chunk as $id) {
                                    $command[] = 'SET';
                                    $command[] = 'u1';
                                    $command[] = $id;
                                    $command[] = 1;
                                }

                                Redis::command('bitfield', $command);
                            }
                        }

                        if(!empty($key))
                            $group_rules[isset($rule['relations']) && $rule['relations'] == 'OR' ? 'or' : 'and'][] = $key;
                    }

                    $rules[] = $group_rules;
                }

                // Вычисление групп
                $groups_keys = [];
                foreach($rules as $group_id => $group_rules){
                    $and_key = null;
                    if(!empty($group_rules['and'])){
                        if(count($group_rules['and']) > 1){
                            $and_key = "group_and_$group_id";
                            $params = ["and", $and_key];
                            foreach($group_rules['and'] as $result){
                                $params[] = $result;
                            }
                            Redis::command('bitop', $params);
                        }else{
                            $and_key = $group_rules['and'][0];
                        }
                    }

                    if(!empty($group_rules['or'])){
                        $groups_keys[] =
                            [
                                'key' => "group_$group_id",
                                'relations' => $group_rules['relations']
                            ];
                        $params = ["or", "group_$group_id", $and_key];
                        foreach($group_rules['or'] as $result){
                            $params[] = $result;
                        }
                        Redis::command('bitop', $params);
                    }elseif(!empty($and_key)){
                        $groups_keys[] =
                            [
                                'key' => $and_key,
                                'relations' => $group_rules['relations']
                            ];
                    }
                }

                // Вычисление результата
                $and_keys = [];
                foreach($groups_keys as $group_id => $key){
                    if($key['relations'] == 'AND'){
                        $and_keys[] = $key['key'];
                        unset($groups_keys[$group_id]);
                    }
                }

                $and_key = null;
                if(count($and_keys) > 1){
                    $and_key = "result_and";
                    $params = ["and", $and_key];
                    foreach($and_keys as $key){
                        $params[] = $key;
                    }
                    Redis::command('bitop', $params);
                }elseif(isset($and_keys[0])){
                    $and_key = $and_keys[0];
                }

                if(!empty($and_key)){
                    $groups_keys[] = [
                        'key' => $and_key,
                        'relations' => 'OR'
                    ];
                }

                $result_key = null;
                if(count($groups_keys) > 1){
                    $result_key = 'result';
                    $params = ["or", $result_key];
                    foreach($groups_keys as $key){
                        $params[] = $key['key'];
                    }
                    Redis::command('bitop', $params);
                }elseif(!empty($groups_keys)){
                    $key = array_shift($groups_keys);
                    $result_key = $key['key'];
                }

                if(isset($result_key)){
                    $bitmap = Redis::get($result_key);
                    $ids = $this->bitmap_ids($bitmap);
                }

                if(empty($current_show)){
                    $current_show = 60;
                }

                $page = !empty($request->page) ? $request->page : 1;
                $offset = $current_show * ($page - 1);

                if($current_sort['sort'] == 'price'){
                    if($current_sort['dest'] == 'asc')
                        $all_sorted = Redis::command('zrange', ['prices', '0', '-1']);
                    else
                        $all_sorted = Redis::command('zrevrange', ['prices', '0', '-1']);

                    $sorted = array_intersect($all_sorted, $ids);
                }else{
                    $sorted = $ids;
                }

                $current_page_ids = array_slice($sorted, $offset, $current_show);

                if(!empty($current_page_ids)){
                    $products = Product::select($select)
                        ->leftJoin('localization', function($leftJoin) {
                            $leftJoin->on('products.id', '=', 'localization.localizable_id')
                                ->where('localization.localizable_type', '=', 'Products')
                                ->where('localization.language', '=', config('locale'))
                                ->where('field', 'name');
                        })
                        ->with(['attributes.info', 'attributes.value'])
                        ->when($current_sort, function($query) use ($current_sort){
                            return $query->orderBy('products.'.$current_sort['sort'], $current_sort['dest']);
                        });

                    $products->whereIn('products.id', $current_page_ids);
                    $products = new LengthAwarePaginator($products->get(), count($sorted), $current_show, $page, ['path' => request()->url(), 'pageName' => 'page']);
                }else{
                    $products = new LengthAwarePaginator(collect([]), 0, $current_show, $page, ['path' => request()->url(), 'pageName' => 'page']);
                }

                $products->appends($request->except(['page']));
            }else{
                $query = Product::select($select)->leftJoin('localization', function($leftJoin) {
                    $leftJoin->on('products.id', '=', 'localization.localizable_id')
                        ->where('localization.localizable_type', '=', 'Products')
                        ->where('localization.language', '=', config('locale'))
                        ->where('localization.field', 'name');
                });
                $relations = [];
                foreach($request->filter as $group){
                    foreach ($group as $condition){
                        if($condition['criterion'] == 'category'){
                            $relations[] = ['product_categories', 'product_categories.product_id', '=', 'products.id'];
                        }elseif($condition['criterion'] == 'attribute'){
                            $relations[] = ['product_attributes', 'product_attributes.product_id', '=', 'products.id'];
                        }
                    }

                    $relation = isset($group[0]['relations']) && $group[0]['relations'] == 'OR' ? 'orWhere' : 'where';

                    $query->{$relation}(function ($query) use($group){
                        foreach ($group as $condition){
                            $relation = isset($condition['relations']) && $condition['relations']=='OR' ? 'orWhere' : 'where';
                            if($condition['criterion'] == 'category') {
                                if(empty($condition['value'])){
                                    $query->doesntHave('categories');
                                }else{
                                    if ($condition['condition'] == 'with_child') {
                                        $category = Category::find($condition['value']);
                                        $query->{$relation . 'In'}('product_categories.category_id', array_merge([$category->id], $category->getChildrenCategories($category->id)));
                                    } else {
                                        $query->{$relation}('product_categories.category_id', $condition['value']);
                                    }
                                }
                            }elseif($condition['criterion'] == 'attribute'){
                                if(!empty($condition['value'])){
                                    $query->{$relation}('product_attributes.attribute_value_id', $condition['value']);
                                }elseif(!empty($condition['attribute'])){
                                    $query->{$relation}('product_attributes.attribute_id', $condition['attribute']);
                                }
                            }elseif($condition['criterion'] == 'status'){
                                $query->{$relation}('products.stock', $condition['value']);
                            }elseif($condition['criterion'] == 'price'){
                                $query->{$relation}('products.price', $condition['condition'], $condition['value']);
                            }elseif($condition['criterion'] == 'description'){
                                $query->{$relation}('products.description', $condition['condition'], $condition['value']);
                            }
                        }
                    });
                }
                foreach ($relations as $relation){
                    $query->leftJoin($relation[0], $relation[1], $relation[2], $relation[3]);
                }
                $products = $query->with(['attributes.info', 'attributes.value', 'image', 'categories'])->paginate($current_show)->appends($request->except(['page']));
            }

            $current_search = false;
        }else{
            if(0 && env('REDIS_CACHE')){
                $results = [];
                $ids = [];

                if(!empty($category_id)){
                    $selected_filters[] = [
                        'name' => 'Категория: '.Category::find($category_id)->name,
                        'link' => '/admin/products'
                    ];
                    $results[] = "category_".$category_id;
                }

                if($stock !== false){
                    if($stock > 0){
                        $selected_filters[] = [
                            'name' => 'Статус: В наличии',
                            'link' => '/admin/products'
                        ];
                        $results[] = "product_stock";
                    }else{
                        $selected_filters[] = [
                            'name' => 'Нет в наличии',
                            'link' => '/admin/products'
                        ];
                        $results[] = "product_no_stock";
                    }
                }

                if(!empty($results)){
                    if(count($results) > 1){
                        $params = ["and", "result"];
                        foreach($results as $result){
                            $params[] = $result;
                        }
                        Redis::command('bitop', $params);
                        $bitmap = Redis::get('result');
                    }else{
                        $bitmap = Redis::get($results[0]);
                    }
                    $ids = $this->bitmap_ids($bitmap);
                }

                if(empty($current_show)){
                    $current_show = 60;
                }

                $page = !empty($request->page) ? $request->page : 1;
                $offset = $current_show * ($page - 1);

                if($current_sort['sort'] == 'price'){
                    if($current_sort['dest'] == 'asc')
                        $all_sorted = Redis::command('zrange', ['prices', '0', '-1']);
                    else
                        $all_sorted = Redis::command('zrevrange', ['prices', '0', '-1']);

                    if(empty($results)){
                        $sorted = $all_sorted;
                    }else{
                        $sorted = array_intersect($all_sorted, $ids);
                    }
                }else{
                    if(empty($results)){
                        $all_sorted = Redis::command('zrange', ['prices', '0', '-1']);
                        asort($all_sorted);

                        $sorted = $all_sorted;
                    }else{
                        $sorted = $ids;
                    }
                }

                $current_page_ids = array_slice($sorted, $offset, $current_show);

                $products = Product::select($select)
                    ->leftJoin('localization', function($leftJoin) {
                        $leftJoin->on('products.id', '=', 'localization.localizable_id')
                            ->where('localization.localizable_type', '=', 'Products')
                            ->where('localization.language', '=', config('locale'))
                            ->where('field', 'name');
                    })
                    ->with(['attributes.info', 'attributes.value'])
                    ->when($current_sort, function($query) use ($current_sort){
                        return $query->orderBy('products.'.$current_sort['sort'], $current_sort['dest']);
                    });

                if(!empty($current_page_ids))
                    $products->whereIn('products.id', $current_page_ids);

                $products = new LengthAwarePaginator($products->get(), count($sorted), $current_show, $page, ['path' => request()->url(), 'pageName' => 'page']);
            }else{
                if(!empty($category_id)){
                    $selected_filters[] = [
                        'name' => 'Категория: '.Category::find($category_id)->name,
                        'link' => '/admin/products'
                    ];
                }

                if($stock !== false){
                    if($stock > 0){
                        $selected_filters[] = [
                            'name' => 'Статус: В наличии',
                            'link' => '/admin/products'
                        ];
                    }elseif($stock == -2){
                        $selected_filters[] = [
                            'name' => 'Нет в наличии',
                            'link' => '/admin/products'
                        ];
                    }
                }

                $products = Product::select($select)
                    ->leftJoin('localization', function($leftJoin) {
                        $leftJoin->on('products.id', '=', 'localization.localizable_id')
                            ->where('localization.localizable_type', '=', 'Products')
                            ->where('localization.language', '=', config('locale'))
                            ->where('field', 'name');
                    })
                    ->when($category_id, function($query) use ($category_id){
                        return $query->join('product_categories AS cat', 'products.id', '=', 'cat.product_id')->where('cat.category_id', $category_id);
                    })
                    ->when(($stock !== false), function($query) use ($stock){
                        return $query->where('stock', $stock);
                    })
                    ->when($current_sort, function($query) use ($current_sort){
                        return $query->orderBy('products.'.$current_sort['sort'], $current_sort['dest']);
                    })
                    ->when(!empty($request->discount_is_over), function($query){
                        $query->whereNotNull('sale_to')->where('sale_to', '<', date('Y-m-d H:i:s'));
                    })
                    ->with(['attributes.info', 'attributes.value'])
                    ->paginate($current_show);
            }

            $current_search = false;
        }

        if($stock !== false){
            $products->appends(['stock' => $stock]);
        }

        $categories = new Category();

        return view('admin.products.index', [
            'products' => $products,
            'categories' => $categories->getTreeList(),
            'all_attributes' => Attribute::select(['attributes.id', 'localization.value as name'])->leftJoin('localization', function($leftJoin) {
	            $leftJoin->on('attributes.id', '=', 'localization.localizable_id')
	                     ->where('localization.localizable_type', '=', 'Attributes')
		                 ->where('localization.language', '=', 'ru')
	                     ->where('field', 'name');
            })->with(['values' => function($query){
	            $query->select(['attribute_values.*', 'localization.value as name'])->leftJoin('localization', function($join) {
		            $join->on('attribute_values.id', '=', 'localization.localizable_id')
		                 ->where('localization.localizable_type', '=', 'Values')
			             ->where('localization.language', '=', 'ru')
		                 ->where('field', 'name');
	            });
            }])->get()->toArray(),
            'array_sort' => $this->sort,
            'current_sort' => $current_sort,
            'array_show' => $this->show,
            'current_show' => $current_show,
            'current_search' => $current_search,
            'selected_filters' => $selected_filters,
            'filters' => !empty($request->filter) ? $request->filter : null
        ]);
    }

    private function generateFilterLink($filters, $g, $r){
        unset($filters[$g][$r]);

        $link = '/admin/products?';
        $attributes = [];

        foreach($filters as $gi => $group){
            foreach($group as $ri => $rule){
                foreach($rule as $attr => $val){
                    $attributes[] = "filter[$gi][$ri][$attr]=$val";
                }
            }
        }

        $link .= implode('&', $attributes);

        return $link;
    }

    /**
     * Получение id отфильтрованных товаров
     *
     * @param Request $request
     */
    public function adminGetFilteredIdsAction(Request $request){
        if ($request->search) {
            $products = new Product();
            $search_text = $request->search;

            $products = $products->select(['products.id'])
                ->where('name', 'like', '%' . $search_text . '%')
                ->orWhere('sku', 'like', '%' . $search_text . '%')
                ->get()
                ->pluck('id');

        }elseif(!empty($request->filter)) {
            $query = Product::select(['products.id']);
            $relations = [];
            foreach($request->filter as $group){
                foreach ($group as $condition){
                    if($condition['criterion'] == 'category'){
                        $relations[] = ['product_categories', 'product_categories.product_id', '=', 'products.id'];
                    }elseif($condition['criterion'] == 'attribute'){
                        $relations[] = ['product_attributes', 'product_attributes.product_id', '=', 'products.id'];
                    }
                }

                $relation = isset($group[0]['relations']) && $group[0]['relations'] == 'OR' ? 'orWhere' : 'where';

                $query->{$relation}(function ($query) use($group){
                    foreach ($group as $condition){
                        $relation = isset($condition['relations']) && $condition['relations']=='OR' ? 'orWhere' : 'where';
                        if($condition['criterion'] == 'category') {
                            if ($condition['condition'] == 'with_child') {
                                $category = Category::find($condition['value']);
                                $query->{$relation . 'In'}('product_categories.category_id', array_merge([$category->id], $category->get_children_categories($category->id)));
                            } else {
                                $query->{$relation}('product_categories.category_id', $condition['value']);
                            }
                        }elseif($condition['criterion'] == 'attribute'){
                            if(!empty($condition['value'])){
                                $query->{$relation}('product_attributes.attribute_value_id', $condition['value']);
                            }elseif(!empty($condition['attribute'])){
                                $query->{$relation}('product_attributes.attribute_id', $condition['attribute']);
                            }
                        }elseif($condition['criterion'] == 'status'){
                            $query->{$relation}('products.stock', $condition['value']);
                        }elseif($condition['criterion'] == 'price'){
                            $query->{$relation}('products.price', $condition['condition'], $condition['value']);
                        }
                    }
                });
            }
            foreach ($relations as $relation){
                $query->leftJoin($relation[0], $relation[1], $relation[2], $relation[3]);
            }
            $products = $query->get()->pluck('id');
        }else {
            $category_id = false;
            $stock = false;

            if (isset($request->category)) {
                $category_id = $request->category;
            }
            if (isset($request->stock)) {
                $stock = $request->stock;
            }

            $products = Product::select(['products.id'])->when($category_id, function($query) use ($category_id){
                return $query->join('product_categories AS cat', 'products.id', '=', 'cat.product_id')->where('cat.category_id', $category_id);
            })
            ->when(($stock !== false), function($query) use ($stock){
                return $query->where('stock', $stock);
            })
            ->get()
            ->pluck('id');
        }

        echo implode(',', $products->toArray());
    }

	/**
	 * Получение данных о товаре
	 *
	 * @param $sku
	 *
	 * @return array
	 */
    public function getProductData($sku){
    	$product = Product::where('sku', $sku)->with('image')->first();

    	if(empty($product)){
    		return ['result' => 'error', 'message' => 'Товар не найден'];
	    }

    	return [
    		'result' => 'success',
		    'product' => [
		    	'id' => $product->id,
			    'sku' => $product->sku,
			    'image' => $product->image->url(),
			    'name' => $product->name,
			    'price' => $product->price
		    ]
	    ];
    }

    /**
     * Массовое обновление
     *
     * @param $action
     * @param Request $request
     * @return array
     */
    public function massAction($action, Request $request){
        if($action == 'add_category'){
            $category = Category::find($request->category);
            if(!empty($category)){
                $products = Product::select(['products.id'])
                    ->whereIn('products.id', explode(',', $request->products))
                    ->whereDoesntHave('categories', function ($query) use($category) {
                        $query->where(['categories.id' => $category->id]);
                    })
                    ->get();
                $product_categories = [];
                if(!empty($products)){
                    foreach ($products as $product){
                        $product_categories[] = [
                            'product_id' => $product->id,
                            'category_id' => $category->id
                        ];
                    }

                    DB::table('product_categories')->insert($product_categories);
                    return ['result' => 'success', 'message' => 'В категорию "'.$category->name.'" добавлено '.count($product_categories).' товаров.'];
                }else{
                    return ['result' => 'error', 'message' => 'Нет товаров для обновления.'];
                }
            }else{
                return ['result' => 'error', 'message' => 'Не найдена категория.'];
            }
        }elseif($action == 'remove_category'){
            $category = Category::find($request->category);
            if(!empty($category)) {
                $count = DB::table('product_categories')
                    ->whereIn('product_id', explode(',', $request->products))
                    ->where('category_id', $category->id)
                    ->count();
                if(!empty($count)){
                    DB::table('product_categories')
                        ->whereIn('product_id', explode(',', $request->products))
                        ->where('category_id', $category->id)
                        ->delete();
                    return ['result' => 'success', 'message' => 'Из категории "'.$category->name.'" удалено '.$count.' товаров.'];
                }else{
                    return ['result' => 'error', 'message' => 'Нет товаров для обновления.'];
                }
            }else{
                return ['result' => 'error', 'message' => 'Не найдена категория.'];
            }
        }elseif($action == 'change_categories'){
            $category = Category::find($request->category);
            if(!empty($category)) {
                $count = DB::table('product_categories')
                    ->whereIn('product_id', explode(',', $request->products))
                    ->count();
                if(!empty($count)){
                    DB::table('product_categories')
                        ->whereIn('product_id', explode(',', $request->products))
                        ->where('category_id', '!=', $category->id)
                        ->delete();

                    $products = Product::select(['products.id'])
                        ->whereIn('products.id', explode(',', $request->products))
                        ->whereDoesntHave('categories', function ($query) use($category) {
                            $query->where(['categories.id' => $category->id]);
                        })
                        ->get();
                    $product_categories = [];
                    if(!empty($products)){
                        foreach ($products as $product){
                            $product_categories[] = [
                                'product_id' => $product->id,
                                'category_id' => $category->id
                            ];
                        }

                        DB::table('product_categories')->insert($product_categories);
                    }

                    return ['result' => 'success', 'message' => $count.' товаров перемещено в категорию "'.$category->name.'"'];
                }else{
                    return ['result' => 'error', 'message' => 'Нет товаров для обновления.'];
                }
            }else{
                return ['result' => 'error', 'message' => 'Не найдена категория.'];
            }
        }elseif($action == 'remove_products'){
            $count = Product::whereIn('products.id', explode(',', $request->products))
                ->count();
            Product::whereIn('products.id', explode(',', $request->products))
                ->delete();
            return ['result' => 'success', 'message' => "Удалено $count товаров."];
        }elseif($action == 'change_status'){
	        $count = Product::whereIn('products.id', explode(',', $request->products))->where('stock', '!=', $request->status)
	                        ->count();
	        Product::whereIn('products.id', explode(',', $request->products))->where('stock', '!=', $request->status)
	               ->update(['stock' => $request->status]);
	        return ['result' => 'success', 'message' => "Обновлено $count товаров."];
        }elseif($action == 'change_sort_priority'){
            $count = Product::whereIn('products.id', explode(',', $request->products))->count();
            Product::whereIn('products.id', explode(',', $request->products))
                ->update(['sort_priority' => $request->sort_priority]);
            return ['result' => 'success', 'message' => "Обновлено $count товаров."];
        }elseif($action == 'add_price'){
	        $count = Product::whereIn('products.id', explode(',', $request->products))
	                        ->count();
	        Product::whereIn('products.id', explode(',', $request->products))
		            ->increment('original_price', $request->num);
	        return ['result' => 'success', 'message' => "Обновлено $count товаров."];
        }elseif($action == 'add_sale_price'){
	        $count = Product::whereIn('products.id', explode(',', $request->products))
	                        ->count();
	        Product::whereIn('products.id', explode(',', $request->products))
	               ->increment('sale_price', $request->num);
	        return ['result' => 'success', 'message' => "Обновлено $count товаров."];
        }elseif($action == 'multiply_price'){
	        $count = Product::whereIn('products.id', explode(',', $request->products))
	                        ->count();
	        Product::whereIn('products.id', explode(',', $request->products))
	               ->update(['original_price' => DB::raw("original_price * $request->num")]);
	        return ['result' => 'success', 'message' => "Обновлено $count товаров."];
        }elseif($action == 'multiply_sale_price'){
	        $count = Product::whereIn('products.id', explode(',', $request->products))
	                        ->count();
	        Product::whereIn('products.id', explode(',', $request->products))
	            ->update(['sale_price' => DB::raw("sale_price * $request->num")]);
	        return ['result' => 'success', 'message' => "Обновлено $count товаров."];
        }elseif($action == 'multiply_sale'){
	        $count = Product::whereIn('products.id', explode(',', $request->products))
	                        ->count();

	        $k = 1 - $request->sale_percent / 100;
	        $sale_from = date('Y-m-d', strtotime($request->sale_from));
	        $sale_to = date('Y-m-d', strtotime($request->sale_to));

            Product::whereIn('products.id', explode(',', $request->products))->update([
                'sale_price' => DB::raw("original_price * $k"),
                'sale' => 1,
                'sale_from' => $sale_from,
                'sale_to' => $sale_to
            ]);

            $date = date('Y-m-d H:i:s');

            Product::where('sale', 1)
                ->where('sale_from', '<=', $date)
                ->where('sale_to', '>=', $date)
                ->where('price', '!=', DB::raw('sale_price'))
                ->update(['price' => DB::raw('sale_price')]);

            $sale = Sale::find(1);
            $products = Product::select(['id', 'price'])->whereIn('products.id', explode(',', $request->products))->get();
            $data = [];
            foreach($products as $product){
                $data[$product->id] = [
                    'sale_id' => 1,
                    'sale_price' => $product->price
                ];
            }
            $sale->products()->syncWithoutDetaching($data);

            if(env('REDIS_CACHE')) {
                foreach($products as $product){
                    Redis::command('zadd', ['prices', $product->price * 100, $product->id]);
                }
                $products = $sale->products()->select('product_id')->get()->pluck('product_id')->unique()->sort()->values()->all();

                Redis::command('del', ['sale_1']);
                foreach(array_chunk($products, 100) as $data){
                    $command = ['sale_1'];

                    foreach($data as $product_id){
                        $command[] = 'SET';
                        $command[] = 'u1';
                        $command[] = $product_id;
                        $command[] = 1;
                    }

                    Redis::command('bitfield', $command);
                }
            }

	        return ['result' => 'success', 'message' => "Обновлено $count товаров."];
        }

        return ['result' => 'error', 'message' => 'Не правильно передан метод.'];
    }

    /**
     * Страница создания товара
     *
     * @return \Illuminate\Http\Response
     */
    public function adminCreateAction(){
        return view('admin.products.create')
            ->with('categories', Category::getSelect())
            ->with('attributes', Attribute::all())
            ->with('editors', localizationFields(['description', 'instructions', 'security', 'compound', 'shelf_life', 'storage_conditions', 'seo_description']));
    }

	/**
	 * Создание товара
	 *
	 * @param Request $request
	 * @param Product $products
	 *
	 * @return $this
	 */
    public function adminStoreAction(Request $request, Product $products){
        $attributes_error = $this->validateAttributes($request->product_attributes);
//        $validator = Validator::make($request->all(), $this->rules, $this->messages);
//
//        if($validator->fails() || $attributes_error){
//            return redirect()
//                ->back()
//                ->withInput()
//                ->with('message-error', 'Сохранение не удалось! Проверьте форму на ошибки!')
//                ->withErrors($validator)
//                ->with('attributes_error', $attributes_error);
//        }

        $data = $request->only($products->getFillable());

        if(empty($data['stock']))
            $data['stock'] = 1;
        if(empty($data['stock_sync']))
            $data['stock_sync'] = 0;
        if(empty($data['sale']))
            $data['sale'] = 0;
        if(empty($data['sort_priority']))
            $data['sort_priority'] = 0;

        if(empty($data['file_id']) && !empty($request->gallery)){
            $data['file_id'] = $request->gallery[0];
        }

	    $products->fill($data);

//        $current_time = time();
//        if(!empty($request->sale) && !empty($request->sale_price) && $request->sale_from <= $current_time && $request->sale_to >= $current_time){
//	        $products->price = $request->sale_price;
//        }else{
//	        $products->price = $request->original_price;
//        }
        $products->price = 0;

	    $products->save();
	    $products->saveSeo($request);
	    $products->saveLocalization($request);
        $products->saveGalleries($request);

	    $products->categories()->sync($request->product_category_id);

	    if(!empty($request->product_attributes)){
		    foreach ($request->product_attributes as $attribute){
			    $product_attributes[] = [
				    'product_id' => $products->id,
				    'attribute_id' => $attribute['id'],
				    'attribute_value_id' => $attribute['value'],
			    ];
		    }

		    $products->attributes()->createMany($product_attributes);
	    }

	    if(!empty($request->variations))
            $this->updateVariations($products, $request->variations);

        $products->load('seo');

        if(env('REDIS_CACHE')) {
            Redis::command('setbit', ["product_visible", $products->id, empty($products->visible) ? 0 : 1]);
            Redis::command('setbit', ["product_stock", $products->id, $products->stock > 0 ? 1 : 0]);
            Redis::command('setbit', ["product_not_stock", $products->id, $products->stock === -2 ? 1 : 0]);
            Redis::command('setbit', ["product_under_the_order", $products->id, $products->stock === -1 ? 1 : 0]);
            Redis::command('setbit', ["product_expected", $products->id, $products->stock === 0 ? 1 : 0]);
            Redis::command('zadd', ['prices', $products->price * 100, $products->id]);

            if(!empty($request->product_category_id)){
                foreach($request->product_category_id as $category_id){
                    Redis::command('setbit', ["category_$category_id", $products->id, 1]);
                }
            }

            if(!empty($request->product_attributes)){
                foreach($request->product_attributes as $attr){
                    Redis::command('setbit', ["attribute_".$attr['value'], $products->id, 1]);
                }
            }

            $prods = Product::select(['products.id', 'localization.value as name'])->leftJoin('localization', function($join){
                $join->on('products.id', '=', 'localization.localizable_id')
                        ->where('localization.localizable_type', 'Products')
                        ->where('localization.language', 'ru')
                        ->where('localization.field', 'name');
                })
                ->orderBy('localization.value', 'asc')
                ->get();

            Redis::command('del', ['names']);
            foreach($prods as $i => $prod){
                Redis::command('zadd', ['names', $i, $prod->id]);
            }
        }

	    Action::createEntity($products);

        return redirect('/admin/products')
            ->with('message-success', 'Товар ' . $products->name . ' успешно добавлен.');
    }

    /**
     * Изменение товара
     *
     * @param Request $request
     * @param $id
     * @return mixed
     */
    public function adminEditAction(Request $request, $id) {
        $product = Product::find($id);

        if(empty($product))
            abort(404);

        $categories = [];
        if(!empty($product->categories)){
            foreach ($product->categories as $category){
                $categories[] = $category->id;
            }
        }

        $product_attributes = collect($product->attributes()->select(['product_id', 'attribute_id', 'attribute_value_id'])->get()->toArray())->unique();

        if(!empty($request->prev)){
            $prev = $request->prev;
        }else{
            $prev = app('url')->previous();
        }

        $sets = Product::where('id', '<>', $id)->get();

        return view('admin.products.edit')
            ->with('product', $product)
            ->with('product_attributes', $product_attributes)
            ->with('categories', Category::getSelect())
            ->with('added_categories', $categories)
            ->with('attributes', Attribute::all())
            ->with('seo', $product->seo)
	        ->with('languages', Config::get('app.locales_names'))
            ->with('sets', $sets)
            ->with('related', $product->related->pluck('id')->toArray())
            ->with('prev', $prev)
	        ->with('editors', localizationFields(['description', 'instructions', 'security', 'compound', 'shelf_life', 'storage_conditions', 'seo_description']));
    }

    /**
     * Обновление товара
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function adminUpdateAction(Request $request, $id){
//        $rules = $this->rules;
//        $rules['sku'] = "required|unique:products,sku,$id";
        $attributes_error = $this->validateAttributes($request->product_attributes);
//        $validator = Validator::make($request->all(), $rules, $this->messages);

//        if ($validator->fails() || $attributes_error) {
//            return redirect()
//                ->back()
//                ->withInput()
//                ->with('message-error', 'Сохранение не удалось! Проверьте форму на ошибки!')
//                ->withErrors($validator)
//                ->with('attributes_error', $attributes_error);
//        }
        $product = Product::find($id);

        $product_data = $product->fullData();

        $data = $request->only($product->getFillable());

        if(empty($data['stock']))
            $data['stock'] = 1;
        if(empty($data['stock_sync']))
            $data['stock_sync'] = 0;
        if(empty($data['sale']))
            $data['sale'] = 0;
        if(empty($data['sort_priority']))
            $data['sort_priority'] = 0;

        if(empty($data['file_id']) && !empty($request->gallery)){
            $data['file_id'] = $request->gallery[0];
        }

	    $product->fill($data);

//	    $current_time = time();
//	    if(!empty($request->sale) && !empty($request->sale_price) && (empty($request->sale_from) || $request->sale_from <= $current_time) && (empty($request->sale_to) || $request->sale_to >= $current_time)){
//		    $product->price = $request->sale_price;
//	    }else{
//		    $product->price = $request->original_price;
//	    }
        $product->price = 0;

        if(env('REDIS_CACHE')) {
            Redis::command('setbit', ["product_visible", $product->id, empty($product->visible) ? 0 : 1]);
            Redis::command('setbit', ["product_stock", $product->id, $product->stock > 0 ? 1 : 0]);
            Redis::command('setbit', ["product_not_stock", $product->id, $product->stock === -2 ? 1 : 0]);
            Redis::command('setbit', ["product_under_the_order", $product->id, $product->stock === -1 ? 1 : 0]);
            Redis::command('setbit', ["product_expected", $product->id, $product->stock === 0 ? 1 : 0]);
            Redis::command('zadd', ['prices', $product->price * 100, $product->id]);

            $prods = Product::select(['products.id', 'localization.value as name'])->leftJoin('localization', function($join){
                $join->on('products.id', '=', 'localization.localizable_id')
                    ->where('localization.localizable_type', 'Products')
                    ->where('localization.language', 'ru')
                    ->where('localization.field', 'name');
            })
                ->orderBy('localization.value', 'asc')
                ->get();

            Redis::command('del', ['names']);
            foreach($prods as $i => $prod){
                Redis::command('zadd', ['names', $i, $prod->id]);
            }
        }
	    $product->push();
	    $product->saveSeo($request);
	    $product->saveLocalization($request);
	    $product->saveGalleries($request);

        if(!empty($request->related)){
            foreach(Product::whereIn('id', $request->related)->get() as $prod){
                $r = [$product->id];
                foreach($request->related as $rel_id){
                    if($rel_id != $prod->id)
                        $r[] = $rel_id;
                }
                $prod->related()->syncWithoutDetaching($r);
            }
        }
        $product->related()->sync($request->related);

        // Обновление коллекций категорий Redis
        if(env('REDIS_CACHE')) {
            $current_categories = $product->categories->pluck('id')->toArray();
            $new_categories = [];
            $category = new Category();
            if(!empty($request->product_category_id)){
                foreach ($request->product_category_id as $category_id) {
                    $ids = $category->getParentCategories($category_id);
                    foreach($ids as $id){
                        if(!empty($id))
                            $new_categories[] = $id;
                    }
                }
            }
            foreach($current_categories as $category_id){
                Redis::command('setbit', ["category_$category_id", $product->id, 0]);
            }
            foreach(array_unique($new_categories) as $category_id){
                Redis::command('setbit', ["category_$category_id", $product->id, 1]);
            }
        }
        $product->categories()->sync($request->product_category_id);

        // Обновление коллекций атрибутов Redis
        if(env('REDIS_CACHE')) {
            $values = [];
            if(!empty($request->product_attributes)){
                foreach($request->product_attributes as $attr){
                    $values[] = $attr['value'];
                }
            }
            $current_values = $product->values->pluck('id')->toArray();
            foreach($current_values as $value_id){
                Redis::command('setbit', ["attribute_$value_id", $product->id, 0]);
            }
            foreach($values as $i => $value_id){
                Redis::command('setbit', ["attribute_$value_id", $product->id, 1]);
            }
        }
        $product->updateAttributes($request->product_attributes);

        $this->updateVariations($product, $request->variations);

//        if(!empty($request->similar)) {
//            foreach(Product::whereIn('id', $request->similar)->get() as $prod){
//                $r = [$product->id];
//                foreach($request->similar as $rel_id){
//                    if($rel_id != $prod->id)
//                        $r[] = $rel_id;
//                }
//                $prod->similar()->attach($r);
//            }
//        }
//        $product->similar()->sync($request->similar);

        Action::updateEntity(Product::find($product->id), $product_data);

        return redirect(!empty($request->prev) ? $request->prev : '/admin/products')
            ->with('message-success', 'Товар ' . $product->name . ' успешно отредактирован.');
    }

    public function adminUpdatePriceAction(Request $request, $id){
        $user = Sentinel::check();
        if(!$user->hasAccess(['products.view'])){
            return response()->json(['result' => 'error']);
        }

        $product = Product::find($id);
        $product->original_price = $request->price;

        $current_time = time();
        if(!empty($product->sale) && !empty($product->sale_price) && $product->sale_from <= $current_time && $product->sale_to >= $current_time){
            $product->price = $product->sale_price;
        }else{
            $product->price = $request->price;
        }

        $product->save();

        if(env('REDIS_CACHE')) {
            Redis::command('zadd', ['prices', $product->price * 100, $product->id]);
        }

        return response()->json(['result' => 'success']);
    }

    public function adminDuplicateAction($id){
        $product = new Product();
        $original_product = Product::with(['localization', 'categories', 'attributes', 'seo.localization', 'variations.attribute_values', 'galleries'])->find($id)->toArray();

        $product_data = [];
        foreach($product->getFillable() as $key){
            $product_data[$key] = $original_product[$key];
        }

        $product_categories = [];
        foreach($original_product['categories'] as $category){
            $product_categories[] = $category['id'];
        }

        $gallery = [];
        foreach($original_product['galleries'] as $image){
            $gallery[] = $image['file_id'];
        }

        $request = new Request();
        $request_data = [
            'canonical' => '',
            'robots' => '',
            'url' => null,
            'gallery' => $gallery
        ];
        foreach($original_product['localization'] as $localization){
            $request_data[$localization['field'].'_'.$localization['language']] = $localization['value'];
        }
        foreach($original_product['seo']['localization'] as $localization){
            $request_data[$localization['field'].'_'.$localization['language']] = $localization['value'];
        }
        $request_data['variations'] = [];
        foreach($original_product['variations'] as $variation){
            $variation_attributes = [];
            foreach($variation['attribute_values'] as $attr){
                $variation_attributes[] = $attr['id'];
            }
            $request_data['variations'][] = [
                'price' => $variation['price'],
                'stock' => $variation['stock'],
                'id' => $variation_attributes
            ];
        }

        $request->merge($request_data);
        $product->fill($product_data);
        $product->save();
        $product->saveSeo($request);
        $product->saveLocalization($request);
        $product->saveGalleries($request);

        $product->categories()->sync($product_categories);

        if(!empty($original_product['attributes'])){
            $product_attributes = [];
            foreach($original_product['attributes'] as $attribute){
                $product_attributes[] = [
                    'product_id' => $product->id,
                    'attribute_id' => $attribute['attribute_id'],
                    'attribute_value_id' => $attribute['attribute_value_id'],
                ];
            }

            $product->attributes()->createMany($product_attributes);
        }

        if(!empty($request->variations))
            $this->updateVariations($product, $request->variations);

        $product->load('seo');

        if(env('REDIS_CACHE')) {
            Redis::command('setbit', ["product_visible", $product->id, empty($product->visible) ? 0 : 1]);
            Redis::command('setbit', ["product_stock", $product->id, $product->stock > 0 ? 1 : 0]);
            Redis::command('setbit', ["product_not_stock", $product->id, $product->stock === -2 ? 1 : 0]);
            Redis::command('setbit', ["product_under_the_order", $product->id, $product->stock === -1 ? 1 : 0]);
            Redis::command('setbit', ["product_expected", $product->id, $product->stock === 0 ? 1 : 0]);
            Redis::command('zadd', ['prices', $product->price * 100, $product->id]);

            if(!empty($request->product_category_id)){
                foreach($request->product_category_id as $category_id){
                    Redis::command('setbit', ["category_$category_id", $product->id, 1]);
                }
            }

            if(!empty($request->product_attributes)){
                foreach($request->product_attributes as $attr){
                    Redis::command('setbit', ["attribute_".$attr['value'], $product->id, 1]);
                }
            }

            $prods = Product::select(['products.id', 'localization.value as name'])->leftJoin('localization', function($join){
                $join->on('products.id', '=', 'localization.localizable_id')
                    ->where('localization.localizable_type', 'Products')
                    ->where('localization.language', 'ru')
                    ->where('localization.field', 'name');
            })
                ->orderBy('localization.value', 'asc')
                ->get();

            Redis::command('del', ['names']);
            foreach($prods as $i => $prod){
                Redis::command('zadd', ['names', $i, $prod->id]);
            }
        }

        Action::createEntity($product);

        return redirect('/admin/products/edit/'.$product->id)
            ->with('message-success', 'Товар ' . $product->name . ' успешно скопирован.');
    }

	/**
	 * Обновление наличия
	 *
	 * @param Request $request
	 * @param $id
	 *
	 * @return string
	 */
    public function adminUpdateStockAction(Request $request, $id){
        $user = Sentinel::check();
        if(!$user->hasAccess(['products.view'])){
            return response()->json(['result' => 'error']);
        }

        $product = Product::find($id);
        $product->stock = $request->stock;
        $product->save();

        if(env('REDIS_CACHE')) {
            Redis::command('setbit', ["product_stock", $product->id, $product->stock > 0 ? 1 : 0]);
            Redis::command('setbit', ["product_not_stock", $product->id, $product->stock == '-2' ? 1 : 0]);
            Redis::command('setbit', ["product_under_the_order", $product->id, $product->stock == '-1' ? 1 : 0]);
            Redis::command('setbit', ["product_expected", $product->id, $product->stock == '0' ? 1 : 0]);
        }

        return response()->json(['result' => 'success']);
    }

	public function adminUpdateVisibilityAction(Request $request, $id){
		$product = Product::find($id);
		if(empty($product)){
			return json_encode([]);
		}

        $product_data = $product->fullData();

		$product->update(['visible' => $request->stock]);

        Action::updateEntity($product, $product_data);

		return json_encode($product->toArray());
	}

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::find($id);
        $name = $product->name;

        if(!empty($product)){
            $product->delete();
        }

        return redirect()->back()
            ->with('message-success', 'Товар ' . $name . ' успешно удален.');
    }

    /**
     * Получение списка всех атрибутов
     *
     * @param Attribute $attributes
     * @return string|void
     */
    public function getAttributes(Attribute $attributes, Request $request)
    {
        if(!empty($request->ids)){
            $attr = $attributes->whereIn('id', $request->ids)->get();
        }else{
            $attr = $attributes->all();
        }
        $response = [];

        if(!empty($attr)){
            foreach ($attr as $attribute) {
                $response[] = [
                    'attribute_id'    => $attribute->id,
                    'attribute_name'  => $attribute->name
                ];
            }
        }

        return json_encode($response);
    }

    /**
     * Получение списка значений переданного атрибута
     *
     * @param Attribute $attributes
     * @param Request $request
     * @return string|void
     */
    public function getAttributeValues(Attribute $attributes, Request $request)
    {
        $attribute = $attributes->find((int)$request->attribute_id);
        $response = [];

        if ($attribute !== null) {
            foreach ($attribute->values as $value) {
                $response[] = [
                    'attribute_value_id'    => $value->id,
                    'attribute_value'       => $value->name
                ];
            }
        }

        return json_encode($response);
    }

	/**
	 * Живой поиск для модулей
	 *
	 * @param Request $request
	 * @param Product $products
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function livesearch(Request $request, Product $products)
	{
		$search_text = $request->input('search');
		if(strpos($search_text, '%') === 0){
			$search_text = urlencode($search_text);
		}

		if(!empty($request->limit)){
			$limit = $request->limit;
		}else{
			$limit = 8;
		}

//		// Установка текущей страницы пагинации
//		$results = $products->search(trim($search_text), 1, $limit);

        $filter = new Filter();
        $filter->setCategory(1);
        $filter->setSearchText($search_text);
        $results = $filter->getProducts(['id', 'desc'], $limit, 1, 'grid');

		foreach ($results as $result) {
			if ($result) {
				$json[] = [
					'product_id' => $result->id,
					'name'       => $result->name,
					'sku'       => $result->sku,
					'url'        => $result->link(),
					'price'        => $result->price,
					'stock'        => $result->stock,
					'image'        => !empty($result->image) ? $result->image->url() : '/uploads/no_image.jpg',
				];
			}
		}

		if(!empty($json)){
			return response()->json($json);
		}else{
			return response()->json([]);
		}
	}

    /**
     * Страница поиска
     *
     * @param Request $request
     * @param string $page
     * @return mixed
     */
    public function search(Request $request, $page = 'page-1')
    {
        $filter = new Filter();
        $filter->setCategory(1);
        $filter->setPage((int)str_replace('page-', '', $page));
        $search_text = $request->input('text');

        if(strpos($search_text, '%') === 0){
	        $search_text = urlencode($search_text);
        }

        $filter->setSearchText($search_text);

        $view = empty($request->view) || !in_array($request->view, ['grid', 'list']) ? 'grid' : $request->view;

        $products = $filter->getProducts(['id', 'desc'], 18, (int)str_replace('page-', '', $page), $view);

        return view('public.search')
            ->with('products', $products)
            ->with('view', $view)
            ->with('search_text', $search_text)
            ->with('filter', $filter->getFilterAttributes());
    }

    /**
     * Валидация атрибутов товара на одинаковые значения
     *
     * @param $attributes
     * @return bool|string
     */
    public function validateAttributes($attributes){
        $attributes_error = false;

        if(!empty($attributes)){
            foreach($attributes as $product_attribute){
                if(isset($product_attribute['value']))
                    $product_attribute_values[] = $product_attribute['value'];
            }

            foreach(array_count_values($product_attribute_values) as $count_value){
                if ($count_value > 1) {
                    $attributes_error = 'Значения атрибутов не могут быть одинаковы!';
                    break;
                }
            }
        }

        return $attributes_error;
    }

    /**
     * Добавление размеров в вариации
     *
     * @param $data
     * @return mixed
     */
    public function addSizesVariations($data){
        if(isset($data['tables']['product_attributes'])){
            $price = $data['tables']['products']['price'];
            $variations = [];
            foreach ($data['tables']['product_attributes'] as $key => $attr){
                if($attr['attribute_id'] == 7){
                    if(!empty($attr['attribute_value_id'])) {
                        $variations[] = [
                            'id' => [$attr['attribute_value_id']],
                            'price' => $price,
                            'stock' => isset($attr['with_stock']) ? $attr['with_stock'] : 1
                        ];
                    }
                    if(isset($data['tables']['product_attributes'][$key]['with_stock'])){
                        unset($data['tables']['product_attributes'][$key]['with_stock']);
                    }
                }
            }
            $data['tables']['variations'] = $variations;
        }

        return $data;
    }

    public function updatePricesAndStock($data){
        $errors = [];
        foreach($data as $key => $row){
            if(empty($row['tables']['products']['sku'])){
                $errors[] = [
                    'id' => $key+1,
                    'errors' => ['Не указан артикул товара']
                ];
                continue;
            }
            $product = Product::where('sku', $row['tables']['products']['sku'])->first();
            if(empty($product)){
                $errors[] = [
                    'id' => $key+1,
                    'errors' => ['Не удалось найти товар с артикулом: '.$row['tables']['products']['sku']]
                ];
                continue;
            }
            $old_price = $product->price;
            if($old_price == $row['tables']['products']['price']){
                $old_price = $product->old_price;
            }elseif($old_price < $row['tables']['products']['price']){
                $old_price = 0;
            }

            if(!empty($row['tables']['product_attributes'])){
                $stock = 1;
            }else{
                $stock = 0;
            }

            $product->fill([
                'price' => $row['tables']['products']['price'],
                'old_price' => $old_price,
                'stock' => $stock
            ]);

            $product->push();

            if(!empty($row['tables']['product_attributes'])){
                $ids = [];
                $product_attributes = [];
                foreach ($row['tables']['product_attributes'] as $attribute) {
                    if(!empty($attribute['attribute_value_id'])) {
                        $product_attributes[] = [
                            'product_id' => $product->id,
                            'attribute_id' => $attribute['attribute_id'],
                            'attribute_value_id' => $attribute['attribute_value_id'],
                        ];
                    }
                    if(!in_array($attribute['attribute_id'], $ids)){
                        $ids[] = $attribute['attribute_id'];
                    }
                }

                $product->attributes()->whereIn('attribute_id', $ids)->delete();
                $product->attributes()->createMany($product_attributes);
            }

            if(isset($row['tables']['variations'])){
                foreach ($row['tables']['variations'] as $key => $variation) {
                    $row['tables']['variations'][$key]['price'] = $row['tables']['products']['price'];
                }
                $this->updateVariations($product, $row['tables']['variations']);
            }
        }

        return $errors;
    }

    /**
     * Валидация импортируемых данных
     *
     * @param $prepared_data
     * @return array
     */
    public function validate_prepared_data($prepared_data){
        $products = new Product();
        $names = [];
        foreach ($products->select('name')->get() as $product){
            $names[] = $product->name;
        }
        $errors = [];
        foreach ($prepared_data as $id => $row){

            $err = [];

            if(empty($row['tables']['products']['name'])){
                $err[] = 'Не заполнено название товара.';
            }
//            if(in_array($row['tables']['products']['name'], $names)){
//                $err[] = 'Дубль названия товара.';
//            }
//            if(empty($row['tables']['products']['price'])){
//                $err[] = 'Не заполнена цена товара.';
//            }

            foreach ($row['tables']['galleries'] as $image){
                if(empty($image['images'])){
                    $err[] = 'Неизвестное изображение.';
                }
            }

//            foreach ($row['tables']['product_categories'] as $category){
//                if(empty($category['category_id'])){
//                    $err[] = 'Неизвестная категория.';
//                }
//            }

            if(isset($row['tables']['product_attributes'])){
                foreach ($row['tables']['product_attributes'] as $attribute){
                    if(empty($attribute['attribute_value_id'])){
                        $err[] = 'Неизвестное значение атрибута (id атрибута '.$attribute['attribute_id'].').';
                    }
                }
            }

            if(!empty($err)){
                $errors[] = [
                    'id' => $id+1,
                    'errors' => $err
                ];
            }
        }

        return $errors;
    }

    /**
     * Парсинг опций вставки
     *
     * @param $field
     * @return array|bool
     */
    public function get_field_options($field){
        $params = explode('.', $field);
        if(count($params) < 2)
            return false;
        $options = [
            'table' => $params[0],
            'field' => $params[1]
        ];
        $count = count($params);
        if($count > 2){
            for($i=2; $i<$count; $i++){
                if(strpos($params[$i], 'selector') === 0){
                    $options['selector'] = preg_replace('/selector\((.+)\)/', '$1', $params[$i]);
                }elseif(strpos($params[$i], 'attached_field') === 0){
                    if(!isset($options['attached_fields']))
                        $options['attached_fields'] = [];
                    $attached_field = explode(':', preg_replace('/attached_field\((.+)\)/', '$1', $params[$i]), 2);
                    $options['attached_fields'][$attached_field[0]] = $attached_field[1];
                }elseif($params[$i] == 'unique'){
                    $options['unique'] = true;
                }elseif($params[$i] == 'with_stock'){
                    $options['with_stock'] = 1;
                }elseif(strpos($params[$i], 'replace') === 0){
                    if(!isset($options['attached_fields']))
                        $options['attached_fields'] = [];
                    $replace = explode(':', preg_replace('/replace\((.+)\)/', '$1', $params[$i]), 3);
                    $options['replace'] = ['table' => $replace[0], 'find' => $replace[1], 'replaced' => $replace[2]];
                }elseif(strpos($params[$i], 'relations') === 0){
                    $options['relations'] = preg_replace('/relations\((.+)\)/', '$1', $params[$i]);
                }elseif(strpos($params[$i], 'load') === 0){
                    $options['load'] = explode(':', preg_replace('/load\((.+)\)/', '$1', $params[$i]), 2);
                }
            }
        }

        return $options;
    }

    /**
     * Получение одного поля таблицы по другому
     *
     * @param $data
     * @param $table
     * @param $find
     * @param $replaced
     * @return mixed
     */
    public function replace_inserted_data($data, $table, $find, $replaced){
        $model_name = 'App\Models\\'.str_replace(' ', '', ucwords(str_replace('_', ' ', preg_replace('/s$/', '', $table))));

        if(!class_exists($model_name))
            $model_name = 'App\Models\\'.str_replace(' ', '', ucwords(str_replace('_', ' ', $table)));
        if(!class_exists($model_name) || $data == '')
            return null;

        if($table == 'categories'){
            $br = explode('>', $data);
            if(count($br) > 1){
                $table = new $model_name;
                $parent = 0;
                foreach ($br as $name){
                    $result = $table->select($replaced)->where($find, '=', trim($name))->where('parent_id', $parent)->take(1)->get()->first();
                    if(empty($result)){
                        return empty($parent) ? null : $parent;
                    }
                    $parent = $result->id;
                }

                return $result !== null ? $result->$replaced : $result;
            }
        }

        $table = new $model_name;
        $result = $table->select($replaced)->where($find, '=', trim($data))->take(1)->get()->first();

        return $result !== null ? $result->$replaced : $result;
    }

    /**
     * Удаление нежелательных символов
     *
     * @param $value
     */
    function trim_value(&$value)
    {
        if(is_string($value)) {
            $value = preg_replace('/(^"|"$|;$|\.$|,$|,\s?,)/', '', preg_replace('@^\s*|\s*$@u', '', $value));
        }
    }

	/**
	 * Список экспортов
	 *
	 * @param ProductsExport $exports
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function exportList(ProductsExport $exports){
		return view('admin.products.export.index', [
			'exports' => $exports->all()
		]);
	}

	/**
	 * Страница создания нового экспорта
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function createExport(ProductsExport $exports){
		return view('admin.products.export.create', [
			'categories' => Category::select(['categories.id', 'localization.value as name'])
                ->join('localization', 'categories.id', '=', 'localization.localizable_id')
                ->where('localization.localizable_type', 'Categories')
                ->where('localization.field', 'name')
                ->where('localization.language', 'ru')
                ->get(),
			'all_attributes' => Attribute::select(['attributes.id', 'localization.value as name'])
                ->join('localization', 'attributes.id', '=', 'localization.localizable_id')
                ->where('localization.localizable_type', 'Attributes')
                ->where('localization.field', 'name')
                ->where('localization.language', 'ru')
                ->with(['values' => function($query){
                    $query->select(['attribute_values.id', 'attribute_values.attribute_id', 'localization.value as name'])
                        ->join('localization', 'attribute_values.id', '=', 'localization.localizable_id')
                        ->where('localization.localizable_type', 'Values')
                        ->where('localization.field', 'name')
                        ->where('localization.language', 'ru');
                }])
                ->get()
                ->toArray(),
			'schedules' => $exports->getSchedulesNames(),
			'field_types' => $exports->getFieldTypes(),
			'modifications' => $exports->getModifications(),
		]);
	}

	/**
	 * Создание нового экспорта
	 *
	 * @param Request $request
	 * @param ProductsExport $exports
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function storeExport(Request $request, ProductsExport $exports){
		$schedule = null;
		if(!empty($request->schedule) &&  isset($exports->schedules[$request->schedule])) {
			$schedule = (object) [ 'method' => $request->schedule ];

			$schedule->nextRun = time() + $exports->schedules[$request->schedule];

			if(in_array($request->schedule, ['daily', 'weekly', 'monthly', 'quarterly', 'yearly'])){
				$schedule->nextRun = $schedule->nextRun - $schedule->nextRun%86400 - date('Z');
			}
		}

		$data = [
			'name' => $request->name,
			'type' => $request->type,
			'filters' => json_encode($request->filter),
			'structure' => json_encode($request->fields),
			'schedule' => json_encode($schedule),
			'url' => !empty($request->url) ? $request->url : ''
		];

		$id = $exports->insertGetId($data);

		return redirect('/admin/products/export')
			->with('message-success', 'Экспорт "' . $request->name . '" успешно добавлен.');
	}

	/**
	 * Страница изменения настроек экспорта
	 *
	 * @param $id
	 * @param ProductsExport $exports
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function editExport($id, ProductsExport $exports){
		$export = $exports->find($id);

		if(empty($export))
			abort(404);

		return view('admin.products.export.edit', [
			'export' => $export,
			'categories' => Category::all(),
			'all_attributes' => Attribute::select(['attributes.id', 'localization.value as name'])
                ->join('localization', 'attributes.id', '=', 'localization.localizable_id')
                ->where('localization.localizable_type', 'Attributes')
                ->where('localization.field', 'name')
                ->where('localization.language', 'ru')
                ->with(['values' => function($query){
                    $query->select(['attribute_values.id', 'attribute_values.attribute_id', 'localization.value as name'])
                        ->join('localization', 'attribute_values.id', '=', 'localization.localizable_id')
                        ->where('localization.localizable_type', 'Values')
                        ->where('localization.field', 'name')
                        ->where('localization.language', 'ru');
                }])
                ->get()
                ->toArray()
		]);
	}

	/**
	 * Обновление настроек экспорта
	 *
	 * @param $id
	 * @param Request $request
	 * @param ProductsExport $exports
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function updateExport($id, Request $request, ProductsExport $exports){
		$export = $exports->find($id);

		$schedule = $export->schedule;
		if(!empty($request->schedule) && empty($schedule)){
			$schedule = (object)[];
		}

		if(!empty($request->schedule) &&  isset($exports->schedules[$request->schedule])){
			if(!isset($schedule->method) || $schedule->method != $request->schedule){
				$schedule->nextRun = time() + $exports->schedules[$request->schedule];

				if(in_array($request->schedule, ['daily', 'weekly', 'monthly', 'quarterly', 'yearly'])){
					$schedule->nextRun = $schedule->nextRun - $schedule->nextRun%86400 - date('Z');
				}
			}
			$schedule->method = $request->schedule;
		}

		if(empty($request->schedule)){
			if(isset($schedule->updated_at)){
				$schedule->method = '';
				unset($schedule->nextRun);
			}else{
				$schedule = null;
			}
		}

		$data = [
			'name' => $request->name,
			'type' => $request->type,
			'filters' => json_encode($request->filter),
			'structure' => json_encode($request->fields),
			'schedule' => json_encode($schedule),
			'url' => empty($request->url) ? '' : $request->url
		];

		$exports->find($id)->update($data);

		return redirect('/admin/products/export')
			->with('message-success', 'Экспорт "' . $request->name . '" успешно обновлён.');
	}

	/**
	 *  Скачать экспортируемые данные в виде файла
	 *
	 * @param $id
	 * @param ProductsExport $exports
	 *
	 * @return array|null
	 * @throws \PhpOffice\PhpSpreadsheet\Exception
	 * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
	 * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
	 */
	public function downloadExport($id, ProductsExport $exports){
		return $exports->generateFile($id);
	}

	/**
	 * Обновление файла экспотра
	 *
	 * @param $id
	 * @param ProductsExport $exports
	 * @param Request $request
	 *
	 * @return mixed
	 */
	public function refreshExport($id, ProductsExport $exports, Request $request){
		$export = $exports->find($id);

		$schedule = $export->schedule;

		if(!empty($request->start)){
			$schedule->offset = 0;
		}
		$result = $export->generateFile($id, $export->url, 1000, $schedule->offset);
		$schedule->status = $result['saved'] / $result['total'];
		$schedule->offset = $result['saved'];

		if($schedule->status == 1){
			$schedule = $export->completeGeneration($schedule);
		}

		$export->schedule = json_encode($schedule);
		$export->save();

		return $result;

//		if($result['total'] == $result['saved']){
//			return redirect('/admin/products/export')
//				->with('message-success', 'Экспорт "' . $export->name . '" успешно сформирован.');
//		}else{
//			return redirect('/admin/products/export');
//		}
	}

	public function destroyExport($id, ProductsExport $exports){
        $export = $exports->find($id);
        $file = storage_path('app/exports/temp/'.$export->url.'.'.$export->type);
        if(is_file($file)){
            unset($file);
        }

        Action::deleteEntity($export);

        $export->delete();

        return redirect()->back()
            ->with('message-success', 'Экспорт ' . $export->name . ' успешно удален.');
    }


	public function import(ProductsImport $imports){
		return view('admin.products.import.index')
			->with('imports', $imports->all());
	}

	public function uploadImportFile(Request $request, ProductsImport $imports){
        $errors = [];

        if($request->hasFile('import_file')){
            $file = $request->file('import_file');
            $file_name = $file->getClientOriginalName();

            if($request->hasFile('attachments')){
                $attachments = $request->file('attachments');
                $attachments_name = $attachments->getClientOriginalName();
            }else{
                $attachments_name = null;
            }

            $id = $imports->insertGetId([
                'name' => 'Импорт '.date('Y-m-d H:i:s'),
                'file' => $file_name,
                'attachments' => $attachments_name,
                'status' => 0,
//                'errors' => [],
//                'structure' => [],
//                'schedule' => [],
//                'settings' => []
            ]);

            $import = $imports->find($id);

            $path =  storage_path('app/imports/'.$id);

            if(!is_dir($path)){
                mkdir($path, 0777);
            }

            $file->move(storage_path('app/imports/'.$id), $file_name);
            if(isset($attachments)){
                $attachments->move(storage_path('app/imports/'.$id), $attachments_name);
            }

            $path = storage_path('app/imports/'.$id.'/'.$file_name);

            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($path);
            $data = $spreadsheet->getSheet(0)->toArray();

            $headings = array_diff(array_shift($data), array(null));
            array_walk(
                $data,
                function (&$row) use ($headings) {
                    $row = array_combine($headings, array_slice ($row, 0, count($headings)));
                }
            );

            if(!empty($data)){
                $parts_dir = storage_path('app/imports/'.$id.'/parts');
                if(!is_dir($parts_dir)){
                    mkdir($parts_dir, 0777);
                }
                $parts = array_chunk($data, 100);
                $settings = [
                    'total' => count($data),
                    'parts' => []
                ];
                foreach($parts as $i => $part){
                    file_put_contents($parts_dir.'/'.$i.'.json', json_encode($part, JSON_UNESCAPED_UNICODE));
                    $settings['parts'][] = [
                        'name' => $i.'.json',
                        'count' => count($part),
                        'imported' => 0,
                        'errors' => 0
                    ];
                }
                $import->settings = $settings;
            }else{
                $errors[] = 'Файл для импорта пуст.';
            }
        }else{
            $errors[] = 'Не выбран файл для импорта.';
        }

        if(!empty($errors)){
            $this->rmRec(storage_path('app/imports/'.$id));
            $import->delete();
            return ['result' => 'error', 'errors' => $errors];
        }else{
            $import->save();
            return ['result' => 'success', 'redirect' => '/admin/products/import/edit/'.$id];
        }
    }

    private function rmRec($path) {
        if (is_file($path)) return unlink($path);
        if (is_dir($path)) {
            foreach(scandir($path) as $p) if (($p!='.') && ($p!='..'))
                $this->rmRec($path.DIRECTORY_SEPARATOR.$p);
            return rmdir($path);
        }
        return false;
    }

    /**
     * Страница настройки импорта
     *
     * @param $id
     * @param ProductsImport $imports
     * @return mixed
     */
    public function editImport($id, ProductsImport $imports){
	    $import = $imports->find($id);
	    $root_path = storage_path('app/imports/'.$id);

        $product = [];
        if(isset($import->settings->parts[0]->name) && is_file($root_path.'/parts/'.$import->settings->parts[0]->name)){
            $products = json_decode(file_get_contents($root_path.'/parts/'.$import->settings->parts[0]->name));
            if(!empty($products)){
                $product = $products[0];
            }
        }

        $fields = [
            'product.id' => 'ID товара',
            'localization.name_ru' => 'Название товара на русском',
            'localization.name_ua' => 'Название товара на украинском',
            'localization.description_ru' => 'Описание товара на русском',
            'localization.description_ua' => 'Описание товара на украинском',
            'seo.meta_title_ru' => 'Title на русском',
            'seo.meta_title_ua' => 'Title на украинском',
            'seo.meta_description_ru' => 'Meta description на русском',
            'seo.meta_description_ua' => 'Meta description на украинском',
            'seo.url' => 'URL',
            'product.original_price' => 'Базовая цена',
            'product.sale_price' => 'Акционная цена',
            'product.sale' => 'Включение акционной цены',
            'product.sale_from' => 'Дата начала акции',
            'product.sale_to' => 'Дата окончания акции',
            'product.sku' => 'Артикул',
            'product.file_id' => 'Основное фото товара',
            'galleries.file_id' => 'Галлерея фотографий',
            'product.stock' => 'Наличие',
            'category.id' => 'Категории',
            'attribute_values.id' => 'Атрибуты',
        ];

        if(empty($import->structure)){
            $structure = (object)[];
            foreach ($product as $import_field => $data){
                $structure->$import_field = (object)[
                    'type' => '',
                    'not_found' => '',
                    'data' => $data
                ];
            }
        }else{
            $structure = $import->structure;
            foreach ($structure as $title => $value){
                if(isset($product->$title)){
                    $structure->$title->data = $product->$title;
                }else{
                    unset($structure->$title);
                }
            }
        }

        return view('admin.products.import.edit')
            ->with('import', $import)
            ->with('product', $product)
            ->with('structure', $structure)
            ->with('fields', $fields);
    }

    /**
     * Сохранение насироек импорта
     *
     * @param $id
     * @param Request $request
     * @param ProductsImport $imports
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function updateImport($id, Request $request, ProductsImport $imports){
        $import = $imports->find($id);

        $import->name = $request->name;

        $structure = [];
        foreach($request->fields as $field){
            $title = $field['title'];
            unset($field['title']);
            $structure[$title] = $field;
        }

        $import->structure = $structure;

        $settings = $import->settings;
        $settings->type = $request->type;
        $settings->relation = $request->relation;
        $import->settings = $settings;

        $import->save();
//        dd($import, $structure, $request->all(), $settings);
        return redirect('/admin/products/import/edit/'.$import->id)
            ->with('message-success', 'Импорт "' . $import->name . '" успешно обновлён.');
    }

	/**
	 * Запуск следующей итерации импорта
	 *
	 * @param $id
	 * @param ProductsImport $imports
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
    public function nextImportStep($id, ProductsImport $imports){
		$result = $imports->find($id)->runNextImportStep();
		return response()->json($result);
    }

	/**
	 * Повторный запуск импорта
	 *
	 * @param $id
	 * @param ProductsImport $imports
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
    public function refreshImport($id, ProductsImport $imports){
	    $imports->find($id)->refreshImport();
	    return response()->json(['progress' => 0]);
    }

	/**
	 * Удаление импорта
	 *
	 * @param $id
	 * @param ProductsImport $imports
	 *
	 * @return $this
	 */
    public function destroyImport($id, ProductsImport $imports){
    	$import = $imports->find($id);
    	$name = $import->name;
	    $this->rmRec(storage_path('app/imports/'.$id));
	    $import->delete();
	    return redirect('/admin/products/import')
		    ->with('message-success', 'Импорт "' . $name . '" удалён.');
    }

    /**
     * Страница обновления базы Redis
     *
     * @return mixed
     */
    public function adminRedisSyncAction(){
//	    Redis::command('FLUSHDB');

        return view('admin.products.redis.index')
            ->with('products_pages', ceil(Product::count()/1000))
            ->with('categories_pages', ceil(Category::count()/5))
            ->with('attributes_pages', ceil(AttributeValue::count()/5))
            ->with('sales_pages', ceil(Sale::count()/5));
    }

    private function bitmap_ids($bitmap){
        $bytes = unpack('C*', $bitmap);
        $bin = join(array_map(function($byte){
            return sprintf("%08b", $byte);
        }, $bytes));
        return array_keys(str_split($bin), 1);
    }

    /**
     * Процесс обновления базы Redis
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function adminRedisSyncProgress(Request $request){
        if($request->action == 'products'){
            return $this->redisProductsSync($request->page);
        }elseif($request->action == 'categories'){
            return $this->redisCategoriesSync($request->page);
        }elseif($request->action == 'attributes'){
            return $this->redisAttributesSync($request->page);
        }elseif($request->action == 'sales'){
            return $this->redisSalesSync($request->page);
        }elseif($request->action == 'search'){
            return $this->redisSearchIndex($request->page);
        }

        return response()->json(['result' => 'error']);
    }

    /**
     * Установка битмапов для фильтрируемых свойств товара
     *
     * @param $page
     * @return \Illuminate\Http\JsonResponse
     */
    private function redisProductsSync($page){
        $products = Product::select(['id', 'stock', 'visible', 'price'])->limit(1000)->offset(1000 * ($page - 1))->get();

        if($page == 1){
            Redis::command('del', ['product_visible']);
            Redis::command('del', ['product_stock']);
            Redis::command('del', ['product_not_stock']);
            Redis::command('del', ['product_under_the_order']);
            Redis::command('del', ['product_expected']);
            Redis::command('del', ['prices']);
            Redis::command('del', ['names']);

            $prods = Product::select(['products.id', 'localization.value as name'])->leftJoin('localization', function($join){
                $join->on('products.id', '=', 'localization.localizable_id')
                    ->where('localization.localizable_type', 'Products')
                    ->where('localization.language', 'ru')
                    ->where('localization.field', 'name');
                })
                ->orderBy('localization.value', 'asc')
                ->get();

            foreach($prods as $i => $prod){
                Redis::command('zadd', ['names', $i, $prod->id]);
            }
        }

        $visible = ['product_visible'];
        $stock = ['product_stock'];
        $not_stock = ['product_not_stock'];
        $under_the_order = ['product_under_the_order'];
        $expected = ['product_expected'];

        foreach($products as $product){
            $visible[] = 'SET';
            $visible[] = 'u1';
            $visible[] = $product->id;
            $visible[] = (int)!empty($product->visible);

            $stock[] = 'SET';
            $stock[] = 'u1';
            $stock[] = $product->id;
            $stock[] = $product->stock > 0 ? 1 : 0;

            $not_stock[] = 'SET';
            $not_stock[] = 'u1';
            $not_stock[] = $product->id;
            $not_stock[] = $product->stock === -2 ? 1 : 0;

            $under_the_order[] = 'SET';
            $under_the_order[] = 'u1';
            $under_the_order[] = $product->id;
            $under_the_order[] = $product->stock === -1 ? 1 : 0;

            $expected[] = 'SET';
            $expected[] = 'u1';
            $expected[] = $product->id;
            $expected[] = $product->stock === 0 ? 1 : 0;

            Redis::command('zadd', ['prices', $product->price * 100, $product->id]);
        }

        Redis::command('bitfield', $visible);
        Redis::command('bitfield', $stock);
        Redis::command('bitfield', $not_stock);
        Redis::command('bitfield', $under_the_order);
        Redis::command('bitfield', $expected);

        return response()->json(['result' => 'success']);
    }

    /**
     * Установка битмапов для категорий товаров
     *
     * @param $page
     * @return \Illuminate\Http\JsonResponse
     */
    private function redisCategoriesSync($page){
        $categories = Category::select(['id'])->limit(5)->offset(5 * ($page - 1))->orderBy('id')->get();
        foreach($categories as $category){
            $ids = array_merge([$category->id], $category->getChildrenCategories($category->id));
            $products = Category::select(['prod.product_id as id'])->join('product_categories AS prod', 'categories.id', '=', 'prod.category_id')->whereIn('categories.id', $ids)->get()->pluck('id')->unique()->sort()->values()->all();

	        Redis::command('del', ['category_'.$category->id]);
	        foreach(array_chunk($products, 1000) as $data){
		        $command = ['category_'.$category->id];

		        foreach($data as $product_id){
			        $command[] = 'SET';
			        $command[] = 'u1';
			        $command[] = $product_id;
			        $command[] = 1;
		        }

		        Redis::command('bitfield', $command);
	        }
        }

        return response()->json(['result' => 'success', 'ids' => $categories->pluck('id')->toArray(),  'keys' => Redis::command('keys', ['*'])]);
    }

    /**
     * Установка битмапов для атрибутов товаров
     *
     * @param $page
     * @return \Illuminate\Http\JsonResponse
     */
    private function redisAttributesSync($page){
        $values = AttributeValue::select(['id'])->limit(5)->offset(5 * ($page - 1))->get();
        foreach($values as $value){
            $products = $value->products()->select('product_id')->get()->pluck('product_id')->unique()->sort()->values()->all();

	        Redis::command('del', ['attribute_'.$value->id]);
	        foreach(array_chunk($products, 100) as $data){
		        $command = ['attribute_'.$value->id];

		        foreach($data as $product_id){
			        $command[] = 'SET';
			        $command[] = 'u1';
			        $command[] = $product_id;
			        $command[] = 1;
		        }

		        Redis::command('bitfield', $command);
	        }
        }

        return response()->json(['result' => 'success', 'keys' => Redis::command('keys', ['*'])]);
    }

    /**
     * Установка битмапов для акций товаров
     *
     * @param $page
     * @return \Illuminate\Http\JsonResponse
     */
    private function redisSalesSync($page){
        $sales = Sale::select(['id'])->limit(5)->offset(5 * ($page - 1))->get();
        foreach($sales as $sale){
            $products = $sale->products()->select('product_id')->get()->pluck('product_id')->unique()->sort()->values()->all();

	        Redis::command('del', ['sale_'.$sale->id]);
	        foreach(array_chunk($products, 100) as $data){
		        $command = ['sale_'.$sale->id];

		        foreach($data as $product_id){
			        $command[] = 'SET';
			        $command[] = 'u1';
			        $command[] = $product_id;
			        $command[] = 1;
		        }

		        Redis::command('bitfield', $command);
	        }
        }

        return response()->json(['result' => 'success', 'keys' => Redis::command('keys', ['*'])]);
    }

    private function redisSearchIndex($page){
        $items = Product::select(['id', 'external_id'])
            ->with(['localization' => function($query){
                $query->select(['localizable_id', 'value'])->whereIn('field', ['name', 'description']);
            }])
            ->take(100)
            ->offset(100 * ($page - 1))
            ->get();

        if($page == 1){
	        Redis::command('ZREMRANGEBYSCORE', ['words', '-inf', '+inf']);
	        Redis::command('del', ['words']);
        }

        foreach($items as $item){
            $tags = [mb_strtolower($item->external_id)];
            foreach($item->localization as $value){
                $tags = array_merge($tags, explode(' ', mb_strtolower(strip_tags($value->value))));
            }

            foreach(array_unique($tags) as $tag){
                Redis::command('zincrby', ['words:'.$tag, 1, $item->id]);
            }
        }

        return response()->json(['result' => 'success']);
    }

    /**
     * Обновление вариаций
     *
     * @param $product
     * @param $variations
     */
    public function updateVariations($product, $variations){
        $current_variations = $product->variations;
        $add = [];
        $update = [];
        $remove = $current_variations->pluck(['id'])->toArray();
        if(!empty($variations)){
            foreach ($variations as $variation){
                $add_var = true;
                foreach ($current_variations as $var){
                    if(empty($variation['id'])){
                        $add_var = false;
                        break;
                    }
                    if($var->price == $variation['price']){
                        if(empty(array_diff($variation['id'], $var->attribute_values->pluck(['id'])->toArray()))){
                            $add_var = false;
                            unset($remove[array_search($var->id,$remove)]);
                            if($var->stock != $variation['stock']){
                                $update[$var->id] = $variation;
                            }
                            break;
                        }
                    }
                }
                if($add_var){
                    $add[] = $variation;
                }
            }
        }
        foreach ($remove as $id){
            $v = new Variation();
            $v->find($id)->update(['product_id' => null]);
        }
        foreach ($add as $variation){
            if(!empty($variation['price']) && !empty($variation['id'])){
                $v = new Variation();
                $id = $v->insertGetId(['product_id' => $product->id, 'price' => $variation['price'], 'stock' => $variation['stock']]);
                $v->find($id)->attribute_values()->attach($variation['id']);
            }
        }
        foreach ($update as $id => $variation){
            $v = Variation::where('id', $id);
            $v->update(['stock' => $variation['stock']]);
        }
    }

    public function filterAction(Request $request){
        $orders = [
            'price-asc' => ['price', 'asc'],
            'price-desc' => ['price', 'desc'],
            'name-asc' => ['name', 'asc'],
            'name-desc' => ['name', 'desc'],
        ];

        $filter = new Filter();
        $category = Category::find($request->category);
        $filter->setCategory($category);
        if(!empty($request->page) && $request->page > 1)
            $filter->setPage((int)$request->page);

        if(!empty($request->sale)){
            $filter->setSale($request->sale);
        }

        if(!empty($request->stock)){
            $filter->setStock($request->stock);
        }

        if($request->price_min !== null){
            $filter->setMinPrice($request->price_min);
        }

        if($request->price_max !== null){
            $filter->setMaxPrice($request->price_max);
        }

        if(!empty($request->search_text)){
            $filter->setSearchText($request->search_text);
        }

        $filter->setAttributesValues($request->filters);
        $products = $filter->getProducts(isset($orders[$request->order]) ? $orders[$request->order] : ['id', 'desc'], !empty($request->limit) ? $request->limit : 18, !empty($request->page) && $request->page > 1 ? $request->page : 1);
        $attributes = $filter->getFilterAttributes(true);

        return response()->json([
            'result' => 'success',
            'html' => view('public.layouts.products_list')
                ->with('category', $filter->getCurrentCategory())
                ->with('products', $products)
                ->with('view', !empty($request->view) ? $request->view : 'grid')
                ->with('is_search', !empty($request->search_text))
                ->render(),
            'filters' => view('public.layouts.filters')
                ->with('filter', $attributes)
                ->with('current_categories', !empty($category) ? collect((array)$category->get_parent_categories())->pluck('id')->toArray() : '')
                ->render(),
            'mob_filters' => view('public.layouts.mob_filters')
                ->with('filter', $attributes)
                ->render(),
            'checked' => view('public.layouts.selected_filters')
                ->with('selected_filters', $filter->getSelectedFilters())
                ->with('category', $category)
                ->render(),
            'link' => $filter->getCurrentLink($request->order, $request->view, !empty($request->limit) ? $request->limit : 12),
            'pagination' => view('public.layouts.pagination')
                ->with('paginator', $products)
                ->with('js', true)
                ->render()
        ]);
    }

    public function promImport(){
        $path =  storage_path('app/products.csv');
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($path);
        $data = $spreadsheet->getSheet(0)->toArray();

        $headings = array_diff(array_shift($data), array(null));
        array_walk(
            $data,
            function (&$row) use ($headings) {
                $row = array_combine($headings, array_slice ($row, 0, count($headings)));
            }
        );

        if(!empty($data)){
            $products_data = [];
            $sku = null;
            foreach($data as $row){
                if(!empty($row['sku'])){
                    $sku = $row['sku'];
                    $item = [];
                    foreach($row as $key => $col){
                        if(!empty($col)){
                            if(in_array($key, ['description', 'name'])){
                                $item[$key]['ru'] = $col;
                            }elseif(in_array($key, ['sku', 'gtin', 'image', 'image_label', 'manufacturer', 'meta_description', 'meta_keyword', 'meta_title', 'price',
                                'short_description', 'small_image', 'small_image_label', 'thumbnail', 'thumbnail_label', 'url_key', 'url_path', 'weight', 'qty', 'is_in_stock'])){
                                $item[$key] = $col;
                            }elseif($key != '_store'){
                                $item[$key][] = $col;
                            }
                        }else{
                            $item[$key] = null;
                        }
                    }
                }else{
                    $item = $products_data[$sku];
                    foreach($row as $key => $col){
                        if(!empty($col)){
                            if($row['_store'] == 'ua' && in_array($key, ['description', 'name'])){
                                $item[$key]['ua'] = $col;
                            }elseif(!in_array($key, ['_store', 'sku', 'gtin', 'image', 'image_label', 'manufacturer', 'meta_description', 'meta_keyword', 'meta_title', 'price',
                                'short_description', 'small_image', 'small_image_label', 'thumbnail', 'thumbnail_label', 'url_key', 'url_path', 'weight', 'qty', 'is_in_stock'])){
                                $item[$key][] = $col;
                            }
                        }
                    }
                }

                $products_data[$sku] = $item;
            }

//            dd($products_data['I000021210']);

//            $files = new File();
//            foreach($products_data as $sku => $item){
//                if(!empty(Product::where('sku', $sku)->first()))
//                    continue;
//                $product = new Product();
//                $images = [];
//                $image_id = null;
//                if(!empty($item['image']) || !empty($item['_media_image'])){
//                    $images_links = [];
//                    if(!empty($item['image'])){
//                        $images_links[] = 'https://lilov.com.ua/media/catalog/product'.$item['image'];
//                    }
//
//                    if(!empty($item['_media_image'])){
//                        foreach($item['_media_image'] as $link){
//                            if(!in_array('https://lilov.com.ua/media/catalog/product'.$link, $images_links))
//                                $images_links[] = 'https://lilov.com.ua/media/catalog/product'.$link;
//                        }
//                    }
//
//                    foreach($images_links as $i => $url){
//                        $file = $files->uploadFromUrlImages(trim($url));
//                        $images[] = $file->id;
//                        if(empty($i))
//                            $image_id = $file->id;
//                    }
//                }
//
//                $categories = [];
//                if(!empty($item['_category'])){
//                    $parent_cat_name = '';
//                    foreach($item['_category'] as $cat_name){
//                        if(!empty($parent_cat_name)){
//                            $cat_name = trim(str_replace($parent_cat_name.'/', '', $cat_name));
//                        }
//                        $category = Localization::select('localizable_id')->where(['field' => 'name', 'language' => 'ru', 'value' => $cat_name, 'localizable_type' => 'Categories'])->first();
//                        if(!empty($category)){
//                            $categories[] = $category->localizable_id;
//                        }
//                        $parent_cat_name = !empty($parent_cat_name) ? ($parent_cat_name.'/'.$cat_name) : $cat_name;
//                    }
//                }
//
//                $product->external_id = null;
//                $product->name = $item['name']['ru'];
//                $product->sku = $sku;
//                $product->gtin = $item['gtin'];
//                $product->stock = (int)$item['qty'];
//                $product->visible = true;
//                $product->original_price = (float)$item['price'];
//                $product->price = (float)$item['price'];
//                $product->file_id = !empty($image_id) ? $image_id : null;
//                $product->save();
//
//                // Обновление коллекций видимости и цен Redis
//                if(env('REDIS_CACHE')) {
//                    Redis::command('setbit', ["product_visible", $product->id, empty($product->visible) ? 0 : 1]);
//                    Redis::command('setbit', ["product_stock", $product->id, $product->stock > 0 ? 1 : 0]);
//                    Redis::command('setbit', ["product_not_stock", $product->id, $product->stock === -2 ? 1 : 0]);
//                    Redis::command('setbit', ["product_under_the_order", $product->id, $product->stock === -1 ? 1 : 0]);
//                    Redis::command('setbit', ["product_expected", $product->id, $product->stock === 0 ? 1 : 0]);
//                    Redis::command('zadd', ['prices', $product->price * 100, $product->id]);
//                }
//
//                $request = new Request();
//                $rd = [
//                    'name_ru' => $item['name']['ru'],
//                    'name_ua' => isset($item['name']['ua']) ? $item['name']['ua'] : $item['name']['ru'],
//                    'description_ru' => $item['description']['ru'],
//                    'description_ua' => isset($item['description']['ua']) ? $item['description']['ua'] : $item['description']['ru'],
//                    'short_description_ru' => $item['short_description'],
//                    'short_description_ua' => $item['short_description'],
//                    'gallery' => $images,
//                    'product_category_id' => $categories
//                ];
//
//                $rd['url'] = '/'.Str::slug($item['url_path']);
//                $rd['seo_name_ru'] = $item['name']['ru'];
//                $rd['seo_name_ua'] = isset($item['name']['ua']) ? $item['name']['ua'] : $item['name']['ru'];
//                $rd['meta_title_ru'] = $item['meta_title'];
//                $rd['meta_title_ua'] = $item['meta_title'];
//                $rd['meta_keywords_ru'] = $item['meta_keyword'];
//                $rd['meta_keywords_ua'] = $item['meta_keyword'];
//                $rd['meta_description_ru'] = $item['meta_description'];
//                $rd['meta_description_ua'] = $item['meta_description'];
//
//                $request->merge($rd);
//                $product->saveSeo($request);
//                $product->saveLocalization($request);
//                $product->saveGalleries($request);
//                $product->categories()->sync($categories);
//
//                $values = [];
//                if(!empty($item['color'])){
//                    foreach($item['color'] as $color){
//                        $value = $this->updateAttributeValue(1, $color);
//                        if(!empty($value)){
//                            $values[$value->id] = ['attribute_id' => 1];
//                        }
//                    }
//                }
//                if(!empty($item['manufacturer'])){
//                    $value = $this->updateAttributeValue(2, $item['manufacturer']);
//                    if(!empty($value)){
//                        $values[$value->id] = ['attribute_id' => 2];
//                    }
//                }
//
//                // Обновление коллекций атрибутов Redis
//                if(env('REDIS_CACHE')) {
//                    $current_values = $product->values->pluck('id')->toArray();
//                    foreach($current_values as $value_id){
//                        if(!isset($values[$value_id])){
//                            Redis::command('setbit', ["attribute_$value_id", $product->id, 0]);
//                        }
//                    }
//                    foreach($values as $value_id => $attr){
//                        if(!in_array($value_id, $current_values)){
//                            Redis::command('setbit', ["attribute_$value_id", $product->id, 1]);
//                        }
//                    }
//                }
//
//                $product->values()->sync($values);
//            }

//            foreach($products_data as $sku => $item){
//                if(!empty($item['_links_related_sku'])){
//                    $product = Product::where('sku', $sku)->first();
//                    $product->related()->sync(Product::whereIn('sku', $item['_links_related_sku'])->get()->pluck('id')->toArray());
//                }
//            }
        }
    }

    private function updateAttribute($name){
        $slug = Str::slug(str_replace(['-', '_', ' '], '', mb_strtolower(translit($name))), '');
        $attribute = Attribute::select('attributes.*')
            ->leftJoin('localization', function($leftJoin) {
                $leftJoin->on('attributes.id', '=', 'localization.localizable_id')
                    ->where('localization.localizable_type', '=', 'Attributes')
                    ->where('localization.language', '=', 'ru')
                    ->where('field', 'name');
            })
            ->orWhere('slug', $slug)
            ->first();

        if(empty($attribute)){
            $attr_id = Attribute::insertGetId(['slug' => $slug, 'external_id' => null]);
            $attribute = Attribute::find($attr_id);
            $request = new Request();
            $request->merge([
                'name_ru' => $name,
                'name_ua' => $name
            ]);
            $attribute->saveLocalization($request);
        }else{
            if(empty($attribute->external_id)){
                $attribute->external_id = null;
                $attribute->save();
            }

            $attribute->load('localization');
            if($attribute->name != $name){
                $request = new Request();
                $request->merge([
                    'name_ru' => $name,
                    'name_ua' => $name
                ]);
                $attribute->saveLocalization($request);
            }
        }

        return $attribute;
    }

    private function updateAttributeValue($attr_id, $name){
        $slug = Str::slug(str_replace(['-', '_', '', '!'], '', mb_strtolower(translit($name))), '');

        if($attr_id == 1){
            $value = AttributeValue::select('attribute_values.*')
                ->leftJoin('localization', function ($leftJoin) {
                    $leftJoin->on('attribute_values.id', '=', 'localization.localizable_id')
                        ->where('localization.localizable_type', '=', 'Values')
                        ->where('localization.language', '=', 'ru')
                        ->where('field', 'name');
                })
                ->where('localization.value', $name)
                ->where('attribute_id', $attr_id)
                ->first();
        }else{
            $value = AttributeValue::select('attribute_values.*')
                ->where('value', $slug)
                ->where('attribute_id', $attr_id)
                ->first();
        }

        if(empty($value)){
            $value_id = AttributeValue::insertGetId([
                'external_id' => null,
                'attribute_id' => $attr_id,
                'value' => $slug,
                'file_id' => null
            ]);
            $value = AttributeValue::find($value_id);
            $request = new Request();
            $request->merge([
                'name_ru' => $name,
                'name_ua' => $name
            ]);
            $value->saveLocalization($request);
        }else{
            $value->load('localization');
            if($value->name != $name){
                $request = new Request();
                $request->merge([
                    'name_ru' => $name,
                    'name_ua' => $name
                ]);
                $value->saveLocalization($request);
            }
        }

        return $value;
    }

    public function promFiltersImport(){
        $path =  storage_path('app/public/products.xlsx');
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($path);
        $data = $spreadsheet->getSheet(0)->toArray();

        $headings = array_diff(array_shift($data), array(null));
        $attribute_keys = [];
        $id_key = null;
        foreach($headings as $i => $n){
            if($n == 'Название_Характеристики' && $headings[$i+2] == 'Значение_Характеристики'){
                $attribute_keys[$i] = $i+2;
            }elseif(empty($id_key) && $n == 'Уникальный_идентификатор'){
                $id_key = $i;
            }
        }

        if(!empty($data)){
            $products = [];
            foreach($data as $r => $row){
                if($r){
                    $attributes = [];
                    if(!empty($row[26])){
                        $attributes['Производитель'] = [$row[26]];
                    }
                    if(!empty($row[27])){
                        $attributes['Страна производитель'] = [$row[27]];
                    }

                    foreach($attribute_keys as $a => $v){
                        if(!empty($row[$a]) && !empty($row[$v])){
                            if(empty($attributes[$row[$a]]))
                                $attributes[$row[$a]] = [];

                            $attributes[$row[$a]][] = $row[$v];
                        }
                    }

                    if(!empty($attributes))
                        $products[$row[$id_key]] = $attributes;
                }
            }

            foreach($products as $product_id => $attributes){
                $product = Product::where('external_id', $product_id)->first();
                if(!empty($product)){
                    $values = [];
                    foreach($attributes as $attr_name => $attr_values){
                        $attribute = $this->updateAttribute($attr_name);
                        foreach($attr_values as $val_name){
                            $value = $this->updateAttributeValue($attribute->id, $val_name);
                            if(!empty($value)){
                                $values[$value->id] = ['attribute_id' => $attribute->id];
                            }
                        }
                    }

                    // Обновление коллекций атрибутов Redis
                    if(env('REDIS_CACHE')) {
                        $current_values = $product->values->pluck('id')->toArray();
                        foreach($current_values as $value_id){
                            if(!isset($values[$value_id])){
                                Redis::command('setbit', ["attribute_$value_id", $product->id, 0]);
                            }
                        }
                        foreach($values as $value_id => $attr){
                            if(!in_array($value_id, $current_values)){
                                Redis::command('setbit', ["attribute_$value_id", $product->id, 1]);
                            }
                        }
                    }

                    $product->values()->sync($values);
                }
            }
        }

//        $path =  storage_path('app/public/stock.xlsx');
//        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($path);
//        $data = $spreadsheet->getSheet(0)->toArray();
//        foreach($data as $i => $row){
//            if($i){
//                $id = $row[0];
//                if(!empty($id)){
//                    Product::where('id', $id)->update([
//                        'name' => $row[1],
//                        'sku' => $row[2],
//                        'price' => str_replace(',', '.', $row[3]),
//                        'original_price' => str_replace(',', '.', $row[3]),
//                        'stock' => $row[4],
//                    ]);
//                    Localization::where([
//                        'field' => 'name',
//                        'language' => 'ru',
//                        'localizable_type' => 'Products',
//                        'localizable_id' => $id
//                    ])->update([
//                        'value' => $row[1]
//                    ]);
//                }
//            }
//        }
    }

    public function popupAction(Request $request){
        $product = Product::find($request->id);

        return view('public.layouts.product_popup')
            ->with('product', $product)
            ->with('type', $request->type);
    }
}
