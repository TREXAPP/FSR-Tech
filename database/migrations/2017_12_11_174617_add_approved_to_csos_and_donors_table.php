<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddApprovedToCsosAndDonorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('csos', function (Blueprint $table) {
            $table->boolean('approved')->default(0);
        });
        Schema::table('donors', function (Blueprint $table) {
            $table->boolean('approved')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
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
}
