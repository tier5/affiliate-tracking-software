<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgentUrlDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agent_url_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('url_id')->unsigned()->index();
            $table->integer('affiliate_id')->unsigned()->index();
            $table->tinyInteger('type')->default(1)->comment('1 => visit, 2 => sale, 3 => lead');
            $table->tinyInteger('status')->default(0);
            $table->string('ip');
            $table->bigInteger('count')->default(0);
            $table->string('browser')->nullable();
            $table->string('os')->nullable();
            $table->string('orderId')->nullable();
            $table->string('price')->nullable();
            $table->timestamps();

            $table->foreign('url_id')
                ->references('id')->on('agent_urls')
                ->onDelete('cascade');
            $table->foreign('affiliate_id')
                ->references('id')->on('affiliates')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('agent_url_details');
    }
}
