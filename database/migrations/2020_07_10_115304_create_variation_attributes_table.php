<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVariationAttributesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('variation_attributes', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('variation_id');
            $table->unsignedInteger('attribute_value_id');

            $table->foreign('variation_id')->references('id')->on('variations');
            $table->foreign('attribute_value_id')->references('id')->on('attribute_values');
            $table->index('id');
            $table->index('variation_id');
            $table->index('attribute_value_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('variation_attributes', function (Blueprint $table) {
            $table->dropForeign('variation_attributes_variation_id_foreign');
            $table->dropForeign('variation_attributes_attribute_value_id_foreign');
        });

        Schema::drop('variation_attributes');
    }
}
