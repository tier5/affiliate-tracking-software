<?php

use Phinx\Migration\AbstractMigration;

class AddDiscountPriceToParameterList extends AbstractMigration
{
    public function up()
    {
        // create the table
        $table = $this->table('subscription_pricing_plan_parameter_list');
        $table->addColumn('discount_price', 'decimal', ['null' => false, 'default' => 0.00, 'precision' => 10, 'scale' => 2, 'after' => 'upgrade_discount'])  
            ->update();
    }
    
    public function down()
    {
        $table = $this->table('subscription_pricing_plan_parameter_list');
        $table->removeColumn('discount_price')
            ->update();
    }
}
