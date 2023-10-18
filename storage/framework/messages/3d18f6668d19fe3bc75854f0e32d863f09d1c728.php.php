<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Coupon;
use App\Models\Action;
use Validator;
use App;

class CouponsController extends Controller
{
    private $messages = [
        'code.required' => 'Поле должно быть заполнено!',
        'price.required_without' => 'Поле должно быть заполнено!',
        'percent.required_without' => 'Поле должно быть заполнено!',
    ];

    /**
     * Список купонов в админпанели
     *
     * @return \Illuminate\Http\Response
     */
    public function adminIndexAction(){
        return view('admin.coupons.index')->with('coupons', Coupon::paginate(20));
    }

    public function adminCreateAction(Request $request){
        if(!empty($request->prev)){
            $prev = $request->prev;
        }else{
            $prev = app('url')->previous();
        }

        return view('admin.coupons.create')->with('prev', $prev);
    }

    public function adminGenerateCodeAction(){
        $code = Str::random();
        $i = 0;
        while(!empty(Coupon::where('code', $code)->first())){
            $code = Str::random();
            $i++;
            if($i == 100){
                break;
            }
        }

        return $code;
    }

	/**
	 * Создание купона
	 *
	 * @param Request $request
	 * @param Coupon $coupons
	 *
	 * @return $this
	 */
    public function adminStoreAction(Request $request, Coupon $coupons){
        $rules = [
            'code' => 'required|unique:coupons,code',
            'price' => 'required_without:percent',
            'percent' => 'required_without:price',
        ];

        $messages = [
            'code.required' => 'Код не указан!',
            'price.required_without' => 'Размер скидки не указан!',
            'percent.required_without' => 'Размер скидки не указан!',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if($validator->fails()){
            $errors = [];
            foreach($validator->errors()->messages() as $key => $vals){
                $errors[] = $vals[0];
            }
            return response()->json(['result' => 'error', 'errors' => $errors]);
        }

        $data = $request->only(['name', 'code', 'price', 'percent', 'disposable', 'min_total', 'without_sale']);
        $data['disposable'] = empty($request->disposable);
        $data['from'] = date('Y-m-d', strtotime($request->from));
        $data['to'] =  date('Y-m-d', strtotime($request->to));
        if(in_array($request->scope, ['products', 'categories'])){
            $data['scope'] = json_encode(['type' => $request->scope, 'ids' => explode(',', $request->{'scope_'.$request->scope})]);
        }else{
            $data['scope'] = null;
        }

        if(!isset($data['price']))
            $data['price'] = null;
        if(!isset($data['percent']))
            $data['percent'] = null;
        if(!isset($data['min_total']))
            $data['min_total'] = 0;
        if(!isset($data['without_sale']))
            $data['without_sale'] = 0;

        if(!empty($data['percent']) && $data['percent'] > 100){
            $data['percent'] = 100;
        }

        $coupons->fill($data);
        $coupons->save();

        Action::createEntity($coupons);

        return redirect('/admin/coupons/edit/'.$coupons->id);
    }

	/**
	 * Страница изменения купона
	 *
	 * @param Request $request
	 * @param $id
	 *
	 * @return mixed
	 */
    public function adminEditAction(Request $request, $id){
        if(!empty($request->prev)){
	        $prev = $request->prev;
        }else{
	        $prev = app('url')->previous();
        }

        return view('admin.coupons.edit')
            ->with('coupon', Coupon::find($id))
            ->with('prev', $prev);
    }

	/**
	 * Обновление купона
	 *
	 * @param Request $request
	 * @param $id
	 * @param Coupon $coupons
	 *
	 * @return $this
	 */
    public function adminUpdateAction(Request $request, $id, Coupon $coupons){
        $rules = [
            'code' => 'required|unique:coupons,code,'.$id,
            'price' => 'required_without:percent',
            'percent' => 'required_without:price',
        ];

        $validator = Validator::make($request->all(), $rules, $this->messages);

        if($validator->fails()){
            return redirect()
                ->back()
                ->withInput()
                ->with('message-error', 'Сохранение не удалось! Проверьте форму на ошибки!')
                ->withErrors($validator);
        }

        $coupon = $coupons->find($id);

        $coupon_data = $coupon->fullData();

        $data = $request->only(['name', 'code', 'price', 'percent', 'disposable', 'min_total', 'without_sale']);
        $data['disposable'] = empty($request->disposable);

        if(!empty($request->from))
            $data['from'] = date('Y-m-d', strtotime($request->from));
        else
            $data['from'] = null;

        if(!empty($request->to))
            $data['to'] = date('Y-m-d', strtotime($request->to));
        else
            $data['to'] = null;

        if(in_array($request->scope, ['products', 'categories'])){
            $data['scope'] = json_encode(['type' => $request->scope, 'ids' => explode(',', $request->{'scope_'.$request->scope})]);
        }else{
            $data['scope'] = null;
        }

        if(!isset($data['price']))
            $data['price'] = null;
        if(!isset($data['percent']))
            $data['percent'] = null;
        if(!isset($data['min_total']))
            $data['min_total'] = 0;
        if(!isset($data['without_sale']))
            $data['without_sale'] = 0;

        if(!empty($data['percent']) && $data['percent'] > 100){
            $data['percent'] = 100;
        }

        $coupon->fill($data);
        $coupon->save();

        Action::updateEntity($coupons->find($id), $coupon_data);

        if(!empty($request->prev)){
	        return redirect()->to($request->prev)
                 ->with('message-success', 'Купон ' . $coupon->name . ' успешно обновлён.');
        }else{
	        return redirect('/admin/sales')
		        ->with('message-success', 'Купон ' . $coupon->name . ' успешно обновлён.');
        }
    }

    /**
     * Удаление купона
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function adminDestroyAction($id){
        $coupon = Coupon::find($id);

        Action::deleteEntity($coupon);

        $coupon->delete();

        return redirect('/admin/coupons')
            ->with('message-success', 'Купон ' . $coupon->code . ' успешно удалён.');
    }
}
