<?php

namespace App\Http\Controllers;

use Cartalyst\Sentinel\Native\Facades\Sentinel;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use Symfony\Component\HttpFoundation\StreamedResponse;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat as PHPExcel_Style_NumberFormat;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\ShopReview;
use App\Models\Sendpulse;
use App\Models\UserData;
use App\Models\Wishlist;
use App\Models\Setting;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\Review;
use App\Models\Action;
use App\Models\Order;
use App\Models\User;
use Validator;

class UserController extends Controller
{
	public $settings;

	protected $rules = [
		'email' => 'required|unique:users'
	];
	protected $messages = [
		'email.required' => 'Поле должно быть заполнено!',
		'first_name.required' => 'Поле должно быть заполнено!',
		'email.unique' => 'Поле должно быть уникальным!'
	];

	/**
	 * Список всех зарегистрированных и незарегистрированных пользователей
	 * в панели администратора
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(){
		$users = User::join('role_users', function ($join) {
			$join->on('users.id', '=', 'role_users.user_id')
			     ->whereIn('role_users.role_id', [5,6]);
		})->paginate(8);

		return view('admin.users.index', [
			'users' => $users,
			'title' => 'Пользователи сайта'
		]);
	}

	/**
	 * @return mixed
	 */
	public function managers(){
		$users = User::join('role_users', function ($join) {
			$join->on('users.id', '=', 'role_users.user_id')
			     ->whereIn('role_users.role_id', [2]);
		})->paginate(8);

		return view('admin.users.index', [
			'users' => $users,
			'title' => 'Менеджеры сайта'
		]);
	}

	/**
	 * @return mixed
	 */
	public function moderators(){
		$users = User::join('role_users', function ($join) {
			$join->on('users.id', '=', 'role_users.user_id')
			     ->whereIn('role_users.role_id', [3]);
		})->paginate(8);

		return view('admin.users.index', [
			'users' => $users,
			'title' => 'Модераторы сайта'
		]);
	}

    /**
     * @return mixed
     */
    public function marketers(){
        $users = User::join('role_users', function ($join) {
            $join->on('users.id', '=', 'role_users.user_id')
                ->whereIn('role_users.role_id', [4]);
        })->paginate(8);

        return view('admin.users.index', [
            'users' => $users,
            'title' => 'Маркетологи сайта'
        ]);
    }

