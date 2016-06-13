<?php

namespace Vokuro\Services;

use Vokuro\Services\ServicesConsts;
use Vokuro\Models\SubscriptionPlan;
use Vokuro\Models\SubscriptionPricingPlan;

class SubscriptionManager extends BaseService {
    
    function __construct($config) {
        parent::__construct($config);
    }
    
    public function getSubscriptionPricingPlans() {
        return $subscriptionPricingPlans = SubscriptionPricingPlan::find();
    }
    
    public function createSubscriptionPlan($newSubscriptionParameters) {
        
        $userId = $newSubscriptionParameters['userAccountId'];
        
        /* Configure subscription parameters */
        if ($newSubscriptionParameters['pricingPlanId'] === 'Unpaid') {
            
            $pricingPlan = SubscriptionPricingPlan::findFirst();   // Default pricing plan is always first in the table
            $pricingPlanId = $pricingPlan->id;
            $locations = $newSubscriptionParameters['freeLocations'];
            $smsMessagesPerLocation = $newSubscriptionParameters['freeSmsMessagesPerLocation'];
            $paymentPlan = ServicesConsts::$PAYMENT_PLAN_FREE;
            
        } else {
            
            $subscriptionPricingPlan = SubscriptionPricingPlan::query()  
                ->where("id = :id:")
                ->bind(["id" => $newSubscriptionParameters['pricingPlanId']])
                ->execute()
                ->getFirst();
            $pricingPlanId = $subscriptionPricingPlan->id;   
            if ($subscriptionPricingPlan->getTrialPeriod()) {
                $paymentPlan = ServicesConsts::$PAYMENT_PLAN_TRIAL;  
                $locations = $subscriptionPricingPlan->getMaxLocationsOnFreeAccount();
                $smsMessagesPerLocation = $subscriptionPricingPlan->getMaxMessagesOnFreeAccount();
            } else {
                $paymentPlan = ServicesConsts::$PAYMENT_PLAN_MONTHLY;;
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
            return $subscriptionPlan->getMessages();
        }
        
        return true;
    }
    
    public function getSubscriptionPlan($userId) {
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
    
    public function getPricingPlan($pricingPlanId) {
        $subscriptionPricingPlan = SubscriptionPricingPlan::query()  
            ->where("id = :id:")
            ->bind(["id" => intval($pricingPlanId)])
            ->execute()
            ->getFirst();
        if(!$subscriptionPricingPlan) {
            return false;
        }
        return $subscriptionPricingPlan->toArray();
    }
    
    public function getPricingPlanByName($pricingPlanName) {
        $subscriptionPricingPlan = SubscriptionPricingPlan::query()  
            ->where("name = :name:")
            ->bind(["name" => $pricingPlanName])
            ->execute()
            ->getFirst();
        if(!$subscriptionPricingPlan) {
            return false;
        }
        return $subscriptionPricingPlan->toArray();
    }
   
}
