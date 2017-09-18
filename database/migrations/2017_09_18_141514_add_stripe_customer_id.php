<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStripeCustomerId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('agent_url_details', function (Blueprint $table) {
            $table->string('stripe_customer_id')->nullable()->after('price');
            $table->boolean('tracking_flag')->default(0)->comment('1 => Active, 0 => Inactive')->after('stripe_customer_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('agent_url_details', function (Blueprint $table) {
            $table->dropColumn('stripe_customer_id');
            $table->dropColumn('tracking_flag');
        });
    }
}
