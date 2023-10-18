<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AttributeValuesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $attribute_values = array(
            array('id' => '1','external_id' => NULL,'attribute_id' => '1','value' => 'tmmilam','file_id' => NULL),
            array('id' => '2','external_id' => NULL,'attribute_id' => '1','value' => 'tmmilamchemical','file_id' => NULL),
            array('id' => '3','external_id' => NULL,'attribute_id' => '2','value' => 'sprei','file_id' => NULL),
            array('id' => '4','external_id' => NULL,'attribute_id' => '2','value' => 'geli','file_id' => NULL),
            array('id' => '5','external_id' => NULL,'attribute_id' => '2','value' => 'suhie','file_id' => NULL),
            array('id' => '6','external_id' => NULL,'attribute_id' => '2','value' => 'zhidkie','file_id' => NULL),
            array('id' => '7','external_id' => NULL,'attribute_id' => '3','value' => '500ml','file_id' => NULL),
            array('id' => '8','external_id' => NULL,'attribute_id' => '3','value' => '520ml','file_id' => NULL),
            array('id' => '9','external_id' => NULL,'attribute_id' => '3','value' => '750ml','file_id' => NULL),
            array('id' => '10','external_id' => NULL,'attribute_id' => '3','value' => '950ml','file_id' => NULL),
            array('id' => '11','external_id' => NULL,'attribute_id' => '3','value' => '1l','file_id' => NULL),
            array('id' => '12','external_id' => NULL,'attribute_id' => '3','value' => '2l','file_id' => NULL),
            array('id' => '13','external_id' => NULL,'attribute_id' => '3','value' => '4l','file_id' => NULL),
            array('id' => '14','external_id' => NULL,'attribute_id' => '3','value' => '5l','file_id' => NULL),
            array('id' => '15','external_id' => NULL,'attribute_id' => '3','value' => '80g','file_id' => NULL),
            array('id' => '16','external_id' => NULL,'attribute_id' => '3','value' => '100g','file_id' => NULL),
            array('id' => '17','external_id' => NULL,'attribute_id' => '3','value' => '200g','file_id' => NULL),
            array('id' => '18','external_id' => NULL,'attribute_id' => '3','value' => '450g','file_id' => NULL),
            array('id' => '19','external_id' => NULL,'attribute_id' => '3','value' => '500g','file_id' => NULL),
            array('id' => '20','external_id' => NULL,'attribute_id' => '3','value' => '2kg','file_id' => NULL),
            array('id' => '21','external_id' => NULL,'attribute_id' => '4','value' => 'universalnoe','file_id' => NULL),
            array('id' => '22','external_id' => NULL,'attribute_id' => '4','value' => 'dlyacvetnogobelya','file_id' => NULL),
            array('id' => '23','external_id' => NULL,'attribute_id' => '4','value' => 'dlyabelogobelya','file_id' => NULL),
            array('id' => '24','external_id' => NULL,'attribute_id' => '4','value' => 'dlyadetskogobelya','file_id' => NULL),
            array('id' => '25','external_id' => NULL,'attribute_id' => '4','value' => 'dlyachernogobelya','file_id' => NULL)
        );

        DB::table('attribute_values')->insert( $attribute_values);
    }
}