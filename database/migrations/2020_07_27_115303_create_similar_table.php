<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSimilarTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('similar_products', function (Blueprint $table) {
            $table->unsignedInteger('product_id')->nullable();
            $table->unsignedInteger('similar_id')->nullable();

            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('similar_id')->references('id')->on('products');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('similar_products', function (Blueprint $table) {
            $table->dropForeign('similar_products_product_id_foreign');
            $table->dropForeign('similar_products_similar_id_foreign');
        });

        Schema::drop('similar_products');
    }
}
