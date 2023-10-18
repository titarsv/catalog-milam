<?php

namespace App\Models;

use Illuminate\Support\Facades\Cache;

class Setting extends Entity
{
    protected $fillable = [
        'id',
        'key',
        'value',
        'autoload'
    ];

    public $extra = [
        'newpost_api_key',
        'newpost_regions_update_period',
        'newpost_regions_last_update',
        'newpost_cities_update_period',
        'newpost_cities_last_update',
        'newpost_warehouses_update_period',
        'newpost_warehouses_last_update',
        'liqpay_api_public_key',
        'liqpay_api_private_key',
        'liqpay_api_currency',
        'liqpay_api_sandbox',
        'delivery_methods',
        'payment_methods',
        'wayforpay_account',
        'wayforpay_secret',
        'wayforpay_sandbox',
        'newpost_sender_id',
        'newpost_sender_city_id',
        'newpost_warehouse_sender_id',
    ];

    public $entity_type = 'setting';
    protected $table = 'settings';
    public $timestamps = false;


    /**
     * Получение глобальных настроек
     * @return array
     */
    public function get_global(){
        $settings = Cache::store('array')->remember('global_settings', 1, function () {
            return $this->convert_to_array($this->where('autoload', true)->get());
        });

        return $settings;
    }

	/**
	 * Получение всех настроек
	 *
	 * @return object
	 */
    public function get_all(){
        return $this->convert_to_array($this->all());
    }

    /**
     * Получение определённой настройки
     * @param $key
     * @return mixed|string
     */
	public function get_setting($key){
		$setting = $this->where('key', $key)->first();

		if(!empty($setting)) {
			return $this->maybe_json_decode($setting->value);
		}else
			return '';
	}

	/**
	 * Преобразование настроек в ассоциативный массив
	 *
	 * @param $data
	 *
	 * @return object
	 */
    public function convert_to_array($data){

        $settings = [];
        if($data !== null){
            foreach ($data as $setting){
                $settings[$setting->key] = $this->maybe_json_decode($setting->value);
            }
        }

        return (object)$settings;
    }

    /**
     * Декодирование json в случае необходимости
     * @param $string
     * @return mixed
     */
    static function maybe_json_decode($string) {
        if(is_string($string))
            $decoded = json_decode($string);
        if(isset($decoded) && (is_object($decoded) || is_array($decoded)))
            return $decoded;
        else
            return $string;
    }

    /**
     * Преобразование в json строку случае необходимости
     * @param $data
     * @return mixed
     */
    static function maybe_json_encode($data) {;
        if(is_array($data) || is_object($data))
            return json_encode($data, JSON_UNESCAPED_UNICODE);
        else
            return $data;
    }

    /**
     * Добавление настройки
     * @param $key
     * @param $value
     * @param bool $autoload
     */
    public function add_setting($key, $value, $autoload = false){
        $id = $this->insertGetId(['key' => $key, 'value' => $this->maybe_json_encode($value), 'autoload' => $autoload]);

        Action::createEntity($this->find($id));
    }

    /**
     * Обновление настройки
     * @param $key
     * @param $value
     * @param bool $autoload
     */
    public function update_setting($key, $value, $autoload = false){
        if($this->isset_setting($key)){
            $setting = $this->where('key', $key)->first();
            $setting_data = $setting->fullData();
//            $this->where('key', $key)
            $setting->update(['value' => $this->maybe_json_encode($value), 'autoload' => $autoload]);

            Action::updateEntity($setting, $setting_data);
        }else{
            $this->add_setting($key, $value);
        }
    }

    /**
     * Проверка наличия настройки
     * @param $key
     * @return bool
     */
    public function isset_setting($key){
        if($this->where('key', $key)->count())
            return true;
        else
            return false;
    }

    /**
     * Обновление группы настроек
     * @param $settings
     * @param $autoload
     */
    public function update_settings($settings, $autoload){
        foreach($settings as $key => $value){
            $this->update_setting($key, $value, $autoload);
        }
    }

    public function get_extra(){
        return $this->convert_to_array($this->whereIn('key', $this->extra)->get());
    }

    protected function dataMap(){
        return [
            'attributes' => [
                'id' => '',
                'key' => '',
                'value' => ''
            ]
        ];
    }

    public function fieldsNames(){
        return [
            'attributes.key' => [
                'name' => 'Ключ'
            ],
            'attributes.value' => [
                'name' => 'Значение'
            ]
        ];
    }
}
