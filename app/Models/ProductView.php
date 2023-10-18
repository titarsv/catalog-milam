<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductView extends Model
{
    protected $table = 'products_views';
    public $timestamps = false;

    public $fillable = [
        'product_id',
        'session',
        'time'
    ];

	public function product(){
        return $this->belongsTo('App\Models\Product', 'product_id');
	}
}