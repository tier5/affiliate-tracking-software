<?php

use Phinx\Migration\AbstractMigration;

class ChangeAnnualDiscountColumnType extends AbstractMigration
{
    /**
     * Migrate Up.
     */
    public function up()
    {
        $users = $this->table('subscription_pricing_plan');
        $users->changeColumn('annual_discount', 'decimal', ['null' => false, 'default' => 0.00, 'precision' => 10, 'scale' => 2])
              ->save();
    }
    
    /**
     * Migrate Down.
     */
    public function down()
    {
        $users = $this->table('subscription_pricing_plan');
        $users->changeColumn('annual_discount', 'boolean', ['null' => false, 'default' => false])
              ->save();
    }
}
