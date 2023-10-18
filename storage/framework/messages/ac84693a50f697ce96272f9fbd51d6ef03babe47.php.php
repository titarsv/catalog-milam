<?php

namespace App\Http\Controllers;

use App\Models\Attribute;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Models\Viber;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Setting;

class ApiController extends Controller
{
    public function index(Request $request, $method = ''){
    	if(empty($request->user_id)){
    		return response()->json(['status' => 'error', 'message' => 'Не указан ID пользователя']);
	    }else{
		    $user = User::find($request->user_id);
		    if(empty($user)){
			    return response()->json(['status' => 'error', 'message' => 'Пользователь с таким ID не существует']);
		    }else{
			    $hash = md5(md5($user->password).str_replace(['?hash='.$request->hash, '&hash='.$request->hash], ['?', ''], $_SERVER['REQUEST_URI']));
			    if($hash != $request->hash && $method != 'test_hash'){
				    return response()->json(['status' => 'error', 'message' => 'Неверный хеш, для проверки хеша можете воспользоваться следующим методом: '.env('APP_URL').'/api/test_hash']);
			    }
		    }
	    }

        if(!empty($method) && method_exists($this, $method)){
            return $this->{$method}($request);
        }

        return response()->json(['status' => 'error', 'message' => 'Не указан метод', 'methods' => [
            'products_list' => 'Список товаров',
            'product' => 'Информация о товаре',
            'update_product' => 'Обновление товара',
            'orders_list' => 'Список заказов',
            'update_order' => 'Обновление заказа',
            'categories_list' => 'Список категорий',
            'category' => 'Информация о категории',
            'attributes_list' => 'Список атрибутов',
        ]]);
    }

