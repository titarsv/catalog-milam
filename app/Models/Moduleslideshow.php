<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App;

class Moduleslideshow extends Model
{
    protected $table = 'module_slideshow';
    protected $fillable = [
        'file_id',
        'file_xs_id',
        'sort_order',
        'link',
        'enable_link',
        'slide_data',
        'status'
    ];

    public function image(){
        return $this->hasOne('App\Models\File', 'id', 'file_id');
    }

    public function image_xs(){
        return $this->hasOne('App\Models\File', 'id', 'file_xs_id');
    }

    public function data(){
        return json_decode($this->slide_data);
    }
}
