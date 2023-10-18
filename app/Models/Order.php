<?php

namespace App\Models;

use App;

class Order extends Entity
{
    public $table = 'orders';
    public $countries = 'orders';
    protected $fillable = [
        'external_id',
        'user_id',
        'products',
        'total_price',
        'total_quantity',
        'status_id',
        'user_info',
        'delivery',
        'payment',
        'notes',
        'viewed',
        'history'
    ];

    public function __construct(array $attributes = [])
    {
        $this->countries = [
            'ukraine' => __('Украина'),
            'germany' => __('Германия')
        ];
        parent::__construct($attributes);
    }

    public static function boot(){
        parent::boot();

        self::updating(function($model){
            if($model->original['status_id'] != $model->attributes['status_id'] && $model->original['status_id'] == 1 && in_array($model->attributes['status_id'], [2,3,4])){
                $saved_products = json_decode($model->products, true);
                $products = $model->getProducts();
                $coupons = new Coupon();

                foreach($products as $key => $product){
                    if($product['product']->certificate && empty($product['generated'])){
                        $price = $product['product']->original_price;

                        $coupons->generateCoupon([
                            'price' => $price,
                            'send_to' => $model->getEmailAttribute()
                        ]);

                        $saved_products[$product['product_code']]['generated'] = true;
                    }
                }

                $model->products = json_encode($saved_products, JSON_UNESCAPED_UNICODE);
            }
        });
    }

    public function coupon(){
        return $this->hasOne('App\Models\Coupon', 'id', 'coupon_id');
    }

    public function getNameAttribute(){
        $userInfo = $this->getUserInfo();
        if(isset($userInfo->name) && !empty(trim($userInfo->name)))
            return $userInfo->name;
        else{
            $user = $this->user;
            return !empty($user->name) ? $user->name : '';
        }
    }

    public function getEmailAttribute(){
        $userInfo = $this->getUserInfo();
        if(!empty($userInfo->email))
            return $userInfo->email;
        else{
            $user = $this->user;
            return !empty($user->email) ? $user->email : '';
        }
    }

    public function getPhoneAttribute(){
        $userInfo = $this->getUserInfo();
        if(!empty($userInfo->phone))
            return $userInfo->phone;
        else{
            $user = $this->user;
            return !empty($user->phone) ? $user->phone : '';
        }
    }

    public function getCityAttribute(){
        $deliveryInfo = $this->getDeliveryInfo();
        if(!empty($deliveryInfo['city']))
            return $deliveryInfo['city'];
        else{
//            $user = $this->user;
//            return !empty($user->getAddress()->city) ? $user->getAddress()->city : '';
            return '';
        }
    }

    public function getDeliveryMethodAttribute(){
        $deliveryInfo = $this->getDeliveryInfo();
        if(!empty($deliveryInfo['method']))
            return $deliveryInfo['method'];
        else{
            return '';
        }
    }

