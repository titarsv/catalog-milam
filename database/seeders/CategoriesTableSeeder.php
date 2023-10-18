<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = array(
            array('id' => '1','external_id' => NULL,'slug' => 'products','file_id' => NULL,'parent_id' => NULL,'sort_order' => '1','status' => '1','created_at' => '2022-05-16 14:35:24','updated_at' => '2022-05-16 14:56:37','deleted_at' => NULL),
            array('id' => '2','external_id' => NULL,'slug' => 'sredstvodlyastirki','file_id' => '1','parent_id' => '1','sort_order' => '1','status' => '1','created_at' => '2022-05-16 14:45:47','updated_at' => '2022-05-16 16:48:57','deleted_at' => NULL),
            array('id' => '3','external_id' => NULL,'slug' => 'sredstvopouhoduzavannoykomnatoy','file_id' => '2','parent_id' => '1','sort_order' => '2','status' => '1','created_at' => '2022-05-16 14:46:53','updated_at' => '2022-05-16 16:50:34','deleted_at' => NULL),
            array('id' => '4','external_id' => NULL,'slug' => 'sredstvodlyakuhni','file_id' => '3','parent_id' => '1','sort_order' => '3','status' => '1','created_at' => '2022-05-16 14:47:41','updated_at' => '2022-05-16 16:52:06','deleted_at' => NULL),
            array('id' => '5','external_id' => NULL,'slug' => 'suhiechistyaschiesredstva','file_id' => '4','parent_id' => '1','sort_order' => '4','status' => '1','created_at' => '2022-05-16 14:48:24','updated_at' => '2022-05-16 17:01:52','deleted_at' => NULL),
            array('id' => '6','external_id' => NULL,'slug' => 'sredstvodlyadoma','file_id' => '5','parent_id' => '1','sort_order' => '5','status' => '1','created_at' => '2022-05-16 14:49:10','updated_at' => '2022-07-11 11:19:29','deleted_at' => NULL),
            array('id' => '7','external_id' => NULL,'slug' => 'zhidkoemylodlyaruk','file_id' => '6','parent_id' => '1','sort_order' => '6','status' => '1','created_at' => '2022-05-16 14:49:56','updated_at' => '2022-05-16 16:56:47','deleted_at' => NULL),
            array('id' => '8','external_id' => NULL,'slug' => 'sredstvodlyamytyaposudy','file_id' => '7','parent_id' => '1','sort_order' => '7','status' => '1','created_at' => '2022-05-16 14:50:53','updated_at' => '2022-05-16 16:58:07','deleted_at' => NULL),
            array('id' => '9','external_id' => NULL,'slug' => 'dlyaotbelivaniyaidezinfekcii','file_id' => '8','parent_id' => '1','sort_order' => '8','status' => '1','created_at' => '2022-05-16 14:51:39','updated_at' => '2022-05-16 17:00:58','deleted_at' => NULL),
            array('id' => '10','external_id' => NULL,'slug' => 'pyatnovyvoditeliiotbelivateli','file_id' => NULL,'parent_id' => '2','sort_order' => '1','status' => '1','created_at' => '2022-05-16 16:00:07','updated_at' => '2022-05-16 16:49:26','deleted_at' => NULL),
            array('id' => '11','external_id' => NULL,'slug' => 'gelidlyastirki','file_id' => NULL,'parent_id' => '2','sort_order' => '2','status' => '1','created_at' => '2022-05-16 16:00:48','updated_at' => '2022-05-16 16:49:53','deleted_at' => NULL),
            array('id' => '12','external_id' => NULL,'slug' => 'stiralnyeporoshki','file_id' => NULL,'parent_id' => '2','sort_order' => '3','status' => '1','created_at' => '2022-05-16 16:01:35','updated_at' => '2022-05-16 16:50:16','deleted_at' => NULL),
            array('id' => '13','external_id' => NULL,'slug' => 'dlyachistotyidezinfekcii','file_id' => NULL,'parent_id' => '3','sort_order' => '1','status' => '1','created_at' => '2022-05-16 16:02:39','updated_at' => '2022-05-16 16:50:57','deleted_at' => NULL),
            array('id' => '14','external_id' => NULL,'slug' => 'sanitarnogigienicheskiesredstva','file_id' => NULL,'parent_id' => '3','sort_order' => '2','status' => '1','created_at' => '2022-05-16 16:03:40','updated_at' => '2022-05-16 16:51:06','deleted_at' => NULL),
            array('id' => '15','external_id' => NULL,'slug' => 'dlyaprochistkitrub','file_id' => NULL,'parent_id' => '3','sort_order' => '3','status' => '1','created_at' => '2022-05-16 16:04:26','updated_at' => '2022-05-16 16:51:13','deleted_at' => NULL),
            array('id' => '16','external_id' => NULL,'slug' => 'universalnyesredstva','file_id' => NULL,'parent_id' => '3','sort_order' => '4','status' => '1','created_at' => '2022-05-16 16:05:43','updated_at' => '2022-05-16 16:51:21','deleted_at' => NULL),
            array('id' => '17','external_id' => NULL,'slug' => 'sredstvaotpleseni','file_id' => NULL,'parent_id' => '6','sort_order' => '1','status' => '1','created_at' => '2022-05-16 16:10:43','updated_at' => '2022-05-16 16:56:10','deleted_at' => NULL),
            array('id' => '18','external_id' => NULL,'slug' => 'poliroli','file_id' => NULL,'parent_id' => '6','sort_order' => '2','status' => '1','created_at' => '2022-05-16 16:11:30','updated_at' => '2022-05-16 16:11:30','deleted_at' => NULL),
            array('id' => '19','external_id' => NULL,'slug' => 'dlyakovrov','file_id' => NULL,'parent_id' => '6','sort_order' => '3','status' => '1','created_at' => '2022-05-16 16:12:37','updated_at' => '2022-05-16 16:55:52','deleted_at' => NULL),
            array('id' => '20','external_id' => NULL,'slug' => 'universalnyesredstva2','file_id' => NULL,'parent_id' => '6','sort_order' => '4','status' => '1','created_at' => '2022-05-16 16:13:35','updated_at' => '2022-05-16 16:56:03','deleted_at' => NULL),
            array('id' => '21','external_id' => NULL,'slug' => 'dlyamytyapola','file_id' => NULL,'parent_id' => '6','sort_order' => '5','status' => '1','created_at' => '2022-05-16 16:14:30','updated_at' => '2022-05-16 16:55:33','deleted_at' => NULL),
            array('id' => '22','external_id' => NULL,'slug' => 'dlyamytyastekla','file_id' => NULL,'parent_id' => '6','sort_order' => '6','status' => '1','created_at' => '2022-05-16 16:15:19','updated_at' => '2022-05-16 16:55:40','deleted_at' => NULL),
            array('id' => '23','external_id' => NULL,'slug' => 'antibakterialnyykompleks','file_id' => NULL,'parent_id' => '7','sort_order' => '1','status' => '1','created_at' => '2022-05-16 16:17:45','updated_at' => '2022-05-16 16:57:40','deleted_at' => NULL),
            array('id' => '24','external_id' => NULL,'slug' => 'aromatizirovannoe','file_id' => NULL,'parent_id' => '7','sort_order' => '2','status' => '1','created_at' => '2022-05-16 16:18:49','updated_at' => '2022-05-16 16:18:49','deleted_at' => NULL),
            array('id' => '25','external_id' => NULL,'slug' => 'glicerinovoe','file_id' => NULL,'parent_id' => '7','sort_order' => '3','status' => '1','created_at' => '2022-05-16 16:19:47','updated_at' => '2022-05-16 16:19:47','deleted_at' => NULL),
            array('id' => '26','external_id' => NULL,'slug' => 'kremmylo','file_id' => NULL,'parent_id' => '7','sort_order' => '4','status' => '1','created_at' => '2022-05-16 16:20:38','updated_at' => '2022-05-16 16:57:32','deleted_at' => NULL),
            array('id' => '27','external_id' => NULL,'slug' => 'balzam','file_id' => NULL,'parent_id' => '8','sort_order' => '1','status' => '1','created_at' => '2022-05-16 16:21:19','updated_at' => '2022-05-16 16:21:19','deleted_at' => NULL),
            array('id' => '28','external_id' => NULL,'slug' => 'limon','file_id' => NULL,'parent_id' => '8','sort_order' => '2','status' => '1','created_at' => '2022-05-16 16:22:19','updated_at' => '2022-05-16 16:22:19','deleted_at' => NULL),
            array('id' => '29','external_id' => NULL,'slug' => 'yabloko','file_id' => NULL,'parent_id' => '8','sort_order' => '3','status' => '1','created_at' => '2022-05-16 16:23:25','updated_at' => '2022-05-16 16:23:25','deleted_at' => NULL),
            array('id' => '30','external_id' => NULL,'slug' => 'dlyachistkiplit','file_id' => NULL,'parent_id' => '4','sort_order' => '1','status' => '1','created_at' => '2022-05-16 16:24:23','updated_at' => '2022-05-16 17:00:18','deleted_at' => NULL),
            array('id' => '31','external_id' => NULL,'slug' => 'sredstvaotnakipi','file_id' => NULL,'parent_id' => '4','sort_order' => '2','status' => '1','created_at' => '2022-05-16 16:25:00','updated_at' => '2022-05-16 17:00:26','deleted_at' => NULL),
            array('id' => '32','external_id' => NULL,'slug' => 'universalnyesredstva3','file_id' => NULL,'parent_id' => '4','sort_order' => '3','status' => '1','created_at' => '2022-05-16 16:26:48','updated_at' => '2022-05-16 17:00:33','deleted_at' => NULL),
            array('id' => '33','external_id' => NULL,'slug' => 'belizna','file_id' => NULL,'parent_id' => '9','sort_order' => '1','status' => '1','created_at' => '2022-05-16 16:27:32','updated_at' => '2022-05-16 16:27:32','deleted_at' => NULL),
            array('id' => '34','external_id' => NULL,'slug' => 'sredstvadlyadezinfekcii','file_id' => NULL,'parent_id' => '9','sort_order' => '2','status' => '1','created_at' => '2022-05-16 16:28:55','updated_at' => '2022-05-16 17:01:26','deleted_at' => NULL)
        );

        DB::table('categories')->insert($categories);
    }
}