<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEmailConfirmedToCsosAndDonorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('csos', function (Blueprint $table) {
            $table->boolean('email_confirmed')->default(0);
        });
        Schema::table('csos', function (Blueprint $table) {
            $table->string('email_token')->nullable();
        });
        Schema::table('donors', function (Blueprint $table) {
            $table->boolean('email_confirmed')->default(0);
        });
        Schema::table('donors', function (Blueprint $table) {
            $table->string('email_token')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('donors', 'email_confirmed')) {
            Schema::table('donors', function (Blueprint $table) {
                $table->dropColumn('email_confirmed');
            });
        }
        if (Schema::hasColumn('donors', 'email_token')) {
            Schema::table('donors', function (Blueprint $table) {
                $table->dropColumn('email_token');
            });
        }
        if (Schema::hasColumn('csos', 'email_confirmed')) {
            Schema::table('csos', function (Blueprint $table) {
                $table->dropColumn('email_confirmed');
            });
        }
        if (Schema::hasColumn('csos', 'email_token')) {
            Schema::table('csos', function (Blueprint $table) {
                $table->dropColumn('email_token');
            });
        }
    }
}
