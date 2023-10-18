<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGalleriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('galleries', function (Blueprint $table) {
	        $table->engine = 'InnoDB';
	        $table->bigIncrements('id');
	        $table->string('field')->nullable();
	        $table->unsignedInteger('file_id');
	        $table->string('parent_type');
	        $table->unsignedInteger('parent_id');
	        $table->unsignedInteger('order');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
	        $table->softDeletes();

	        $table->foreign('file_id')->references('id')->on('files');
	        $table->index('id');
	        $table->index('file_id');
	        $table->index('parent_type');
	        $table->index('parent_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
	    Schema::table('galleries', function (Blueprint $table) {
		    $table->dropForeign('galleries_file_id_foreign');
	    });
        Schema::dropIfExists('galleries');
    }
}
