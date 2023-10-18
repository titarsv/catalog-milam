<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Redis;
use Illuminate\Pagination\Paginator;
use App\Models\Category;
use App\Models\Product;
use App\Models\Page;

class SitemapController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $locale = app()->getLocale();

        $categories = Category::select(['id', 'parent_id'])->where('status', 1)->whereNull('parent_id')
            ->with(['children' => function($query) use ($locale){
                $query->select('id', 'parent_id', 'file_id')->where('status', 1)->with(['localization' => function($query) use($locale){
                    $query->select(['field', 'language', 'value', 'localizable_type', 'localizable_id'])->where('language', $locale);
                }])
                ->with(['seo' => function($query){
                    $query->select(['seotable_id', 'url']);
                }])
                ->withCount('children')
                ->with(['attributes' => function($query){
                    $query->where('attributes.id', '!=', 3)
                        ->where('attributes.id', '!=', 4)
                        ->with('values.localization');
                }])
                ->withCount(['products' => function($query){
                    $query->where('stock', '>', 0)->where('visible', 1);
                }])
                ->with(['children' => function($query) use ($locale){
                    $query->select('id', 'parent_id')->where('status', 1)->with(['localization' => function($query) use($locale){
                        $query->select(['field', 'language', 'value', 'localizable_type', 'localizable_id'])->where('language', $locale);
                    }])
                    ->with(['seo' => function($query){
                        $query->select(['seotable_id', 'url']);
                    }])
                    ->withCount('children')
                    ->with(['attributes' => function($query){
                        $query->where('attributes.id', '!=', 3)
                            ->where('attributes.id', '!=', 4)
                            ->with('values.localization');
                    }])
                    ->withCount(['products' => function($query){
                        $query->where('stock', '>', 0)->where('visible', 1);
                    }]);
                }]);
            }])
            ->with(['localization' => function($query) use($locale){
                $query->select(['field', 'language', 'value', 'localizable_type', 'localizable_id'])->where('language', $locale);
            }])
            ->with(['seo' => function($query){
                $query->select(['seotable_id', 'url']);
            }])
            ->withCount('children')
            ->with(['attributes' => function($query){
                $query->where('attributes.id', '!=', 3)
                    ->where('attributes.id', '!=', 4)
                    ->with('values.localization');
            }])
            ->withCount(['products' => function($query){
                $query->where('stock', '>', 0)->where('visible', 1);
            }])
            ->get();

        $results = ["product_visible", "product_stock"];
        foreach($categories[0]->children as $c => $cat){
            $cat_results = array_merge($results, ["category_".$cat->id]);
            foreach($cat->attributes as $a => $attr){
                foreach($attr->values as $v => $val){
                    $attr_results = array_merge($cat_results, ["attribute_".$val->id]);
                    $params = ["and", "count"];
                    foreach($attr_results as $result){
                        $params[] = $result;
                    }
                    Redis::command('bitop', $params);
                    $count = Redis::bitcount('count');
                    if(empty($count))
                        unset($categories[0]->children[$c]->attributes[$a]->values[$v]);
                }
            }

            if(!empty($cat->children)){
                foreach($cat->children as $s => $subcat){
                    $cat_results = array_merge($results, ["category_".$subcat->id]);
                    foreach($subcat->attributes as $a => $attr){
                        foreach($attr->values as $v => $val){
                            $attr_results = array_merge($cat_results, ["attribute_".$val->id]);
                            $params = ["and", "count"];
                            foreach($attr_results as $result){
                                $params[] = $result;
                            }
                            Redis::command('bitop', $params);
                            $count = Redis::bitcount('count');
                            if(empty($count))
                                unset($categories[0]->children[$c]->children[$s]->attributes[$a]->values[$v]);
                        }
                    }
                }
            }
        }

        return view('public.sitemap')
            ->with('categories_count', Category::count())
            ->with('categories', $categories)
            ->with('pages', Page::with('seo', 'localization')->get());
    }

    public function products($page = 1){
	    $p = str_replace('page-', '', $page);
	    Paginator::currentPageResolver(function () use ($p) {
		    return $p;
	    });
	    $products = Product::where('stock', '>', 0)
            ->where('visible', 1)
            ->with(['seo', 'localization'])
            ->paginate(50);

	    return view('public.sitemap-products')
            ->with('products', $products);
    }
}
