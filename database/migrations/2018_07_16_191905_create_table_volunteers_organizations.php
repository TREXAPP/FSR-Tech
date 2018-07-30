<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableVolunteersOrganizations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('volunteers_organizations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('volunteer_id')->unsigned();
            $table->integer('organization_id')->unsigned();
            $table->string('type')->default('volunteer_preference');
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
        Schema::dropIfExists('volunteers_organizations');
    }
}
