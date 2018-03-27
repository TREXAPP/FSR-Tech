<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPortionSizeToQuantityTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quantity_types', function (Blueprint $table) {
            $table->double('portion_size')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('quantity_types', 'portion_size')) {
            Schema::table('quantity_types', function (Blueprint $table) {
                $table->dropColumn('portion_size');
            });
        }
    }
}
