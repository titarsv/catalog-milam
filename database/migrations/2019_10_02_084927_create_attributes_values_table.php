<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttributesValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attribute_values', function (Blueprint $table) {
	        $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('external_id')->nullable();
            $table->unsignedInteger('attribute_id');
            $table->string('value');
	        $table->unsignedInteger('file_id')->nullable();

	        $table->foreign('attribute_id')->references('id')->on('attributes');
	        $table->foreign('file_id')->references('id')->on('files');
	        $table->index('id');
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
	    Schema::table('attribute_values', function (Blueprint $table) {
		    $table->dropForeign('attribute_values_attribute_id_foreign');
		    $table->dropForeign('attribute_values_file_id_foreign');
	    });

        Schema::drop('attribute_values');
    }
}
