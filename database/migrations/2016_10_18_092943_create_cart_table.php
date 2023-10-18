<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCartTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cart', function(Blueprint $table){
            $table->increments('id');
            $table->integer('user_id')->nullable();
            $table->string('session_id');
            $table->longText('products')->nullable();
            $table->integer('total_quantity');
            $table->float('total_price');
            $table->float('coupon_sale')->nullable();
            $table->float('total_sale')->nullable();
            $table->text('user_data')->nullable();
            $table->text('cart_data')->nullable();
            $table->unsignedInteger('coupon_id')->nullable();
            $table->timestamps();

	        $table->index('id');
	        $table->index('user_id');
	        $table->index('session_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('cart');
    }
}
