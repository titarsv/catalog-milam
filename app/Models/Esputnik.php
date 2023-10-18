<?php

namespace App\Models;

class Esputnik
{
    private $api_key = null;
    private $api_user = null;
    private $api_password = null;

    public function __construct()
    {
        $this->api_key = env('ESPUTNIK_KEY', null);
        if(empty($this->api_key)){
            $this->api_user = env('ESPUTNIK_USER', null);
            $this->api_password = env('ESPUTNIK_PASSWORD', null);
        }else{
            $this->api_user = 'triplefork';
            $this->api_password = $this->api_key;
        }
    }

    public function order($user, $order)
    {
        $o = new \stdClass();

        // ОБЯЗАТЕЛЬНЫЕ ПОЛЯ

        $o->status = "INITIALIZED";
        $o->date = date('Y-m-d\TH:i:s');
        $o->externalOrderId = $order->id;
        $o->externalCustomerId = $order->user_id;
        $o->totalCost = $order->total_price;

        // НЕОБЯЗАТЕЛЬНЫЕ ПОЛЯ

        $o->email = $user['email'];
        $o->phone = $user['phone'];
        $o->firstName = $user['first_name'];
        $o->lastName = $user['last_name'];
        $o->storeId = "";
        $o->shipping = 0;  // Стоимость доставки (дополнительная информация, при расчётах не учитывается).

        $delivery = $order->getDeliveryInfo();

        $o->deliveryMethod = isset($delivery['method']) ? $delivery['method'] : '';
        $o->deliveryAddress = "г. Киев, ул. Крещатик, 100500"; // Адрес доставки заказа.
        $o->taxes = 0;

        if ($order->payment == 'cash'){
            $o->paymentMethod = "Оплата наличными при получении";
        }elseif($order->payment == 'prepayment'){
            $o->paymentMethod = "Предоплата";
        }elseif($order->payment == 'privat'){
            $o->paymentMethod = "На расчетный счет Приват Банка";
        }elseif($order->payment == 'nal_delivery'){
            $o->paymentMethod = "Наличными курьеру";
        }elseif($order->payment == 'nal_samovivoz'){
            $o->paymentMethod = "Оплата при самовывозе";
        }elseif($order->payment == 'nalogenniy'){
            $o->paymentMethod = "Оплата наложенным платежом";
        }
        $o->discount = $order->total_sale;

        $o->items = [];

        foreach($order->getProducts() as $item) {
            $name = $item['product']->name;
            if (!empty($item['variations'])){
                $name .= ' (';
                foreach ($item['variations'] as $n => $val) {
                    $name .= $n.': '.$val.';';
                }
                $name .= ')';
            }

            $category = $item['product']->categories()->first();
            if(!empty($category)){
                $category_name = $category->name;
            }else{
                $category_name = 'Каталог';
            }

            $o->items[] = [
                // обязательные поля

                'name' => $name,
                'cost' => $item['price'],
                'category' => $category_name,
                'quantity' => $item['quantity'],
                'externalItemId' => $item['product']->id,

                // необязательные поля

                'url' => $item['product']->link(),
                'imageUrl' => !empty($item['product']->image) ? url($item['product']->image->url([100, 100])) : url('/uploads/no_image.jpg'),
                'description' => $item['product']->description,
            ];
        }

        $orders_list = new \stdClass();
        $orders_list->orders = [$o];

        $this->request('v1/orders', $orders_list);
    }

    private function request($method, $data){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json', 'Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_URL, 'https://esputnik.com/api/'.$method);
        curl_setopt($ch,CURLOPT_USERPWD, $this->api_user.':'.$this->api_password);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
        curl_setopt ($ch, CURLOPT_SSLVERSION, 6);
        $output = curl_exec($ch);
        curl_close($ch);
//        dd($output);
    }
}