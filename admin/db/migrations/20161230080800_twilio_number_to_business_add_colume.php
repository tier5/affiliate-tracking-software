<?php

use Phinx\Migration\AbstractMigration;

class TwilioNumberToBusinessAddColume extends AbstractMigration
{
    public function up() {
        $table = $this->table('twilio_number_to_business');
        $table->addColumn("parent_twilio_api_key", 'text', ['null' => false, 'default' => ''])
                ->addColumn("parent_twilio_auth_token", 'text', ['null' => false, 'default' => ''])
                ->addColumn('parent_user_id', 'integer', ['null' => false, 'default' => '0'])
                ->addColumn('purchased', 'integer', ['null' => false, 'default' => '0'])
                ->addColumn("twilio_purchase_token", 'text', ['null' => false, 'default' => ''])->update();

        
    }

    public function down() {
        $this->query("ALTER TABLE twilio_number_to_business
          DROP COLUMN `parent_twilio_api_key`,`parent_twilio_auth_token`,`parent_user_id`,`purchased`,`twilio_purchase_token`");

    }
}
