<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ModulesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $modules = array(
            array('id' => '1','name' => 'Слайдшоу на главной','alias_name' => 'slideshow','status' => '1','settings' => '{"quantity":6}'),
            array('id' => '2','name' => 'Викторина','alias_name' => 'quiz','status' => '1','settings' => '{}'),
            array('id' => '3','name' => 'Новинки товаров','alias_name' => 'latest','status' => '1','settings' => '{"quantity":6}'),
            array('id' => '4','name' => 'Бестселлеры','alias_name' => 'bestsellers','status' => '1','settings' => '{"quantity":8}')
        );

        DB::table('modules')->insert($modules);
    }
}
