<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTableListingOffersAddDeliveredByHub extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('listing_offers', function (Blueprint $table) {
            $table->boolean('delivered_by_hub')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('listing_offers', 'delivered_by_hub')) {
            Schema::table('listing_offers', function (Blueprint $table) {
                $table->dropColumn('delivered_by_hub');
            });
        }
    }
}
