<?php

namespace Vokuro\Services;

use Vokuro\Models\SubscriptionPlan;
use Vokuro\Models\SubscriptionPricingPlan;

class SubscriptionManager {
    
    static $PAYMENT_PLAN_FREE = 'FR';
    static $PAYMENT_PLAN_TRIAL = 'TR';
    static $PAYMENT_PLAN_MONTHLY = 'M';
    static $PAYMENT_PLAN_YEARLY = 'Y';
    
    static $TRIAL_PLAN_LOCATIONS = 1;
    static $TRIAL_PLAN_MESSAGES = 100;
    
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
            $paymentPlan = SubscriptionManager::$PAYMENT_PLAN_FREE;
            
        } else {
            
            $subscriptionPricingPlan = SubscriptionPricingPlan::query()  
                ->where("id = :id:")
                ->bind(["id" => $newSubscriptionParameters['pricingPlanId']])
                ->execute()
                ->getFirst();
            $pricingPlanId = $subscriptionPricingPlan->id;   
            if ($subscriptionPricingPlan->getTrialPeriod()) {
                $paymentPlan = SubscriptionManager::$PAYMENT_PLAN_TRIAL;  
                $locations = $subscriptionPricingPlan->getMaxLocationsOnFreeAccount();
                $smsMessagesPerLocation = $subscriptionPricingPlan->getMaxMessagesOnFreeAccount();
            } else {
                $paymentPlan = SubscriptionManager::$PAYMENT_PLAN_MONTHLY;;
                $locations = 0;
                $smsMessagesPerLocation = 0;;
            }
            
            
    static $TRIAL_PLAN_MESSAGES = 100;
    
            
        }
        
        // Subscription plan
        $subscriptionPlan = new SubscriptionPlan();
        $subscriptionPlan->setUserId(intval($userId));
        $subscriptionPlan->setLocations(intval($locations));
        $subscriptionPlan->setSmsMessagesPerLocation(intval($smsMessagesPerLocation));
        $subscriptionPlan->setPaymentPlan($paymentPlan);
        $subscriptionPlan->setSubscriptionPricingPlanId(intval($pricingPlanId));
        if (!$subscriptionPlan->create()) {
            return $subscriptionPlan->getMessages();
        }
        
        return true;
    }
    
    public function getUserSubscription($userId) {
        $subscriptionPlan = SubscriptionPlan::query()  
            ->where("user_id = :user_id:")
            ->bind(["user_id" => intval($userId)])
            ->execute()
            ->getFirst();
        if(!$subscriptionPlan) {
            return false;
        }
        return $subscriptionPlan->toArray();
    }
   
}
