<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableHubListingOffers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hub_listing_offers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('hub_id')->unsigned();
            $table->integer('listing_id')->unsigned();
            $table->string('status', 50)->nullable();
            $table->float('quantity')->nullable();
            $table->integer('beneficiaries_no')->nullable();
            $table->timestamps();
        });

        Schema::table('hub_listing_offers', function (Blueprint $table) {
            $table->foreign('hub_id')->references('id')->on('hubs');
            $table->foreign('listing_id')->references('id')->on('listings');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hub_listing_offers', function (Blueprint $table) {
            $table->dropForeign(['hub_id', 'listing_id']);
        });
        
        Schema::dropIfExists('hub_listing_offers');
    }
}
