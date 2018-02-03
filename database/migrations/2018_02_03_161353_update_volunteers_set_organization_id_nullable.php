<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateVolunteersSetOrganizationIdNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('volunteers', function (Blueprint $table) {
            $table->dropForeign(['organization_id']);
        });

        Schema::table('volunteers', function (Blueprint $table) {
            $table->integer('organization_id')->unsigned()->nullable(true)->change();
        });

        Schema::table('volunteers', function (Blueprint $table) {
            $table->boolean('global')->default(0);
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
        Schema::table('volunteers', function (Blueprint $table) {
            $table->dropForeign(['organization_id']);
        });

        Schema::table('volunteers', function (Blueprint $table) {
            $table->integer('organization_id')->unsigned()->nullable(false)->change();
        });

        if (Schema::hasColumn('volunteers', 'global')) {
            Schema::table('volunteers', function (Blueprint $table) {
                $table->dropColumn('global');
            });
        }

        Schema::table('volunteers', function (Blueprint $table) {
            $table->foreign('organization_id')->references('id')->on('organizations');
        });
    }
}
