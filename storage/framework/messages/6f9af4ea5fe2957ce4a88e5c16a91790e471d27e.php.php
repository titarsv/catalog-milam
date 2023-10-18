<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Newpost;
use App\Models\Order;
use App\Models\Setting;
use App\Models\User;
use App\Models\LiqPay;
use App\Models\Justin;
use App\Models\Coupon;
use App\Models\WayForPay;
use Carbon\Carbon;
use Cartalyst\Sentinel\Native\Facades\Sentinel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Validator;
use App;

class CheckoutController extends Controller
{
    /**
     * Оформление заказа
     *
     * @param $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function showAction($data){
        $request = $data->request;
        if($request->method() == 'POST'){
            return $this->createOrder($data);
        }

        $cart = new Cart;

        $settings = new Setting();
        $newpost = new Newpost();

        $cities = [];
        foreach($newpost->getMainCities() as $city){
            $cities[$city->id] = app()->getLocale() == 'ua' ? $city->name_ua : $city->name_ru;
        }

        return view('public.checkout')
            ->with('cart', $cart->current_cart())
            ->with('cities', $cities)
            ->with('delivery_cost', 0)
            ->with('seo', $data->seo)
            ->with('methods', $settings->get_setting('delivery_methods'))
            ->with('payments', $settings->get_setting('payment_methods'));
    }

    /**
     * Создание заказа
     *
     * @param $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function createOrder($data){
        $request = $data->request;
        $order = new Order();
        $cart = new Cart();
        $users = new User();
        $cart = $cart->current_cart();

        if(!$cart->total_quantity){
            return response()->json(['error' => ['cart' => 'В корзине нет товаров!']]);
        }

        $rules = [
            'phone'     => 'required|regex:/^[0-9\-! ,\'\"\/+@\.:\(\)]+$/',
            'name' => 'required',
            'email' => 'required|regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix',
//            'payment' => 'required',
//            'delivery' => 'required',
        ];

        $messages = [
            'phone.required'    => 'Вы не указали телефон!',
            'phone.regex'       => 'Некорректный номер телефона!',
            'email.required'    => 'Вы не указали email!',
            'email.regex'       => 'Некорректный email!',
            'name.required'     => 'Вы не указали имя!',
            'payment.required'  => 'Не выбран способ оплаты!',
            'delivery.required' => 'Не выбран способ доставки!'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if($validator->fails() || is_null($cart)){
            $errors = is_null($cart) ? 'Ваша корзина пуста!' : $validator->messages();
            return response()->json(['error' => $errors]);
        }

        $errors = $this->validateFields($request->all());
        if($errors){
            return response()->json(['error' => $errors]);
        }

        if(empty($request->email)){
            $request->email = 'email'.rand(0, 1000000).'@placeholder.com';
            while($users->checkIfUnregistered($request->phone, $request->email)){
                $request->email = 'email'.rand(0, 1000000).'@placeholder.com';
            }
        }

        $user = Sentinel::check();

        if(!$user){
            $existed_user = $users->checkIfUnregistered($request->phone, $request->email);

            if(!is_null($existed_user)) {
                $user = $existed_user;
            } else {
                $register = new LoginController();
                $user = $register->storeAsUnregistered($request);
            }
        }

        $user = User::find($user->id);

        $delivery_method = $request->delivery;
        $delivery_info = [
            'method'    => $delivery_method,
            'info'      => in_array($delivery_method, ['emc', 'dhl', 'fedex', 'newpost_international', 'tnt']) ? $request->international : $request->$delivery_method,
            'delivery_cost' => 0
        ];

        $delivery_info['info']['country'] = $request->country;
        if($request->country == 'ukraine'){
            $delivery_info['info']['city_id'] = $request->city_id;
            $newpost = new Newpost();
            $city = $newpost->getCityByCid($request->city_id);
            if(!empty($city))
                $delivery_info['info']['city'] = $city->name_ru;
        }else{
            $delivery_info['info']['city'] = $request->city;
        }

        $cart = $cart->update_cart();

        if(!empty($request->promo)){
            $promo = [];
            foreach($request->promo as $p){
                $promo[] = trim($p);
            }
        }else{
            $promo = null;
        }

        $data = [
            'user_id'   => $user->id,
            'products'  => $cart->products,
            'total_quantity'    => $cart->total_quantity,
            'total_price'       => $cart->total_price,
            'total_sale'       => $cart->total_sale + $cart->payment_sale,
            'user_info'         => json_encode([
                'name'  => !empty($request->name) ? $request->name : $user->first_name . ' ' . $user->last_name,
                'email' => !empty($request->email) ? $request->email : $user->email,
                'phone' => !empty($request->phone) ? $request->phone : (isset($user->user_data->phone) ? $user->user_data->phone : ""),
                'comment' => $request->comment,
                'not_call' => !empty($request->callback) ? true : false,
                'promo' => $promo
            ], JSON_UNESCAPED_UNICODE),
            'delivery'  => json_encode($delivery_info, JSON_UNESCAPED_UNICODE),
            'payment'   => $request->payment,
            'status_id' => 0,
            'coupon_id' => $cart->coupon_id,
            'created_at' => Carbon::now()
        ];

        $id = $order->insertGetId($data);
        if(!empty($cart->coupon_id) && $cart->coupon->disposable){
            $cart->coupon->used = 1;
            $cart->coupon->save();
        }
        $order = Order::find($id);
//        foreach($order->getProducts() as $item){
//            if(!$item['product']->stock_sync){
//                $item['product']->stock = $item['product']->stock - (int)$item['quantity'];
//                if($item['product']->stock < 0){
//                    $item['product']->stock = 0;
//                }
//                $item['product']->save();
//            }
//        }

        $this->sendOrderMails($id);
        if(!env('APP_DEBUG')){
            $this->sendToTelegram($order);
        }

        if($request->payment == 'online' && !empty($order->total_price - $order->total_sale)){
            $wayforpay = new WayForPay();
            return response()->json(['success' => 'wayforpay', 'form' => $wayforpay->getPaymentForm($order->find($id))]);
        }else{
            return response()->json(['success' => 'redirect', 'order_id' => $id]);
        }
    }

    public function sendOrderMails($order_id){
        $cart = new Cart();
        $order = Order::find($order_id);
        $order->update(['status_id' => 1]);
        $order_user = json_decode($order->user_info, true);
        $cart = $cart->current_cart();
        $cart->current_cart()->delete();

        $setting = new Setting();
        Mail::send('emails.order', ['user' => $order_user, 'order' => $order, 'admin' => true], function($msg) use ($setting, $order_id){
            $msg->from('admin@'.str_replace(['http://', 'https://'], '', env('APP_URL')), 'Интернет-магазин milam.ua');
            $msg->to(get_object_vars((object)$setting->get_setting('notify_emails')));
            $msg->subject('Новый заказ №'.$order_id);
        });

        Mail::send('emails.order', ['user' => $order_user, 'order' => $order, 'admin' => false], function($msg) use ($order_user){
            $msg->from('admin@'.str_replace(['http://', 'https://'], '', env('APP_URL')), 'Интернет-магазин milam.ua');
            $msg->to($order_user['email']);
            $msg->subject('Новый заказ');
        });
    }

    private function sendToTelegram(Order $order){
        $settings = new Setting();
        $telegram = (array)$settings->get_setting('telegram');
        if(!empty($telegram['token'])){
            $bot = new \TelegramBot\Api\Client($telegram['token']);
            $user = json_decode($order->user_info);
            $delivery = $order->getDeliveryInfo();
            $products = $order->getProducts();

            $text = "Новый заказ на сайте: https://milam.ua/admin/orders/edit/".$order->id."\n";
            $text .= "Сумма заказа: ".((float)$order->total_price - (float)$order->total_sale)."грн.\n";
            $text .= "Контакты покупателя: ".(isset($user->name) ? $user->name : '')." ".(isset($user->phone) ? $user->phone : '')."\n";
            $text .= "Доставка: ".
                (!empty($delivery['method']) ? $delivery['method']." " : "").
                (!empty($delivery['region']) ? $delivery['region']." " : "").
                (!empty($delivery['city']) ? $delivery['city']." " : "").
                $order->getAddressAttribute()."\n";
            $text .= "Оплата: ".$order->getPaymentMethodAttribute()."\n";
            $text .= "Заказаны следующие товары:\n";

            foreach($products as $product_id => $item) {
                $text .= $item['product']->name." (".$item['quantity']."шт.)\n";
            }

            foreach($telegram['clients'] as $id => $client){
                if($client->moderated){
                    $bot->sendMessage($client->chat, $text);
                }
            }
        }
    }

    /**
     * Получене данных для Liqpay
     *
     * @param $order
     * @return \Illuminate\Http\JsonResponse
     * @throws \League\Flysystem\Exception
     */
	public function getLiqpayData($order){
		$public_key = config('liqpay.public_key');
		$private_key = config('liqpay.private_key');
		$liqpay = new LiqPay($public_key, $private_key);
		$checkout = $liqpay->cnb_form([
			'action'    => 'pay',
			'amount'    => $order->total_price - $order->total_sale,
			'currency'  => 'UAH',
			'description'   => 'Оплата заказа №' . $order->id . ' на сайте Milam',
			'order_id'  => $order->id,
			'sandbox'   => 0,
			'version'   => 3,
			'result_url' => url('/checkout/complete?order_id=' . $order->id)
		]);

		return response()->json(['success' => 'liqpay', 'liqpay' => $checkout, 'order_id' => $order->id]);
	}

