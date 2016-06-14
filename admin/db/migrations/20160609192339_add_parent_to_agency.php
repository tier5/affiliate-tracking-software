<?php

use Phinx\Migration\AbstractMigration;

class AddParentToAgency extends AbstractMigration
{
    public function up()
    {
        // create the table
        $table = $this->table('agency');
        $table->addColumn('parent_id', 'integer', ['null' => false, 'default' => 0])
            ->save();
    }

    public function down()
    {
        $this->query("ALTER TABLE agency DROP COLUMN parent_id");
    }
}
