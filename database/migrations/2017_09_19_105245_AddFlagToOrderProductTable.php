<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFlagToOrderProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_products', function (Blueprint $table) {
            $table->boolean('tracking_flag')->default(0)->comment('1 => Active, 0 => Inactive')->after('status');
            $table->boolean('first_flag')->default(0)->comment('1 => cycle sale , 0 => first sale')->after('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_products', function (Blueprint $table) {
            $table->dropColumn('tracking_flag');
            $table->dropColumn('first_flag');
        });
    }
}
