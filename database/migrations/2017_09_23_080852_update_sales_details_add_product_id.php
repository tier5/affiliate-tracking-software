<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateSalesDetailsAddProductId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
        Schema::table('sales_details', function(Blueprint $table){
            $table->integer('product_id')->unsigned()->nullable()->after('sales_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(){
        Schema::table('sales_details', function(Blueprint $table){
            $table->dropColumn('product_id');
        });
    }
}
