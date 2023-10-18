<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BlogTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $blog = array(
            array('id' => '1','user_id' => '1','image_id' => '23','status' => '1','created_at' => '2022-05-18 14:18:52','updated_at' => '2022-07-25 15:39:46','deleted_at' => '2022-07-25 15:39:46'),
            array('id' => '2','user_id' => '1','image_id' => '23','status' => '0','created_at' => '2022-05-18 14:20:14','updated_at' => '2022-08-03 11:24:13','deleted_at' => NULL),
            array('id' => '3','user_id' => '1','image_id' => '23','status' => '1','created_at' => '2022-05-18 14:20:46','updated_at' => '2022-07-25 15:39:50','deleted_at' => '2022-07-25 15:39:50'),
            array('id' => '4','user_id' => '1','image_id' => '219','status' => '1','created_at' => '2022-08-02 10:00:50','updated_at' => '2022-09-27 13:03:46','deleted_at' => NULL),
            array('id' => '5','user_id' => '1','image_id' => '220','status' => '1','created_at' => '2022-08-09 11:20:22','updated_at' => '2022-09-27 13:03:56','deleted_at' => NULL)
        );

	    DB::table('blog')->insert($blog);
    }
}
