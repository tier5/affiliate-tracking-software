<?php

use Phinx\Migration\AbstractMigration;

class AddAgencySubscriptions extends AbstractMigration
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
        // create the table
        $table = $this->table('agency_subscription_plan');
        $table->addColumn('pricing_plan_id', 'integer', [ 'signed' => false, 'limit' => 10, 'null' => false, 'default' => 0])
              ->addColumn('agency_id', 'integer', [ 'signed' => false, 'limit' => 10, 'null' => false, 'default' => 0])
              ->addColumn('created_at', 'timestamp', [ 'null' => false, 'default' => 'CURRENT_TIMESTAMP'])
              ->addColumn('updated_at', 'timestamp', [ 'null' => false ])
              ->addColumn('deleted_at', 'timestamp', [ 'null' => false ])
              ->create();
              
        $table = $this->table('agency_pricing_plan');
        $table->addColumn('name', 'string', ['null' => false, 'default' => '', 'limit' => 255])
              ->addColumn('price_per_business', 'decimal', ['null' => false, 'default' => 0.00, 'precision' => 10, 'scale' => 2])
              ->addColumn('number_of_businesses', 'integer', [ 'signed' => false, 'limit' => 10, 'null' => false, 'default' => 0])
              ->addColumn('created_at', 'timestamp', [ 'null' => false, 'default' => 'CURRENT_TIMESTAMP'])
              ->addColumn('updated_at', 'timestamp', [ 'null' => false ])
              ->addColumn('deleted_at', 'timestamp', [ 'null' => false ])
              ->addIndex(['name'], ['unique' => true])
              ->create();

        // Starting subscriptions
        $tStartingPlans = [
            [
                'name'                      => 'Ten for ten',
                'price_per_business'        => 10.00,
                'number_of_businesses'      => 10,
            ],
            [
                'name'                      => 'Twenty for eight',
                'price_per_business'        => 8.00,
                'number_of_businesses'      => 20,
            ],
        ];

        $this->insert('agency_pricing_plan', $tStartingPlans);
    }

    public function down()
    {
        $this->dropTable('agency_pricing_plan');
        $this->dropTable('agency_subscription_plan');
    }
}