    public function getAddressAttribute(){
        $deliveryInfo = $this->getDeliveryInfo();
        if($deliveryInfo['key'] == 'courier')
            return sprintf(__('г.').' %s, '.__('доставка по городу на адрес').': %s', isset($deliveryInfo['city']) ? $deliveryInfo['city'] : '',
            (isset($deliveryInfo['street']) ? $deliveryInfo['street'] : '').
                (isset($deliveryInfo['house']) ? ' '.$deliveryInfo['house'] : '').
                (isset($deliveryInfo['apartment']) ? ', '.__('кв').'.'.$deliveryInfo['apartment'] : '').
                (isset($deliveryInfo['details']) ? ', '.$deliveryInfo['details'] : ''));
        elseif($deliveryInfo['key'] == 'newpost')
            return sprintf(__('г.').' %s, '.__('доставка Новой почтой в').' %s', isset($deliveryInfo['city']) ? $deliveryInfo['city'] : '', isset($deliveryInfo['warehouse']) ? $deliveryInfo['warehouse'] : '');
        elseif($deliveryInfo['key'] == 'newpost_courier')
            return sprintf(__('г.').' %s, '.__('доставка Новой почтой по адресу').': %s', isset($deliveryInfo['city']) ? $deliveryInfo['city'] : '', isset($deliveryInfo['address']) ? $deliveryInfo['address'] : '');
        elseif($deliveryInfo['key'] =='justin')
            return $deliveryInfo['warehouse'];
        elseif($deliveryInfo['key'] == 'ukrpost')
            return sprintf(__('г.').' %s, '.__('доставка Укрпочтой по адресу').': %s', isset($deliveryInfo['city']) ? $deliveryInfo['city'] : '',
                $deliveryInfo['street'].
                ' '.$deliveryInfo['house'].
                ', '.__('кв').'.'.$deliveryInfo['apart'].
                ', '.$deliveryInfo['index']);
        elseif($deliveryInfo['key'] == 'pickup')
            return sprintf(__('г.').' %s, '.__('самовывоз из салона').' %s', isset($deliveryInfo['city']) ? $deliveryInfo['city'] : '', isset($deliveryInfo['warehouse']) ? $deliveryInfo['warehouse'] : '');
        elseif(in_array($deliveryInfo['key'], ['emc', 'dhl', 'fedex', 'newpost_international', 'tnt']))
            return sprintf('%s, '.__('г.').' %s, '.__('доставка').' %s '.__('по адресу').': %s',
                isset($deliveryInfo['country_name']) ? $deliveryInfo['country_name'] : '',
                isset($deliveryInfo['city']) ? $deliveryInfo['city'] : '',
                isset($deliveryInfo['method']) ? $deliveryInfo['method'] : '',
                $deliveryInfo['street'].
                ' '.$deliveryInfo['house'].
                ', '.__('кв').'.'.$deliveryInfo['apart'].
                ', '.$deliveryInfo['index']);
        else{
            return '';
        }
    }

    public function getPaymentMethodAttribute(){
        $methods = [
            'cash' => __('Наличными при самовывозе'),
            'card' => __('На карту Приватбанка'),
            'online' => __('Через WayForPay'),
            'prepayment' => __('Предоплата')
        ];
        if(isset($methods[$this->payment]))
            return $methods[$this->payment];
        else{
            return '';
        }
    }

    public function getHistoryAttribute(){
        if(empty($this->attributes['history']) || $this->attributes['history'] === '[]'){
            $history = [];
        }else{
            $history = json_decode($this->attributes['history'], true);
        }

        return $history;
    }

    public function getUserInfo(){
        return json_decode($this->user_info);
    }

    /**
     * Связь с моделью статусов заказа
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function status(){
        return $this->belongsTo('App\Models\OrderStatus', 'status_id');
    }

    /**
     * Получение незавершенного заказа по id пользователя
     *
     * @param $user
     * @return int
     */
    public function getCurrentIncompleteOrder($user){
        if($user){
            $order = $this->where('user_id', $user->id)->where('status_id', 0)->first();

            if (!is_null($order)) {
                return $order->id;
            }
        }

        return 0;
    }

    /**
     * Товары в заказе
     *
     * @return array
     */
    public function getProducts(){
        $products = json_decode($this->products, true);
        $result = [];

        foreach ($products as $product_code => $data) {
            $variation_attrs = [];
            if(!empty($data['variation'])){
                $v = new Variation();
                $variation = $v->find($data['variation']);
                $values = $variation->attribute_values;
                foreach($values as $value){
                    $attr = $value->attribute;
                    if(!isset($variation_attrs[$attr->name])){
                        $variation_attrs[$attr->name] = $value->name;
                    }
                }
            }

            $product_vars = explode('_', $product_code);
            $result[] = [
                'product'   => Product::where('id', $product_vars[0])->with('attributes.value', 'image')->first(),
                'quantity'  => $data['quantity'],
                'variations'  => $variation_attrs,
                'price'  => $data['price'],
	            'product_code' => $product_code,
	            'product_sum' => $data['price']*$data['quantity'] * (100 - $data['sale_percent']) / 100 - $data['sale']
            ];
        }

        return $result;
    }

