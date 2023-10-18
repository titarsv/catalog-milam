<?php

namespace App\Http\Controllers;

use Illuminate\Pagination\Paginator;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Filter;
use App\Models\Action;
use App\Models\Sale;
use Validator;
use Config;
use App;
use DB;


class SalesController extends Controller
{

    private $rules = [
        'name_ru' => 'required'
    ];

    private $messages = [
        'name_ru.required' => 'Поле должно быть заполнено!'
    ];

    public function salesList($data){
        if(isset($data->params[0]) && strpos($data->params[0], 'page-') === 0){
            $page = (int)str_replace('page-', '', $data->params[0]);
        }else{
            $page = 1;
        }

        Paginator::currentPageResolver(function() use ($page){
            return $page;
        });

        $sales = Sale::where('show_from', '<=', DB::raw('now()'))->where('show_to', '>=', DB::raw('now()'))->where('beauty', 0)->paginate(5);

        if($sales->currentPage() > $sales->lastPage()){
            abort(404);
        }

        return view('public.sales')
            ->with('seo', $data->seo)
            ->with('sales', $sales);
    }

	/**
	 * Страница акции
	 *
	 * @param $data
	 *
	 * @return \Illuminate\Http\JsonResponse
	 * @throws \Throwable
	 */
	public function showAction($data){
		$filter = new Filter();
        $category = Category::find(1);
        $filter->setCategory($category)->setSale($data->seo->seotable->id);

		if(isset($data->params)){
			foreach ($data->params as $param) {
				if(!empty($param))
                    $filter->setParam($param);
			}
		}

		if(!empty($filter->getUndefined())){
			abort( 404 );
		}

        $orders = [
            'date-desc' => ['id', 'desc'],
            'price-asc' => ['price', 'asc'],
            'price-desc' => ['price', 'desc'],
        ];

		return view('public.sale')
			->with('seo', $data->seo)
            ->with('category', $category)
            ->with('subcategories', $category->children)
            ->with('sale', $data->seo->seotable)
			->with('products', $filter->getProducts(isset($orders[$data->request->order]) ? $orders[$data->request->order] : ['id', 'desc'], 18))
			->with('filter', $filter->getFilterAttributes(true))
            ->with('selected_filters', $filter->getSelectedFilters())
            ->with('price', $filter->getPriceSlider())
            ->with('view', empty($data->request->view) || !in_array($data->request->view, ['tile', 'list']) ? 'tile' : $data->request->view)
			->with('path', $data->request->path());
	}

    /**
     * Список акций в админпанели
     *
     * @return \Illuminate\Http\Response
     */
    public function adminIndexAction(){
        return view('admin.sales.index')->with('sales', Sale::paginate(20));
    }

    /**
     * Страница создания акции
     *
     * @return mixed
     */
    public function adminCreateAction(){
        return view('admin.sales.create')
            ->with('languages', Config::get('app.locales_names'))
	        ->with('editors', localizationFields(['body', 'seo_description']));
    }

	/**
	 * Создание акции
	 *
	 * @param Request $request
	 * @param Sale $sales
	 *
	 * @return $this
	 */
    public function adminStoreAction(Request $request, Sale $sales){
        $validator = Validator::make($request->all(), $this->rules, $this->messages);

        if($validator->fails()){
            return redirect()
                ->back()
                ->withInput()
                ->with('message-error', 'Сохранение не удалось! Проверьте форму на ошибки!')
                ->withErrors($validator);
        }

        $sales->fill($request->only('sale_percent', 'status', 'show_from', 'show_to'));
        $sales->file_id = !empty($request->file_id) ? $request->file_id : null;
//        $sales->file_xs_id = !empty($request->file_xs_id) ? $request->file_xs_id : null;
//        $sales->preview_id = !empty($request->preview_id) ? $request->preview_id : null;
        $sales->save();
        $sales->saveSeo($request);
        $sales->saveLocalization($request);

        Action::createEntity($sales);

        return redirect('/admin/sales')
            ->with('message-success', 'Акция ' . $sales->name . ' успешно добавлена.');
    }

	/**
	 * Страница изменения акции
	 *
	 * @param Request $request
	 * @param $id
	 *
	 * @return mixed
	 */
    public function adminEditAction(Request $request, $id){
        $sale = Sale::find($id);

        if(!empty($request->prev)){
	        $prev = $request->prev;
        }else{
	        $prev = app('url')->previous();
        }

        return view('admin.sales.edit')
            ->with('sale', $sale)
            ->with('products', $sale->productsList(!empty($request->page) ? $request->page : 1))
            ->with('prev', $prev)
            ->with('languages', Config::get('app.locales_names'))
	        ->with('editors', localizationFields(['body', 'seo_description']))
	        ->with('seo', $sale->seo);
    }

	/**
	 * Обновление акции
	 *
	 * @param Request $request
	 * @param $id
	 * @param Sale $sales
	 *
	 * @return $this
	 */
    public function adminUpdateAction(Request $request, $id, Sale $sales){
        $rules = $this->rules;

        $validator = Validator::make($request->all(), $rules, $this->messages);

        if($validator->fails()){
            return redirect()
                ->back()
                ->withInput()
                ->with('message-error', 'Сохранение не удалось! Проверьте форму на ошибки!')
                ->withErrors($validator);
        }

        $sale = $sales->find($id);

        $sale_data = $sale->fullData();

        $sale->fill($request->only('sale_percent', 'status', 'show_from', 'show_to'));
        $sale->file_id = !empty($request->file_id) ? $request->file_id : null;
//        $sale->file_xs_id = !empty($request->file_xs_id) ? $request->file_xs_id : null;
//        $sale->preview_id = !empty($request->preview_id) ? $request->preview_id : null;
        $sale->save();
        $sale->saveSeo($request);
        $sale->saveLocalization($request);

        Action::updateEntity($sales->find($id), $sale_data);

        if(!empty($request->prev)){
	        return redirect()->to($request->prev)
                 ->with('message-success', 'Акция ' . $sale->name . ' успешно обновлена.');
        }else{
	        return redirect('/admin/sales')
		        ->with('message-success', 'Акция ' . $sale->name . ' успешно обновлена.');
        }
    }

    /**
     * Удаление акции
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function adminDestroyAction($id){
        $sale = Sale::find($id);

        Action::deleteEntity($sale);

        $sale->delete();

        return redirect('/admin/sales')
            ->with('message-success', 'Акция ' . $sale->name . ' успешно удалена.');
    }
}
