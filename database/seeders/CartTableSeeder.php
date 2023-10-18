<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CartTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Db::table('cart')->insert([
            [
                'user_id'       => 1,
                'session_id'  => md5(rand(0,5000000)),
                'products'      => json_encode([]),
                'total_quantity' => 0,
                'total_price'   => 0,
                'total_sale'   => 0,
                'user_data'   => '',
                'cart_data'   => '',
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now()
            ]
        ]);

    }
}
