<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Validator;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\ProductsInOrder;
use App\Models\User;
use App\Models\UserData;
use App\Models\Ipay;
use App\Models\Cart;
use App\Models\Newpost;
use App\Models\Justin;
use Carbon\Carbon;
use Cartalyst\Sentinel\Native\Facades\Sentinel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;
use App;

use Illuminate\Support\Facades\Session;

class OrdersController extends Controller
{

    /**
     * Страница благодарности
     *
     * @param Request $request
     *
     * @return $this|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function thanks(Request $request){
        $order = Order::find($request->order_id);
        if($order->viewed)
            return redirect(base_url('/'));

        $order->update(['viewed' => 1]);

        $delivery = $order->getDeliveryInfo();

        return view('public.thanks')
            ->with('order', $order)
            ->with('delivery_cost', isset($delivery['delivery_cost']) ? $delivery['delivery_cost'] : 0);
    }

    public function index(Request $request){
        if (isset($request->status)) {
            $orders = Order::where('status_id', $request->status)->orderBy('id', 'desc')->paginate(20);
        } elseif (isset($request->weeks)) {
            $date = Carbon::now()->subWeek(2);
            $orders = Order::where('created_at', '>', $date)->orderBy('id', 'desc')->paginate(20);
        } else {
            $orders = Order::orderBy('id', 'desc')->paginate(20);
        }

        foreach ($orders as $order) {
            $order->user = json_decode($order->user_info, true);
            $order->date = $order->created_at->format('d.m.Y');
            $order->time = $order->created_at->format('H:i');
            if ($order->status_id) {
                if ($order->status_id == 1) {
                    $order->class = 'warning';
                }elseif($order->status_id == 6){
                    $order->class = 'danger';
                }elseif($order->status_id == 7){
                    $order->class = 'warning';
                } else {
                    $order->class = 'info';
                }
            } else {
                $order->class = 'danger';
            }
        }
        return view('admin.orders.index', [
            'orders' => $orders,
            'order_status' => OrderStatus::all()
        ]);
    }

    public function create(){
        return view('admin.orders.create')->with([
            'orders_statuses' => OrderStatus::all(),
        ]);
    }

    public function store(Request $request){
        $products = [];
        $total_quantity = 0;
        $total_price = 0;
        $history = [];

        if(!empty($request->products)) {
            foreach ($request->products as $product) {
                if (isset($products[$product['code']])) {
                    if ($product['qty']) {
                        $products[$product['code']]['quantity'] = $product['qty'];
                        $total_quantity += $product['qty'];
                        $total_price += $products[$product['code']]['price'] * $product['qty'];
                    } else {
                        unset($products[$product['code']]);
                    }
                } elseif ($product['qty'] > 0) {
                    $prod = Product::find($product['code']);
                    if (!empty($prod)) {
                        $products[$product['code']] = [
                            'quantity' => $product['qty'],
                            'price' => $prod->price,
                            'sale' => 0,
                            'sale_percent' => 0
                        ];
                        $total_quantity += $product['qty'];
                        $total_price += $prod->price * $product['qty'];
                    }
                }
            }
        }

        $user_info = [];
        $user_info['name'] = $request->user_name;
        $user_info['phone'] = $request->user_phone;
        $user_info['email'] = $request->user_email;
        $user_info['comment'] = $request->comment;

        $delivery_info = [];
        $delivery_info['method'] = $request->delivery;
        if($delivery_info['method'] == 'pickup'){
            $delivery_info['info'] = [];
        }elseif($delivery_info['method'] == 'newpost' || $delivery_info['method'] == 'justin'){
            $delivery_info['info'] = [
                'region' => $request->region,
                'city' => $request->city,
                'warehouse' => $request->warehouse
            ];
        }elseif($delivery_info['method'] == 'courier'){
            $delivery_info['info'] = [
                'street' => $request->street,
                'house' => $request->house,
                'apartment' => $request->apartment
            ];
        }elseif($delivery_info['method'] == 'other'){
            $delivery_info['info'] = [
                'name' => $request->name,
                'region' => $request->region,
                'city' => $request->city,
                'warehouse' => $request->warehouse
            ];
        }

        $users = new User();
        $existed_user = $users->checkIfUnregistered($request->user_phone, $request->user_email);
        if(!is_null($existed_user)) {
            $user = $existed_user;
        } else {
            $register = new LoginController();
            $r = new Request();
            $name = explode(' ', $request->user_name);
            $r->merge([
                'first_name' => $name[0],
                'last_name' => isset($name[1]) ? $name[1] : '',
                'phone'     => $request->user_phone,
                'email'     => $request->user_email,
            ]);
            $user = $register->storeAsUnregistered($r);
        }

        $id = Order::insertGetId([
            'user_id'   => $user->id,
            'products' => json_encode($products, JSON_UNESCAPED_UNICODE),
            'total_quantity' => $total_quantity,
            'total_price' => $total_price,
            'status_id' => $request->status,
            'user_info' => json_encode($user_info, JSON_UNESCAPED_UNICODE),
            'delivery' => json_encode($delivery_info, JSON_UNESCAPED_UNICODE),
            'notes' => $request->notes,
            'payment' => $request->payment,
            'history' => json_encode($history, JSON_UNESCAPED_UNICODE)
        ]);

        return redirect('/admin/orders/edit/'. $id)
            ->with('message-success', 'Заказ № ' . $id . ' успешно создан.');
    }

    public function edit($id)
    {
        $order = Order::find($id);
//        $newpost = new Newpost();
//        dd($newpost->getCounterparties('Лазаренко Сергій'));
//        dd($newpost->getContacts('88b3742a-37ea-11e6-a54a-005056801333'));

        $order->user = json_decode($order->user_info);
        $order->date = $order->updated_at->format('d.m.Y');
        $order->time = $order->updated_at->format('H:i');
        if ($order->status_id) {
            if ($order->status_id == 1){
                $order->class = 'warning';
            } else {
                $order->class = 'info';
            }
        } else {
            $order->class = 'danger';
        }

        $delivery_info = $order->getDeliveryInfo();
        if(isset($delivery_info['method']) && $delivery_info['method'] == 'Новая Почта'){
            $original_delivery_info = json_decode($order->delivery, true);
            $newpost = new Newpost();
            $delivery_info['region'] = [
                'options' => $newpost->getRegions(),
                'selected' =>  !empty($original_delivery_info['info']['region']) ? $original_delivery_info['info']['region'] : ''
            ];

            if(!empty($original_delivery_info['info']['region'])){
                $delivery_info['city'] = [
                    'options' => $newpost->getCities($newpost->getRegionRef($original_delivery_info['info']['region'])->region_id),
                    'selected' => !empty($original_delivery_info['info']['city']) ? $original_delivery_info['info']['city'] : ''
                ];
            }else{
                $delivery_info['city'] = [
                    'options' => [(object)['id' => null, 'name_ru' => 'Выберите область']],
                    'selected' => !empty($original_delivery_info['info']['city']) ? $original_delivery_info['info']['city'] : ''
                ];
            }

            if(!empty($original_delivery_info['info']['city'])){
                $delivery_info['warehouse'] = [
                    'options' => $newpost->getWarehouses($newpost->getCityRef($original_delivery_info['info']['city'])->city_id),
                    'selected' => !empty($original_delivery_info['info']['warehouse']) ? $original_delivery_info['info']['warehouse'] : ''
                ];
            }else{
                $delivery_info['warehouse'] = [
                    'options' => [(object)['id' => null, 'address_ru' => 'Выберите населённый пункт']],
                    'selected' => !empty($original_delivery_info['info']['warehouse']) ? $original_delivery_info['info']['warehouse'] : ''
                ];
            }
        }elseif(isset($delivery_info['method']) && $delivery_info['method'] == 'Самовывоз из "Justin"'){
            $original_delivery_info = json_decode($order->delivery, true);
            $justin = new Justin();
            $delivery_info['region'] = [
                'options' => $justin->getRegions(),
                'selected' =>  !empty($original_delivery_info['info']['region']) ? $original_delivery_info['info']['region'] : ''
            ];
            foreach($delivery_info['region']['options'] as $id => $option){
                $option['id'] = $id;
                $option['name_ru'] = $option['name'];
                $delivery_info['region']['options'][$id] = (object)$option;
            }

            if(!empty($original_delivery_info['info']['region'])){
                $delivery_info['city'] = [
                    'options' => $justin->getCities($original_delivery_info['info']['region']),
                    'selected' => !empty($original_delivery_info['info']['city']) ? $original_delivery_info['info']['city'] : ''
                ];
                foreach($delivery_info['city']['options'] as $id => $option){
                    $option['id'] = $id;
                    $option['name_ru'] = $option['name'];
                    $delivery_info['city']['options'][$id] = (object)$option;
                }
            }else{
                $delivery_info['city'] = [
                    'options' => [(object)['id' => null, 'name_ru' => 'Выберите область']],
                    'selected' => !empty($original_delivery_info['info']['city']) ? $original_delivery_info['info']['city'] : ''
                ];
            }

            if(!empty($original_delivery_info['info']['city'])){
                $delivery_info['warehouse'] = [
                    'options' => $justin->getWarehouses($original_delivery_info['info']['city']),
                    'selected' => !empty($original_delivery_info['info']['warehouse']) ? $original_delivery_info['info']['warehouse'] : ''
                ];
                foreach($delivery_info['warehouse']['options'] as $id => $option){
                    $option['id'] = $id;
                    $option['address_ru'] = $option['name'];
                    $delivery_info['warehouse']['options'][$id] = (object)$option;
                }
            }else{
                $delivery_info['warehouse'] = [
                    'options' => [(object)['id' => null, 'address_ru' => 'Выберите населённый пункт']],
                    'selected' => !empty($original_delivery_info['info']['warehouse']) ? $original_delivery_info['info']['warehouse'] : ''
                ];
            }
        }

        $settings = new Setting();

        return view('admin.orders.edit', [
            'order' => $order,
            'next' => Order::where('id', '>', $order->id)->orderBy('id', 'asc')->first(),
            'prev' => Order::where('id', '<', $order->id)->orderBy('id', 'desc')->first(),
            'orders_statuses' => OrderStatus::all(),
            'delivery_info' => $delivery_info,
            'sms' => [
                'payment' => $settings->get_setting('sms_payment'),
                'delivery' => $settings->get_setting('sms_delivery'),
                'promo' => $settings->get_setting('sms_promo')
            ]
        ]);
    }

//    public function show(Cart $cart)
//    {
//        $products = $cart->get_products();
//
//        if(Sentinel::check()){
//            $adress = User::find(Sentinel::check()->id)->user_data->adress;
//
//            return view('public.order')
//                ->with('adress', json_decode($adress))
//                ->with('products', $products);
//        }
//        return view('public.order')
//            ->with('products', $products);
//    }

    public function update($id, Request $request)
    {
        $order = Order::find($id);
        $products = json_decode($order->products, true);
        $total_quantity = 0;
        $total_price = 0;
        $history = $order->history;

        if(!empty($request->products)) {
            foreach ($request->products as $product) {
                if (isset($products[$product['code']])) {
                    if ($product['qty']) {
                        $products[$product['code']]['quantity'] = $product['qty'];
                        $total_quantity += $product['qty'];
                        $total_price += $products[$product['code']]['price'] * $product['qty'];
                    } else {
                        unset($products[$product['code']]);
                    }
                } elseif ($product['qty'] > 0) {
                    $prod = Product::find($product['code']);
                    if (!empty($prod)) {
                        $products[$product['code']] = [
                            'quantity' => $product['qty'],
                            'price' => $prod->price,
                            'sale' => 0,
                            'sale_percent' => 0
                        ];
                        $total_quantity += $product['qty'];
                        $total_price += $prod->price * $product['qty'];
                    }
                }
            }
        }

        $user_info = json_decode($order->user_info, true);
        $user_info['name'] = $request->user_name;
        $user_info['phone'] = $request->user_phone;
        $user_info['email'] = $request->user_email;
        $user_info['comment'] = $request->comment;

        $delivery_info = json_decode($order->delivery, true);
        $delivery_info['method'] = $request->delivery;
        if($delivery_info['method'] == 'pickup'){
            $delivery_info['info'] = [];
        }elseif($delivery_info['method'] == 'newpost' || $delivery_info['method'] == 'justin'){
            $delivery_info['info'] = [
                'region' => $request->region,
                'city' => $request->city,
                'warehouse' => $request->warehouse
            ];
        }elseif($delivery_info['method'] == 'courier'){
            $delivery_info['info'] = [
                'street' => $request->street,
                'house' => $request->house,
                'apartment' => $request->apartment
            ];
        }elseif($delivery_info['method'] == 'other'){
            $delivery_info['info'] = [
                'name' => $request->name,
                'region' => $request->region,
                'city' => $request->city,
                'warehouse' => $request->warehouse
            ];
        }

        if($request->status !== $order->status_id){
            $history[time()] = [
                'msg' => 'Статус заказа изменён на '.OrderStatus::find($request->status)->status,
                'old_status_id' => $order->status_id,
                'new_status_id' => $request->status
            ];
        }

        $order->update([
            'products' => json_encode($products, JSON_UNESCAPED_UNICODE),
            'total_quantity' => $total_quantity,
            'total_price' => $total_price,
            'status_id' => $request->status,
            'user_info' => json_encode($user_info, JSON_UNESCAPED_UNICODE),
            'delivery' => json_encode($delivery_info, JSON_UNESCAPED_UNICODE),
            'notes' => $request->notes,
            'payment' => $request->payment,
            'history' => json_encode($history, JSON_UNESCAPED_UNICODE)
        ]);
        return redirect('/admin/orders/edit/'. $id)
            ->with('message-success', 'Заказ № ' . $id . ' успешно обновлен.');
    }

    public function newOrderUser(Request $request)
    {
        $user_id = Sentinel::check()->id;
        $rules = [
            'first_name'            => 'required',
            'phone'                 => 'required|regex:/^[0-9\-! ,\'\"\/+@\.:\(\)]+$/',
            'email'                 => 'required|email'
        ];
        $messages = [
            'first_name.required'    => 'Не заполнены обязательные поля!',
            'phone.required'         => 'Не заполнены обязательные поля!',
            'phone.regex'            => 'Некорректный телефон!',
            'email.required'         => 'Не заполнены обязательные поля!',
            'email.email'            => 'Некорректный email-адрес!',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors($validator);
        }
        User::find($user_id)->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name
        ]);

        $address = json_encode([
            'city' => $request->city,
            'street' => $request->street,
            'house' => $request->house,
            'flat' => $request->flat
        ], JSON_UNESCAPED_UNICODE);
        UserData::where('user_id', $user_id)->update([
            'phone'     => $request->phone,
            'adress' => $address,
            'other_data' => json_encode($request->except(['_token', 'first_name', 'last_name', 'city', 'street', 'house', 'flat']), JSON_UNESCAPED_UNICODE),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        $order_data = json_encode($request->except(['_token', 'first_name', 'last_name', 'email', 'phone', 'password', 'password_confirmation']), JSON_UNESCAPED_UNICODE);

        $this->orderStore($user_id, $order_data);
        return redirect('/user/history')
            ->with('status', 'Ваш заказ оформлен.');
    }

    public function newOrder(Request $request)
    {
//        return dd($request);
        $password = $request->password ? $request->password : 'null';
        $order_data = json_encode($request->except(['_token', 'first_name', 'last_name', 'email', 'phone', 'password', 'password_confirmation']), JSON_UNESCAPED_UNICODE);
        $rules = [
            'first_name'            => 'required',
            'phone'                 => 'required|regex:/^[0-9\-! ,\'\"\/+@\.:\(\)]+$/',
            'email'                 => 'required|email'
        ];
        $messages = [
            'first_name.required'    => 'Не заполнены обязательные поля!',
            'phone.required'         => 'Не заполнены обязательные поля!',
            'phone.regex'            => 'Некорректный телефон!',
            'email.required'         => 'Не заполнены обязательные поля!',
            'email.email'            => 'Некорректный email-адрес!',
        ];

        $user_exists = User::where('email', $request->email)->first();

        if($request->registration == 'on'){
            $rules['email'] = 'required|email|unique:users';
            if ($user_exists){
                $user = Sentinel::findById($user_exists->id);
                if($user->inRole('unregistered')){
                    $rules['email'] = 'required|email';
                }
            }

            $rules['password'] = 'required|min:6|confirmed';
            $rules['password_confirmation'] = 'required|min:6';

            $messages['password.required'] = 'Не заполнены обязательные поля!';
            $messages['password.min'] = 'Пароль должен быть не менее 6 символов!';
            $messages['password.confirmed'] = 'Введенные пароли не совпадают!';
            $messages['email.unique'] = 'Пользователь с таким email-ом уже зарегистрирован123!';
        }else{
            //$request->merge(['password' => $password]);
        }
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors($validator);
        }

        if ($user_exists){
            $user = Sentinel::findById($user_exists->id);
            $user_id = $user->id;

            if($user->inRole('unregistered')){
                if($request->registration == 'off'){
                    $this->orderStore($user_id, $order_data);        // записываем в базу и радуемся
                    return redirect(App::getLocale() == 'ua' ? '/ua/thank_you' : '/thank_you')
                        ->with('order_status', 'Ваш заказ оформлен. Оператор свяжется с вами в ближайшее время.');
                }
                $credentials = [
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'password' => $request->password,
                    'permissions' => [
                        'user' => true
                    ]
                ];

                Sentinel::update($user, $credentials);

                $role = Sentinel::findRoleBySlug('unregistered');
                $role->users()->detach($user);

                $userRole = Sentinel::findRoleBySlug('user');
                $userRole->users()->attach($user);


                $this->orderStore($user_id, $order_data);        // записываем в базу заказ и радуемся

                $data = UserData::where('user_id', $user_id)->first();
                $data->phone = $request->phone;
                $data->save();

                $credentials['email'] = $request->email;
                $auth = Sentinel::authenticateAndRemember($credentials);

                if($auth){
                    return redirect(App::getLocale() == 'ua' ? '/ua/user/history' : '/user/history')
                        ->with('status', 'Ваш заказ оформлен. Оператор свяжется с вами в ближайшее время.');
                }else{
                    return redirect()
                        ->back()
                        ->withInput()
                        ->withErrors(['error' => 'При регистрации произошла ошибка!']);
                }

            }else{
                return redirect()
                    ->back()
                    ->withInput()
                    ->withErrors(['email' => 'Пользователь с таким email-ом уже зарегистрирован!']);
            }
        }

        //return dd($request);
        if($request->registration == 'off'){
            $user_role = Sentinel::findRoleBySlug('unregistered');
            $credentials['permissions'] = ['unregistered' => true];
        }else{
            $user_role = Sentinel::findRoleBySlug('user');
            $credentials['permissions'] = ['user' => true];
        }
        $credentials = [
            'email' => $request->email,
            'password' => $password,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'permissions' => [
                'user' => true
            ]
        ];

        $new_user = Sentinel::registerAndActivate($credentials);

        $user_role->users()->attach($new_user);
        $user_id = $new_user->id;

        $this->orderStore($user_id, $order_data);        // записываем в базу и радуемся заказу

        UserData::create([
            'user_id'   => $user_id,
            'image_id'  => 1,
            'phone'     => $request->phone,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        if($request->registration == 'on') {
            $auth = Sentinel::authenticateAndRemember($credentials);
            if ($auth) {
                return redirect(App::getLocale() == 'ua' ? '/ua/user/history' : '/user/history')
                    ->with('status', 'Ваш заказ оформлен. Оператор свяжется с вами в ближайшее время.');
            }
        }else{
            return redirect(App::getLocale() == 'ua' ? '/ua/thank_you' : '/thank_you')
                ->with('order_status', 'Ваш заказ оформлен. Оператор свяжется с вами в ближайшее время.');
        }
    }

    /**
     * @param $user_id
     * @param $order_data
     * @return bool
     */
    public function orderStore($user_id, $order_data)
    {
        $user_cart_id = Session::get('user_id');

        $current_cart = Cart::where('user_id', $user_cart_id)->first();

        $new_order_data = [
            'user_id' => $user_id,
            'products_sum' => $current_cart->products_sum,
            'products_quantity' => $current_cart->products_quantity,
            'status_id' => 1,
            'order_data' => $order_data
        ];
        $new_order = Order::create($new_order_data);
        foreach($current_cart->products_cart as $products){
            $products_in_cart['product_id'] = $products->product->id;
            $products_in_cart['product_quantity'] = $products->product_quantity;
            $products_in_cart['product_sum'] = $products->product_quantity * $products->product->price;

            $new_products_cart = new ProductsInOrder($products_in_cart);
            $new_order->products()->save($new_products_cart);
        }
        $current_cart->delete();
        $new_user_cart_id = md5(rand(0,100500));
        Session::put('user_id', $new_user_cart_id);
        return true;
    }