    /**
     * Подгрузка различных темплейтов в зависимости от выбранного способа доставки
     *
     * @param Request $request
     * @param Newpost $newpost
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function delivery(Request $request, Newpost $newpost){
        if (!is_null($request->cookie('current_order_id'))) {
            $current_order_id = $request->cookie('current_order_id');
        } elseif ($request->order_id) {
            $current_order_id = $request->order_id;
        }else {
            $current_order_id = 0;
        }

        $cart = new Cart();
        $cart = $cart->current_cart();

        $city = $request->city;

        if($request->delivery == 'newpost') {
            $regions = $newpost->getRegions();
            $region_id = null;
            $cities = null;
            $city_id = null;
            $warehouses = null;
            if(!empty($city)){
                $city = $newpost->findCity($city);

                if(!empty($city)){
                    $region_id = $city->region_id;
                    $cities = $newpost->getCities($region_id);
                    $city_id = $city->city_id;
                    $warehouses = $newpost->getWarehouses($city_id);
                }
            }

            return response()->json([
                'delivery' => view('public.checkout.newpost', [
                    'regions' => $regions,
                    'region_id' => $region_id,
                    'cities' => $cities,
                    'city_id' => $city_id,
                    'warehouses' => $warehouses,
                    'current_order_id' => $current_order_id,
                    'lang' => App::getLocale()
                ])->render(),
                'confirmation' => view('public.checkout.confirmation', [
                    'cart' => $cart,
                    'delivery_cost' => 0,
                ])->render()
            ]);
        }elseif($request->delivery == 'justin'){
            $justin = new Justin();
            $regions = $justin->getRegions();
            $region_id = null;
            $city_id = null;
            $cities = null;
            $warehouses = null;

            return response()->json([
                'delivery' => view('public.checkout.justin', [
                    'regions' => $regions,
                    'region_id' => $region_id,
                    'cities' => $cities,
                    'city_id' => $city_id,
                    'warehouses' => $warehouses,
                    'current_order_id' => $current_order_id,
                    'subtotal' => 0,
                    'total' => 0,
                    'lang' => App::getLocale()
                ])->render(),
                'confirmation' => view('public.checkout.confirmation', [
                    'cart' => $cart,
                    'delivery_cost' => 0,
                ])->render()
            ]);
        }else{
            $region_name = '';

            if(!empty($city)){
                $city = $newpost->findCity($city);

                if(!empty($city)){
                    $region = DB::table('newpost_regions')->where('region_id', $city->region_id)->first();

                    if(!empty($region)){
                        $region_name = $region->{'name_'.App::getLocale()};
                    }
                }
            }

            return response()->json([
                'delivery' => view('public.checkout.' . $request->delivery, [
                    'current_order_id' => $current_order_id,
                    'region' => $region_name
                ])->render(),
                'confirmation' => view('public.checkout.confirmation', [
                    'cart' => $cart,
                    'delivery_cost' => 0,
                ])->render()
            ]);
        }
    }

    /**
     * Валидация полей доставки
     *
     * @param $data
     * @return mixed
     */
    public function validateFields($data)
    {
        $errors = [];

        if(isset($data['delivery'])) {
            if ($data['delivery'] == 'newpost') {
                $rules = [
                    'newpost.region' => 'not_in:0',
                    'newpost.city' => 'not_in:0',
                    'newpost.warehouse' => 'not_in:0',
                ];

                $messages = [
                    'newpost.region.not_in' => 'Выберите область!',
                    'newpost.city.not_in' => 'Выберите город!',
                    'newpost.warehouse.not_in' => 'Выберите отделение!',
                ];
            } elseif ($data['delivery'] == 'courier') {
                $rules = [
                    'courier.street' => 'required',
                    'courier.house' => 'required',
//                    'city' => 'required',
                ];

                $messages = [
                    'courier.street.required' => 'Не указана улица!',
                    'courier.house.required' => 'Не указан номер дома!',
//                    'city.required' => 'Не указан город!',
                ];
            } elseif ($data['delivery'] == 'ukrpost') {
                $rules = [
//                    'ukrpost.region' => 'required',
                    'city_id' => 'required',
                    'ukrpost.index' => 'required|numeric',
                    'ukrpost.street' => 'required',
                    'ukrpost.house' => 'required',
                ];

                $messages = [
//                    'ukrpost.region.required' => 'Не указана область!',
                    'city_id.required' => 'Не указан город!',
                    'ukrpost.index.required' => 'Не указан почтовый индекс!',
                    'ukrpost.index.numeric' => 'Индекс должен быть числовым!',
                    'ukrpost.street.required' => 'Не указана улица!',
                    'ukrpost.house.required' => 'Не указан номер дома!',
                ];
            } elseif ($data['delivery'] == 'other') {
                $rules = [
                    'other.name' => 'required',
                    'other.region' => 'required',
                    'other.city' => 'required',
                    'other.warehouse' => 'required',
                ];

                $messages = [
                    'other.name.required' => 'Не указана служба доставки!',
                    'other.region.required' => 'Не указана область!',
                    'other.city.required' => 'Не указан город!',
                    'other.warehouse' => 'Не указано отделение!',
                ];
            } elseif (!$data['delivery']) {
                $errors = [
                    'delivery' => 'Не выбран метод доставки!',
                ];
            }
        }else{
            $errors = [
                'delivery' => 'Не выбран метод доставки!',
            ];
        }

        $rules['payment'] = 'required|in:fitting,cod,online,cash';
//        $rules['payment'] = 'required|in:card,cash,prepayment';
        $messages['payment.required'] = 'Не выбран способ оплаты!';
        $messages['payment.in'] = 'Выбран некорректный способ оплаты!';

        $validator = Validator::make($data, $rules, $messages);

        if($validator->fails()){
            $errors = array_merge($errors, $validator->messages()->toArray());
        }

        if (!empty($errors))
            return $errors;

        return false;
    }

