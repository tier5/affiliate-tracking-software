<?php

namespace Vokuro\Services;

use Vokuro\Models\SubscriptionPlan;
use Vokuro\Models\SubscriptionPricingPlan;

class SubscriptionManager {
    
    public function getSubscriptionPricingPlans() {
        return $subscriptionPricingPlans = SubscriptionPricingPlan::find();
    }
    
    public function createSubscriptionPlan($newSubscriptionParameters) {
        
        $userId = $newSubscriptionParameters['userAccountId'];
        
        /* REFACTOR:  Need to create a subscription service accessesible through DI, MT 2016 */ 
        if ($newSubscriptionParameters['pricingPlanId'] === 'Unpaid') {
            
            $pricingPlan = SubscriptionPricingPlan::findFirst();   // Default pricing plan is always first in the table
            $pricingPlanId = $pricingPlan->id;
            $locations = $newSubscriptionParameters['freeLocations'];
            $smsMessagesPerLocation = $newSubscriptionParameters['freeSmsMessagesPerLocation'];
            $paymentPlan = 'FR';
            
        } else {
            
            $subscriptionPricingPlan = SubscriptionPricingPlan::query()  
                ->where("id = :id:")
                ->bind(["id" => $newSubscriptionParameters['pricingPlanId']])
                ->execute()
                ->getFirst();
            $pricingPlanId = $subscriptionPricingPlan->id;   
            if ($subscriptionPricingPlan->getTrialPeriod()) {
                $paymentPlan = 'TR';
                $locations = 1;
                $smsMessagesPerLocation = $subscriptionPricingPlan->getMaxSmsDuringTrialPeriod();
            } else {
                $paymentPlan = 'M';
                $locations = 0;
                $smsMessagesPerLocation = 0;;
            }
            
        }
        
        // Subscription plan
        $subscriptionPlan = new SubscriptionPlan();
        $subscriptionPlan->setUserId(intval($userId));
        $subscriptionPlan->setLocations(intval($locations));
        $subscriptionPlan->setSmsMessagesPerLocation(intval($smsMessagesPerLocation));
        $subscriptionPlan->setPaymentPlan($paymentPlan);
        $subscriptionPlan->setSubscriptionPricingPlanId(intval($pricingPlanId));
        if (!$subscriptionPlan->create()) {
            foreach ($subscriptionPlan->getMessages() as $message) {
                echo "Message: ", $message->getMessage();
                echo "Field: ", $message->getField();
                echo "Type: ", $message->getType();
            }
            return false;
        }
        
        return true;
    }    
  
}
