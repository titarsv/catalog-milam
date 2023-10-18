<?php

namespace App\Http\Controllers;

use Cartalyst\Sentinel\Native\Facades\Sentinel;
use Illuminate\Support\Facades\Redis;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\Newpost;
use App\Models\Product;
use App\Models\Price;
use App\Models\File;
use Validator;
use Cache;
use Excel;

class SettingsController extends Controller
{
	private $user;
	private $settings;

	function __construct(Setting $settings)
	{
		$this->user = Sentinel::check();
		$this->settings = $settings;
	}

	public function index()
	{
		$settings = $this->settings->get_all();

		$image_sizes = config('image.sizes');

		return view('admin.settings')
			->with('user', $this->user)
			->with('settings', $settings)
			->with('image_sizes', $image_sizes);
	}

	public function update(Request $request, Setting $settings)
	{
		$rules = [
			'notify_emails.*' => 'email|distinct|filled',
			'other_phones.*' => 'distinct|filled|regex:/^[0-9\-! ,\'\"\/+@\.:\(\)]+$/',
			'main_phone_1' => 'regex:/^[0-9\-! ,\'\"\/+@\.:\(\)]+$/',
			'main_phone_2' => 'regex:/^[0-9\-! ,\'\"\/+@\.:\(\)]+$/',
		];

		$messages = [
			'notify_emails.*.email' => 'Введите корректный e-mail адрес!',
			'notify_emails.*.distinct' => 'Значения одинаковы!',
			'notify_emails.*.filled' => 'Поле должно быть заполнено!',
			'other_phones.*.distinct' => 'Значения одинаковы!',
			'other_phones.*.filled' => 'Поле должно быть заполнено!',
			'other_phones.*.regex' => 'Неверный формат телефона!',
			'main_phone_1.regex' => 'Неверный формат телефона!',
			'main_phone_2.regex' => 'Неверный формат телефона!',
		];

		$validator = Validator::make($request->all(), $rules, $messages);

		if ($validator->fails()) {
			return redirect()
				->back()
				->withInput()
				->with('message-error', 'Сохранение не удалось! Проверьте форму на ошибки!')
				->withErrors($validator);
		}

        if(!empty($request->usd_rate) && $settings->get_setting('usd_rate') != $request->usd_rate){
            $settings->update_setting('usd_rate', $request->usd_rate);
        }

        if(!empty($request->eur_rate) && $settings->get_setting('eur_rate') !=$request->eur_rate){
            $settings->update_setting('eur_rate', $request->eur_rate);
        }

		$settings->update_settings($request->except('_token', 'usd_rate', 'eur_rate'), true);

		return back()->with('message-success', 'Настройки успешно сохранены!');
	}

	public function extraIndex(Setting $setting)
	{
		$update_period = [
			[
				'period'    => 'Каждый день',
				'value'     => 86400
			],
			[
				'period'    => 'Раз в неделю',
				'value'     => 604800
			],
			[
				'period'    => 'Раз в месяц',
				'value'     => 2592000
			],
			[
				'period'    => 'Раз в полгода',
				'value'     => 15552000
			],
		];

		$currencies = ['USD', 'EUR', 'RUB', 'UAH', 'BYN', 'KZT'];

        $newpost = new Newpost();
        $data = $newpost->getCounterparties('', 'Sender');
        $np_senders = !empty($data) ? $data['data'] : [];

        $settings = $setting->get_extra();
        $warehouses = !empty($settings->newpost_sender_city_id) ? $newpost->getWarehouses($settings->newpost_sender_city_id) : [];

		return view('admin.extra_settings', [
			'settings' => $settings,
			'delivery_names' => [
                'pickup' => __('Самовывоз'),
                'newpost' => __('Новая Почта'),
                'justin' => __('Justin'),
                'courier' => __('Курьер'),
                'other' => __('Другое')
            ],
			'payment_names' => [
                'online' => __('Через WayForPay'),
                'cash' => __('Наличными при самовывозе'),
                'card' => __('На карту Приватбанка')
            ],
			'update_period' => $update_period,
			'currencies' => $currencies,
			'np_senders' => $np_senders,
			'warehouses' => $warehouses
		]);
	}

	public function seoSettings()
	{
		$settings = $this->settings->get_all();

		$image_sizes = config('image.sizes');

		return view('admin.seo_settings')
			->with('user', $this->user)
			->with('settings', $settings)
			->with('image_sizes', $image_sizes)
			->with('image', empty($settings->ld_image) ? File::find(1) : File::find($settings->ld_image));
	}

	public function seoUpdate(Request $request, Setting $settings)
	{
		$rules = [
			'ld_type' => 'required',
			'ld_name' => 'required',
			'ld_description' => 'required',
		];

		$messages = [
			'ld_type.required' => 'Поле Тип должно быть заполнено!',
			'ld_name.required' => 'Поле Название организации должно быть заполнено!',
			'ld_description.required' => 'Поле Описание должно быть заполнено!',
		];

		$validator = Validator::make($request->all(), $rules, $messages);

		if ($validator->fails()) {
			return redirect()
				->back()
				->withInput()
				->with('message-error', 'Сохранение не удалось! Проверьте форму на ошибки!')
				->withErrors($validator);
		}

		$data = $request->except('_token');
		$data['social'] = array_diff($data['social'], ['']);

		$settings->update_settings($data, true);

		return back()->with('message-success', 'Настройки успешно сохранены!');
	}

