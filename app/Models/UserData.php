<?php

namespace App\Models;

class UserData extends Entity
{
    protected $table = 'users_data';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'file_id',
        'phone',
        'address',
        'company',
        'other_data',
        'subscribe',
        'city',
        'gender'
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
    public function image()
    {
        return $this->belongsTo('App\Models\Image')->withDefault(['id' => 1, 'href' => 'no_image.jpg', 'title' => 'Изображение не выбрано', 'type' => 'default', 'sizes' => '{"100_100":{"href":"no_image_100x100.jpg","w":100,"h":100}}']);
    }

    public function address()
    {
        return json_decode($this->address);
    }

    protected function dataMap(){
        return [
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
        ];
    }
}
