<?php

use Phinx\Migration\AbstractMigration;

class AddBusinessSubscriptionInvitationTable extends AbstractMigration
{
    public function up()
    {
        // create the table
        $table = $this->table('business_subscription_invitation');
        $table->addColumn('business_subscription_plan_id', 'integer', ['null' => false, 'default' => 0])
            ->addColumn('user_id', 'integer', ['null' => false, 'limit' => 10, 'signed' => false ])  
            ->addColumn('token', 'string', ['null' => false, 'default' => 'none'])
            ->addColumn('created_at', 'timestamp', [ 'null' => false, 'default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('updated_at', 'timestamp', [ 'null' => false ])
            ->addColumn('deleted_at', 'timestamp', [ 'null' => false ])
            ->addForeignKey('user_id', 'users', 'id', [ 'delete' => 'CASCADE', 'update' => 'CASCADE' ])
            ->addForeignKey('business_subscription_plan_id', 'business_subscription_plan', 'id', [ 'delete' => 'CASCADE', 'update' => 'CASCADE' ])
            ->create();
    }
    
    public function down()
    {
        $this->dropTable('business_subscription_invitation');
    }
}
