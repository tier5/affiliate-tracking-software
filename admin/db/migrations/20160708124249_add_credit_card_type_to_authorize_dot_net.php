<?php

use Phinx\Migration\AbstractMigration;

class AddCreditCardTypeToAuthorizeDotNet extends AbstractMigration
{
    /**
     * Migrate Up.
     */
    public function up()
    {
        $table = $this->table('authorize_dot_net');
        $table->addColumn('credit_card_type', 'string', ['null' => false, 'after' => 'subscription_id', 'default' => ''])
            ->update();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $table = $this->table('authorize_dot_net');
        $table->removeColumn('credit_card_type')
            ->update();            
    }
}
