<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableVolunteerAvailabilities extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('volunteer_availabilities', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('volunteer_id')->unsigned();
            $table->integer('timeframe_id')->unsigned();
            $table->boolean('is_available')->default(true);
            $table->string('comment')->nullable();
            $table->string('status')->default('active');
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
        Schema::dropIfExists('volunteer_availabilities');
    }
}
