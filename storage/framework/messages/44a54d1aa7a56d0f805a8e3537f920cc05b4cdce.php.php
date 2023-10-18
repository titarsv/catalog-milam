<?php

namespace App\Http\Controllers;

use Cartalyst\Sentinel\Native\Facades\Sentinel;
use App\Models\AttributeValue;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Attribute;
use App\Models\Category;
use App\Models\Filter;
use App\Models\Action;
use App\Models\Sale;
use App\Models\Product;
use App\Models\File;
use Validator;
use Config;

class CategoriesController extends Controller
{

    private $rules = [
        'name_ru' => 'required'
    ];

    private $messages = [
        'name_ru.required' => 'Поле должно быть заполнено!'
    ];

	/**
	 * Каталог товаров
	 *
	 * @param $data
	 *
	 * @return \Illuminate\Http\JsonResponse
	 * @throws \Throwable
	 */
	public function showAction($data){
        if(empty($data->seo->seotable)){
            abort(404);
        }
		$filter = new Filter();
        $category = $data->seo->seotable;

        if(empty($category->parent_id)){
            return view('public.categories')
                ->with('seo', $data->seo)
                ->with('category', $category)
                ->with('categories', $category->children);
        }

        $filter->setCategory($category);

        if(isset($data->request['text'])){
            $filter->setSearchText($data->request->input('text'));
        }

        if($data->seo->url != $category->seo->url){
            $data->params = explode('/', str_replace((app()->getLocale() == 'ru' ? '' : '/'.app()->getLocale()).$category->seo->url.'/', '', '/'.$data->request->path()));
        }

		if(isset($data->params)){
			foreach($data->params as $param){
				if(!empty($param))
                    $filter->setParam($param);
			}
		}

		if(!empty($filter->getUndefined())){
			abort(404);
		}

		$orders = [
			'rating-desc' => ['rating', 'desc'],
			'name-asc' => ['name', 'asc'],
			'name-desc' => ['name', 'desc'],
            'price-asc' => ['price', 'asc'],
            'price-desc' => ['price', 'desc'],
		];

        $attributes = $filter->getFilter();
        $page = $filter->getPage();

        if(!empty($attributes)){
            if(count($attributes) > 1
                || count(current($attributes)) > 1
                || (!empty($val_ids) && count($val_ids) > 1)
                || isset($attributes[3]) || isset($attributes[4])){
                $data->seo->robots = 'noindex, nofollow';
            }
        }

        if($page > 1){
            $data->seo->meta_title = $data->seo->meta_title.' '.__('Страница').' '.$page;
            $data->seo->meta_description = $data->seo->meta_description.' '.__('Страница').' '.$page;
            $data->seo->robots = 'index, follow';
            $data->seo->canonical = str_replace('/page-'.$page, '', $data->request->url());
        }

        if($filter->with_price_filter || $filter->with_stock_filter || !empty($data->request['limit']) || !empty($data->request['order'])){
            $data->seo->robots = 'noindex, nofollow';
        }

        if(empty(count($attributes)) && $page == 1 && empty($data->request->order) && empty($data->request->view)){
            $data->seo->canonical = $data->request->url();
        }

        $products = $filter->getProducts(isset($orders[$data->request->order]) ? $orders[$data->request->order] : ['name', 'asc'], !empty($data->request->limit) ? $data->request->limit : 12, $filter->getPage());

		return view('public.catalog')
			->with('seo', $data->seo)
			->with('category', $category)
			->with('products', $products)
            ->with('categories', Category::find(1)->children)
			->with('filter', $filter->getFilterAttributes(true))
			->with('selected_filters', $filter->getSelectedFilters())
			->with('additional_crumb', !empty($data->seo->name) && $data->seo->name != $category->seo->name ? (object)['name' => $data->seo->name] : null);
	}

    /**
     * Список категорий
     *
     * @return \Illuminate\Http\Response
     */
    public function adminIndexAction(){
        return view('admin.categories.index')->with('categories', Category::orderBy('id')->paginate(20));
    }

