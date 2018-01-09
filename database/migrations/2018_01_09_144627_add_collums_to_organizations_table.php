<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCollumsToOrganizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('organizations', function (Blueprint $table) {
            $table->time('working_hours_from')->default('09:00:00');
            $table->time('working_hours_to')->default('17:00:00');
            $table->integer('image_id')->nullable();
            $table->string('status', 20)->default('active');
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
        if (Schema::hasColumn('organizations', 'working_hours_from')) {
            Schema::table('organizations', function (Blueprint $table) {
                $table->dropColumn('working_hours_from');
            });
        }
        if (Schema::hasColumn('organizations', 'working_hours_to')) {
            Schema::table('organizations', function (Blueprint $table) {
                $table->dropColumn('working_hours_to');
            });
        }
        if (Schema::hasColumn('organizations', 'image_id')) {
            Schema::table('organizations', function (Blueprint $table) {
                $table->dropColumn('image_id');
            });
        }
        if (Schema::hasColumn('organizations', 'status')) {
            Schema::table('organizations', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }
        if (Schema::hasColumn('organizations', 'created_at')) {
            Schema::table('organizations', function (Blueprint $table) {
                $table->dropColumn('created_at');
            });
        }
        if (Schema::hasColumn('organizations', 'updated_at')) {
            Schema::table('organizations', function (Blueprint $table) {
                $table->dropColumn('updated_at');
            });
        }
    }
}
