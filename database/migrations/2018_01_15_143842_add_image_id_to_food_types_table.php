<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddImageIdToFoodTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('food_types', 'default_image')) {
            Schema::table('food_types', function (Blueprint $table) {
                $table->dropColumn('default_image');
            });
        }

        Schema::table('food_types', function (Blueprint $table) {
            $table->integer('image_id')->nullable();
        });

        Schema::table('food_types', function (Blueprint $table) {
            $table->renameColumn('comment', 'description');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('food_types', function (Blueprint $table) {
            $table->renameColumn('description', 'comment');
        });

        if (Schema::hasColumn('food_types', 'image_id')) {
            Schema::table('food_types', function (Blueprint $table) {
                $table->dropColumn('image_id');
            });
        }

        Schema::table('food_types', function (Blueprint $table) {
            $table->string('default_image')->nullable();
        });
    }
}
