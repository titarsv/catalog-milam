<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_data', function(Blueprint $table){
	        $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('file_id')->nullable();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->string('user_birth')->nullable();
            $table->string('city')->nullable();
            $table->boolean('gender')->default(0);
            $table->text('other_data')->nullable();
            $table->tinyInteger('subscribe');

	        $table->foreign('user_id')->references('id')->on('users');
	        $table->foreign('file_id')->references('id')->on('files');
	        $table->index('id');
	        $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
	    Schema::table('users_data', function (Blueprint $table) {
		    $table->dropForeign('users_data_user_id_foreign');
		    $table->dropForeign('users_data_file_id_foreign');
	    });

        Schema::drop('users_data');
    }
}
