<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('images', function (Blueprint $table) {
	        $table->engine = 'InnoDB';
            $table->increments('id');
	        $table->unsignedInteger('file_id');
            $table->string('path');
            $table->string('mime');
            $table->string('size');
            $table->string('width');
            $table->string('height');
            $table->string('crop');
	        $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
	        $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));

	        $table->foreign('file_id')->references('id')->on('files');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
	    Schema::table('images', function (Blueprint $table) {
		    $table->dropForeign('images_file_id_foreign');
	    });
        Schema::drop('images');
    }
}