    /**
     * Страница создания категории
     *
     * @param Category $categories
     * @return mixed
     */
    public function adminCreateAction(Category $categories){
        $all_categories = $categories->getTreeList();

        return view('admin.categories.create')
            ->with('categories', $all_categories)
            ->with('attributes', Attribute::all())
            ->with('languages', Config::get('app.locales_names'))
	        ->with('editors', localizationFields(['seo_description']));
    }

	/**
	 * Создание категории
	 *
	 * @param Request $request
	 * @param Category $categories
	 *
	 * @return $this
	 */
    public function adminStoreAction(Request $request, Category $categories){
        $validator = Validator::make($request->all(), $this->rules, $this->messages);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withInput()
                ->with('message-error', 'Сохранение не удалось! Проверьте форму на ошибки!')
                ->withErrors($validator);
        }

        $data = $request->only('parent_id', 'status');
        $data['slug'] = str_replace(['/', '-', '_'], '', $request->url);
        $categories->fill($data);
        $categories->file_id = !empty($request->file_id) ? $request->file_id : null;
        $categories->sort_order = !empty($request->sort_order) ? $request->sort_order : 0;
	    $name_key = 'name_'.Config::get('app.locale');
        $categories->slug = Str::slug(mb_strtolower(translit($request->$name_key)));
        $categories->save();
	    $categories->saveSeo($request);
	    $categories->saveLocalization($request);
        $categories->saveGalleries($request);

	    $categories->attributes()->sync($request->related_attribute_ids);

        Action::createEntity($categories);

