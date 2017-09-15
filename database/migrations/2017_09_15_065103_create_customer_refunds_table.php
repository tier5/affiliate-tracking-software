<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerRefundsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_refunds', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('campaign_id')->index()->unsigned();
            $table->integer('log_id')->index()->unsigned();
            $table->double('amount');
            $table->timestamps();

            $table->foreign('campaign_id')
                ->references('id')->on('campaigns')
                ->onDelete('CASCADE');
            $table->foreign('log_id')
                ->references('id')->on('agent_url_details')
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
        Schema::dropIfExists('customer_refunds');
    }
}
