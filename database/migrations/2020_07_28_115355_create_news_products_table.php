<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewsProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('news_products', function (Blueprint $table) {
	        $table->engine = 'InnoDB';
	        $table->increments('id');
            $table->unsignedInteger('news_id');
	        $table->unsignedInteger('product_id');

            $table->foreign('news_id')->references('id')->on('news');
	        $table->foreign('product_id')->references('id')->on('products');
	        $table->index('id');
            $table->index('news_id');
	        $table->index('product_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
	    Schema::table('news_products', function (Blueprint $table) {
		    $table->dropForeign('news_products_product_id_foreign');
		    $table->dropForeign('news_products_news_id_foreign');
	    });

        Schema::dropIfExists('news_products');
    }
}
