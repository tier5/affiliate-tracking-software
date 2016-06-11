<?php

use Phinx\Migration\AbstractMigration;

class AddSubscriptionPricingPlanParameterListTable extends AbstractMigration
{
    public function up()
    {
        // create the table
        $table = $this->table('subscription_pricing_plan_parameter_list');
        $table->addColumn('min_locations', 'integer', ['null' => false, 'default' => 0])
              ->addColumn('max_locations', 'integer', ['null' => false, 'default' => 0])
              ->addColumn('discount', 'decimal', ['null' => false, 'default' => 0.00, 'precision' => 10, 'scale' => 2])
              ->addColumn('created_at', 'timestamp', ['null' => false, 'default' => 'CURRENT_TIMESTAMP' ])
              ->addColumn('updated_at', 'timestamp', ['null' => false ])
              ->addColumn('deleted_at', 'timestamp', ['null' => false ])
              ->create();
    }
    
    public function down()
    {
        $this->dropTable('subscription_pricing_plan_parameter_list');
    }
}

