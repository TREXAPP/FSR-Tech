<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTableLocations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('locations', function (Blueprint $table) {
            $table->integer('region_id')->unsigned()->default(1);
        });

        Schema::table('locations', function (Blueprint $table) {
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
        Schema::table('locations', function (Blueprint $table) {
            $table->dropForeign(['region_id']);
        });

        if (Schema::hasColumn('locations', 'region_id')) {
            Schema::table('locations', function (Blueprint $table) {
                $table->dropColumn('region_id');
            });
        }
    }
}
