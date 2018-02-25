<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PointDonorTypesToOrganizations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('donors', function (Blueprint $table) {
            $table->dropForeign(['donor_type_id']);
        });

        if (Schema::hasColumn('donors', 'donor_type_id')) {
            Schema::table('donors', function (Blueprint $table) {
                $table->dropColumn('donor_type_id');
            });
        }

        Schema::table('organizations', function (Blueprint $table) {
            $table->integer('donor_type_id')->nullable()->unsigned();
        });

        Schema::table('organizations', function (Blueprint $table) {
            $table->foreign('donor_type_id')->references('id')->on('donor_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('organizations', function (Blueprint $table) {
            $table->dropForeign(['donor_type_id']);
        });

        if (Schema::hasColumn('organizations', 'donor_type_id')) {
            Schema::table('organizations', function (Blueprint $table) {
                $table->dropColumn('donor_type_id');
            });
        }

        Schema::table('donors', function (Blueprint $table) {
            $table->integer('donor_type_id')->nullable()->unsigned();
        });

        Schema::table('donors', function (Blueprint $table) {
            $table->foreign('donor_type_id')->references('id')->on('donor_types');
        });
    }
}
