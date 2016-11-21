<?php

use Phinx\Migration\AbstractMigration;

class AddViralToSubscriptionPlans extends AbstractMigration
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
    public function up() {
        $table = $this->table('subscription_pricing_plan');
        $table->addColumn("is_viral", 'boolean', ['null' => false, 'default' => false])->update();

        $table = $this->table('sharing_code');
        $table->addColumn("subscription_id", "integer", ['null' => false, 'default' => false])
            ->addColumn("created_at", "datetime")
            ->update();
    }

    public function down() {
        $this->query("ALTER TABLE subscription_pricing_plan
          DROP COLUMN `is_viral`
        ");

        $this->query("ALTER TABLE sharing_code
          DROP COLUMN `subscription_id`
        ");

        $this->query("ALTER TABLE sharing_code
          DROP COLUMN `created_at`
        ");
    }
}
