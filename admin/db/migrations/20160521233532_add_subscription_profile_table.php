<?php

use Phinx\Migration\AbstractMigration;

class AddSubscriptionProfileTable extends AbstractMigration
{
    public function up()
    {
        // create the table
        $table = $this->table('subscription_profile');
        $table->addColumn('enable_free_account', 'boolean', ['null' => false, 'default' => false])
            ->addColumn('enable_discount_on_upgrade', 'boolean', ['null' => false, 'default' => false])
            ->addColumn('base_price', 'decimal', ['null' => false, 'default' => 0.00, 'precision' => 10, 'scale' => 2])
            ->addColumn('cost_per_sms', 'decimal', ['null' => false, 'default' => false, 'precision' => 10, 'scale' => 2])
            ->addColumn('trial_period', 'boolean', ['null' => false, 'default' => false ])
            ->addColumn('max_sms_during_trial_period', 'integer', ['null' => false, 'default' => 0])
            ->addColumn('max_messages_on_free_account', 'integer', ['null' => false, 'default' => 0])
            ->addColumn('updgrade_discount', 'decimal', ['null' => false, 'default' => 0.00, 'precision' => 10, 'scale' => 2])
            ->addColumn('charge_per_sms', 'decimal', ['null' => false, 'default' => 0.00, 'precision' => 10, 'scale' => 2])
            ->addColumn('max_sms_messages', 'integer', ['null' => false, 'default' => 0])
            ->addColumn('trial_number_of_days', 'integer', ['null' => false, 'default' => 0])
            ->addColumn('collect_credit_card_on_sign_up', 'boolean', ['null' => false, 'default' => false])
            ->addColumn('pricing_details', 'text', ['null' => false, 'default' => ''])
            ->addColumn('agency_id', 'integer', ['null' => true ])
            ->addColumn('created_at', 'datetime', ['null' => false ])
            ->addColumn('updated_at', 'datetime', ['null' => false ])
            ->addColumn('deleted_at', 'datetime', ['null' => false ])
            ->addForeignKey('agency_id', 'agency', 'agency_id', [ 'delete' => 'CASCADE', 'update' => 'CASCADE' ]) 
            ->create();
    }
    
    public function down()
    {
        $this->dropTable('subscription_profile');
    }
}

