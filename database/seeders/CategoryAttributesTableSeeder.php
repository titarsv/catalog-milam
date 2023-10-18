<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoryAttributesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $category_attributes = array(
            array('id' => '1','category_id' => '1','attribute_id' => '1'),
            array('id' => '2','category_id' => '1','attribute_id' => '2'),
            array('id' => '3','category_id' => '1','attribute_id' => '3'),
            array('id' => '4','category_id' => '1','attribute_id' => '4'),
            array('id' => '5','category_id' => '2','attribute_id' => '1'),
            array('id' => '6','category_id' => '2','attribute_id' => '2'),
            array('id' => '7','category_id' => '2','attribute_id' => '3'),
            array('id' => '8','category_id' => '2','attribute_id' => '4'),
            array('id' => '9','category_id' => '10','attribute_id' => '1'),
            array('id' => '10','category_id' => '10','attribute_id' => '2'),
            array('id' => '11','category_id' => '10','attribute_id' => '3'),
            array('id' => '12','category_id' => '10','attribute_id' => '4'),
            array('id' => '13','category_id' => '11','attribute_id' => '1'),
            array('id' => '14','category_id' => '11','attribute_id' => '2'),
            array('id' => '15','category_id' => '11','attribute_id' => '3'),
            array('id' => '16','category_id' => '11','attribute_id' => '4'),
            array('id' => '17','category_id' => '12','attribute_id' => '1'),
            array('id' => '18','category_id' => '12','attribute_id' => '2'),
            array('id' => '19','category_id' => '12','attribute_id' => '3'),
            array('id' => '20','category_id' => '12','attribute_id' => '4'),
            array('id' => '21','category_id' => '3','attribute_id' => '1'),
            array('id' => '22','category_id' => '3','attribute_id' => '2'),
            array('id' => '23','category_id' => '3','attribute_id' => '3'),
            array('id' => '24','category_id' => '13','attribute_id' => '1'),
            array('id' => '25','category_id' => '13','attribute_id' => '2'),
            array('id' => '26','category_id' => '13','attribute_id' => '3'),
            array('id' => '27','category_id' => '14','attribute_id' => '1'),
            array('id' => '28','category_id' => '14','attribute_id' => '2'),
            array('id' => '29','category_id' => '14','attribute_id' => '3'),
            array('id' => '30','category_id' => '15','attribute_id' => '1'),
            array('id' => '31','category_id' => '15','attribute_id' => '2'),
            array('id' => '32','category_id' => '15','attribute_id' => '3'),
            array('id' => '33','category_id' => '16','attribute_id' => '1'),
            array('id' => '34','category_id' => '16','attribute_id' => '2'),
            array('id' => '35','category_id' => '16','attribute_id' => '3'),
            array('id' => '36','category_id' => '4','attribute_id' => '1'),
            array('id' => '37','category_id' => '4','attribute_id' => '2'),
            array('id' => '38','category_id' => '4','attribute_id' => '3'),
            array('id' => '39','category_id' => '21','attribute_id' => '1'),
            array('id' => '40','category_id' => '21','attribute_id' => '2'),
            array('id' => '41','category_id' => '21','attribute_id' => '3'),
            array('id' => '42','category_id' => '22','attribute_id' => '1'),
            array('id' => '43','category_id' => '22','attribute_id' => '2'),
            array('id' => '44','category_id' => '22','attribute_id' => '3'),
            array('id' => '45','category_id' => '18','attribute_id' => '1'),
            array('id' => '46','category_id' => '18','attribute_id' => '2'),
            array('id' => '47','category_id' => '18','attribute_id' => '3'),
            array('id' => '48','category_id' => '19','attribute_id' => '1'),
            array('id' => '49','category_id' => '19','attribute_id' => '2'),
            array('id' => '50','category_id' => '19','attribute_id' => '3'),
            array('id' => '51','category_id' => '20','attribute_id' => '1'),
            array('id' => '52','category_id' => '20','attribute_id' => '2'),
            array('id' => '53','category_id' => '20','attribute_id' => '3'),
            array('id' => '54','category_id' => '17','attribute_id' => '1'),
            array('id' => '55','category_id' => '17','attribute_id' => '2'),
            array('id' => '56','category_id' => '17','attribute_id' => '3'),
            array('id' => '57','category_id' => '7','attribute_id' => '3'),
            array('id' => '58','category_id' => '24','attribute_id' => '3'),
            array('id' => '59','category_id' => '25','attribute_id' => '3'),
            array('id' => '60','category_id' => '26','attribute_id' => '3'),
            array('id' => '61','category_id' => '23','attribute_id' => '3'),
            array('id' => '62','category_id' => '8','attribute_id' => '3'),
            array('id' => '63','category_id' => '27','attribute_id' => '3'),
            array('id' => '64','category_id' => '28','attribute_id' => '3'),
            array('id' => '65','category_id' => '29','attribute_id' => '3'),
            array('id' => '66','category_id' => '30','attribute_id' => '1'),
            array('id' => '67','category_id' => '30','attribute_id' => '2'),
            array('id' => '68','category_id' => '30','attribute_id' => '3'),
            array('id' => '69','category_id' => '31','attribute_id' => '1'),
            array('id' => '70','category_id' => '31','attribute_id' => '2'),
            array('id' => '71','category_id' => '31','attribute_id' => '3'),
            array('id' => '72','category_id' => '32','attribute_id' => '1'),
            array('id' => '73','category_id' => '32','attribute_id' => '2'),
            array('id' => '74','category_id' => '32','attribute_id' => '3'),
            array('id' => '75','category_id' => '9','attribute_id' => '1'),
            array('id' => '76','category_id' => '9','attribute_id' => '2'),
            array('id' => '77','category_id' => '9','attribute_id' => '3'),
            array('id' => '78','category_id' => '33','attribute_id' => '1'),
            array('id' => '79','category_id' => '33','attribute_id' => '2'),
            array('id' => '80','category_id' => '33','attribute_id' => '3'),
            array('id' => '81','category_id' => '34','attribute_id' => '1'),
            array('id' => '82','category_id' => '34','attribute_id' => '2'),
            array('id' => '83','category_id' => '34','attribute_id' => '3'),
            array('id' => '84','category_id' => '5','attribute_id' => '1'),
            array('id' => '85','category_id' => '5','attribute_id' => '3')
        );

        DB::table('category_attributes')->insert($category_attributes);
    }
}
