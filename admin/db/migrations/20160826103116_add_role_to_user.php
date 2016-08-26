<?php

use Phinx\Migration\AbstractMigration;

class AddRoleToUser extends AbstractMigration
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

        $table = $this->table('users');
        $table->addColumn('role', 'string', array('limit' => 255,'null'=>true, 'default' => 'Admin'))->update();

        $this->query("UPDATE profiles SET name='User' WHERE name='Employee'");

    }

    public function down() {
        $this->query("UPDATE profiles SET name='Employee' WHERE name='User'");
        $this->query("ALTER TABLE users 
          DROP COLUMN role
        ");
    }
}
