<?php

use Phinx\Migration\AbstractMigration;

class AddAgencyDetails extends AbstractMigration
{
    public function up()
    {
        $table = $this->table('agency');
        $table->addColumn('address2', 'string', ['null' => false, 'default' => ""])
            ->addColumn('website', 'string', ['null' => false, 'default' => ""])
            ->addColumn('email_from_name', 'string', ['null' => false, 'default' => ""])
            ->addColumn('email_from_address', 'string', ['null' => false, 'default' => ""])
            ->save();

        $table = $this->table('users');
        $table->addColumn('last_name', 'string', ['null' => false, 'default' => ""])
            ->save();
    }


    public function down()
    {
        $this->query("ALTER TABLE agency 
          DROP COLUMN address2,
          DROP COLUMN website,
          DROP COLUMN email_from_name,
          DROP COLUMN email_from_address
        ");

        $this->query("ALTER TABLE users 
          DROP COLUMN last_name
        ");
    }
}