    /**
     * Загрузка списка городов Новой Почты
     *
     * @param Request $request
     * @param Newpost $newpost
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCities(Request $request, Newpost $newpost){
        if(!is_object($request)){
            return response()->json(['error' => 'При загрузке городов произошла ошибка. Пожалуйста, попробуйте еще раз!']);
        }
        $region = $newpost->getRegionRef($request->region_id);

        if (!empty($region)) {
            $cities = $newpost->getCities($region->region_id);
        } else {
            return response()->json(['error' => 'При загрузке городов произошла ошибка. Пожалуйста, попробуйте еще раз!']);
        }

        if ($cities) {
            return response()->json(['success' => $cities]);
        } else {
            return response()->json(['error' => 'При загрузке городов произошла ошибка. Пожалуйста, попробуйте еще раз!']);
        }
    }

    /**
     * Загрузка списка отделений Новой Почты
     *
     * @param Request $request
     * @param Newpost $newpost
     * @return \Illuminate\Http\JsonResponse
     */
    public function getWarehouses(Request $request, Newpost $newpost){
        $city = $newpost->getCityRef($request->city_id);

        if(!is_null($city) && isset($city->city_id)){
            $warehouses = [];
            foreach($newpost->getWarehouses($city->city_id) as $warehouse){
                $warehouses[$warehouse->id] = app()->getLocale() == 'ua' ? $warehouse->address_ua : $warehouse->address_ru;
            }
//            $warehouses = $newpost->getWarehouses($city->city_id);
        }else{
            return response()->json(['error' => 'При загрузке отделений произошла ошибка. Пожалуйста, попробуйте еще раз!']);
        }

        if($warehouses){
            return response()->json(['success' => $warehouses, 'msg' => __('Выбрать отделение')]);
        }else{
            return response()->json(['error' => 'При загрузке отделений произошла ошибка. Пожалуйста, попробуйте еще раз!']);
        }
    }

