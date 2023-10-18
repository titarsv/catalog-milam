<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_reviews', function(Blueprint $table){
	        $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('parent_review_id')->nullable();
            $table->unsignedInteger('user_id')->nullable();
            $table->unsignedInteger('product_id');
	        $table->smallInteger('grade')->nullable();
            $table->text('review');
            $table->text('answer')->nullable();
            $table->string('author');
            $table->string('email');
            $table->boolean('published')->default(0);
            $table->boolean('new')->default(1);
            $table->boolean('confirmed_purchase')->default(0);
            $table->boolean('notification')->default(0);
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->softDeletes();

	        $table->foreign('product_id')->references('id')->on('products');
	        $table->foreign('user_id')->references('id')->on('users');
	        $table->index('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
	    Schema::table('product_reviews', function (Blueprint $table) {
		    $table->dropForeign('product_reviews_product_id_foreign');
	    });

        Schema::drop('product_reviews');
    }
}
