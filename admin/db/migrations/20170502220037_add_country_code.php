<?php

use Phinx\Migration\AbstractMigration;

class AddCountryCode extends AbstractMigration
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
        $table->addColumn(
                'country_code',
                'string', [
                    'after' => 'deleted',
                    'null' => false,
                    'default' => '1',
                    ]
                )
              ->update();
    }

    public function down()
    {
        $table = $this->table('agency');
        $table->removeColumn('deactivated_with_agency')
              ->save();
    }
}
