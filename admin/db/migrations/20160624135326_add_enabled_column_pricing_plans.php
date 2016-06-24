<?php

use Phinx\Migration\AbstractMigration;

class AddEnabledColumnPricingPlans extends AbstractMigration
{
    public function up()
    {
        // create the table
        $table = $this->table('subscription_pricing_plan');
        $table->addColumn('enabled', 'boolean', ['null' => false, 'default' => false, 'after' => 'name'])
            ->update();
    }
    
    public function down()
    {
        $table = $this->table('subscription_pricing_plan');
        $table->removeColumn('enabled')
            ->update();
    }
}
