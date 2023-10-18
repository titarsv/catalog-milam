<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AttributesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $attributes = array(
            array('id' => '1','external_id' => NULL,'slug' => 'brend','created_at' => '2022-05-16 19:29:16','updated_at' => '2022-05-16 19:29:16','deleted_at' => NULL),
            array('id' => '2','external_id' => NULL,'slug' => 'tipprodukcii','created_at' => '2022-05-16 19:30:15','updated_at' => '2022-05-16 19:30:15','deleted_at' => NULL),
            array('id' => '3','external_id' => NULL,'slug' => 'obemves','created_at' => '2022-05-16 19:31:21','updated_at' => '2022-05-16 19:31:21','deleted_at' => NULL),
            array('id' => '4','external_id' => NULL,'slug' => 'naznachenie','created_at' => '2022-05-16 19:40:04','updated_at' => '2022-05-16 19:40:04','deleted_at' => NULL)
        );

        DB::table('attributes')->insert($attributes);
    }
}
