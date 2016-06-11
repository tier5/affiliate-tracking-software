<?php

use Phinx\Migration\AbstractMigration;

class AddDefaultSubscriptionPricingPlan extends AbstractMigration
{
    public function up()
    {
        // Get the joining table
        $joining_table = $this->table('subscription_pricing_plan_has_parameter_list');
        
        // Add the default subscription pricing_plan
        $table = $this->table('subscription_pricing_plan');
        $table->insert(
            [ 
                'name' => 'Review Velocity - Default',
                'enable_free_account' => true,
                'enable_discount_on_upgrade' => true,
                'base_price' => 49.00,
                'cost_per_sms' => 0.0075,
                'annual_plan_discount' => 0.1,
                'trial_period' => false,
                'max_sms_during_trial_period' => 10,
                'max_messages_on_free_account' => 100,
                'max_locations_on_free_account' => 1,
                'updgrade_discount' => 0.10,
                'charge_per_sms' => 0.10,
                'max_sms_messages' => 1000,
                'trial_number_of_days' => 5,
                'collect_credit_card_on_sign_up' => true,
                'pricing_details' => ''
            ]
        );
        $table->saveData();
        
        // Subscription pricing_plan id
        $subscriptionPricingPlanId = $this->adapter->getConnection()->lastInsertId();

        // Add the default subscription pricing_plan parameter collection
        $this->insert(
           'subscription_pricing_plan_parameter_list', 
            [
                [
                  'min_locations' => 1,
                  'max_locations' => 10,
                  'discount' => 0.00
                ],
                [
                  'min_locations' => 11,
                  'max_locations' => 20,
                  'discount' => 0.05
                ],
                [
                  'min_locations' => 21,
                  'max_locations' => 30,
                  'discount' => 0.10
                ],
                [
                  'min_locations' => 31,
                  'max_locations' => 40,
                  'discount' => 0.15
                ],
                [
                  'min_locations' => 41,
                  'max_locations' => 50,
                  'discount' => 0.20
                ],
                [
                  'min_locations' => 51,
                  'max_locations' => 60,
                  'discount' => 0.25
                ],
                [
                  'min_locations' => 61,
                  'max_locations' => 70,
                  'discount' => 0.30
                ],
                [
                  'min_locations' => 71,
                  'max_locations' => 80,
                  'discount' => 0.35
                ],
                [
                  'min_locations' => 81,
                  'max_locations' => 90,
                  'discount' => 0.40
                ],
                [
                  'min_locations' => 91,
                  'max_locations' => 100,
                  'discount' => 0.45
                ],
            ]
        );
         
        $rows = $this->fetchAll('SELECT * FROM subscription_pricing_plan_parameter_list');
        foreach ($rows as $row) {
            // Add the default subscription pricing_plan
            $joining_table->insert([ 'subscription_pricing_plan_id' => $subscriptionPricingPlanId, 'parameter_list_id' => $row['id'] ]);
        }
        $joining_table->saveData();
        
    }
    
    public function down() 
    {
        $this->execute('SET foreign_key_checks = 0'); 
        $this->execute('DELETE FROM subscription_pricing_plan_parameter_list');
        $this->execute('DELETE FROM subscription_pricing_plan_has_parameter_list');
        $this->execute('DELETE FROM subscription_pricing_plan');
        $this->execute('SET foreign_key_checks = 1');
    }
    
}
