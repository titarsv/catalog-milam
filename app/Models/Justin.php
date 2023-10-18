<?php

namespace App\Models;

use Illuminate\Support\Facades\Cache;
use App;

//https://justin.ua/api/api_justin_documentation.pdf
class Justin
{
    public function getRegions(){
        $locale = App::getLocale();

        $regions = Cache::remember('justin_regions_'.$locale, 10080, function() use($locale){
            $url = 'https://api.justin.ua/justin_pms/hs/v2/runRequest';

            $data = [
                'keyAccount' => 'TolmachevaAO',
                'sign' => sha1('$DHCvusW:'.date('Y-m-d')),
                'request' => 'getData',
                'type' => 'catalog',
                'name' => 'cat_Region',
                'params' => [
                    'language' => $locale
                ]
            ];

            $response = $this->request($url, 'POST', $data);
            $regions = [];
            foreach($response->data as $region){
                $regions[$region->fields->uuid] = [
                    'name' => $region->fields->descr
                ];
            }

            uasort($regions, array($this, 'sort'));

            return $regions;
        });

        return $regions;
    }

    public function getCities($region_uuid){
        $locale = App::getLocale();

        $cities = Cache::remember('justin_cities_'.$region_uuid.'_'.$locale, 10080, function() use($region_uuid, $locale){
            $url = 'https://api.justin.ua/justin_pms/hs/v2/runRequest';

            $data = [
                'keyAccount' => 'TolmachevaAO',
                'sign' => sha1('$DHCvusW:'.date('Y-m-d')),
                'request' => 'getData',
                'type' => 'catalog',
                'name' => 'cat_Cities',
                'params' => [
                    'language' => $locale
                ],
                'filter' => [[
                    'name' => 'objectOwner',
                    'comparison' => 'equal',
                    'leftValue' => $region_uuid
                ]]
            ];

            $response = $this->request($url, 'POST', $data);
            $cities = [];
            foreach($response->data as $city){
                $cities[$city->fields->uuid] = [
                    'name' => $city->fields->descr
                ];
            }

            uasort($cities, array($this, 'sort'));

            return $cities;
        });

        return $cities;
    }

    public function getRegionNameByUuid($uuid){
        $locale = App::getLocale();

        $region_name = Cache::remember('justin_region_'.$uuid.'_'.$locale, 10080, function() use($uuid, $locale){
            $url = 'https://api.justin.ua/justin_pms/hs/v2/runRequest';

            $data = [
                'keyAccount' => 'TolmachevaAO',
                'sign' => sha1('$DHCvusW:'.date('Y-m-d')),
                'request' => 'getData',
                'type' => 'catalog',
                'name' => 'cat_Region',
                'TOP' => 1,
                'params' => [
                    'language' => $locale
                ],
                'filter' => [[
                    'name' => 'uuid',
                    'comparison' => 'equal',
                    'leftValue' => $uuid
                ]]
            ];

            $response = $this->request($url, 'POST', $data);

            if(!empty($response->data)){
                foreach($response->data as $region){
                    if($region->fields->uuid == $uuid)
                        return $region->fields->descr;
                }
            }

            return null;
        });

        return $region_name;
    }

    public function getCityNameByUuid($uuid){
        $locale = App::getLocale();

        $city_name = Cache::remember('justin_city_'.$uuid.'_'.$locale, 10080, function() use($uuid, $locale){
            $url = 'https://api.justin.ua/justin_pms/hs/v2/runRequest';

            $data = [
                'keyAccount' => 'TolmachevaAO',
                'sign' => sha1('$DHCvusW:'.date('Y-m-d')),
                'request' => 'getData',
                'type' => 'catalog',
                'name' => 'cat_Cities',
                'params' => [
                    'language' => $locale
                ],
                'filter' => [[
                    'name' => 'uuid',
                    'comparison' => 'equal',
                    'leftValue' => $uuid
                ]]
            ];

            $response = $this->request($url, 'POST', $data);
            if(!empty($response->data)){
                foreach($response->data as $city){
                    if($city->fields->uuid == $uuid)
                        return $city->fields->descr;
                }
            }

            return null;
        });

        return $city_name;
    }

    public function getAllWarehouses(){
        $locale = App::getLocale();

        $warehouses = Cache::remember('justin_warehouses_'.$locale, 10080, function() use($locale){
            $url = 'https://api.justin.ua/justin_pms/hs/v2/runRequest';

            $data = [
                'keyAccount' => 'TolmachevaAO',
                'sign' => sha1('$DHCvusW:'.date('Y-m-d')),
                'request' => 'getData',
                'type' => 'request',
                'name' => 'req_DepartmentsLang',
                'language' => $locale,
                'params' => [
                    'language' => $locale
                ],
                'filter' => []
            ];

            $response = $this->request($url, 'POST', $data);

            $warehouses = [];
            foreach($response->data as $warehouse){
                $warehouses[$warehouse->fields->Depart->uuid] = [
                    'name' => $warehouse->fields->descr . ' ('.$warehouse->fields->street->descr.(!empty($warehouse->fields->houseNumber) ? ' '.$warehouse->fields->houseNumber : '').')'
                ];
            }

            uasort($warehouses, array($this, 'sort'));

            return $warehouses;
        });

        return $warehouses;
    }

