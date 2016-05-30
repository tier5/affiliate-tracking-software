<?php

use Phinx\Migration\AbstractMigration;

class BaseMigration extends AbstractMigration
{
    public function up()
    {   
        $options = $this->adapter->getOptions();
        
        $migrationCommand = 
            "mysql -h {$options['host']} -u {$options['user']} -p{$options['pass']} {$options['name']}".
            " < db/migrations/20160521233500_baseline.sql";
        
        exec($migrationCommand);
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $options = $this->adapter->getOptions();
    }
}
