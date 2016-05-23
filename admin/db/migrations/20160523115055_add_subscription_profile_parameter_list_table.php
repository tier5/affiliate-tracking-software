<?php

use Phinx\Migration\AbstractMigration;

class AddSubscriptionProfileParameterListTable extends AbstractMigration
{
    public function up()
    {
        // create the table
        $table = $this->table('subscription_profile_parameter_list');
        $table->addColumn('min_locations', 'integer', ['null' => false, 'default' => 0])
              ->addColumn('max_locations', 'integer', ['null' => false, 'default' => 0])
              ->addColumn('discount', 'decimal', ['null' => false, 'default' => 0.00, 'precision' => 10, 'scale' => 2])
              ->create();
    }
    
    public function down()
    {
        $this->dropTable('subscription_profile_parameter_list');
    }
}

