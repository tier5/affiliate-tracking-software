<?php

use Phinx\Migration\AbstractMigration;

class AdjustInitialAgencyPlans extends AbstractMigration
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
        $this->execute("TRUNCATE agency_pricing_plan");

        // Starting subscriptions
        $tStartingPlans = [
            [
                'name'                      => '0 Ten for ten',
                'initial_fee'               => '0',
                'price_per_business'        => 10.00,
                'number_of_businesses'      => 10,
            ],
            [
                'name'                      => '0 Twenty for eight',
                'initial_fee'               => '0',
                'price_per_business'        => 8.00,
                'number_of_businesses'      => 20,
            ],
            [
                'name'                      => '97 Ten for ten',
                'initial_fee'               => '97',
                'price_per_business'        => 10.00,
                'number_of_businesses'      => 10,
            ],
            [
                'name'                      => '97 Twenty for eight',
                'initial_fee'               => '97',
                'price_per_business'        => 8.00,
                'number_of_businesses'      => 20,
            ],
            [
                'name'                      => '197 Ten for ten',
                'initial_fee'               => '197',
                'price_per_business'        => 10.00,
                'number_of_businesses'      => 10,
            ],
            [
                'name'                      => '197 Twenty for eight',
                'initial_fee'               => '197',
                'price_per_business'        => 8.00,
                'number_of_businesses'      => 20,
            ],
        ];

        $this->insert('agency_pricing_plan', $tStartingPlans);
    }

    public function down() {
        $this->execute("TRUNCATE agency_pricing_plan");

        // Starting subscriptions
        $tStartingPlans = [
            [
                'name'                      => '0 Ten for ten',
                'initial_fee'               => '0',
                'price_per_business'        => 10.00,
                'number_of_businesses'      => 10,
            ],
            [
                'name'                      => '0 Twenty for eight',
                'initial_fee'               => '0',
                'price_per_business'        => 8.00,
                'number_of_businesses'      => 20,
            ],
            [
                'name'                      => '197 Ten for ten',
                'initial_fee'               => '197',
                'price_per_business'        => 10.00,
                'number_of_businesses'      => 10,
            ],
            [
                'name'                      => '197 Twenty for eight',
                'initial_fee'               => '197',
                'price_per_business'        => 8.00,
                'number_of_businesses'      => 20,
            ],
            [
                'name'                      => '297 Ten for ten',
                'initial_fee'               => '297',
                'price_per_business'        => 10.00,
                'number_of_businesses'      => 10,
            ],
            [
                'name'                      => '297 Twenty for eight',
                'initial_fee'               => '297',
                'price_per_business'        => 8.00,
                'number_of_businesses'      => 20,
            ],
        ];

        $this->insert('agency_pricing_plan', $tStartingPlans);
    }
}
