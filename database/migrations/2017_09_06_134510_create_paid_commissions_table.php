<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaidCommissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paid_commissions', function (Blueprint $table) {
            $table->increments('id');
            $table->biginteger('affiliate_id')->unsigned()->index();
            $table->biginteger('user_id')->unsigned()->index();
            $table->string('paid_commission');
            $table->timestamps();

            $table->foreign('affiliate_id')
                ->references('id')->on('users')
                ->onDelete('RESTRICT');
            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('paid_commissions');
    }
}