        return redirect('/admin/categories')
            ->with('message-success', 'Категория ' . $categories->name . ' успешно добавлена.');
    }

	/**
	 * Страница изменения категории
	 *
	 * @param Request $request
	 * @param $id
	 *
	 * @return mixed
	 */
    public function adminEditAction(Request $request, $id){
        $category = Category::find($id);
        $attributes = $category->attributes->pluck('id')->toArray();

        if(!empty($request->prev)){
	        $prev = $request->prev;
        }else{
	        $prev = app('url')->previous();
        }

        $all_categories = $category->getTreeList($id);

        return view('admin.categories.edit')
            ->with('attributes', Attribute::all())
            ->with('related_attributes', $attributes)
            ->with('category', $category)
            ->with('prev', $prev)
            ->with('categories', $all_categories)
            ->with('languages', Config::get('app.locales_names'))
	        ->with('editors', localizationFields(['seo_description']))
	        ->with('seo', $category->seo);
    }

	/**
	 * Обновление категории
	 *
	 * @param Request $request
	 * @param $id
	 * @param Category $categories
	 *
	 * @return $this
	 */
    public function adminUpdateAction(Request $request, $id, Category $categories){
        $rules = $this->rules;

        $validator = Validator::make($request->all(), $rules, $this->messages);

        if ($validator->fails()){
            return redirect()
                ->back()
                ->withInput()
                ->with('message-error', 'Сохранение не удалось! Проверьте форму на ошибки!')
                ->withErrors($validator);
        }

        $category = $categories->find($id);

        $category_data = $category->fullData();

        $data = $request->only('parent_id', 'status');
        $data['slug'] = str_replace(['/', '-', '_'], '', $request->url);
        $category->fill($data);
	    $category->file_id = !empty($request->file_id) ? $request->file_id : null;
        $category->sort_order = !empty($request->sort_order) ? $request->sort_order : 0;
        $category->save();
	    $category->saveSeo($request);
	    $category->saveLocalization($request);
        $category->saveGalleries($request);

        $category->attributes()->sync($request->related_attribute_ids);

        Action::updateEntity($categories->find($id), $category_data);

        if(!empty($request->prev)){
	        return redirect()->to($request->prev)
                 ->with( 'message-success', 'Категория ' . $category->name . ' успешно обновлена.' );
        }else{
	        return redirect('/admin/categories')
		        ->with('message-success', 'Категория ' . $category->name . ' успешно обновлена.');
        }
    }

    /**
     * Удаление категории
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function adminDestroyAction($id){
        $category = Category::find($id);

        Action::deleteEntity($category);

        $category->delete();

        return redirect('/admin/categories')
            ->with('message-success', 'Категория ' . $category->name . ' успешно удалена.');
    }

    public function promImport(){
        $path =  storage_path('app/public/categories.xlsx');
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
            $categories = new Category();

            foreach($data as $item){
                if(!empty($item['Номер_родителя'])){
                    $parent = $categories->where('external_id', $item['Номер_родителя'])->first();
                    if(empty($parent)){
                        continue;
                    }
                }
                $category = $categories->where('external_id', $item['Номер_группы'])->first();
                if(empty($category)){
                    $query = $categories->select('categories.*')
                        ->join('localization', 'localization.localizable_id', '=', 'categories.id')
                        ->where('localization.localizable_type', 'Categories')
                        ->where('localization.language', 'ru')
                        ->where('localization.value', $item['Название_группы']);

                    if(!empty($item['Номер_родителя'])){
                        $query->where('parent_id', $parent->id);
                    }

                    $category = $query->first();
                }


                if(!empty($category)){
                    $category->external_id = $item['Номер_группы'];
                    $category->parent_id = !empty($parent) ? $parent->id : 1;
                    $category->save();
                }else{
                    $category_id = Category::insertGetId([
                        'parent_id' => !empty($parent) ? $parent->id : 1,
                        'external_id' => $item['Номер_группы'],
                        'slug' => Str::slug(translit($item['Название_группы']), '-'),
                        'file_id' => null,
                        'sort_order' => 0,
                        'status' => 1
                    ]);
                    $category = Category::find($category_id);
                }

                if(!empty($category)){
                    $request = new Request();
                    $request->merge([
                        'url' => '/'.Str::slug(translit($item['Название_группы']), '-'),
                        'name_ru' => $item['Название_группы'],
                        'name_ua' => $item['Название_группы_укр'],
                        'seo_name_ru' => $item['Название_группы'],
                        'seo_name_ua' => $item['Название_группы_укр'],
                        'meta_title_ru' => $item['HTML_заголовок_группы'],
                        'meta_title_ua' => $item['HTML_заголовок_группы_укр'],
                        'meta_description_ru' => $item['HTML_описание_группы'],
                        'meta_description_ua' => $item['HTML_описание_группы_укр'],
                        'meta_keywords_ru' => $item['HTML_ключевые_слова_группы'],
                        'meta_keywords_ua' => $item['HTML_ключевые_слова_группы_укр']
                    ]);
                    $category->saveSeo($request);
                    $category->saveLocalization($request);
                }
            }
        }
    }

    public function adminChildrenAction($id, Category $category){
        $data = [];
        $categories = $category->select(['id'])
            ->with(['localization', 'children'])
            ->where('parent_id', $id)
            ->get();
        $data['categories'] = [];
        foreach($categories as $category){
            $data['categories'][] = [
                'id' => $category->id,
                'name' => $category->name,
                'has_children' => (bool)$category->children->count()
            ];
        }
        if($id > 0){
            $products = $category->find($id)->products()->select('product_id as id', 'sku', 'name', 'price', 'file_id', 'stock')->with('image')->get();
            $products_arr = [];
            foreach ($products as $product){
                $products_arr[] = [
                    'id' => $product->id,
                    'sku' => $product->sku,
                    'name' => $product->name,
                    'price' => $product->price,
                    'image' => $product->image->url([100, 100]),
                    'stock' => $product->stock
                ];
            }
            $data['products'] = $products_arr;
        }

        return response()->json($data);
    }

    public function adminLivesearchAction(Request $request, Category $categories){
        $data = [];
        foreach(
            $categories
                ->select(['categories.id', 'localization.value as name'])
                ->leftJoin('localization', function($join){
                    $join->on('categories.id', '=', 'localization.localizable_id')
                        ->where('localizable_type', 'Categories')
                        ->where('field', 'name')
                        ->where('language', 'ru');
                })
                ->where('localization.value', 'like', '%'.$request->search.'%')
                ->get() as $category){
            $data[] = [
                'id' => $category->id,
                'name' => $category->name
            ];
        }

        return response()->json($data);
    }
}
