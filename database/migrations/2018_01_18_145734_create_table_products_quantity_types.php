<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableProductsQuantityTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products_quantity_types', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id')->unsigned();
            $table->integer('quantity_type_id')->unsigned();
            $table->boolean('default')->default(0);
            $table->double('portion_size')->nullable();
        });


        if (Schema::hasColumn('quantity_types', 'portion_size')) {
            Schema::table('quantity_types', function (Blueprint $table) {
                $table->dropColumn('portion_size');
            });
        }

        Schema::table('products_quantity_types', function (Blueprint $table) {
            $table->foreign('quantity_type_id')->references('id')->on('quantity_types');
        });

        Schema::table('products_quantity_types', function (Blueprint $table) {
            $table->foreign('product_id')->references('id')->on('products');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products_quantity_types', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
        });

        Schema::table('products_quantity_types', function (Blueprint $table) {
            $table->dropForeign(['quantity_type_id']);
        });

        Schema::table('quantity_types', function (Blueprint $table) {
            $table->double('portion_size')->nullable();
        });

        Schema::dropIfExists('products_quantity_types');
    }
}
