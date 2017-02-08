<?php

use Phinx\Migration\AbstractMigration;

class AddCurrencyToSubscription extends AbstractMigration
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
    public function up()
    {
        $table = $this->table('subscription_pricing_plan');
        $table->addColumn(
                'currency',
                'string', [
                    'after' => 'enable_discount_on_upgrade',
                    'null' => false,
                    'default' => 'USD',
                    'limit' => 3
                    ]
                )
              ->update();
    }

    public function down()
    {
        $table = $this->table('subscription_pricing_plan');
        $table->removeColumn('currency')
              ->save();
    }
}
