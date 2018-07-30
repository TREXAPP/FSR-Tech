<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditVolunteersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('volunteers', 'global')) {
            Schema::table('volunteers', function (Blueprint $table) {
                $table->dropColumn('global');
            });
        }
        Schema::table('volunteers', function (Blueprint $table) {
            $table->string('type')->default('added_by_organization');
        });
        Schema::table('volunteers', function (Blueprint $table) {
            $table->string('address')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('volunteers', 'address')) {
            Schema::table('volunteers', function (Blueprint $table) {
                $table->dropColumn('address');
            });
        }
        if (Schema::hasColumn('volunteers', 'type')) {
            Schema::table('volunteers', function (Blueprint $table) {
                $table->dropColumn('type');
            });
        }
        Schema::table('volunteers', function (Blueprint $table) {
            $table->boolean('global')->default(0);
        });
    }
}
