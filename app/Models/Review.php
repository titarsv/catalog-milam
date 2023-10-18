<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class Review extends Entity
{
    use SoftDeletes;

    protected $table = 'product_reviews';
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'user_id',
        'parent_review_id',
        'product_id',
        'grade',
        'review',
        'answer',
        'author',
        'email',
        'phone',
        'new',
        'published',
        'confirmed_purchase',
        'notification'
    ];

    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }

    public function product()
    {
        return $this->hasOne('App\Models\Product', 'id', 'product_id');
    }

    public function galleries(){
        return $this->morphMany('App\Models\Gallery', 'parent');
    }

    public function gallery(){
        return $this->morphMany('App\Models\Gallery', 'parent')->where('field', 'gallery')->orderBy('order');
    }

    public function saveGalleries($files){
        $request = new Request();
        $request->merge(['gallery' => $files]);
        $gallery = new Gallery();
        $gallery->saveGalleries($request, $this, ['gallery']);
    }
}
