<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->increments('id');
            $table->float('sale_percent')->nullable();
            $table->unsignedInteger('file_id')->nullable();
//            $table->unsignedInteger('file_xs_id')->nullable();
//            $table->unsignedInteger('preview_id')->nullable();
//            $table->string('banner_color')->nullable();
            $table->boolean('status')->default(0);
            $table->timestamp('show_from')->nullable();
            $table->timestamp('show_to')->nullable();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->softDeletes();

            $table->foreign('file_id')->references('id')->on('files');
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
        Schema::table('sales', function (Blueprint $table) {
            $table->dropForeign('sales_file_id_foreign');
        });
        Schema::drop('sales');
    }
}