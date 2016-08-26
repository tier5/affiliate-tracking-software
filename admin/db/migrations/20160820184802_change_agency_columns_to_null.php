<?php

use Phinx\Migration\AbstractMigration;

class ChangeAgencyColumnsToNull extends AbstractMigration
{
    public function up()
    {
        $table = $this->table('agency');
        $table->changeColumn('address2', 'string', ['null' => true, 'default' => ""])
            ->changeColumn('website', 'string', ['null' => true, 'default' => ""])
            ->changeColumn('email_from_name', 'string', ['null' => true, 'default' => ""])
            ->changeColumn('email_from_address', 'string', ['null' => true, 'default' => ""])
            ->update();

        $table = $this->table('users');
        $table->changeColumn('last_name', 'string', ['null' => true, 'default' => ""])
            ->update();
    }


    public function down()
    {
        $table = $this->table('agency');
        $table->changeColumn('address2', 'string', ['null' => false, 'default' => ""])
            ->changeColumn('website', 'string', ['null' => false, 'default' => ""])
            ->changeColumn('email_from_name', 'string', ['null' => false, 'default' => ""])
            ->changeColumn('email_from_address', 'string', ['null' => false, 'default' => ""])
            ->update();

        $table = $this->table('users');
        $table->changeColumn('last_name', 'string', ['null' => false, 'default' => ""])
            ->update();
    }
}
