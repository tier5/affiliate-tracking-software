<?php

use Phinx\Migration\AbstractMigration;

class AddPrimaryKeyReviewInviteReviewSite extends AbstractMigration
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
        $this->query("ALTER TABLE review_invite_review_site ADD COLUMN `id` int(10) UNSIGNED PRIMARY KEY AUTO_INCREMENT FIRST");
    }

    public function down() {
        $this->query("ALTER TABLE review_invite_review_site DROP COLUMN `id`");
    }
}