	public function newpostUpdate(Newpost $newpost){
		$result = $newpost->updateAll();

		if ($result){
			$message_status = 'message-success';
			$message_text = 'Данные API Новой Почты успешно обновлены!';
		} else {
			$message_status = 'message-error';
			$message_text = 'При обновлении данных произошла ошибка! Подробности: /storage/logs/laravel.log';
		}
		return redirect('/admin/delivery-and-payment')
			->with($message_status, $message_text);
	}

	public function extraUpdate(Request $request, Setting $settings){
		$rules = [
			'newpost_api_key' => 'filled|max:32',
			'newpost_regions_update_period' => 'filled|not_in:0',
			'newpost_cities_update_period' => 'filled|not_in:0',
			'newpost_warehouses_update_period' => 'filled|not_in:0',
			'liqpay_api_public_key' => 'filled',
			'liqpay_api_private_key' => 'filled',
			'liqpay_api_currency' => 'filled|not_in:0'
		];

		$messages = [
			'newpost_api_key.filled' => 'Поле должно быть заполнено!',
			'newpost_api_key.max' => 'Длина ключа должна быть не более 32 символов!',
			'newpost_regions_update_period.filled' => 'Выберите период!',
			'newpost_regions_update_period.not_in' => 'Выберите период!',
			'newpost_cities_update_period.filled' => 'Выберите период!',
			'newpost_cities_update_period.not_in' => 'Выберите период!',
			'newpost_warehouses_update_period.filled' => 'Выберите период!',
			'newpost_warehouses_update_period.not_in' => 'Выберите период!',
			'liqpay_api_public_key.filled' => 'Поле должно быть заполнено!',
			'liqpay_api_private_key.filled' => 'Поле должно быть заполнено!',
			'liqpay_api_currency.filled' => 'Выберите валюту платежей!',
			'liqpay_api_currency.not_in' => 'Выберите валюту платежей!',
		];

		$validator = Validator::make($request->all(), $rules, $messages);

		if($validator->fails()){
			return redirect()
				->back()
				->withInput()
				->with('message-error', 'Сохранение не удалось! Проверьте форму на ошибки!')
				->withErrors($validator);
		}

		$delivery_methods = [
		    'pickup' => 0,
		    'newpost' => 0,
            'justin' => 0,
            'courier' => 0,
            'other' => 0
        ];

		if($request->has('delivery_methods')){
            foreach($delivery_methods as $method => $status){
                if(in_array($method, $request->delivery_methods)){
                    $delivery_methods[$method] = 1;
                }
            }

            $settings->update_setting('delivery_methods', $delivery_methods, false);
        }

//		$settings->update_settings($request->except('_token', 'delivery_methods'), false);

        $payment_methods = [
            'cash' => 0,
            'card' => 0
        ];

        if($request->has('payment_methods')){
            foreach($payment_methods as $method => $status){
                if(in_array($method, $request->payment_methods)){
                    $payment_methods[$method] = 1;
                }
            }

            $settings->update_setting('payment_methods', json_encode($payment_methods), false);
        }

        if(!empty($request->newpost_sender_id) && $settings->get_setting('newpost_sender_id') != $request->newpost_sender_id){
            $newpost = new Newpost();
            $data = $newpost->getCounterparties('', 'Sender');
            $np_senders = !empty($data) ? $data['data'] : [];

            foreach($np_senders as $sender){
                if($sender['Ref'] == $request->newpost_sender_id){
                    $settings->update_setting('newpost_sender_city_id', $sender['City'], false);
                }
            }

            $data = $newpost->getContacts($request->newpost_sender_id);
            $np_contacts = !empty($data) ? $data['data'] : [];

            if(!empty($np_contacts)){
                $settings->update_setting('newpost_sender_contact_id', $np_contacts[0]['Ref'], false);
                $settings->update_setting('newpost_sender_contact_phone', $np_contacts[0]['Phones'], false);
            }
        }

        $settings->update_settings($request->except('_token', 'delivery_methods', 'payment_methods'), false);
		Cache::flush();

		return back()->with('message-success', 'Настройки успешно сохранены!');
	}

	public function adminTelegramAction(Setting $settings){
	    return view('admin.telegram')
            ->with('telegram', (array)$settings->get_setting('telegram'));
    }

    public function updateTelegramAction(Setting $settings, Request $request){
	    $telegram = (array)$settings->get_setting('telegram');
        $telegram['token'] = $request->token;

        if(empty($telegram['clients'])){
            $telegram['clients'] = [];
        }

        $token = $telegram['token'];
        $bot = new \TelegramBot\Api\Client($token);

        foreach($telegram['clients'] as $id => $client){
            if(empty($telegram['clients']->$id->moderated) && !empty($request->clients[$id])){
                $bot->sendMessage($telegram['clients']->$id->chat, 'Ваша заявка на подписку подтверждена!');
            }
            $telegram['clients']->$id->moderated = !empty($request->clients[$id]);
        }

        $settings->update_setting('telegram', $telegram);

        return view('admin.telegram')
            ->with('telegram', $telegram);
    }
}
