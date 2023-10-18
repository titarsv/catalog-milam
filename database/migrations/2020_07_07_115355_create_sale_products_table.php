<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaleProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_products', function (Blueprint $table) {
	        $table->engine = 'InnoDB';
	        $table->increments('id');
            $table->unsignedInteger('sale_id');
	        $table->unsignedInteger('product_id');
            $table->float('sale_price')->nullable();

            $table->foreign('sale_id')->references('id')->on('sales');
	        $table->foreign('product_id')->references('id')->on('products');
	        $table->index('id');
            $table->index('sale_id');
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
	    Schema::table('sale_products', function (Blueprint $table) {
		    $table->dropForeign('sale_products_product_id_foreign');
		    $table->dropForeign('sale_products_sale_id_foreign');
	    });

        Schema::dropIfExists('sale_products');
    }
}
