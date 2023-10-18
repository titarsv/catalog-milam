<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pages = array(
            array('id' => '1','template' => 'public.layouts.pages.home','parent_id' => NULL,'status' => '1','sort_order' => '0','created_at' => '2022-05-16 15:12:05','updated_at' => '2022-05-16 12:22:40','deleted_at' => NULL),
            array('id' => '2','template' => 'public.layouts.pages.about','parent_id' => NULL,'status' => '1','sort_order' => '1','created_at' => '2022-05-16 15:12:20','updated_at' => '2022-05-16 12:25:04','deleted_at' => NULL),
            array('id' => '3','template' => 'public.layouts.pages.partners','parent_id' => NULL,'status' => '1','sort_order' => '2','created_at' => '2022-05-16 15:12:30','updated_at' => '2022-05-16 12:24:58','deleted_at' => NULL),
            array('id' => '4','template' => 'public.layouts.pages.contacts','parent_id' => NULL,'status' => '1','sort_order' => '3','created_at' => '2022-05-16 15:12:39','updated_at' => '2022-05-16 12:28:04','deleted_at' => NULL)
        );

        DB::table('pages')->insert($pages);
    }
}