    public function getWarehouses($city){
        $locale = App::getLocale();

        $warehouses = Cache::remember('justin_warehouses_'.md5($city).'_'.$locale, 10080, function() use($city, $locale){
            $url = 'https://api.justin.ua/justin_pms/hs/v2/runRequest';

            $data = [
                'keyAccount' => 'TolmachevaAO',
                'sign' => sha1('$DHCvusW:'.date('Y-m-d')),
                'request' => 'getData',
                'type' => 'request',
                'name' => 'req_DepartmentsLang',
                'language' => $locale,
                'params' => [
                    'language' => $locale
                ],
                'filter' => [[
                    'name' => 'city',
                    'comparison' => 'equal',
                    'leftValue' => $city
                ]]
            ];

            $response = $this->request($url, 'POST', $data);

            $warehouses = [];
            foreach($response->data as $warehouse){
                $warehouses[$warehouse->fields->Depart->uuid] = [
                    'name' => $warehouse->fields->descr . ' ('.$warehouse->fields->street->descr.(!empty($warehouse->fields->houseNumber) ? ' '.$warehouse->fields->houseNumber : '').')'
                ];
            }

            uasort($warehouses, array($this, 'sort'));

            return $warehouses;
        });

        return $warehouses;
    }

    public function getWarehouseById($id){
        $locale = App::getLocale();

        $warehouse_name = Cache::remember('justin_warehouse_'.$id.'_'.$locale, 10080, function() use($id, $locale){
            $url = 'https://api.justin.ua/justin_pms/hs/v2/runRequest';

            $data = [
                'keyAccount' => 'TolmachevaAO',
                'sign' => sha1('$DHCvusW:'.date('Y-m-d')),
                'request' => 'getData',
                'type' => 'request',
                'name' => 'req_DepartmentsLang',
                'language' => $locale,
                'params' => [
                    'language' => $locale
                ],
                'filter' => [[
                    'name' => 'objectOwner',
                    'comparison' => 'equal',
                    'leftValue' => $id
                ]]
            ];

            $response = $this->request($url, 'POST', $data);
            if(!empty($response->data)){
                foreach($response->data as $warehouse){
                    if($warehouse->fields->Depart->uuid == $id)
                        return $warehouse->fields->descr . ' ('.$warehouse->fields->street->descr.(!empty($warehouse->fields->houseNumber) ? ' '.$warehouse->fields->houseNumber : '').')';
                }
            }

            return null;
        });

        return $warehouse_name;
    }

    private function sort($x, $y){
        if($x['name'] == $y['name'])
            return 0;
        elseif($x['name'] < $y['name'])
            return -1;
        else
            return 1;
    }

    public function request($url, $method = 'GET', $data = []){
        $ch = curl_init();
        switch ($method){
            case "POST":
                curl_setopt($ch, CURLOPT_POST, 1);
                if($data)
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data, JSON_UNESCAPED_UNICODE));
                break;
            case "PUT":
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
                if($data)
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data, JSON_UNESCAPED_UNICODE));
                break;
            default:
                if($data){
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
                }
        }

        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json', 'Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
        curl_setopt ($ch, CURLOPT_SSLVERSION, 6);
        $output = curl_exec($ch);
        curl_close($ch);
        if(substr($output, 0, 1) !== '{' && substr($output, 0, 1) !== '['){
            return $output;
        }
        return json_decode($output);
    }

    public function createTtn($order, $weight, $volume, $description, $date){
//        $url = 'https://api.justin.ua/justin_pms/hs/api/v1/documents/orders';
        $url = 'https://api.sandbox.justin.ua/client_api/hs/api/v1/documents/orders';

        $data = [
            'api_key' => 'f2290c07-c028-11e9-80d2-525400fb7782',
            'data' => [
                'number' => '20190205',
                'date' => '20190205',
                'sender_city_id' => '50a09bef-dc05-11e7-80c6-00155dfbfb00',
                'sender_company' => 'Milam',
                'sender_contact' => 'Представитель',
                'sender_phone' => '+380503386443',
                'sender_pick_up_address' => '01030',
                'Київ, вул. Б.Хмельницького, 44',
                'pick_up_is_required' => true,
                'sender_branch' => '58748012',
                'receiver' => 'Петров Сергей',
                'receiver_contact' => '',
                'receiver_phone' => '+380978728877',
                'count_cargo_places' => 2,
                'branch' => '7100104224',
                'volume' => 0.02,
                'weight' => 2.5,
                'delivery_type' => 0,
                'declared_cost' => 1500,
                'delivery_amount' => 0,
                'redelivery_amount' => 1500,
                'order_amount' => 1500,
                'redelivery_payment_is_required' => true,
                'redelivery_payment_payer' => 1,
                'delivery_payment_is_required' => true,
                'delivery_payment_payer' => 1,
                'order_payment_is_required' => true
            ]
        ];

        $response = $this->request($url, 'POST', $data);
    }
}
