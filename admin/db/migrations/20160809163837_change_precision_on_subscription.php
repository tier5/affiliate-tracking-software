<?php

use Phinx\Migration\AbstractMigration;

class ChangePrecisionOnSubscription extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {

        $table = $this->table('subscription_pricing_plan');

        $table->changeColumn('base_price', 'decimal', ['null' => false, 'default' => 0.00, 'precision' => 10, 'scale' => 4])
            ->changeColumn('cost_per_sms', 'decimal', ['null' => false, 'default' => false, 'precision' => 10, 'scale' => 4])
            ->changeColumn('max_messages_on_trial_account', 'integer', ['null' => false, 'default' => 0])
            ->changeColumn('updgrade_discount', 'decimal', ['null' => false, 'default' => 0.00, 'precision' => 10, 'scale' => 4])
            ->changeColumn('charge_per_sms', 'decimal', ['null' => false, 'default' => 0.00, 'precision' => 10, 'scale' => 4]);
        $table->update();
    }
}
