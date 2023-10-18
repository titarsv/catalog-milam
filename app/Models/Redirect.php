<?php

namespace App\Models;

class Redirect extends Entity
{
    protected $fillable = [
        'old_url',
        'new_url',
    ];

    public $entity_type = 'redirect';
    protected $table = 'redirects';
	public $timestamps = false;

    protected function dataMap(){
        return [
            'attributes' => [
                'id' => '',
                'old_url' => '',
                'new_url' => ''
            ]
        ];
    }

    public function fieldsNames(){
        return [
            'attributes.old_url' => [
                'name' => 'Старый Url'
            ],
            'attributes.new_url' => [
                'name' => 'Новый Url'
            ]
        ];
    }
}
