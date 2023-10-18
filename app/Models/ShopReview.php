<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class ShopReview extends Entity
{
    use SoftDeletes;

    protected $table = 'shop_reviews';
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'user_id',
        'parent_review_id',
        'grade',
        'review',
        'answer',
        'author',
        'new',
        'published',
        'created_at'
    ];

    public function date(){
    	return date('d/m/Y', strtotime($this->attributes['created_at']));
    }

    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }
}
