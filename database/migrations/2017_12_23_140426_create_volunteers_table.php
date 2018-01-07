<?php

use FSR\ListingMsg;
use FSR\ListingOffer;
use FSR\Volunteer;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVolunteersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('listing_offers', 'volunteer_pickup_name')) {
            Schema::table('listing_offers', function (Blueprint $table) {
                $table->dropColumn('volunteer_pickup_name');
            });
        }

        if (Schema::hasColumn('listing_offers', 'volunteer_pickup_phone')) {
            Schema::table('listing_offers', function (Blueprint $table) {
                $table->dropColumn('volunteer_pickup_phone');
            });
        }

        Schema::table('listing_offers', function (Blueprint $table) {
            $table->integer('volunteer_id')->unsigned();
        });

        Schema::create('volunteers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->integer('image_id')->unsigned()->nullable();
            $table->integer('organization_id')->unsigned();
            $table->integer('added_by_user_id')->unsigned()->nullable();
            $table->timestamps();
        });

        Schema::table('listing_offers', function (Blueprint $table) {
            $table->foreign('volunteer_id')->references('id')->on('volunteers');
        });

        Schema::table('volunteers', function (Blueprint $table) {
            $table->foreign('organization_id')->references('id')->on('organizations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // ListingMsg::truncate();
        // ListingOffer::truncate();
        // Volunteer::truncate();

        Schema::table('volunteers', function (Blueprint $table) {
            $table->dropForeign(['organization_id']);
        });
        Schema::table('listing_offers', function (Blueprint $table) {
            $table->dropForeign(['volunteer_id']);
        });

        Schema::dropIfExists('volunteers');

        if (Schema::hasColumn('listing_offers', 'volunteer_id')) {
            Schema::table('listing_offers', function (Blueprint $table) {
                $table->dropColumn('volunteer_id');
            });
        }

        Schema::table('listing_offers', function (Blueprint $table) {
            $table->string('volunteer_pickup_name')->nullable();
        });

        Schema::table('listing_offers', function (Blueprint $table) {
            $table->string('volunteer_pickup_phone')->nullable();
        });
    }
}
