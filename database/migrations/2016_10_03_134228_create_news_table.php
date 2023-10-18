<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('news', function(Blueprint $table){
            $table->increments('id');
            $table->integer('user_id');
            $table->unsignedInteger('file_id')->nullable();
            $table->boolean('published');
	        $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
	        $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
	        $table->softDeletes();

	        $table->foreign('file_id')->references('id')->on('files');
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
	    Schema::table('news', function (Blueprint $table) {
		    $table->dropForeign('news_file_id_foreign');
	    });

        Schema::dropIfExists('news');
    }
}
