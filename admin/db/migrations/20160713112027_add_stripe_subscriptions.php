<?php

use Phinx\Migration\AbstractMigration;

class AddStripeSubscriptions extends AbstractMigration
{
    public function up() {
        $table = $this->table('stripe_subscriptions');
        $table->addColumn('stripe_customer_id', 'string')
            ->addColumn('user_id', 'integer', [ 'signed' => false, 'limit' => 10, 'null' => false, 'default' => 0])
            ->addColumn('stripe_subscription_id', 'string')
            ->create();

    }
    
    public function down() {
        $this->dropTable('stripe_subscriptions');
    }
}
