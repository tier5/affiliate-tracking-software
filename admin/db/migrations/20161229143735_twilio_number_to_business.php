<?php

use Phinx\Migration\AbstractMigration;

class TwilioNumberToBusiness extends AbstractMigration
{
    public function up() {
        $table = $this->table('twilio_number_to_business');
        $table->addColumn('friendly_name', 'text', ['null' => false, 'default' => ''])
            ->addColumn('phone_number', 'text', ['null' => false, 'default' => ''])
            ->addColumn('buisness_id', 'integer', ['null' => false, 'default' => '0'])
            ->addColumn('created', 'datetime', [ 'null' => false])
            ->addColumn('updated', 'datetime', [ 'null' => false])
            ->create();
    }
    
    public function down() {
        $this->dropTable('twilio_number_to_business');
    }
}