    /**
     * Создание накладной
     *
     * @param $id
     *
     * @return StreamedResponse
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function invoice($id){
        $order = Order::find($id);

        $order->user = json_decode($order->user_info);
        $order->date = $order->updated_at->format('d.m.Y');

        $payments = [
            'cash' => 'Наличными при самовывозе',
            'prepayment' => 'Предоплата',
            'privat' => 'Оплата картой',
            'nal_delivery' => 'Наличными курьеру',
            'nal_samovivoz' => 'Оплата при самовывозе',
            'nalogenniy' => 'Оплата наложенным платежом'
        ];

        $address = [
            'region' => 'Область',
            'city' => 'Город',
            'warehouse' => 'Отделение',
            'index' => 'Почтовый индекс',
            'street' => 'Улица',
            'house' => 'Дом',
            'apartment' => 'Квартира'
        ];

        $user_info = $order->user;
        $delivery = $order->getDeliveryInfo();
        $products = json_decode($order->products);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $worksheet = $spreadsheet->getSheet(0);

        // Ширина столбцов
        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(15);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(40);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(17);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(25);
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(25);

        $data = [];

        $data[0] = [
            0 => "Заказ №".sprintf("%08d", $id),
            1 => '',
            2 => '',
            3 => '',
            4 => $order->date
        ];

        // Размер и толщина шрифта
        $spreadsheet->getActiveSheet()->getStyle('A1')->applyFromArray([
            'font' => [
                'size' => 16,
                'bold' => true,
            ],
        ]);
        // Выравнивание по правому краю
        $spreadsheet->getActiveSheet()->getStyle('E1')->applyFromArray([
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
            ],
        ]);

        $data[1] = [];

        $data[2] = [
            0 => 'Информация о заказчике',
            1 => '',
            2 => 'Информация о доставке/оплате',
            3 => '',
        ];

        $spreadsheet->getActiveSheet()->getStyle('A3:E3')->applyFromArray([
            'font' => [
                'bold' => true,
            ],
        ]);

        $index = 3;
        foreach (['name', 'phone', 'email'] as $key){
            if(!empty($user_info->$key)){
                $data[$index][0] = $user_info->$key;
                $index++;
            }
        }

        if(empty($data[3][0])){
            $data[3][0] = '';
        }
        if(empty($data[4][0])){
            $data[4][0] = '';
        }
        if(empty($data[5][0])){
            $data[5][0] = '';
        }

        // Объединение ячеек
        $spreadsheet->getActiveSheet()->mergeCells('A3:B3');
        $spreadsheet->getActiveSheet()->mergeCells('A4:B4');
        $spreadsheet->getActiveSheet()->mergeCells('A5:B5');
        $spreadsheet->getActiveSheet()->mergeCells('A6:B6');

        $spreadsheet->getActiveSheet()->mergeCells('C3:E3');

        $data[3][1] = '';
        $data[4][1] = '';
        $data[5][1] = '';

        $data[3][2] = 'Адрес доставки:';
        $data[4][2] = 'Способ доставки:';
        $data[5][2] = 'Способ оплаты:';

        $data[3][3] = '';
        if(!empty($delivery)) {
            foreach ( $delivery as $key => $value ) {
                if ( isset( $address[ $key ] ) ) {
                    if ( ! empty( $data[3][3] ) ) {
                        $data[3][3] .= ', ';
                    }
                    $data[3][3] .= $address[ $key ] . ': ' . $value;
                }
            }
            $spreadsheet->getActiveSheet()->getStyle('D4')
                ->getAlignment()->setWrapText(true);
        }
        if ( isset( $delivery['method'] ) ) {
            $data[4][3] = $delivery['method'];
        }else{
            $data[4][3] = '';
        }

        if(isset($payments[$order->payment])) {
            $data[5][3] = $payments[ $order->payment ];
        }

        // Границы диапазона ячеек
        $worksheet->getStyle('A3:B6')->applyFromArray([
            'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '00000000'],
                ],
            ],
        ]);
        $worksheet->getStyle('C3:E6')->applyFromArray([
            'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '00000000'],
                ],
            ],
        ]);

        $data[6] = [];

        $data[7][0] = 'Примечания';

        $spreadsheet->getActiveSheet()->getStyle('A8')->applyFromArray([
            'font' => [
                'bold' => true,
            ],
        ]);

        if(empty($user_info->comment)){
            $data[8][0] = 'Отсутствует';
        }else{
            $data[8][0] = $user_info->comment;
        }

        $spreadsheet->getActiveSheet()->mergeCells('A9:E9');

        $worksheet->getStyle('A8:E9')->applyFromArray([
            'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '00000000'],
                ],
            ],
        ]);

        $data[9][0] = '';

        $data[10] = [
            0 => 'Артикул',
            1 => 'Название',
            2 => 'Шт',
            3 => 'Цена',
            4 => 'Сумма'
        ];

        $spreadsheet->getActiveSheet()->getStyle('A11:E11')->applyFromArray([
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        $spreadsheet->getActiveSheet()->getStyle('A11:E11')->applyFromArray([
            'font' => [
                'bold' => true
            ]
        ]);

        $index = 11;
        foreach($products as $product_id => $product_info){
            $product = Product::find($product_id);
            $price = $product_info->price;
            $qty = $product_info->quantity;

            $data[$index] = [
                0 => $product->sku,
                1 => $product->name,
                2 => number_format($qty, 0, '.', '' ),
                3 => round($price, 2).' грн.',
                4 => round($price * $qty, 2).' грн.'
            ];
            $spreadsheet->getActiveSheet()->getStyle('B'.($index+1))
                ->getAlignment()->setWrapText(true);
            $spreadsheet->getActiveSheet()->getStyle('C'.($index+1).':E'.($index+1))->applyFromArray([
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
            ]);

            $index++;
        }

        $worksheet->getStyle('A11:E'.$index)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '00000000'],
                ],
            ],
            'alignment' => [
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
            ]
        ]);

        $data[$index] = [];

        $index++;

        $data[$index] = [
            0 => 'Итого:',
            1 => '',
            2 => '',
            3 => '',
            4 => $order->total_price.' грн.'
        ];

        $spreadsheet->getActiveSheet()->getStyle('E'.($index+1))->applyFromArray([
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
            ],
        ]);

        $spreadsheet->getActiveSheet()->getStyle('A'.($index+1))->applyFromArray([
            'font' => [
                'size' => 16,
                'bold' => true,
            ],
        ]);

        $spreadsheet->getActiveSheet()->getStyle('D'.($index+1))->applyFromArray([
            'font' => [
                'size' => 16,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
            ],
        ]);

        $spreadsheet->getActiveSheet()->setSelectedCell('E'.($index+1));

        $sheet->fromArray($data, NULL, 'A1');

        // Форматирование печати
        $spreadsheet->getActiveSheet()->getPageSetup()
            ->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
        $spreadsheet->getActiveSheet()->getPageSetup()
            ->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
        $spreadsheet->getActiveSheet()->getPageSetup()->setFitToWidth(1);
        $spreadsheet->getActiveSheet()->getPageSetup()->setFitToHeight(0);
        $spreadsheet->getActiveSheet()->getPageMargins()->setTop(1);
        $spreadsheet->getActiveSheet()->getPageMargins()->setRight(0.75);
        $spreadsheet->getActiveSheet()->getPageMargins()->setLeft(0.75);
        $spreadsheet->getActiveSheet()->getPageMargins()->setBottom(1);
        $spreadsheet->getActiveSheet()->setShowGridlines(false);

        $streamedResponse = new StreamedResponse();
        $streamedResponse->setCallback( function () use ( $spreadsheet ) {
            $writer = new Xls( $spreadsheet );
            $writer->save( 'php://output' );
        } );

        $streamedResponse->setStatusCode( 200 );
        $streamedResponse->headers->set( 'Content-Type', 'text/xls' );
        $streamedResponse->headers->set( 'Content-Disposition', 'attachment; filename="Накладная_'.$id.'.xls"' );

        return $streamedResponse->send();
    }

    /**
     * Удаление товара из заказа
     *
     * @param $id
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function removeProduct($id, Request $request){
        $order = Order::find($id);
        $products = json_decode($order->products, true);
        if(isset($products[$request->key])){
            unset($products[$request->key]);
        }
        $order->products = json_encode($products, JSON_UNESCAPED_UNICODE);

        $total_quantity = 0;
        $total_price = 0;
        foreach($products as $product){
            $total_quantity += $product['quantity'];
            $total_price += $product['price']*$product['quantity'];
        }
        $order->total_quantity = $total_quantity;
        $order->total_price = $total_price;

        $order->save();

        return $this->getOrderForm($order);
    }

    /**
     * Добавление товаров в заказ
     *
     * @param $id
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function addProducts($id, Request $request){
        $order = Order::find($id);
        $products = json_decode($order->products, true);
        $total_quantity = 0;
        $total_price = 0;
        foreach($request->products as $product){
            if(isset($products[$product])){
                $products[$product]['quantity']++;
            }else{
                $prod = Product::find($product);
                if(!empty($prod)) {
                    $products[$product] = [
                        'quantity'     => 1,
                        'price'        => $prod->price,
                        'sale'         => 0,
                        'sale_percent' => 0
                    ];
                }
            }
        }
        foreach($products as $product){
            $total_quantity += $product['quantity'];
            $total_price += $product['price']*$product['quantity'];
        }
        $order->update([
            'products' => json_encode($products, JSON_UNESCAPED_UNICODE),
            'total_quantity' => $total_quantity,
            'total_price' => $total_price
        ]);

        return response()->json(['result' => 'success', 'html' => $this->getOrderForm($order)]);
    }

    /**
     * Изменение колличества товара в заказе
     *
     * @param $id
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function changeQty($id, Request $request){
        $order = Order::find($id);
        $products = json_decode($order->products, true);
        $total_quantity = 0;
        $total_price = 0;
        foreach($products as $key => $product){
            if($key == $request->product){
                $products[$key]['quantity'] = $request->qty;
            }
            $total_quantity += $products[$key]['quantity'];
            $total_price += $product['price']*$products[$key]['quantity'];
        }
        $order->update([
            'products' => json_encode($products, JSON_UNESCAPED_UNICODE),
            'total_quantity' => $total_quantity,
            'total_price' => $total_price
        ]);

        return response()->json(['result' => 'success', 'html' => $this->getOrderForm($order)]);
    }

    private function getOrderForm($order){
        $order->user = json_decode($order->user_info);
        $order->date = $order->updated_at->format('d.m.Y');
        $order->time = $order->updated_at->format('H:i');
        $delivery_info = $order->getDeliveryInfo();
        if(isset($delivery_info['method']) && $delivery_info['method'] == 'Новая Почта'){
            $original_delivery_info = json_decode($order->delivery, true);
            $newpost = new Newpost();
            $delivery_info['region'] = [
                'options' => $newpost->getRegions(),
                'selected' =>  !empty($original_delivery_info['info']['region']) ? $original_delivery_info['info']['region'] : ''
            ];

            if(!empty($original_delivery_info['info']['region'])){
                $delivery_info['city'] = [
                    'options' => $newpost->getCities($newpost->getRegionRef($original_delivery_info['info']['region'])->region_id),
                    'selected' => !empty($original_delivery_info['info']['city']) ? $original_delivery_info['info']['city'] : ''
                ];
            }else{
                $delivery_info['city'] = [
                    'options' => [(object)['id' => null, 'name_ru' => 'Выберите область']],
                    'selected' => !empty($original_delivery_info['info']['city']) ? $original_delivery_info['info']['city'] : ''
                ];
            }

            if(!empty($original_delivery_info['info']['city'])){
                $delivery_info['warehouse'] = [
                    'options' => $newpost->getWarehouses($newpost->getCityRef($original_delivery_info['info']['city'])->city_id),
                    'selected' => !empty($original_delivery_info['info']['warehouse']) ? $original_delivery_info['info']['warehouse'] : ''
                ];
            }else{
                $delivery_info['warehouse'] = [
                    'options' => [(object)['id' => null, 'address_ru' => 'Выберите населённый пункт']],
                    'selected' => !empty($original_delivery_info['info']['warehouse']) ? $original_delivery_info['info']['warehouse'] : ''
                ];
            }
        }elseif(isset($delivery_info['method']) && $delivery_info['method'] == 'Самовывоз из "Justin"'){
            $original_delivery_info = json_decode($order->delivery, true);
            $justin = new Justin();
            $delivery_info['region'] = [
                'options' => $justin->getRegions(),
                'selected' =>  !empty($original_delivery_info['info']['region']) ? $original_delivery_info['info']['region'] : ''
            ];
            foreach($delivery_info['region']['options'] as $id => $option){
                $option['id'] = $id;
                $option['name_ru'] = $option['name'];
                $delivery_info['region']['options'][$id] = (object)$option;
            }

            if(!empty($original_delivery_info['info']['region'])){
                $delivery_info['city'] = [
                    'options' => $justin->getCities($original_delivery_info['info']['region']),
                    'selected' => !empty($original_delivery_info['info']['city']) ? $original_delivery_info['info']['city'] : ''
                ];
                foreach($delivery_info['city']['options'] as $id => $option){
                    $option['id'] = $id;
                    $option['name_ru'] = $option['name'];
                    $delivery_info['city']['options'][$id] = (object)$option;
                }
            }else{
                $delivery_info['city'] = [
                    'options' => [(object)['id' => null, 'name_ru' => 'Выберите область']],
                    'selected' => !empty($original_delivery_info['info']['city']) ? $original_delivery_info['info']['city'] : ''
                ];
            }

            if(!empty($original_delivery_info['info']['city'])){
                $delivery_info['warehouse'] = [
                    'options' => $justin->getWarehouses($original_delivery_info['info']['city']),
                    'selected' => !empty($original_delivery_info['info']['warehouse']) ? $original_delivery_info['info']['warehouse'] : ''
                ];
                foreach($delivery_info['warehouse']['options'] as $id => $option){
                    $option['id'] = $id;
                    $option['address_ru'] = $option['name'];
                    $delivery_info['warehouse']['options'][$id] = (object)$option;
                }
            }else{
                $delivery_info['warehouse'] = [
                    'options' => [(object)['id' => null, 'address_ru' => 'Выберите населённый пункт']],
                    'selected' => !empty($original_delivery_info['info']['warehouse']) ? $original_delivery_info['info']['warehouse'] : ''
                ];
            }
        }

        return view('admin.orders.edit_form', [
            'order' => $order,
            'orders_statuses' => OrderStatus::all(),
            'delivery_info' => $delivery_info
        ])->render();
    }

    public function get_product_data(Request $request){
        $order = Order::find($request->order);
        $products = json_decode($order->products, true);

        if(isset($products[$request->product]))
            return response()->json(['result' => 'success', 'product' => $products[$request->product]]);
        else
            return response()->json(['result' => 'error', 'error' => 'Товар не найден']);
    }

    public function update_product_data(Request $request){
        $order = Order::find($request->order);
        $products = json_decode($order->products, true);

        if(isset($products[$request->product])) {
            if(isset($request->price)){
                $products[$request->product]['price'] = $request->price;
            }
            if(isset($request->sale)){
                $products[$request->product]['sale'] = $request->sale;
                $products[$request->product]['sale_percent'] = 0;
            }
            if(isset($request->sale_percent)){
                $products[$request->product]['sale_percent'] = $request->sale_percent;
                $products[$request->product]['sale'] = 0;
            }

            $total_quantity = 0;
            $total_price = 0;
            foreach($products as $key => $product){
                $total_quantity += $product['quantity'];
                $total_price += $product['price']*$product['quantity'] * (100 - $product['sale_percent']) / 100;
            }

            $order->update([
                'products' => json_encode($products, JSON_UNESCAPED_UNICODE),
                'total_quantity' => $total_quantity,
                'total_price' => round($total_price, 2)
            ]);
            return response()->json(['result' => 'success', 'html' => $this->getOrderForm($order)]);
        }else
            return response()->json(['result' => 'error', 'error' => 'Товар не найден']);
    }

    public function paymentAction(Request $request){
        $ipay = new Ipay('2913', '014def6e69f788115c3d3f6d212d8bf3913352fa', true);
        if($ipay->updatePayment($request->xml) === false)
            abort(404);

        return '';
    }

    public function sendEmail($id, Request $request){
        $order = Order::find($id);

        if(isset($order->user->email) && strpos($order->user->email, '@placeholder.com') === false){
            Mail::send('emails.sendmail', ['html' => $request->text], function($msg) use($order){
                $msg->from('admin@'.str_replace(['http://', 'https://'], '', env('APP_URL')), 'Интернет-магазин Milam');
                $msg->to($order->user->email);
                $msg->subject('Заказ в интернет-магазине Milam');
            });

            return response()->json(['result' => 'success']);
        }

        return response()->json(['result' => 'error']);
    }

    public function sendSms($id, Request $request){
        $order = Order::find($id);
        $user = $order->getUserInfo();

        if(!empty($user->phone)){
            $data = [
                'phone' => [$user->phone],
                'message' => $request->text,
                'src_addr' => 'ExVELO'
            ];
            $url = 'https://im.smsclub.mobi/sms/send';

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data, JSON_UNESCAPED_UNICODE));
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json', 'Content-Type: application/json', 'Authorization: Bearer PoXKaBBthjda-gY']);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
            curl_setopt ($ch, CURLOPT_SSLVERSION, 6);
            $output = curl_exec($ch);
            curl_close($ch);

            if(substr($output, 0, 1) !== '{' && substr($output, 0, 1) !== '['){
                return response()->json(['result' => 'error', 'text' => $output]);
            }

            $history = $order->history;
            $history[time()] = [
                'msg' => 'Отправлено СМС на номер '.$user->phone.' с текстом "'.$request->text.'"',
                'phone' => $user->phone,
                'text' => $request->text
            ];

            $order->update([
                'history' => json_encode($history, JSON_UNESCAPED_UNICODE)
            ]);

            return response()->json(['result' => 'success', 'data' => json_decode($output)]);
        }

        return response()->json(['result' => 'error']);
    }

    public function getSmsClubBalance(){
        $data = [];
        $url = 'https://im.smsclub.mobi/sms/balance';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data, JSON_UNESCAPED_UNICODE));
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json', 'Content-Type: application/json', 'Authorization: Bearer PoXKaBBthjda-gY']);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
        curl_setopt ($ch, CURLOPT_SSLVERSION, 6);
        $output = curl_exec($ch);
        curl_close($ch);

        if(substr($output, 0, 1) !== '{' && substr($output, 0, 1) !== '['){
            return response()->json(['result' => 'error', 'text' => $output]);
        }

        return response()->json(['result' => 'success', 'data' => json_decode($output)]);
    }

    public function delivery(Request $request){
        $newpost = new Newpost();
        if(!empty($request->id)){
            $current_order_id = $request->id;
            $order = Order::find($current_order_id);
            $delivery = $order->getDeliveryInfo();
        }

        if(!empty($delivery) && !empty($delivery['city']))
            $city = $delivery['city'];

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
                'delivery' => view('admin.orders.delivery.newpost', [
                    'regions' => $regions,
                    'region_id' => $region_id,
                    'cities' => $cities,
                    'city_id' => $city_id,
                    'warehouses' => $warehouses,
                    'current_order_id' => isset($current_order_id) ? $current_order_id : '',
                    'lang' => App::getLocale()
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
                'delivery' => view('admin.orders.delivery.justin', [
                    'regions' => $regions,
                    'region_id' => $region_id,
                    'cities' => $cities,
                    'city_id' => $city_id,
                    'warehouses' => $warehouses,
                    'current_order_id' => isset($current_order_id) ? $current_order_id : '',
                    'subtotal' => 0,
                    'total' => 0,
                    'lang' => App::getLocale()
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
                'delivery' => view('admin.orders.delivery.' . $request->delivery, [
                    'current_order_id' => isset($current_order_id) ? $current_order_id : '',
                    'region' => $region_name
                ])->render()
            ]);
        }
    }

    public function updateTtn(Request $request){
        $current_order_id = $request->id;
        $order = Order::find($current_order_id);
        $delivery = json_decode($order->delivery, true);
        $delivery['ttn'] = $request->ttn;
        $order->update(['delivery' => json_encode($delivery, JSON_UNESCAPED_UNICODE)]);

        return response()->json(['result' => 'success']);
    }

    public function getTtnForm(Request $request){
        $current_order_id = $request->id;
        $order = Order::find($current_order_id);
        $delivery = json_decode($order->delivery, true);

        if($delivery['method'] == 'newpost'){
            return response()->json([
                'result' => 'success',
                'html' => view('admin.orders.delivery.newpost_ttn_form')
                    ->with('order', $order)
                    ->render()
            ]);
        }

        return response()->json(['result' => 'error']);
    }
}
