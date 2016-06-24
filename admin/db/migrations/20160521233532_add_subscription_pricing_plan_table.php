<?php

use Phinx\Migration\AbstractMigration;

class AddSubscriptionPricingPlanTable extends AbstractMigration {

    public function up() {
        // create the table
        $table = $this->table('subscription_pricing_plan');
        $table->addColumn('name', 'string', ['null' => false, 'default' => 'NAME ME'])
            ->addColumn('enable_trial_account', 'boolean', ['null' => false, 'default' => false])
            ->addColumn('enable_discount_on_upgrade', 'boolean', ['null' => false, 'default' => false])
            ->addColumn('base_price', 'decimal', ['null' => false, 'default' => 0.00, 'precision' => 10, 'scale' => 2])
            ->addColumn('cost_per_sms', 'decimal', ['null' => false, 'default' => false, 'precision' => 10, 'scale' => 2])
            ->addColumn('max_messages_on_trial_account', 'integer', ['null' => false, 'default' => 0])
            ->addColumn('updgrade_discount', 'decimal', ['null' => false, 'default' => 0.00, 'precision' => 10, 'scale' => 2])
            ->addColumn('charge_per_sms', 'decimal', ['null' => false, 'default' => 0.00, 'precision' => 10, 'scale' => 2])
            ->addColumn('max_sms_messages', 'integer', ['null' => false, 'default' => 0])
            ->addColumn('enable_annual_discount', 'boolean', ['null' => false, 'default' => false])
            ->addColumn('annual_discount', 'boolean', ['null' => false, 'default' => false])
            ->addColumn('pricing_details', 'text', ['null' => false, 'default' => ''])
            ->addColumn('user_id', 'integer', ['null' => false, 'limit' => 10, 'signed' => false ])
            ->addColumn('created_at', 'timestamp', [ 'null' => false, 'default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('updated_at', 'timestamp', [ 'null' => false])
            ->addColumn('deleted_at', 'timestamp', [ 'null' => false])
            ->addIndex(['name'], ['unique' => true, 'name' => 'idx_name'])
            ->addForeignKey('user_id', 'users', 'id', [ 'delete' => 'NO_ACTION', 'update' => 'NO_ACTION' ])
            ->create();
    }
    
    public function down() {
        $this->dropTable('subscription_pricing_plan');
    }

}
