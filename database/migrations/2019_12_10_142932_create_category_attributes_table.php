<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoryAttributesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category_attributes', function (Blueprint $table) {
	        $table->engine = 'InnoDB';
	        $table->increments('id');
	        $table->unsignedInteger('category_id');
	        $table->unsignedInteger('attribute_id');

	        $table->foreign('category_id')->references('id')->on('categories');
	        $table->foreign('attribute_id')->references('id')->on('attributes');
	        $table->index('id');
	        $table->index('category_id');
	        $table->index('attribute_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
	    Schema::table('category_attributes', function (Blueprint $table) {
		    $table->dropForeign('category_attributes_category_id_foreign');
		    $table->dropForeign('category_attributes_attribute_id_foreign');
	    });
        Schema::dropIfExists('category_attributes');
    }
}
