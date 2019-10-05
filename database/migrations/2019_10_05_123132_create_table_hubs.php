<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableHubs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hubs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('first_name', 50)->nullable();
            $table->string('last_name', 50)->nullable();
            $table->string('phone', 50)->nullable();
            $table->string('address', 255)->nullable();
            $table->integer('organization_id')->unsigned();
            $table->integer('region_id')->unsigned();
            $table->string('profile_image_id')->nullable();
            $table->boolean('notifications')->default(0);
            $table->float('location_x')->nullable();
            $table->float('location_y')->nullable();
            $table->string('status', 20)->default('pending');
            $table->boolean('email_confirmed')->default(0);
            $table->string('email_token')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::table('hubs', function (Blueprint $table) {
            $table->foreign('organization_id')->references('id')->on('organizations');
            $table->foreign('region_id')->references('id')->on('regions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hubs', function (Blueprint $table) {
            $table->dropForeign(['organization_id']);
            $table->dropForeign(['region_id']);
        });

        Schema::dropIfExists('hubs');
    }
}
