<?php

namespace App\Models;

use Cartalyst\Sentinel\Native\Facades\Sentinel;

class User extends \Cartalyst\Sentinel\Users\EloquentUser
{
    public $entity_type = 'user';

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $fillable = [
        'email',
        'password',
        'last_name',
        'first_name',
        'patronymic',
        'permissions',
        'currency',
    ];

    public function blog()
    {
        return $this->hasOne('App\Models\Blog', 'user_id', 'id');
    }
    public function user_data()
    {
        return $this->hasOne('App\Models\UserData', 'user_id', 'id');
    }
    public function orders()
    {
        return $this->hasMany('App\Models\Order', 'user_id', 'id');
    }
    public function wishlist()
    {
        return $this->hasMany('App\Models\Wishlist', 'user_id', 'id');
    }
    public function reviews()
    {
        return $this->hasMany('App\Models\Review', 'user_id', 'id');
    }
    public function shopreviews()
    {
        return $this->hasMany('App\Models\ShopReview', 'user_id', 'id');
    }
    public function role()
    {
        return Sentinel::findById($this->id)->roles()->pluck('slug')->toArray();
    }

    public function checkIfUnregistered($phone, $email){
//        return $this->where('email', $email)->orWhere('phone', $phone)->first();
        return $this->where('email', $email)->first();
    }

    /**
     * Сумма покупок
     *
     * @return int
     */
    public function ordersTotal(){
        $total = 0;
        foreach ($this->orders()->where('status_id', 6)->get() as $order){
            $total += $order->total_price;
        }
        return $total;
    }

    /**
     * Размер скидки
     *
     * @return int
     */
    public function sale(){
        $sale = 0;

        return $sale;
    }

    public function fullData($data = null){
        if(empty($data)){
            $data = $this->dataMap();
        }

        if(isset($data['attributes'])){
            if(empty($data['attributes'])){
                foreach(array_merge(array('id'), $this->fillable) as $key){
                    $data['attributes'][$key] = $this->{$key};
                }
            }else{
                foreach($data['attributes'] as $key => $val){
                    $data['attributes'][$key] = $this->{$key};
                }
            }
        }

        if(!empty($data['relations'])){
            $data['relations'] = $this->getRelationsData($data['relations']);
        }

        return $data;
    }

    protected function getRelationsData($data){
        foreach($data as $relation => $d){
            $relations = $this->{$relation}()->get();
            if(!empty($relations)){
                if(get_class($relations) !== 'Illuminate\Database\Eloquent\Collection'){
                    if(method_exists($relations, 'fullData'))
                        $rel_data = $relations->fullData(!empty($d) ? $d : null);
                }else{
                    $rel_data = [];
                    foreach($relations as $rel){
                        if(method_exists($rel, 'fullData')){
                            $item_data = $rel->fullData(!empty($d) ? $d : null);
                            if(is_array($item_data) && isset($item_data['attributes']['id'])){
                                $rel_data[$item_data['attributes']['id']] = $item_data;
                            }else{
                                $rel_data[] = $item_data;
                            }
                        }
                    }
                }

                $data[$relation] = $rel_data;
            }else{
                $data[$relation] = [];
            }
        }

        return $data;
    }

    protected function dataMap(){
        return [
            'attributes' => [
                'id' => '',
                'permissions' => '',
                'first_name' => '',
                'last_name' => '',
                'email' => ''
            ],
            'relations' => [
                'user_data' => [
                    'attributes' => [
                        'id' => '',
                        'user_id' => '',
                        'file_id' => '',
                        'phone' => '',
                        'address' => '',
                        'user_birth' => '',
                        'other_data' => '',
                        'subscribe' => ''
                    ],
                    'relations' => [
                        'image' => [
                            'attributes' => [
                                'id' => '',
                                'path' => ''
                            ]
                        ]
                    ]
                ]
            ]
        ];
    }

    public function fieldsNames(){
        return [
            'attributes.first_name' => [
                'name' => 'Имя'
            ],
            'attributes.last_name' => [
                'name' => 'Фамилия'
            ],
            'attributes.email' => [
                'name' => 'Почта'
            ],
            'relations.user_data' => [
                'name' => '',
                'multiple' => true,
                'fields' => [
                    'attributes.phone' => [
                        'name' => 'Телефон'
                    ],
                    'attributes.address' => [
                        'name' => 'Адрес'
                    ]
                ]
            ],
            'attributes.permissions' => [
                'name' => 'Группа',
                'type' => 'permissions'
            ]
        ];
    }
}
