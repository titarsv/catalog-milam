<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductAttributesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_attributes', function (Blueprint $table) {
	        $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('product_id');
            $table->unsignedInteger('attribute_id');
            $table->unsignedInteger('attribute_value_id');

	        $table->foreign('product_id')->references('id')->on('products');
	        $table->foreign('attribute_id')->references('id')->on('attributes');
	        $table->foreign('attribute_value_id')->references('id')->on('attribute_values');
	        $table->index('id');
	        $table->index('product_id');
	        $table->index('attribute_id');
	        $table->index('attribute_value_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
	    Schema::table('product_attributes', function (Blueprint $table) {
		    $table->dropForeign('product_attributes_product_id_foreign');
		    $table->dropForeign('product_attributes_attribute_id_foreign');
		    $table->dropForeign('product_attributes_attribute_value_id_foreign');
	    });

        Schema::drop('product_attributes');
    }
}
