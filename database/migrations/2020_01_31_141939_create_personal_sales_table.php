<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonalSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('personal_sales', function (Blueprint $table) {
	        $table->engine = 'InnoDB';
	        $table->increments('id');
	        $table->string('name');
	        $table->string('email');
	        $table->unsignedInteger('product_id');
	        $table->unsignedInteger('analog')->nullable();
	        $table->unsignedInteger('lag')->nullable();
	        $table->string('status')->nullable();
	        $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
	        $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
	        $table->softDeletes();

	        $table->foreign('product_id')->references('id')->on('products');
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
	    Schema::table('personal_sales', function (Blueprint $table) {
		    $table->dropForeign('personal_sales_product_id_foreign');
	    });
	    Schema::dropIfExists('personal_sales');
    }
}
