<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopReviewTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shop_reviews', function (Blueprint $table) {
	        $table->engine = 'InnoDB';
	        $table->increments('id');
	        $table->integer('parent_review_id')->nullable();
	        $table->unsignedInteger('user_id')->nullable();
	        $table->smallInteger('grade')->nullable();
	        $table->text('review');
	        $table->text('answer');
	        $table->string('author');
	        $table->boolean('published');
	        $table->boolean('new');
	        $table->timestamps();
	        $table->softDeletes();

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
	    Schema::table('shop_reviews', function (Blueprint $table) {
		    $table->dropForeign('shop_reviews_user_id_foreign');
	    });

	    Schema::dropIfExists('shop_reviews');
    }
}
