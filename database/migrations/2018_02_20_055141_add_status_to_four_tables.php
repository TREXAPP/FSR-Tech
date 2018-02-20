<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusToFourTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('locations', function (Blueprint $table) {
          $table->string('status', 20)->default('active');
      });
      Schema::table('food_types', function (Blueprint $table) {
          $table->string('status', 20)->default('active');
      });
      Schema::table('products', function (Blueprint $table) {
          $table->string('status', 20)->default('active');
      });
      Schema::table('quantity_types', function (Blueprint $table) {
          $table->string('status', 20)->default('active');
      });
      Schema::table('donor_types', function (Blueprint $table) {
          $table->string('status', 20)->default('active');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      if (Schema::hasColumn('locations', 'status')) {
          Schema::table('locations', function (Blueprint $table) {
              $table->dropColumn('status');
          });
      }
      if (Schema::hasColumn('food_types', 'status')) {
          Schema::table('food_types', function (Blueprint $table) {
              $table->dropColumn('status');
          });
      }
      if (Schema::hasColumn('products', 'status')) {
          Schema::table('products', function (Blueprint $table) {
              $table->dropColumn('status');
          });
      }
      if (Schema::hasColumn('quantity_types', 'status')) {
          Schema::table('quantity_types', function (Blueprint $table) {
              $table->dropColumn('status');
          });
      }
      if (Schema::hasColumn('donor_types', 'status')) {
          Schema::table('donor_types', function (Blueprint $table) {
              $table->dropColumn('status');
          });
      }
    }
}
