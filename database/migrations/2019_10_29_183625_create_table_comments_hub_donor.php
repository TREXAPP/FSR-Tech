<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableCommentsHubDonor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hub_donor_comments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('reply_id')->default(0);
            $table->integer('hub_listing_offer_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->string('status', 20)->default('active');
            $table->string('sender_type', 20);
            $table->text('text');
            $table->timestamps();
        });

        Schema::table('hub_donor_comments', function (Blueprint $table) {
            $table->foreign('hub_listing_offer_id')->references('id')->on('hub_listing_offers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hub_donor_comments', function (Blueprint $table) {
            $table->dropForeign(['hub_listing_offer_id']);
        });

        Schema::dropIfExists('hub_donor_comments');
    }
}
