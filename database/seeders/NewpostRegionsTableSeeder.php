<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class NewpostRegionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $newpost_regions = array(
            array('id' => '1','region_id' => '71508128-9b87-11de-822f-000c2965ae0e','name_ua' => 'АРК','name_ru' => 'АРК','region_center' => 'db5c88b7-391c-11dd-90d9-001a92567626'),
            array('id' => '2','region_id' => '71508129-9b87-11de-822f-000c2965ae0e','name_ua' => 'Вінницька','name_ru' => 'Винницкая','region_center' => 'db5c88de-391c-11dd-90d9-001a92567626'),
            array('id' => '3','region_id' => '7150812a-9b87-11de-822f-000c2965ae0e','name_ua' => 'Волинська','name_ru' => 'Волынская','region_center' => 'db5c893b-391c-11dd-90d9-001a92567626'),
            array('id' => '4','region_id' => '7150812b-9b87-11de-822f-000c2965ae0e','name_ua' => 'Дніпропетровська','name_ru' => 'Днепропетровская','region_center' => 'db5c88f0-391c-11dd-90d9-001a92567626'),
            array('id' => '5','region_id' => '7150812c-9b87-11de-822f-000c2965ae0e','name_ua' => 'Донецька','name_ru' => 'Донецкая','region_center' => 'db5c88bf-391c-11dd-90d9-001a92567626'),
            array('id' => '6','region_id' => '7150812d-9b87-11de-822f-000c2965ae0e','name_ua' => 'Житомирська','name_ru' => 'Житомирская','region_center' => 'db5c88c4-391c-11dd-90d9-001a92567626'),
            array('id' => '7','region_id' => '7150812e-9b87-11de-822f-000c2965ae0e','name_ua' => 'Закарпатська','name_ru' => 'Закарпатская','region_center' => 'e221d627-391c-11dd-90d9-001a92567626'),
            array('id' => '8','region_id' => '7150812f-9b87-11de-822f-000c2965ae0e','name_ua' => 'Запорізька','name_ru' => 'Запорожская','region_center' => 'db5c88c6-391c-11dd-90d9-001a92567626'),
            array('id' => '9','region_id' => '71508130-9b87-11de-822f-000c2965ae0e','name_ua' => 'Івано-Франківська','name_ru' => 'Ивано-Франковская','region_center' => 'db5c8904-391c-11dd-90d9-001a92567626'),
            array('id' => '10','region_id' => '71508131-9b87-11de-822f-000c2965ae0e','name_ua' => 'Київська','name_ru' => 'Киевская','region_center' => '8d5a980d-391c-11dd-90d9-001a92567626'),
            array('id' => '11','region_id' => '71508132-9b87-11de-822f-000c2965ae0e','name_ua' => 'Кіровоградська','name_ru' => 'Кировоградская','region_center' => 'db5c891b-391c-11dd-90d9-001a92567626'),
            array('id' => '12','region_id' => '71508133-9b87-11de-822f-000c2965ae0e','name_ua' => 'Луганська','name_ru' => 'Луганская','region_center' => 'db5c88ba-391c-11dd-90d9-001a92567626'),
            array('id' => '13','region_id' => '71508134-9b87-11de-822f-000c2965ae0e','name_ua' => 'Львівська','name_ru' => 'Львовская','region_center' => 'db5c88f5-391c-11dd-90d9-001a92567626'),
            array('id' => '14','region_id' => '71508135-9b87-11de-822f-000c2965ae0e','name_ua' => 'Миколаївська','name_ru' => 'Николаевская','region_center' => 'db5c888c-391c-11dd-90d9-001a92567626'),
            array('id' => '15','region_id' => '71508136-9b87-11de-822f-000c2965ae0e','name_ua' => 'Одеська','name_ru' => 'Одесская','region_center' => 'db5c88d0-391c-11dd-90d9-001a92567626'),
            array('id' => '16','region_id' => '71508137-9b87-11de-822f-000c2965ae0e','name_ua' => 'Полтавська','name_ru' => 'Полтавская','region_center' => 'db5c8892-391c-11dd-90d9-001a92567626'),
            array('id' => '17','region_id' => '71508138-9b87-11de-822f-000c2965ae0e','name_ua' => 'Рівненська','name_ru' => 'Ровенская','region_center' => 'db5c896a-391c-11dd-90d9-001a92567626'),
            array('id' => '18','region_id' => '71508139-9b87-11de-822f-000c2965ae0e','name_ua' => 'Сумська','name_ru' => 'Сумская','region_center' => 'db5c88e5-391c-11dd-90d9-001a92567626'),
            array('id' => '19','region_id' => '7150813a-9b87-11de-822f-000c2965ae0e','name_ua' => 'Тернопільська','name_ru' => 'Тернопольская','region_center' => 'db5c8900-391c-11dd-90d9-001a92567626'),
            array('id' => '20','region_id' => '7150813b-9b87-11de-822f-000c2965ae0e','name_ua' => 'Харківська','name_ru' => 'Харьковская','region_center' => 'db5c88e0-391c-11dd-90d9-001a92567626'),
            array('id' => '21','region_id' => '7150813c-9b87-11de-822f-000c2965ae0e','name_ua' => 'Херсонська','name_ru' => 'Херсонская','region_center' => 'db5c88cc-391c-11dd-90d9-001a92567626'),
            array('id' => '22','region_id' => '7150813d-9b87-11de-822f-000c2965ae0e','name_ua' => 'Хмельницька','name_ru' => 'Хмельницкая','region_center' => 'db5c88ac-391c-11dd-90d9-001a92567626'),
            array('id' => '23','region_id' => '7150813e-9b87-11de-822f-000c2965ae0e','name_ua' => 'Черкаська','name_ru' => 'Черкасская','region_center' => 'db5c8902-391c-11dd-90d9-001a92567626'),
            array('id' => '24','region_id' => '7150813f-9b87-11de-822f-000c2965ae0e','name_ua' => 'Чернівецька','name_ru' => 'Черновицкая','region_center' => 'e221d642-391c-11dd-90d9-001a92567626'),
            array('id' => '25','region_id' => '71508140-9b87-11de-822f-000c2965ae0e','name_ua' => 'Чернігівська','name_ru' => 'Черниговская','region_center' => 'db5c897c-391c-11dd-90d9-001a92567626')
        );

	    DB::table('newpost_regions')->insert($newpost_regions);
    }
}
