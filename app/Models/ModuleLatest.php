<?php

namespace App\Models;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

class ModuleLatest extends Model
{
    protected $table = 'module_latest';
    protected $fillable = ['product_id'];

    public function product(){
        return $this->hasOne('App\Models\Products', 'id', 'product_id');
    }

    public function productsList($current_page = 1){
        $limit = 20;
        $ids = $this->all()->pluck('product_id')->toArray();
        $products = new LengthAwarePaginator(
            Product::select(['products.id', 'products.sku', 'products.stock', 'products.original_price', 'products.file_id', 'localization.value as name'])
                ->whereIn('products.id', $ids)
                ->leftJoin('localization', function($leftJoin) {
                    $leftJoin->on('products.id', '=', 'localization.localizable_id')
                        ->where('localization.localizable_type', '=', 'Products')
                        ->where('localization.language', '=', config('locale'))
                        ->where('field', 'name');
                })
                ->with(['attributes.info', 'attributes.value'])
                ->limit($limit)
                ->offset($limit * ($current_page - 1))
                ->get(),
            $this->count(),
            $limit,
            $current_page,
            [
                'path' => url('/admin/modules/settings/latest')
            ]
        );

        return $products;
    }
}
