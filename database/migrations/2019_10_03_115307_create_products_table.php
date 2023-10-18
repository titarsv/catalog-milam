<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('products', function (Blueprint $table) {
			$table->increments('id');
			$table->string('external_id')->nullable();
            $table->string('name')->nullable();
			$table->string('sku')->nullable();
			$table->string('gtin')->nullable();
			$table->float('price')->nullable();
			$table->float('rental_price')->nullable();
			$table->float('original_price')->nullable();
			$table->float('sale_price')->nullable();
			$table->boolean('sale')->default(0);
			$table->timestamp('sale_from')->nullable();
			$table->timestamp('sale_to')->nullable();
			$table->unsignedInteger('file_id')->nullable();
			$table->integer('stock')->default(0);
			$table->boolean('visible')->default(1);
            $table->integer('sort_priority')->default(0);
            $table->float('rating')->default(0);
            $table->string('sizes_type')->nullable();
            $table->string('sizes_standard')->default('EU');
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
		Schema::table('products', function (Blueprint $table) {
			$table->dropForeign('products_file_id_foreign');
		});
		Schema::drop('products');
	}
}
