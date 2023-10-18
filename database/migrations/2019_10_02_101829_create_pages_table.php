<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->increments('id');
	        $table->string('template')->default('public.page');
            $table->unsignedInteger('parent_id')->nullable()->default(null);
            $table->boolean('status')->default(0);
            $table->integer('sort_order')->default(0);
	        $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
	        $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
	        $table->softDeletes();

	        $table->foreign('parent_id')->references('id')->on('pages');
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
	    Schema::table('pages', function (Blueprint $table) {
		    $table->dropForeign('pages_parent_id_foreign');
	    });

        Schema::drop('pages');
    }
}
