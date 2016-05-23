<?php

use Phinx\Migration\AbstractMigration;

class AddSubscriptionProfileHasParameterListTable extends AbstractMigration
{
    public function up()
    {
        // create the table
        $table = $this->table('subscription_profile_has_parameter_list');
        $table->addColumn('subscription_profile_id', 'integer')
              ->addColumn('parameter_list_id', 'integer')
              ->addForeignKey('subscription_profile_id', 'subscription_profile', 'id', [ 'delete' => 'CASCADE', 'update' => 'CASCADE' ])
              ->addForeignKey('parameter_list_id', 'subscription_profile_parameter_list', 'id', [ 'delete' => 'CASCADE', 'update' => 'CASCADE' ])  
              ->create();  
        
        
    }
    
    public function down()
    {
        $this->dropTable('subscription_profile_has_parameter_list');
    }
}
