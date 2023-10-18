<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
	        $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('external_id')->nullable();
            $table->string('slug');
            $table->unsignedInteger('file_id')->nullable();
            $table->unsignedInteger('parent_id')->nullable();
            $table->integer('sort_order');
            $table->boolean('status');
	        $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
	        $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->softDeletes();

	        $table->foreign('file_id')->references('id')->on('files');
	        $table->foreign('parent_id')->references('id')->on('categories');
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
	    Schema::table('categories', function (Blueprint $table) {
		    $table->dropForeign('categories_file_id_foreign');
		    $table->dropForeign('categories_parent_id_foreign');
	    });

        Schema::drop('categories');
    }
}
