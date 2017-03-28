<?php

use Phinx\Migration\AbstractMigration;

class AddTrialSubToAgency extends AbstractMigration
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
        $table = $this->table('agency_pricing_plan');
        $table->addColumn(
                'trial_period',
                'integer', [
                    'after' => 'number_of_businesses',
                    'null' => false,
                    'default' => 0,
                    ]
                )
              ->update();

        $tNewPlan = [
            [
                'id'                        => 7, // It is important to keep the IDs for these records the same across all databases!
                'name'                      => '0 Ten for ten 14 Trial',
                'initial_fee'               => '0',
                'price_per_business'        => 10.00,
                'number_of_businesses'      => 10,
                'trial_period'              => 14,
            ]
        ];

        $this->insert('agency_pricing_plan', $tNewPlan);
    }

    public function down()
    {
        $table = $this->table('agency_pricing_plan');
        $table->removeColumn('trial_period')
              ->update();

        $this->execute("DELETE FROM agency_pricing_plan WHERE id=7");
    }
}
