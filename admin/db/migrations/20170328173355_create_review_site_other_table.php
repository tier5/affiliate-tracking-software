<?php

use Phinx\Migration\AbstractMigration;

class CreateReviewSiteOtherTable extends AbstractMigration
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
        $reviewSiteOther = $this->table(
                                        'review_site_other', 
                                        array('id' => false, 'primary_key' => array('review_site_id'))
                                        );
        $reviewSiteOther->addColumn('review_site_id', 'integer', array('limit' => 11))
              ->addColumn('name', 'string', array('limit' => 70))
              ->addColumn('logo_path', 'string', array('limit' => 255))
              ->addColumn('icon_path', 'string', array('limit' => 255))
              ->addColumn('base_url', 'string', array('limit' => 255))
              ->create();
    }
}