    /**
     * Загрузка списка городов Justin
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getJustinCities(Request $request){
        $justin = new Justin();

        if(!is_object($request)){
            return response()->json(['error' => 'При загрузке городов произошла ошибка. Пожалуйста, попробуйте еще раз!']);
        }
        $cities = $justin->getCities($request->region_id);

        if($cities){
            return response()->json(['success' => $cities]);
        }else{
            return response()->json(['error' => 'При загрузке городов произошла ошибка. Пожалуйста, попробуйте еще раз!']);
        }
    }

    /**
     * Загрузка списка отделений Justin
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getJustinWarehouses(Request $request){
        $justin = new Justin();
        $warehouses = $justin->getWarehouses($request->city_id);

        return response()->json(['success' => $warehouses]);
    }

    public function applyCoupon(Request $request, Coupon $coupons){
        if(!empty($request->code)){
            $coupon = $coupons->where('code', $request->code)->where('used', 0)->first();

            if(!empty($coupon)){
                $cart = new Cart();
                $cart = $cart->current_cart();

                if((!empty($coupon->user_id) && $coupon->user_id != $cart->user_id) || $coupon->used || (!empty($coupon->shelf_life) && strtotime($coupon->shelf_life) < time())){
                    return response()->json(['result' => 'error', 'msg' => trans('app.promo_error')]);
                }

                $cart = $cart->addCoupon($coupon->id);

                return response()->json(['result' => 'success', 'cart' => [
                    'count' => $cart->total_quantity,
                    'total' => $cart->total_price,
                    'sale' => $cart->total_sale,
                    'coupon_sale' => $cart->coupon_sale,
                    'html' => view('public.checkout.confirmation')
                        ->with('cart', $cart)->render()
                ]]);
            }
        }

        return response()->json(['result' => 'error', 'msg' => trans('app.promo_error')]);
    }

    public function searchCities(Request $request){
        $newpost = new Newpost();

        $cities = [];
        foreach($newpost->findCities($request->search) as $city){
            $cities[$city->id] = app()->getLocale() == 'ua' ? $city->name_ua : $city->name_ru;
        }

        return response()->json(['result' => 'success', 'cities' => $cities]);
    }

    public function changePayment(Request $request){
        $cart = new Cart();
        $cart = $cart->current_cart();

        if(!empty($cart->cart_data)){
            $cart_data = json_decode($cart->cart_data, true);
        }else{
            $cart_data = [];
        }

        $cart_data['payment'] = $request->payment;

        $cart->update(['cart_data' => json_encode($cart_data)]);

        return response()->json(['result' => 'success', 'cart' => [
            'count' => $cart->total_quantity,
            'total' => $cart->total_price,
            'sale' => $cart->total_sale,
            'coupon_sale' => $cart->coupon_sale,
            'html' => view('public.checkout.confirmation')
                ->with('cart', $cart)->render()
        ]]);
    }
}