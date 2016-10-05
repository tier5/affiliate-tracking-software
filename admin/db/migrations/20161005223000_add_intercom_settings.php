<?php

use Phinx\Migration\AbstractMigration;

class AddIntercomSettings extends AbstractMigration
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
        $table = $this->table('agency');
        $table->addColumn('intercom_api_id', 'string', array('limit' => 255,'null'=>true))
            ->addColumn('intercom_security_hash', 'string', array('limit' => 255,'null'=>true))
            ->update();
    }

    public function down()
    {
        $this->query("ALTER TABLE agency DROP COLUMN intercom_security_hash");
        $this->query("ALTER TABLE agency DROP COLUMN intercom_api_id");
    }
}
