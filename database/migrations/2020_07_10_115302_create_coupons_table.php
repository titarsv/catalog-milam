<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table){
            $table->increments('id');
            $table->string('name');
            $table->string('code');
            $table->unsignedInteger('user_id')->nullable();
//            $table->unsignedInteger('product_id')->nullable();
            $table->float('price')->nullable();
            $table->unsignedInteger('percent')->nullable();
            $table->date('from')->nullable();
            $table->date('to')->nullable();
            $table->boolean('disposable')->default(true);
            $table->boolean('used')->default(false);
            $table->json('scope')->nullable();
            $table->json('statistic')->nullable();
            $table->unsignedInteger('min_total')->default(0);
            $table->boolean('without_sale')->default(true);
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));

            $table->foreign('user_id')->references('id')->on('users');
//            $table->foreign('product_id')->references('id')->on('products');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('coupons', function (Blueprint $table) {
            $table->dropForeign('coupons_user_id_foreign');
//            $table->dropForeign('coupons_product_id_foreign');
        });

        Schema::drop('coupons');
    }
}
