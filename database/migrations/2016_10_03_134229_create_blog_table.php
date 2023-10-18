<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blog', function(Blueprint $table){
            $table->increments('id');
            $table->integer('user_id');
            $table->unsignedInteger('image_id')->nullable();
            $table->boolean('status');
	        $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
	        $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
	        $table->softDeletes();

	        $table->foreign('image_id')->references('id')->on('files');
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
	    Schema::table('blog', function (Blueprint $table) {
		    $table->dropForeign('blog_image_id_foreign');
	    });

        Schema::dropIfExists('blog');
    }
}
