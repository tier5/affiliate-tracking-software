<?php

use Phinx\Migration\AbstractMigration;

class InsertOtherSiteRecord extends AbstractMigration
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
        // inserting only one row
        $singleRow = [
            'review_site_id' => 0,
            'name'  => 'Other',
            'logo_path' => '/img/logo/icon-other.png',
            'icon_path' => '/img/logo/icon-other.png'
        ];

        $table = $this->table('review_site');
        $table->insert($singleRow);
        $table->saveData();
    }
}
