<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStripeCredentialToCampaignTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('campaigns', function (Blueprint $table) {
            $table->string('test_pk')->nullable()->after('approval');
            $table->string('test_sk')->nullable()->after('test_pk');
            $table->string('live_pk')->nullable()->after('test_sk');
            $table->string('live_sk')->nullable()->after('live_pk');
            $table->tinyInteger('stripe_mode')->default(1)->comments('1 => Test , 2 => Live');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('campaigns', function (Blueprint $table) {
            $table->dropColumn('test_pk');
            $table->dropColumn('test_sk');
            $table->dropColumn('live_pk');
            $table->dropColumn('live_sk');
            $table->dropColumn('stripe_mode');
        });
    }
}
