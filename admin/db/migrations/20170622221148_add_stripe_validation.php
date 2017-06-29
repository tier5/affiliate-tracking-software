<?php

use Phinx\Migration\AbstractMigration;

class AddStripeValidation extends AbstractMigration
{
    public function up()
    {
        // create the table
        $table = $this->table('agency_stripe_validation');
        $table->addColumn('agency_id', 'integer', ['null' => false, 'default' => 0])
              ->addColumn('stripe_status', 'string', ['null' => false ])
              ->addColumn('action', 'integer', ['null' => false, 'default' => 0])
              ->addColumn('process_status', 'integer', ['null' => false, 'default' => 0])
              ->addColumn('stripe_subscription_id', 'string', ['null' => true, 'default' => ''])
              ->addColumn('stripe_customer_id', 'string', ['null' => true, 'default' => ''])
              ->create();
    }

    public function down()
    {
        $this->dropTable('agency_stripe_validation');
    }
}
