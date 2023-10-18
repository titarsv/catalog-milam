<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function(Blueprint $table){
            $table->increments('id');
            $table->string('external_id')->nullable();
            $table->integer('user_id');
            $table->longText('products');
            $table->integer('total_quantity');
            $table->float('total_price');
            $table->float('total_sale')->nullable();
            $table->text('user_info');
            $table->text('delivery')->nullable();
            $table->text('payment')->nullable();
            $table->integer('status_id');
            $table->unsignedInteger('coupon_id')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('viewed')->nullable();
            $table->text('history')->nullable();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));

            $table->index('id');
	        $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('orders');
    }
}
