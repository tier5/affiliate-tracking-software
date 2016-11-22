<?php

use Phinx\Migration\AbstractMigration;

class FixSharingCodes extends AbstractMigration
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
        // *NOTE ROLLBACK PROBLEM:  There is a risk of data loss if there is a rollback feature.  The problem is rolling forward could be a problem if migrations are ever rolled back before this.  Going to need to manually roll forward for this one.
        $table = $this->table('sharing_code');
        $this->query("ALTER TABLE `sharing_code` CHANGE `agency_id` `business_id` INT");
        $this->query("ALTER TABLE `sharing_code` CHANGE `sharecode` `sharecode` VARCHAR(255)");

        $this->query("ALTER TABLE `agency` CHANGE `viral_sharing_code` `viral_sharing_code` VARCHAR(255)");

        $table->addIndex(['sharecode', 'business_id'], ['unique' => true]);
        $table->removeIndex(['sharecode']);
    }
    public function down()
    {
        // *NOTE ROLLBACK PROBLEM:  There is a risk of data loss if there is a rollback feature.  The problem is rolling forward could be a problem if migrations are ever rolled back before this.  Going to need to manually roll forward for this one.
    }
}
