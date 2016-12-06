<?php

use Phinx\Migration\AbstractMigration;

class AddAddressToLocationReviewSite extends AbstractMigration
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
        $table = $this->table('location_review_site');
        $table->addColumn('address', 'string', array('limit' => 255, 'null' => true))
        ->addColumn('postal_code', 'string', array('limit' => 255, 'null' => true))
        ->addColumn('locality', 'string', array('limit' => 255, 'null' => true))
        ->addColumn('country', 'string', array('limit' => 255, 'null' => true))
        ->addColumn('state_province', 'string', array('limit' => 255, 'null' => true))
        ->addColumn('phone', 'string', array('limit' => 255, 'null' => true))

            ->update();
    }

    public function down()
    {
        $this->query("ALTER TABLE location_review_site 
          DROP address, 
          DROP postal_code, 
          DROP locality, 
          DROP country, 
          DROP state_province, 
          DROP phone
        ");
    }
}
