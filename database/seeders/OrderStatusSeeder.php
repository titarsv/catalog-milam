<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Db::table('order_status')->insert([
            [
                'status' => 'Новый'
            ],
            [
                'status' => 'Оплачен'
            ],
            [
                'status' => 'Принят'
            ],
            [
                'status' => 'Доставлен'
            ],
            [
                'status' => 'Отменен'
            ],
        ]);
    }
}