    /**
     * Информация о доставке в удобночитаемом формате
     *
     * @return array
     */
    public function getDeliveryInfo(){
        $delivery_info = json_decode($this->delivery, true);
        $locale = app()->getLocale();
        if(empty($delivery_info)){
        	return null;
        }

        if(!empty($delivery_info['info']['country']) && isset($this->countries[$delivery_info['info']['country']])){
            $delivery_info['info']['country_name'] = $this->countries[$delivery_info['info']['country']];
        }

        if($delivery_info['method'] == 'newpost'){
            $newpost = new Newpost();
            $result = [
                'key'    => $delivery_info['method'],
                'method'    => __('«Новая почта» — отделение'),
                'delivery_cost' => isset($delivery_info['delivery_cost']) ? $delivery_info['delivery_cost'] : 0
            ];

            if(is_array($delivery_info['info']) && (!empty($delivery_info['info']['city_id']) || !empty($delivery_info['info']['warehouse']))){
//                $result['region'] = $newpost->getRegionRef($delivery_info['info']['region'])->{"name_$locale"};
                if(!empty($delivery_info['info']['warehouse'])){
                    $warehouse = $newpost->getWarehouseByWid($delivery_info['info']['warehouse']);
                    $result['warehouse'] = !empty($warehouse) ? $warehouse->{"address_$locale"} : '';
                }
                if(!empty($delivery_info['info']['city_id'])){
                    $city = $newpost->getCityByCid($delivery_info['info']['city_id']);
                    $result['city'] = !empty($city) ? $city->{"name_$locale"} : '';
                }elseif(!empty($warehouse)){
                    $city = $newpost->getCityByCid($warehouse->city_id);
                    $result['city'] = !empty($city) ? $city->{"name_$locale"} : '';
                }
                if(!empty($city)){
                    $result['region'] = $newpost->getRegionByRid($city->region_id)->{"name_$locale"};
                }
            }else{
                $data = UserData::where('user_id', $this->user_id)->first();
                if(!empty($data)){
                    $address = $data->address();
                    if(!empty($address->npregion))
                        $result['region'] = $newpost->getRegionRef($address->npregion)->{"name_$locale"};
                    if(!empty($address->npcity))
                        $result['city'] = $newpost->getCityByCid($address->npcity)->{"name_$locale"};
                    if(!empty($address->npdepartment))
                        $result['warehouse'] = $newpost->getWarehouseByWid($address->npdepartment)->{"name_$locale"};
                }
            }

            if(!empty($delivery_info['ttn'])){
                $result['ttn'] = $delivery_info['ttn'];
            }

            return $result;
        }elseif($delivery_info['method'] == 'newpost_courier'){
            $delivery_info['info']['method'] = __('«Новая почта» — курьер');
            $delivery_info['info']['key'] = $delivery_info['method'];
            return $delivery_info['info'];
        }elseif($delivery_info['method'] == 'ukrpost'){
            $delivery_info['info']['method'] = __('Укрпочта');
            $delivery_info['info']['key'] = $delivery_info['method'];
            return $delivery_info['info'];
        }elseif($delivery_info['method'] == 'courier'){
            $result = [
                'key'    => $delivery_info['method'],
                'method' => __('Доставка по городу'),
                'delivery_cost' => isset($delivery_info['delivery_cost']) ? $delivery_info['delivery_cost'] : 0
            ];
	        if(is_array($delivery_info['info'])){
                if(!empty($delivery_info['info']['city'])){
                    $result['city'] = $delivery_info['info']['city'];
                }
	        	if(!empty($delivery_info['info']['street'])){
			        $result['street'] = $delivery_info['info']['street'];
		        }
		        if(!empty($delivery_info['info']['house'])){
			        $result['house'] = $delivery_info['info']['house'];
		        }
		        if(!empty($delivery_info['info']['apartment'])){
			        $result['apartment'] = $delivery_info['info']['apartment'];
		        }
		        if(!empty($delivery_info['info']['details'])){
			        $result['details'] = $delivery_info['info']['details'];
		        }

                $newpost = new Newpost();
                if(is_array($delivery_info['info']) && !empty($delivery_info['info']['city_id'])){
                    $city = $newpost->getCityByCid($delivery_info['info']['city_id']);
                    $result['city'] = !empty($city) ? $city->{"name_$locale"} : '';
                }else{
                    $data = UserData::where('user_id', $this->user_id)->first();
                    if(!empty($data)){
                        $address = $data->address();
                        if(!empty($address->npcity))
                            $result['city'] = $newpost->getCityByCid($address->npcity)->{"name_$locale"};
                    }
                }
	        }
            $data = UserData::where('user_id', $this->user_id)->first();
            if(!empty($data)){
                $address = $data->address();
                if(!empty($address)) {
	                foreach ( $address as $key => $val ) {
		                $result[ $key ] = $val;
	                }
                }
            }
            return $result;
        }elseif($delivery_info['method'] == 'justin'){
            $justin = new Justin();
            $result['key'] = $delivery_info['method'];
            $result['method'] = __('Justin');
            $result['delivery_cost'] = isset($delivery_info['delivery_cost']) ? $delivery_info['delivery_cost'] : 0;
            if(is_array($delivery_info['info'])){
                if(!empty($delivery_info['info']['region'])){
                    $region = $justin->getRegionNameByUuid($delivery_info['info']['region']);
                    $result['region'] = !empty($region) ? $region : '';
                }
                if(!empty($delivery_info['info']['city'])){
                    $city = $justin->getCityNameByUuid($delivery_info['info']['city']);
                    $result['city'] = !empty($city) ? $city : '';
                    if(!empty($delivery_info['info']['warehouse'])){
//                    $warehouse = $justin->getWarehouseById($delivery_info['info']['warehouse']);
//                    $result['warehouse'] = !empty($warehouse) ? $warehouse["name_$locale"] : '';
                        $result['warehouse'] = '';
                        $warehouses = $justin->getWarehouses($delivery_info['info']['city']);
                        if(isset($warehouses[$delivery_info['info']['warehouse']]))
                            $result['warehouse'] = $warehouses[$delivery_info['info']['warehouse']]['name'];
                    }
                }
            }
            return $result;
        }elseif($delivery_info['method'] == 'other'){
            $result['key'] = $delivery_info['method'];
            $result['method'] = $delivery_info['info']['name'];
            $result['delivery_cost'] = isset($delivery_info['delivery_cost']) ? $delivery_info['delivery_cost'] : 0;
            if(is_array($delivery_info['info'])){
                if(!empty($delivery_info['info']['region'])){
                    $result['region'] = $delivery_info['info']['region'];
                }
                if(!empty($delivery_info['info']['city'])){
                    $result['city'] = $delivery_info['info']['city'];
                }
                if(!empty($delivery_info['info']['warehouse'])){
                    $result['warehouse'] = $delivery_info['info']['warehouse'];
                }
            }
            return $result;
        }elseif($delivery_info['method'] == 'pickup'){
            $delivery_info['info']['key'] = $delivery_info['method'];
            $delivery_info['info']['method'] = __('Самовывоз');
            $newpost = new Newpost();

            if(is_array($delivery_info['info']) && !empty($delivery_info['info']['city_id'])){
                $city = $newpost->getCityByCid($delivery_info['info']['city_id']);
                $delivery_info['info']['city'] = !empty($city) ? $city->{"name_$locale"} : '';
            }else{
                $data = UserData::where('user_id', $this->user_id)->first();
                if(!empty($data)){
                    $address = $data->address();
                    if(!empty($address->npcity))
                        $delivery_info['info']['city'] = $newpost->getCityByCid($address->npcity)->{"name_$locale"};
                }
            }
            return $delivery_info['info'];
        }elseif(in_array($delivery_info['method'], ['emc', 'dhl', 'fedex', 'newpost_international', 'tnt'])){
            $names = [
                'emc' => 'EMC',
                'dhl' => 'DHL',
                'fedex' => 'FedEx',
                'newpost_international' => __('Новая почта'),
                'tnt' => 'TNT'
            ];
            $delivery_info['info']['method'] = $names[$delivery_info['method']];
            $delivery_info['info']['key'] = $delivery_info['method'];
            return $delivery_info['info'];
        }

        return [
            'error' => 'Невозможно отобразить информацию о доставке!'
        ];
    }

    public function user(){
        return $this->belongsTo('App\Models\User');
    }

    public function photo(){
        $products = $this->getProducts();
        if(!empty($products)){
            foreach($products as $product){
                $image = $product['product']->image;

                if(!empty($image)){
                    return '<img src="'.$image->url().'" alt="'.$product['product']->name.'" class="img-thumbnail">';
                }
            }
        }

        return '';
    }
}
