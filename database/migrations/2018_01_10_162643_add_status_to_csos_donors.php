<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusToCsosDonors extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('csos', function (Blueprint $table) {
            $table->string('status', 20)->default('pending');
        });
        Schema::table('donors', function (Blueprint $table) {
            $table->string('status', 20)->default('pending');
        });

        if (Schema::hasColumn('csos', 'approved')) {
            Schema::table('csos', function (Blueprint $table) {
                $table->dropColumn('approved');
            });
        }
        if (Schema::hasColumn('donors', 'approved')) {
            Schema::table('donors', function (Blueprint $table) {
                $table->dropColumn('approved');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('csos', function (Blueprint $table) {
            $table->boolean('approved')->default(0);
        });
        Schema::table('donors', function (Blueprint $table) {
            $table->boolean('approved')->default(0);
        });
        if (Schema::hasColumn('csos', 'status')) {
            Schema::table('csos', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }
        if (Schema::hasColumn('donors', 'status')) {
            Schema::table('donors', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }
    }
}
