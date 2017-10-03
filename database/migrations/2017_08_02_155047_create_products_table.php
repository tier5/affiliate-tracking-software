<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('campaign_id')->unsigned();
            $table->string('name');
            $table->double('commission');
            $table->tinyInteger('method')->comment('1 => Percentage,2 => Dollars');
            $table->tinyInteger('frequency')->comment('1 => One-Time,2 => Recurring');
            $table->tinyInteger('plan')->nullable()->comment('1 => Daily,2 => Monthly,3 => Quarterly,4 => Yearly');
            $table->timestamps();

            $table->foreign('campaign_id')->references('id')->on('campaigns')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
