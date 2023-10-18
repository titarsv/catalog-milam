<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRelatedProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('related_products', function (Blueprint $table) {
            $table->unsignedInteger('product_id')->nullable();
            $table->unsignedInteger('related_id')->nullable();

            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('related_id')->references('id')->on('products');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('related_products', function (Blueprint $table) {
            $table->dropForeign('related_products_product_id_foreign');
            $table->dropForeign('related_products_related_id_foreign');
        });

        Schema::drop('related_products');
    }
}