    /**
     * Тестирование хеширования данных
     *
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function test_hash($request){
	    if(empty($request->user_id)){
		    return response()->json(['status' => 'error', 'message' => 'Не указан ID пользователя']);
	    }else{
		    $user = User::find($request->user_id);
		    if(empty($user)){
			    return response()->json(['status' => 'error', 'message' => 'Пользователь с таким ID не существует']);
		    }else{
		    	$string = str_replace(['?hash='.$request->hash, '&hash='.$request->hash], ['?', ''], $_SERVER['REQUEST_URI']);
			    $hash = md5(md5($user->password).$string);
			    if($hash != $request->hash){
				    return response()->json(['status' => 'error', 'message' => 'Неверный хеш, должен быть '.$hash.' Для получения правильного хеша необходимо вычислить md5 из суммы строк (\'Ваш токен\' + \'Строка запроса без хеша\') В данном случае строка запроса без хеша = \''.$string.'\'']);
			    }else{
				    return response()->json(['status' => 'success', 'message' => 'Проверка прошла успешно, хеш правильный']);
			    }
		    }
	    }
    }

    /**
     * Список товаров
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function products_list(){
        $products = Product::select('id', 'sku', 'name', 'price', 'stock')->get()->toArray();

        return response()->json(['status' => 'success', 'products' => $products]);
    }

    /**
     * Информация о товаре
     *
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function product($request){
        if(empty($request->id)){
            return response()->json(['status' => 'error', 'message' => 'Не указан ID товара', 'params_list' => [
                'id' => 'ID товара, передаётся GET-параметром'
            ]]);
        }

        $product = Product::find($request->id)->toArray();

        return response()->json(['status' => 'success', 'product' => $product]);
    }

    /**
     * Обновление товара
     *
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update_product($request){
        if(empty($request->id)){
            return response()->json(['status' => 'error', 'message' => 'Не указан ID товара', 'params_list' => [
                'id' => 'ID товара, передаётся GET-параметром',
                'stock' => 'Наличие товара, передаётся GET-параметром',
                'price' => 'Актуальная цена, передаётся GET-параметром',
                'old_price' => 'Старая цена, передаётся GET-параметром'
            ]]);
        }

        $product = Product::find($request->id);

        $data = [];
        if(isset($request->stock)){
            $data['stock'] = $request->stock;
        }
        if(isset($request->price) && is_numeric($request->price)){
            $data['price'] = $request->price;
        }
        if(isset($request->old_price) && is_numeric($request->old_price)){
            $data['old_price'] = $request->old_price;
        }

        $product->update($data);

        return response()->json(['status' => 'success', 'message' => 'Товар обновлен', 'product' => $product]);
    }

    /**
     * Список заказов
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function orders_list(){
        $orders = Order::all()->toArray();

        foreach ($orders as $id => $order){
            $orders[$id]['products'] = json_decode($order['products']);
            $orders[$id]['user_info'] = json_decode($order['user_info']);
            $orders[$id]['delivery'] = json_decode($order['delivery']);
        }

        return response()->json(['status' => 'success', 'products' => $orders]);
    }

    /**
     * Обновление заказа
     *
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update_order($request){
        if(empty($request->id)){
            return response()->json(['status' => 'error', 'message' => 'Не указан ID заказа', 'params_list' => [
                'id' => 'ID заказа, передаётся GET-параметром',
                'status_id' => 'ID статуса заказа, передаётся GET-параметром',
                'ttn' => 'Номер ТТН, передаётся GET-параметром'
            ]]);
        }

        $order = Order::find($request->id);

        $data = [];
        if(isset($request->status_id)){
            $data['status_id'] = (int)$request->status_id;
        }
        if(isset($request->ttn)){
            $delivery = json_decode($order->delivery, true);
            if(!is_array($delivery['info']))
                $delivery['info'] = [];
            $delivery['info']['ttn'] = $request->ttn;
            $data['delivery'] = json_encode($delivery);
        }

        $order->update($data);

        $order['products'] = json_decode($order['products']);
        $order['user_info'] = json_decode($order['user_info']);
        $order['delivery'] = json_decode($order['delivery']);

        return response()->json(['status' => 'success', 'message' => 'Заказ обновлен', 'order' => $order->toArray()]);
    }

	/**
	 * Список категорий
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function categories_list(){
		$categories = Category::select('id', 'name', 'url_alias', 'parent_id')->get()->toArray();

		return response()->json(['status' => 'success', 'categories' => $categories]);
	}

	/**
	 * Информация о категории
	 *
	 * @param $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function category($request){
		if(empty($request->id)){
			return response()->json(['status' => 'error', 'message' => 'Не указан ID категории', 'params_list' => [
				'id' => 'ID категории, передаётся GET-параметром'
			]]);
		}

		$category = Category::find($request->id)->toArray();

		return response()->json(['status' => 'success', 'category' => $category]);
	}

	/**
	 * Список атрибутов
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function attributes_list(){
		$attributes = Attribute::select('id', 'name', 'slug')->with('values')->get()->toArray();

		return response()->json(['status' => 'success', 'attributes' => $attributes]);
	}

	public function viberAction(){
        $viber = new Viber();

        return $viber->getMessage();
    }

    public function viberSetWevHookAction(){
	    $viber = new Viber();

        return $viber->setWebHook();
    }

    public function telegramAction(Setting $settings){
        $telegram = (array)$settings->get_setting('telegram');
        $token = $telegram['token'];
        $bot = new \TelegramBot\Api\Client($token);

        // команда для start
        $bot->command('start', function ($message) use ($bot, $telegram) {
            $user = $message->getFrom();
            $user_id = $user->getId();
            $name = trim($user->getFirstName().' '.$user->getLastName());
            if(!empty($name)){
                $answer = "$name, добро пожаловать в чат!";
            }else{
                $answer = "Добро пожаловать в чат!";
            }

//            if(!empty($telegram['clients']) && isset($telegram['clients'][$user_id])) {
//                $bot->sendMessage($message->getChat()->getId(), $answer);
//            }else{
                $keyboard = new \TelegramBot\Api\Types\ReplyKeyboardMarkup([[['text' => 'Подписаться на рассылку', 'request_contact' => true]]], true, true);
                $bot->sendMessage($message->getChat()->getId(), $answer, null, false, null, $keyboard);
//            }
        });

        // команда для помощи
        $bot->command('help', function ($message) use ($bot) {
            $answer = 'Команды:
            /help - вывод справки';
            $bot->sendMessage($message->getChat()->getId(), $answer);
        });

        $bot->on(function (\TelegramBot\Api\Types\Update $update) use ($bot, $telegram, $settings) {
            $message = $update->getMessage();
            $contact = $message->getContact();
            $user = $message->getFrom();
            $user_id = $user->getId();
            $keyboard = new \TelegramBot\Api\Types\ReplyKeyboardRemove();

            if(!empty($contact))
                $phone = $contact->getPhoneNumber();

            if(!empty($phone)){
                if(empty($telegram['clients']) || !isset($telegram['clients']->$user_id)){
                    $telegram['clients']->$user_id = [
                        'name' => trim($user->getFirstName().' '.$user->getLastName()),
                        'phone' => $contact->getPhoneNumber(),
                        'chat' => $message->getChat()->getId(),
                        'moderated' => false
                    ];
                    $settings->update_setting('telegram', $telegram);

                    $bot->sendMessage($message->getChat()->getId(), 'Ваш номер: ' . $contact->getPhoneNumber() . ' отправлен на модерацию, после успешного прохождения модерации Вы начнёте получать уведомления.', null, false, null, $keyboard);
                }elseif(isset($telegram['clients']->$user_id)){
                    $bot->sendMessage($message->getChat()->getId(), 'Вы уже оформили подписку.', null, false, null, $keyboard);
                }
            }
        }, function () {
            return true;
        });

        $bot->run();
    }
}