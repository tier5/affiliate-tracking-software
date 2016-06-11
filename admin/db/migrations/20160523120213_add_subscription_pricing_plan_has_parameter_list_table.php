<?php

use Phinx\Migration\AbstractMigration;

class AddSubscriptionPricingPlanHasParameterListTable extends AbstractMigration
{
    public function up()
    {
        // create the table
        $table = $this->table('subscription_pricing_plan_has_parameter_list');
        $table->addColumn('subscription_pricing_plan_id', 'integer')
              ->addColumn('parameter_list_id', 'integer')
              ->addForeignKey('subscription_pricing_plan_id', 'subscription_pricing_plan', 'id', [ 'delete' => 'CASCADE', 'update' => 'CASCADE' ])
              ->addForeignKey('parameter_list_id', 'subscription_pricing_plan_parameter_list', 'id', [ 'delete' => 'CASCADE', 'update' => 'CASCADE' ])  
              ->addIndex(['subscription_pricing_plan_id', 'parameter_list_id'], ['unique' => true ])
              ->create();  
        
    }
    
    public function down()
    {
        $this->dropTable('subscription_pricing_plan_has_parameter_list');
    }
}
