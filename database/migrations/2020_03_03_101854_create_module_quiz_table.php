<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModuleQuizTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('module_quiz', function (Blueprint $table) {
	        $table->increments('id');
	        $table->unsignedInteger('file_id')->nullable();
	        $table->integer('sort_order')->default(0);
	        $table->boolean('status')->default(1);
	        $table->json('questions')->nullable();
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
	    Schema::table('module_quiz', function (Blueprint $table) {
		    $table->dropForeign('module_quiz_file_id_foreign');
	    });

        Schema::dropIfExists('module_quiz');
    }
}
