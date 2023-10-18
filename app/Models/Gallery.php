<?php

namespace App\Models;

class Gallery extends Entity
{
    protected $table = 'galleries';

    protected $fillable = [
        'field',
        'file_id',
        'parent_type',
        'parent_id',
        'order'
    ];

    public function parent(){
        return $this->morphTo();
    }

    public function image(){
        return $this->hasOne('App\Models\File', 'id', 'file_id');
    }

    public function saveGalleries($request, $parent, $fields = []){
        $galleries = [];
        foreach($request->only($fields) as $key => $values){
            foreach($values as $i => $val){
                $gallery_data = [
                    'field' => $key,
                    'file_id' => $val,
                    'order' => $i
                ];

                $gallery = $parent->galleries()->where($gallery_data)->first();

                if(empty($gallery)){
                    $parent->galleries()->create($gallery_data);
                    $galleries[] = $parent->galleries()->where($gallery_data)->first()->id;
                }else{
                    $galleries[] = $gallery->id;
                }
            }
        }

        $parent->galleries()->whereNotIn('id', $galleries)->delete();
    }

    public function url(){
        return !empty($this->image) ? $this->image->url() : null;
    }

    public function webp($size = 'full', $attributes = [], $lazy = false, $crop = 'cover'){
        return !empty($this->image) ? $this->image->webp($size, $attributes, $lazy, $crop) : null;
    }

    protected function dataMap(){
        return [
            'attributes' => [],
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
