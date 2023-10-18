<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RelatedProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $related_products = array(
            array('product_id' => '77','related_id' => '62'),
            array('product_id' => '62','related_id' => '77'),
            array('product_id' => '62','related_id' => '78'),
            array('product_id' => '77','related_id' => '78'),
            array('product_id' => '78','related_id' => '62'),
            array('product_id' => '78','related_id' => '77'),
            array('product_id' => '63','related_id' => '79'),
            array('product_id' => '63','related_id' => '80'),
            array('product_id' => '80','related_id' => '79'),
            array('product_id' => '80','related_id' => '63'),
            array('product_id' => '79','related_id' => '63'),
            array('product_id' => '79','related_id' => '80'),
            array('product_id' => '81','related_id' => '64'),
            array('product_id' => '81','related_id' => '82'),
            array('product_id' => '64','related_id' => '81'),
            array('product_id' => '64','related_id' => '82'),
            array('product_id' => '82','related_id' => '64'),
            array('product_id' => '82','related_id' => '81'),
            array('product_id' => '48','related_id' => '83'),
            array('product_id' => '83','related_id' => '48'),
            array('product_id' => '49','related_id' => '84'),
            array('product_id' => '84','related_id' => '49'),
            array('product_id' => '66','related_id' => '86'),
            array('product_id' => '86','related_id' => '66'),
            array('product_id' => '85','related_id' => '65'),
            array('product_id' => '65','related_id' => '85'),
            array('product_id' => '87','related_id' => '67'),
            array('product_id' => '67','related_id' => '87'),
            array('product_id' => '68','related_id' => '88'),
            array('product_id' => '88','related_id' => '68'),
            array('product_id' => '89','related_id' => '24'),
            array('product_id' => '24','related_id' => '89'),
            array('product_id' => '4','related_id' => '90'),
            array('product_id' => '90','related_id' => '4'),
            array('product_id' => '91','related_id' => '5'),
            array('product_id' => '5','related_id' => '91'),
            array('product_id' => '92','related_id' => '6'),
            array('product_id' => '6','related_id' => '92'),
            array('product_id' => '93','related_id' => '8'),
            array('product_id' => '8','related_id' => '93'),
            array('product_id' => '94','related_id' => '11'),
            array('product_id' => '11','related_id' => '94')
        );

	    DB::table('related_products')->insert($related_products);
    }
}
