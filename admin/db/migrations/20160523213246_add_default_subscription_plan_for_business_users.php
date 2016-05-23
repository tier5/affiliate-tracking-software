<?php

use Phinx\Migration\AbstractMigration;

class AddDefaultSubscriptionPlanForBusinessUsers extends AbstractMigration
{
    public function up()
    {
        $defaultSubscriptionProfile = $this->fetchRow('SELECT * FROM subscription_profile');
                
        $users = $this->fetchAll('SELECT * FROM users WHERE profilesId = 2');
        foreach ($users as $user) 
        {
            $subscriptionPlanTable = $this->table('subscription_plan');
            $subscriptionPlanTable->insert(
                [
                    'locations' => $defaultSubscriptionProfile['max_locations_on_free_account'],
                    'sms_messages_per_location' => $defaultSubscriptionProfile['max_messages_on_free_account'],
                    'payment_plan' => 'monthly',
                    'subscription_profile_id' => $defaultSubscriptionProfile['id'],
                    'user_id' => $user['id']
                ]
            );
            $subscriptionPlanTable->saveData();    
        }
    }
    
    public function down()
    {

    }
}
