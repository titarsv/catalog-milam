<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_categories', function (Blueprint $table) {
	        $table->engine = 'InnoDB';
	        $table->increments('id');
	        $table->unsignedInteger('product_id');
	        $table->unsignedInteger('category_id');
	        $table->unsignedInteger('order')->default(9999);

	        $table->foreign('product_id')->references('id')->on('products');
	        $table->foreign('category_id')->references('id')->on('categories');
	        $table->index('id');
	        $table->index('product_id');
	        $table->index('category_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
	    Schema::table('product_categories', function (Blueprint $table) {
		    $table->dropForeign('product_categories_product_id_foreign');
		    $table->dropForeign('product_categories_category_id_foreign');
	    });

        Schema::dropIfExists('product_categories');
    }
}
