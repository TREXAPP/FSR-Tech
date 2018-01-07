<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDefaultImageToFoodTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('food_types', function (Blueprint $table) {
            $table->string('default_image')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('food_types', 'default_image')) {
            Schema::table('food_types', function (Blueprint $table) {
                $table->dropColumn('default_image');
            });
        }
    }
}
