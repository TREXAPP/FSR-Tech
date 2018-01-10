<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('reply_id')->default(0);
            $table->integer('listing_offer_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->string('status', 20)->default('active');
            $table->string('sender_type', 20);
            $table->text('text');
            $table->timestamps();
        });

        Schema::table('comments', function (Blueprint $table) {
            $table->foreign('listing_offer_id')->references('id')->on('listing_offers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('comments', function (Blueprint $table) {
            $table->dropForeign(['listing_offer_id']);
        });

        Schema::dropIfExists('comments');
    }
}
