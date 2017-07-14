<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubscriptionInvalidationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscription_invalidations', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('agency_type', ['business', 'agency']);
            $table->integer('agency_id')->unique();
            $table->text('name')->nullable();
            $table->text('email')->nullable();
            $table->boolean('stripe_exists_in_db');
            $table->boolean('stripe_subscription_exists');
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
        Schema::dropIfExists('subscription_invalidations');
    }
}
