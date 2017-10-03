<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sales_id')->index()->unsigned();
            $table->double('product_amount')->nullable();
            $table->double('step_payment_amount')->nullable();
            $table->text('charge_id')->nullable();
            $table->tinyInteger('type')->comment('1 => Sale , 2 => Refunded');
            $table->tinyInteger('status')->comment('1 => sale , 2 => upgrade , 3 => renewal');
            $table->double('commission')->nullable();
            $table->timestamps();

            $table->foreign('sales_id')
                ->references('id')->on('order_products')
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
        Schema::dropIfExists('sales_details');
    }
}
