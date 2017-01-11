<?php

use Phinx\Migration\AbstractMigration;

class AgentTwilioConfig extends AbstractMigration
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

    }
    public function up() {
        $table = $this->table('agent_twilio_config');
        $table->addColumn('connect_app_sid', 'text', ['null' => false, 'default' => ''])
            ->addColumn('parent_token', 'text', ['null' => false, 'default' => ''])
            ->addColumn('agent_sid', 'text', ['null' => false, 'default' => ''])
            ->addColumn('agent_account_name', 'text', ['null' => false, 'default' => ''])
            ->addColumn('agent_id', 'integer', ['null' => false, 'default' => 0])
            ->addColumn('created', 'datetime', [ 'null' => false])
            ->addColumn('updated', 'datetime', [ 'null' => false])
            ->create();
    }
    
    public function down() {
        $this->dropTable('agent_twilio_config');
    }
}
