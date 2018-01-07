<?php

use FSR\Listing;
use FSR\FoodType;
use FSR\Product;
use FSR\ListingOffer;
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
        Schema::table('listings', function (Blueprint $table) {
            $table->dropForeign(['food_type_id']);
        });
        //
        if (Schema::hasColumn('listings', 'food_type_id')) {
            Schema::table('listings', function (Blueprint $table) {
                $table->dropColumn('food_type_id');
            });
        }
        //
        if (Schema::hasColumn('listings', 'title')) {
            Schema::table('listings', function (Blueprint $table) {
                $table->dropColumn('title');
            });
        }
        //
        Schema::table('listings', function (Blueprint $table) {
            $table->integer('product_id')->unsigned();
        });
        //
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('description')->nullable();
            $table->integer('food_type_id')->unsigned();
            $table->timestamps();
        });
        //
        Schema::table('listings', function (Blueprint $table) {
            $table->foreign('product_id')->references('id')->on('products');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->foreign('food_type_id')->references('id')->on('food_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // FoodType::truncate();
        // Product::truncate();
        // ListingOffer::truncate();
        // Listing::truncate();
        //

        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['food_type_id']);
        });
        //
        Schema::table('listings', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
        });
        //
        Schema::dropIfExists('products');
        //
        if (Schema::hasColumn('listings', 'product_id')) {
            Schema::table('listings', function (Blueprint $table) {
                $table->dropColumn('product_id');
            });
        }
        //
        Schema::table('listings', function (Blueprint $table) {
            $table->string('title')->nullable();
        });
        //
        Schema::table('listings', function (Blueprint $table) {
            $table->integer('food_type_id')->unsigned();
        });
        //
        Schema::table('listings', function (Blueprint $table) {
            $table->foreign('food_type_id')->references('id')->on('food_types');
        });
    }
}
