<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableHubListings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::create('hub_listings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('hub_id')->unsigned();
            $table->text('description')->nullable();
            $table->float('quantity')->nullable();
            $table->integer('quantity_type_id')->unsigned();
            $table->datetime('date_listed')->nullable();
            $table->datetime('date_expires')->nullable();
            $table->integer('image_id')->nullable()->unsigned();
            $table->time('pickup_time_from')->nullable();
            $table->time('pickup_time_to')->nullable();
            $table->string('status', 50)->nullable();
            $table->integer('product_id')->unsigned();
            $table->date('sell_by_date')->nullable();
            $table->timestamps();
        });

        Schema::table('hub_listings', function (Blueprint $table) {
            $table->foreign('hub_id')->references('id')->on('hubs');
            $table->foreign('quantity_type_id')->references('id')->on('quantity_types');
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
        Schema::table('hub_listings', function (Blueprint $table) {
            $table->dropForeign(['hub_id', 'quantity_type_id', 'product_id']);
        });

        Schema::dropIfExists('hub_listings');
    }
}
