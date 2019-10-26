<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTableListingOffers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('listing_offers', function (Blueprint $table) {
            $table->dropForeign(['listing_id']);
        });

        if (Schema::hasColumn('listing_offers', 'listing_id')) {
            Schema::table('listing_offers', function (Blueprint $table) {
                $table->dropColumn('listing_id');
            });
        }

        Schema::table('listing_offers', function (Blueprint $table) {
            $table->integer('hub_listing_id')->unsigned();
        });

        Schema::table('listing_offers', function (Blueprint $table) {
            $table->foreign('hub_listing_id')->references('id')->on('hub_listings');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('listing_offers', function (Blueprint $table) {
            $table->dropForeign(['hub_listing_id']);
        });

        if (Schema::hasColumn('listing_offers', 'hub_listing_id')) {
            Schema::table('listing_offers', function (Blueprint $table) {
                $table->dropColumn('hub_listing_id');
            });
        }

        Schema::table('listing_offers', function (Blueprint $table) {
            $table->integer('listing_id')->unsigned();
        });

        Schema::table('listing_offers', function (Blueprint $table) {
            $table->foreign('listing_id')->references('id')->on('listings');
        });
    }
}
