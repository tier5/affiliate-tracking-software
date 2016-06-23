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
              ->addColumn('location_discount_percentage', 'decimal', ['null' => false, 'default' => 0.00, 'precision' => 10, 'scale' => 2])
              ->addColumn('base_price', 'decimal', ['null' => false, 'default' => 0.00, 'precision' => 10, 'scale' => 2])
              ->addColumn('sms_charge', 'decimal', ['null' => false, 'default' => 0.00, 'precision' => 10, 'scale' => 2])
              ->addColumn('total_price', 'decimal', ['null' => false, 'default' => 0.00, 'precision' => 10, 'scale' => 2])
              ->addColumn('location_discount', 'decimal', ['null' => false, 'default' => 0.00, 'precision' => 10, 'scale' => 2])
              ->addColumn('upgrade_discount', 'decimal', ['null' => false, 'default' => 0.00, 'precision' => 10, 'scale' => 2])
              ->addColumn('sms_messages', 'integer', ['null' => false, 'default' => 0])
              ->addColumn('sms_cost', 'decimal', ['null' => false, 'default' => 0.00, 'precision' => 10, 'scale' => 2])
              ->addColumn('profit_per_location', 'decimal', ['null' => false, 'default' => 0.00, 'precision' => 10, 'scale' => 2])            
              ->addColumn('subscription_pricing_plan_id', 'integer', ['null' => true])
              ->addColumn('created_at', 'timestamp', ['null' => false, 'default' => 'CURRENT_TIMESTAMP' ])
              ->addColumn('updated_at', 'timestamp', ['null' => false ])
              ->addColumn('deleted_at', 'timestamp', ['null' => false ])
              ->addForeignKey('subscription_pricing_plan_id', 'subscription_pricing_plan', 'id', [ 'delete' => 'CASCADE', 'update' => 'CASCADE'])
              ->create();
    }
    
    public function down()
    {
        $this->dropTable('subscription_pricing_plan_parameter_list');
    }
}