	/**
	 * Display the specified resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function show(){
		$user = Sentinel::check();

		$user = User::find($user->id);

		$orders = Order::where('user_id',$user->id)->orderBy('created_at','desc')->get();

		$wish_list = Wishlist::where('user_id',$user->id)->get();

		return view('users.index')->with('user', $user)
              ->with('orders', $orders)
              ->with('user_data', $user->user_data)
              ->with('wish_list', $wish_list);
	}

	public function history(){
		$user = Sentinel::check();
		$orders = Order::where('user_id',$user->id)->orderBy('created_at','desc')->get();

		return view('users.history')->with('user', $user)->with('orders', $orders);
	}

	public function wishList(){
		$user = Sentinel::check();
		$wish_list = Wishlist::where('user_id',$user->id)
            ->join('products', 'wish_list.product_id', '=', 'products.id')
            ->orderByRaw("if(products.stock = 0, 0, 1) desc")
            ->orderBy('wish_list.created_at', 'desc')
            ->get();
		return view('users.wishlist')->with('user', $user)->with('products', $wish_list);
	}

    public function recommend(){
        $user = Sentinel::check();
        $orders = Order::where('user_id',$user->id)->orderBy('created_at','desc')->get();
        $ids = [];
        foreach($orders as $order){
            foreach($order->getProducts() as $product){
                $ids[] = $product['product']->id;
            }
        }

        $ids = array_merge($ids, DB::table('similar_products')->whereIn('product_id', $ids)->pluck('similar_id')->toArray());

        return view('users.recommend')->with('user', $user)->with('products', Product::whereIn('id',  array_unique($ids))->limit(30)->get());
    }

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id){
		$settings = Setting::find(1);

		$image_size = [
			'width' => $settings->category_image_width,
			'height' => $settings->category_image_height
		];

		$user = User::find($id);

		$permissions = [
            'orders.list' => 'Просмотр списка заказов',
            'orders.view' => 'Просмотр деталей заказа',
            'orders.update' => 'Обновление заказа',
            'users.list' => 'Просмотр списка пользователей',
            'users.view' => 'Просмотр профиля пользователя',
            'users.create' => 'Добавление пользователей',
            'users.update' => 'Обновление профиля пользователя',
            'reviews.list' => 'Просмотр списка отзывов о товаре',
            'reviews.view' => 'Просмотр отзыва о товаре',
            'reviews.update' => 'Обновление отзыва о товаре',
            'reviews.delete' => 'Удаление отзыва о товаре',
            'shopreviews.list' => 'Просмотр списка отзывов о магазине',
            'shopreviews.view' => 'Просмотр отзыва о магазине',
            'shopreviews.update' => 'Обновление отзыва о магазине',
            'shopreviews.delete' => 'Удаление отзыва о магазине',
            'pages.list' => 'Просмотр списка страниц',
            'pages.view' => 'Просмотр страницы',
            'pages.create' => 'Создание страницы',
            'pages.update' => 'Обновление страницы',
            'pages.delete' => 'Удаление страницы',
            'products.list' => 'Просмотр списка товаров',
            'products.view' => 'Просмотр товара',
            'products.create' => 'Создание товара',
            'products.update' => 'Обновление товара',
            'products.delete' => 'Удаление товара',
            'attributes.list' => 'Просмотр списка атрибутов',
            'attributes.view' => 'Просмотр атрибута',
            'attributes.create' => 'Создание атрибута',
            'attributes.update' => 'Обновление атрибута',
            'attributes.delete' => 'Удаление атрибута',
            'categories.list' => 'Просмотр списка категорий',
            'categories.view' => 'Просмотр категории',
            'categories.create' => 'Создание категории',
            'categories.update' => 'Обновление категории',
            'categories.delete' => 'Удаление категории',
            'sales.list' => 'Просмотр списка акций',
            'sales.view' => 'Просмотр акции',
            'sales.create' => 'Создание акции',
            'sales.update' => 'Обновление акции',
            'sales.delete' => 'Удаление акции',
            'export.list' => 'Просмотр списка экспортов',
            'export.view' => 'Просмотр экспорта',
            'export.create' => 'Создание экспорта',
            'export.update' => 'Обновление экспорта',
            'export.delete' => 'Удаление экспорта',
            'import.list' => 'Просмотр списка импортов',
            'import.view' => 'Просмотр импорта',
            'import.create' => 'Создание импорта',
            'import.update' => 'Обновление импорта',
            'import.delete' => 'Удаление импорта',
            'news.list' => 'Просмотр списка новостей',
            'news.view' => 'Просмотр новости',
            'news.create' => 'Создание новости',
            'news.update' => 'Обновление новости',
            'news.delete' => 'Удаление новости',
            'report.lists' => 'Просмотр списка отчётов',
            'report.view' => 'Отчёта',
            'settings.view' => 'Просмотр настроек',
            'settings.update' => 'Изменение настроек',
            'cache.update' => 'Очистка кеша',
            'redis.update' => 'Сброс Redis',
            'modules.list' => 'Просмотр списка модулей',
            'modules.view' => 'Просмотр списка модуля',
            'modules.update' => 'Настройка модуля',
            'seo.settings' => 'Настройка SEO',
            'seo.list' => 'Просмотр списка SEO записей',
            'seo.view' => 'Просмотр SEO записи',
            'seo.create' => 'Создание SEO записи',
            'seo.update' => 'Обновление SEO записи',
            'seo.delete' => 'Удаление SEO записи',
            'redirects.list' => 'Просмотр списка редиректов',
            'redirects.view' => 'Просмотр редиректа',
            'redirects.create' => 'Создание редиректа',
            'redirects.update' => 'Обновление редиректа',
            'redirects.delete' => 'Удаление редиректа',
            'media.list' => 'Просмотр медиафайлов',
            'media.create' => 'Загрузка медиафайлов',
            'actions.list' => 'Просмотр списка событий',
            'actions.view' => 'Просмотр события',
            'coupons.list' => 'Просмотр списка купонов',
            'coupons.view' => 'Просмотр купона',
            'coupons.create' => 'Создание купона',
            'coupons.update' => 'Обновление купона',
            'coupons.delete' => 'Удаление купона',
        ];

		$all_permissions = [];
		foreach($permissions as $permission => $name){
            $all_permissions[] = (object)[
                'id' => $permission,
                'name' => $name
            ];
        }

        $user_permissions = [];
        foreach((array)$user->permissions as $permission => $access){
            if(!empty($access)){
                $user_permissions[] = $permission;
            }
        }

		return view('admin.users.edit')
			->with('u', $user)
			->with('all_permissions', $all_permissions)
			->with('permissions', $user_permissions)
			->with('image_size', $image_size);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id){
		$rules = $this->rules;

		$rules['email'] = 'required|unique:users,email,'.$id.'';
		$rules['first_name'] = 'required';

		$messages = $this->messages;

		$validator = Validator::make($request->all(), $rules, $messages);
		$user = User::find($id);

        $user_data = $user->fullData();

//		$image_id = $request->image_id ? $request->image_id : $user->user_data->image->id;
//
//		$href = Image::find($image_id)->href;
//
//		$request->merge(['href' => $href, 'user_id' => $id]);

		if($validator->fails()){
			return redirect()
				->back()
				->withInput()
				->with('message-error', 'Сохранение не удалось! Проверьте форму на ошибки!')
				->withErrors($validator);
		}

		$request_user = $request->only(['first_name', 'last_name', 'email']);
		$permissions = [];
		foreach((array)$request->permissions as $permission){
            $permissions[$permission] = true;
        }
        $user->permissions = $permissions;

		$user->fill($request_user);
		$user->save();

		$request_user_data = $request->only(['user_id', 'phone', 'address', 'user_birth', 'other_data', 'file_id']);

		$user->user_data()->update($request_user_data);

		if(isset($user->roles->first()->slug) && $user->roles->first()->slug != $request->role){$role = Sentinel::findRoleBySlug($request->role);
			$old_role = Sentinel::findRoleBySlug($user->roles->first()->slug);
			$old_role->users()->detach($user);
			$role = Sentinel::findRoleBySlug($request->role);
			$role->users()->attach($user);
		}

        Action::updateEntity(User::find($id), $user_data);

		return redirect('/admin/users/edit/'.$user->id)
			->with('message-success', 'Пользователь ' . $user->first_name . ' успешно обновлен.');
	}

	/**
	 * Обновление адреса
	 *
	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function updateAddress(Request $request){
		$user = Sentinel::check();
		if ($user) {
			$user = User::find($user->id);

			$address = json_encode([
				'city' => $request->city,
				'post_code' => $request->post_code,
				'street' => $request->street,
				'house' => $request->house,
				'flat' => $request->flat,
				'npregion' => $request->npregion,
				'npcity' => $request->npcity,
				'npdepartment' => $request->npdepartment,
			], JSON_UNESCAPED_UNICODE);

			$user->user_data()->update(['address' => $address]);

			return response()->json(['success' => true]);
		}else{
			return response()->json(['success' => false]);
		}
	}

	public function saveChangedData(Request $request){
		$user = Sentinel::check();
		if($user){
			$user = User::find($user->id);
		}

		$rules = [
			'first_name' => 'required',
			'last_name' => 'required',
//			'phone'     => 'required|regex:/^[0-9\-! ,\'\"\/+@\.:\(\)]+$/',
			'email'     => 'required|email|unique:users,email,'.$user->id
		];

		$messages = [
			'first_name.required' => 'Не заполнены обязательные поля!',
			'last_name.required' => 'Не заполнены обязательные поля!',
//			'phone.required'    => 'Не заполнены обязательные поля!',
//			'phone.regex'       => 'Некорректный телефон!',
			'email.required'    => 'Не заполнены обязательные поля!',
			'email.email'       => 'Некорректный email-адрес!',
			'email.unique'      => 'Пользователь с таким email-ом уже зарегистрирован!'
		];

		$validator = Validator::make($request->all(), $rules, $messages);

		if ($validator->fails()) {
			return redirect()
				->back()
				->withInput()
				->withErrors($validator);
		}

		$user->first_name = $request->first_name;
		$user->last_name = $request->last_name;
		$user->patronymic = $request->patronymic;
		$user->email = htmlspecialchars($request->email);
		if(empty($user->user_data)){
            $user->user_data()->create([
                'phone' => htmlspecialchars($request->phone),
                'address' => htmlspecialchars($request->address),
                'user_birth' => htmlspecialchars($request->user_birth),
                'city' => htmlspecialchars($request->city),
                'gender' => (bool)$request->gender,
                'subscribe' => 0
            ]);
        }else{
            $user->user_data->phone = htmlspecialchars($request->phone);
            $user->user_data->address = htmlspecialchars($request->address);
            $user->user_data->user_birth = htmlspecialchars($request->user_birth);
            $user->user_data->city = htmlspecialchars($request->city);
            $user->user_data->gender = (bool)$request->gender;
        }

        $data = json_decode($user->user_data->other_data, true);
        if(
            empty($data['profile_promo'])
            && !empty($request->first_name)
            && !empty($request->last_name)
            && !empty($request->first_name)
            && !empty($request->patronymic)
            && !empty($request->email)
            && !empty($request->user_birth)
            && !empty($request->phone)
            && !empty($request->city)
            && isset($request->gender)
        ){
            $coupons = new Coupon();
            $coupons->generateCoupon([
                'percent' => 10,
                'send_to' => $request->email
            ]);
            $data['profile_promo'] = true;
            $user->user_data->other_data = json_encode($data);
        }

		$user->push();

		return redirect('/user')
			->with('status', 'Ваши личные данные успешно изменены!')
			->with('process', 'change_data');
	}

	public function updatePassword(Request $request){
		$user = Sentinel::check();

		if(!password_verify($request->old_password, $user->password)){
            return redirect()
                ->back()
                ->withInput()
                ->withErrors([
                    'old_password' => 'Неправильный старый пароль.'
                ])
                ->with('process', 'update_password');
        }

		if($user){
			$user = User::find($user->id);
		}

		$rules = [
			'password'  => 'min:4|confirmed',
			'password_confirmation' => 'min:4'
		];

		$messages = [
			'password.min'      => 'Пароль должен быть не менее 4 символов!',
			'password.confirmed' => 'Введенные пароли не совпадают!'
		];

		$validator = Validator::make($request->all(), $rules, $messages);
		if($validator->fails()){
			return redirect()
				->back()
				->withInput()
				->withErrors($validator)
				->with('process', 'update_password');
		}

		if($request->password) {
			$user->password = password_hash($request->password, PASSWORD_DEFAULT);
		}

		$user->push();

		return redirect()
            ->back();
	}

	public function updateSubscr(Request $request){
		$user = Sentinel::check();
		if ($user) {
			$user = User::find($user->id);
		}

		$rules = [
			'subscr'  => 'required'
		];

		$messages = [
			'subscr.required' => 'Не выбран тип подписки!'
		];

		$validator = Validator::make($request->all(), $rules, $messages);
		if ($validator->fails()) {
			return response()->json($validator);
		}

		if($request->subscr) {
			$user->user_data->subscribe = $request->subscr;
		}

		$user->push();

		return response()->json(['success' => true]);
	}

	public function get_ip(){
		if (!empty($_SERVER['HTTP_CLIENT_IP']))
		{
			$ip=$_SERVER['HTTP_CLIENT_IP'];
		}
		elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
		{
			$ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
		}
		else
		{
			$ip=$_SERVER['REMOTE_ADDR'];
		}
		return $ip;
	}

	public function subscribe(Request $request, User $user, UserData $user_data){
		$rules = [
			'email'     => 'required|email'
		];

        $messages = [
            'email.required'    => trans('app.You_did_not_enter_an_email_address'),
            'email.email'       => trans('app.Incorrect_email_address'),
        ];

		$validator = Validator::make($request->all(), $rules, $messages);

		if($validator->fails()){
			return response()->json($validator->messages(), 200);
		}

//		$user_exists = User::where('email', $request->email)->first();
//
//		if($user_exists){
//			$subscribe = $user->where('id', $user_exists->id)->first();
//			$subscribe->user_data->subscribe = 1;
//			$subscribe->save();
//		}else{
//			$user = Sentinel::registerAndActivate(array(
//				'email'    => $request->email,
//				'password' => 'null',
//				'permissions' => null
//			));
//
//			$role = Sentinel::findRoleBySlug('unregistered');
//			$role->users()->attach($user);
//
//			$user_data->create([
//				'user_id'   => $user->id,
//				'file_id'  => null,
//				'subscribe' => 1,
//				'created_at' => Carbon::now(),
//				'updated_at' => Carbon::now()
//			]);
//		}

        $sendPulse = new Sendpulse();
        $sendPulse->subscribe($request->email);

		return response()->json(['success' => trans('app.You_have_successfully_subscribed_to_the_news')]);
	}

	public function statistic($id){
		$orders = Order::where('user_id', $id)->get();

		return view('admin.users.orders')->with('orders', $orders)->with('user', User::find($id));
	}

	public function reviews($id){
		$reviews = Review::where('user_id', $id)->paginate(10);

		return view('admin.users.reviews')->with('reviews', $reviews)->with('user', User::find($id));
	}

	public function shopreviews($id){
		$shopreviews = ShopReview::where('user_id', $id)->paginate(10);

		return view('admin.users.shopreviews')->with('shopreviews', $shopreviews)->with('user', User::find($id));
	}

	public function adminWishlist($id){
		$wishlist = Wishlist::where('user_id', $id)->paginate(10);

		return view('admin.users.wishlist')->with('wishlist', $wishlist)->with('user', User::find($id));
	}

	/**
	 * Обновление данных пользователя
	 *
	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function saveUserData(Request $request){
		$user = Sentinel::check();
		if ($user) {
			$user = User::find($user->id);
		}

		$rules = [
			'fio' => 'required',
			'phone'     => 'required|regex:/^[0-9\-! ,\'\"\/+@\.:\(\)]+$/',
			'email'     => 'required|email|unique:users,email,'.$user->id
		];

		$messages = [
			'fio.required' => 'Не заполнены обязательные поля!',
			'phone.required'    => 'Не заполнены обязательные поля!',
			'phone.regex'       => 'Некорректный телефон!',
			'email.required'    => 'Не заполнены обязательные поля!',
			'email.email'       => 'Некорректный email-адрес!'
		];

		$validator = Validator::make($request->all(), $rules, $messages);

		if ($validator->fails()) {
			return response()->json($validator);
		}

		$name = explode(' ', $request->fio);

		$user->first_name = htmlspecialchars($name[0]);
		if(isset($name[1]))
			$user->last_name = htmlspecialchars($name[1]);
		$user->email = htmlspecialchars($request->email);
		$user->user_data->phone = htmlspecialchars($request->phone);
		$user->user_data->user_birth = htmlspecialchars($request->user_birth);

		$user->push();

		return response()->json(['success' => true]);
	}

	public function sendMail(){
		$domain = $_SERVER['HTTP_HOST'];
		$_SESSION['http_host'] = $domain;
		$title = '';
		$subject = 'Перезвоните мне!';

        $files = [];
		if(count($_FILES)){
			foreach ($_FILES as $file) {
				if ($file["error"] == 0) {
					$tmp_name = $file["tmp_name"];
					// basename() может спасти от атак на файловую систему;
					// может понадобиться дополнительная проверка/очистка имени файла
					$name = basename($file["name"]);
                    $storage = storage_path('app'.DIRECTORY_SEPARATOR.'temp'.DIRECTORY_SEPARATOR.$name);
					move_uploaded_file($tmp_name, $storage);
					$files[] = array('path' => $storage, 'name' => $tmp_name);
				}
			}
		}

		if (array_key_exists('data', $_POST)){
            $setting = new Setting();
			$eol = PHP_EOL;

			$msg = "";

			$msg .= "<html><body style='font-family:Arial,sans-serif;'>";
			$msg .= "<h2 style='color:#161616;font-weight:bold;font-size:30px;border-bottom:2px dotted #bd0707;'>Новая заявка на сайте $domain " . $title . "</h2>" . $eol;

			$data = json_decode($_POST['data']);

			$session_data = ['sourse' => 'Поисковая система', 'term' => 'Ключ', 'campaign' => 'Кампания'];

			foreach ($data as $key => $params) {
				if (!empty($params->title) && !empty($params->val)) {
					$val = $this->prepare_data($params->val, $key);
					$msg .= "<p><strong>$params->title:</strong> $val</p>" . $eol;
					if (isset($session_data[$key]))
						unset($session_data[$key]);
				}
			}

			foreach ($session_data as $key => $title) {
				if (array_key_exists($key, $_SESSION)) {
					$val = $this->prepare_data($_SESSION[$key], $key);
					$msg .= "<p><strong>$title:</strong> $val</p>" . $eol;
				}
			}

			$msg .= "</body></html>";

            if(isset($data->target) && in_array($data->target->val, [__('Заказ в 1 клик')])){
                $subject = 'Новый заказ в 1 клик!';
                $users = new User;
                $email = 'email'.rand(0, 1000000).'@placeholder.com';
                while($user = $users->checkIfUnregistered($data->phone->val, $email)){
                    if($email == $user->email)
                        $email = 'email'.rand(0, 1000000).'@placeholder.com';
                    else
                        break;
                }
                $existed_user = $users->checkIfUnregistered($data->phone->val, $email);
                if(!is_null($existed_user)){
                    $user = $existed_user;
                }else{
                    $credentials = [
                        'first_name' => '',
                        'last_name' => '',
                        'phone'     => $data->phone->val,
                        'email'     => $email,
                        'password'  => 'null',
                        'permissions' => [
                            'unregistered' => true
                        ]
                    ];

                    $user = Sentinel::registerAndActivate($credentials);

                    $userRole = Sentinel::findRoleBySlug('unregistered');
                    $userRole->users()->attach($user);

                    if($user->id){
                        CartController::cartToUser($user->id);
                        UserData::create([
                            'user_id'   => $user->id,
                            'image_id'  => 1,
                            'subscribe' => 0
                        ]);
                    }
                }
                $product = Product::where('sku', $data->product->val)->first();
                $order_id = Order::insertGetId([
                    'user_id' => $user->id,
                    'products' => json_encode([
                        $product->id => [
                            "quantity"=>"1",
                            "price"=>$product->price,
                            "sale"=>0,
                            "sale_percent"=>0
                        ]
                    ]),
                    'total_quantity' => 1,
                    'total_price' => $product->price,
                    'user_info' => json_encode([
                        'phone' => $data->phone->val
                    ]),
                    'delivery' => json_encode([]),
                    'payment' => 'cash',
                    'status_id' => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }

            $notify_emails = ['romanpantykin@gmail.com', 'pirana_td@ukr.net'];
            if(!empty($data->type)){
                if($data->type->val == 'Поставщик'){
                    $notify_emails = ['snabmilam.in.ua@gmail.com'];
                }elseif($data->type->val == 'Дистрибьютор'){
                    $notify_emails = ['marketingmilam.in.ua@gmail.com'];
                }
            }

			Mail::send('emails.sendmail', ['html' => $msg], function($msg) use ($setting, $subject, $files, $notify_emails){
                $msg->from('admin@'.str_replace(['http://', 'https://'], '', env('APP_URL')), 'Интернет-магазин Milam');
				$msg->to($notify_emails);
				$msg->subject($subject);
				if(!empty($files)){
				    foreach($files as $file){
                        $msg->attach($file['path'], ['as' => $file['name']]);
                    }
                }
			});

			header("HTTP/1.0 200 OK");
			echo '{"status":"success"}';

		} else {
			header("HTTP/1.0 404 Not Found");
			echo '{"status":"error"}';
		}

		if(!empty($files)){
			foreach($files as $file){
				unlink($file['path']);
			}
		}
	}

	public function prepare_data($data, $key){
		switch ($key) {
			case 'referer':
				return substr($data, 0, 30);
			case 'term':
				return urldecode($data);
			default:
				return $data;
		}
	}

	public function send_mail($to, $thm, $html, $path) {
		$fp = fopen($path,"r");
		if (!$fp) {
			print "Файл $path не может быть прочитан";
			exit();
		}

		$file = fread($fp, filesize($path));
		fclose($fp);

		$boundary = "--".md5(uniqid(time())); // генерируем разделитель
		$headers = "MIME-Version: 1.0\n";
		$headers .="Content-Type: multipart/mixed; boundary=\"$boundary\"\n";
		$multipart = "--$boundary\n";

		$kod = 'utf-8';
		$multipart .= "Content-Type: text/html; charset=$kod\n";
		$multipart .= "Content-Transfer-Encoding: Quot-Printed\n\n";
		$multipart .= "$html\n\n";

		$message_part = "--$boundary\n";
		$message_part .= "Content-Type: application/octet-stream\n";
		$message_part .= "Content-Transfer-Encoding: base64\n";
		$message_part .= "Content-Disposition: attachment; filename = \"".$path."\"\n\n";
		$message_part .= chunk_split(base64_encode($file))."\n";
		$multipart .= $message_part."--$boundary--\n";

		if(mail($to, $thm, $multipart, $headers)) {
			return 1;
		}
	}

	public function export(){
	    $users = [[
            'Email',
            'Телефон',
            'Имя',
            'Фамилия',
            'Отчество',
            'Пол',
            'День рождения',
            'Город'
        ]];
	    foreach(User::select(['users.*'])->join('role_users', function ($join) {
            $join->on('users.id', '=', 'role_users.user_id')
                ->whereIn('role_users.role_id', [5,6]);
        })->with(['user_data'])->get() as $user){
	        $phone = is_object($user->user_data) ? $user->user_data->phone : '';
	        if(strlen($phone) == 10){
                $phone = '+38'.$phone;
            }elseif(strlen($phone) == 12){
                $phone = '+'.$phone;
            }

            $users[] = [
                'email' => $user->email,
                'phone' => ' '.$phone,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'patronymic' => $user->patronymic,
                'gender' => is_object($user->user_data) && $user->user_data->gender ? 'Муж' : 'Жен',
                'user_birth' => is_object($user->user_data) ? $user->user_data->user_birth : '',
                'city' => is_object($user->user_data) ? $user->user_data->city : ''
            ];
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);
        $sheet->getColumnDimension('G')->setAutoSize(true);
        $sheet->getColumnDimension('H')->setAutoSize(true);
        $sheet->getStyle('B1:B'.(count($users)+1))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $sheet->fromArray($users, NULL, 'A1');

        $streamedResponse = new StreamedResponse();
        $streamedResponse->setCallback(function() use ($spreadsheet){
            $writer = new Xls( $spreadsheet );
            $writer->save('php://output');
        });

        $streamedResponse->setStatusCode(200);
        $streamedResponse->headers->set('Content-Type', 'text/csv');
        $streamedResponse->headers->set('Content-Disposition', 'attachment; filename="Покупатели.xls"');

        return $streamedResponse->send();
    }

    public function import(Request $request){
        if($request->hasFile('import_file')){
            $file = $request->file('import_file');
            $file_name = $file->getClientOriginalName();

            $file->move(storage_path('app/imports'), $file_name);

            $path = storage_path('app/imports/'.$file_name);

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
                $users = new User();
                foreach($data as $user){
                    if(!empty($user['Email'])){
                        $u = $users->where('email', $user['Email'])->first();
                        if(!empty($u)){
                            $u->email = $user['Email'];
                            $u->first_name = $user['Имя'];
                            $u->last_name = $user['Фамилия'];
                            $u->patronymic = $user['Отчество'];

                            $u->user_data->phone = trim($user['Телефон']);
                            if(!empty($user['День рождения']))
                                $u->user_data->user_birth = date('Y-m-d', strtotime($user['День рождения']));
                            $u->user_data->city = $user['Город'];
                            $u->user_data->gender = $user['Город'] == 'Муж' ? 1 : 0;

                            $u->push();
                        }else{
                            $credentials = [
                                'first_name' => $user['Имя'],
                                'last_name' => $user['Фамилия'],
                                'patronymic' => $user['Отчество'],
                                'email'     => $user['Email'],
                                'password'  => 'null',
                                'permissions' => [
                                    'user' => true
                                ]
                            ];

                            $u = Sentinel::registerAndActivate($credentials);

                            $userRole = Sentinel::findRoleBySlug('user');
                            $userRole->users()->attach($u);

                            if($u->id){
                                UserData::create([
                                    'user_id'   => $u->id,
                                    'image_id'  => null,
                                    'phone' => trim($user['Телефон']),
                                    'user_birth' => !empty($user['День рождения']) ? date('Y-m-d', strtotime($user['День рождения'])) : '',
                                    'city' => $user['Город'],
                                    'gender' => $user['Город'] == 'Муж' ? 1 : 0,
                                    'subscribe' => 0
                                ]);
                            }
                        }
                    }
                }
            }
        }

        return response()->json(['result' => 'success']);
    }
}
