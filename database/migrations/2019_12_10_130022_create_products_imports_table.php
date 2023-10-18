<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsImportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products_imports', function (Blueprint $table) {
	        $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name');
	        $table->string('file');
	        $table->string('attachments')->nullable();
            $table->boolean('status')->default(0);
            $table->json('errors')->nullable();
            $table->json('structure')->nullable();
            $table->string('schedule')->nullable();
            $table->json('settings')->nullable();
            $table->json('statistic')->nullable();
	        $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
	        $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
	        $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products_imports');
    }
}
