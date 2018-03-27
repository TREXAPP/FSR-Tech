<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBasicDbStructure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quantity_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50)->nullable();
            $table->string('description')->nullable();
        });

        Schema::create('food_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50)->nullable();
            $table->text('comment')->nullable();
        });

        Schema::create('donor_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50)->nullable();
            $table->string('description', 50)->nullable();
        });

        Schema::create('organizations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50)->nullable();
            $table->string('description', 50)->nullable();
            $table->string('type', 20);
        });

        Schema::create('locations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50)->nullable();
            $table->string('description', 50)->nullable();
        });

        Schema::create('csos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('first_name', 50)->nullable();
            $table->string('last_name', 50)->nullable();
            $table->string('phone', 50)->nullable();
            $table->string('address', 255)->nullable();
            $table->integer('organization_id')->unsigned();
            $table->integer('location_id')->unsigned();
            $table->string('profile_image_id')->nullable();
            $table->boolean('notifications')->default(0);
            $table->float('location_x')->nullable();
            $table->float('location_y')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::table('csos', function (Blueprint $table) {
            $table->foreign('organization_id')->references('id')->on('organizations');
            $table->foreign('location_id')->references('id')->on('locations');
        });

        Schema::create('donors', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('first_name', 50)->nullable();
            $table->string('last_name', 50)->nullable();
            $table->string('phone', 50)->nullable();
            $table->string('address', 255)->nullable();
            $table->integer('organization_id')->unsigned();
            $table->integer('donor_type_id')->unsigned();
            $table->integer('location_id')->unsigned();
            $table->string('profile_image_id')->nullable();
            $table->boolean('notifications')->default(0);
            $table->float('location_x')->nullable();
            $table->float('location_y')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::table('donors', function (Blueprint $table) {
            $table->foreign('organization_id')->references('id')->on('organizations');
            $table->foreign('location_id')->references('id')->on('locations');
            $table->foreign('donor_type_id')->references('id')->on('donor_types');
        });

        Schema::create('listings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('donor_id')->unsigned();
            $table->text('title')->nullable();
            $table->text('description')->nullable();
            $table->integer('food_type_id')->unsigned();
            $table->float('quantity')->nullable();
            $table->integer('quantity_type_id')->unsigned();
            $table->datetime('date_listed')->nullable();
            $table->datetime('date_expires')->nullable();
            $table->integer('image_id')->nullable()->unsigned();
            $table->time('pickup_time_from')->nullable();
            $table->time('pickup_time_to')->nullable();
            $table->string('listing_status', 50)->nullable();
            // $table->datetime('RecurringType');
            // $table->datetime('RecurringWeekly');
            // $table->datetime('RecurringMonthly');
            $table->timestamps();
        });

        Schema::table('listings', function (Blueprint $table) {
            $table->foreign('donor_id')->references('id')->on('donors');
            $table->foreign('food_type_id')->references('id')->on('food_types');
            $table->foreign('quantity_type_id')->references('id')->on('quantity_types');
        });

        Schema::create('listing_offers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cso_id')->unsigned();
            $table->integer('listing_id')->unsigned();
            $table->string('offer_status', 50)->nullable();
            $table->float('quantity')->nullable();
            $table->integer('beneficiaries_no')->nullable();
            $table->string('volunteer_pickup_name', 50)->nullable();
            $table->string('volunteer_pickup_phone', 20)->nullable();
            $table->timestamps();
        });

        Schema::table('listing_offers', function (Blueprint $table) {
            $table->foreign('cso_id')->references('id')->on('csos');
            $table->foreign('listing_id')->references('id')->on('listings');
        });

        Schema::create('listing_msgs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('listing_offer_id')->unsigned();
            $table->text('msg_text');
            $table->string('msg_status', 50)->nullable();
            $table->string('sender_type', 20)->nullable();
            $table->integer('sender_id')->unsigned();
            $table->timestamps();
        });

        Schema::table('listing_msgs', function (Blueprint $table) {
            $table->foreign('listing_offer_id')->references('id')->on('listing_offers');
        });

        Schema::create('login_log', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->string('user_type', 20);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {


        //drop foreign keys
        Schema::table('csos', function (Blueprint $table) {
            $table->dropForeign(['organization_id']);
            $table->dropForeign(['location_id']);
        });
        Schema::table('donors', function (Blueprint $table) {
            $table->dropForeign(['organization_id']);
            $table->dropForeign(['location_id']);
            $table->dropForeign(['donor_type_id']);
        });
        Schema::table('listings', function (Blueprint $table) {
            $table->dropForeign(['donor_id']);
            $table->dropForeign(['food_type_id']);
            $table->dropForeign(['quantity_type_id']);
        });
        Schema::table('listing_offers', function (Blueprint $table) {
            $table->dropForeign(['cso_id']);
            $table->dropForeign(['listing_id']);
        });
        Schema::table('listing_msgs', function (Blueprint $table) {
            $table->dropForeign(['listing_offer_id']);
        });

        //drop tables
        Schema::dropIfExists('quantity_types');
        Schema::dropIfExists('food_types');
        Schema::dropIfExists('donor_types');
        Schema::dropIfExists('organizations');
        Schema::dropIfExists('locations');
        Schema::dropIfExists('csos');
        Schema::dropIfExists('donors');
        Schema::dropIfExists('listings');
        Schema::dropIfExists('listing_offers');
        Schema::dropIfExists('listing_msgs');
        Schema::dropIfExists('login_log');
    }
}
