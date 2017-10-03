<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCampaignIdToPayCommission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('paid_commissions', function (Blueprint $table) {
            $table->bigInteger('campaign_id')
                ->index()->unsigned()
                ->nullable()->after('paid_commission');

            $table->foreign('campaign_id')
                ->references('id')->on('campaigns')
                ->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('paid_commissions', function (Blueprint $table) {
            $table->dropColumn('campaign_id');
        });
    }
}
