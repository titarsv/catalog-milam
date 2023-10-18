<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Date\DateFormat;

class Price extends Entity
{
    protected $table = 'prices';
    public $timestamps = false;

    protected $fillable = [
        'product_id',
        'type',
        'price',
        'currency',
    ];

    public function product(){
        return $this->belongsTo('App\Models\Price', 'id', 'product_id');
    }
}
