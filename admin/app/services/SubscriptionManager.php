<?php

namespace Vokuro\Services;

use Vokuro\Services\ServicesConsts;
use Vokuro\Models\SubscriptionPlan;
use Vokuro\Models\SubscriptionPricingPlan;
use Phalcon\Mvc\Model\Transaction\Failed as TransactionFailed;
use Phalcon\Mvc\Model\Transaction\Manager as TransactionManager;
    
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
    
    public function createPricingProfile($parameters) {
        
        $status = false;
        
        try {
                
            $id = $this->createSubscriptionPricingProfile($parameters);
            if (!$id) {
                throw new \Exception();
            }
        
            if (!$this->appendPricingParameterLists($id, $parameters)) {
                throw new \Exception();
            }
            
            $status = true;
        
        } catch(Exception $e) {}
        
        return $status;
        
    }
    
    private function createSubscriptionPricingProfile($parameters) {
        
        $subscriptionPricingProfile = new SubscriptionPricingProfile();
        $subscriptionPricingProfile->user_id = $parameters["userId"];
        $subscriptionPricingProfile->name = $parameters["name"];                               
        $subscriptionPricingProfile->enable_trial_account = $parameters["enableTrialAccount"];
        $subscriptionPricingProfile->enable_discount_on_upgrade = $parameters["enableDiscountOnUpgrade"];
        $subscriptionPricingProfile->base_price = $parameters["basePrice"];
        $subscriptionPricingProfile->cost_per_sms = $parameters["costPerSms"];
        $subscriptionPricingProfile->max_messages_on_trial_account = $parameters["maxMessagesOnTrialAccount"];
        $subscriptionPricingProfile->updgrade_discount = $parameters["upgradeDiscount"];
        $subscriptionPricingProfile->charge_per_sms = $parameters["chargePerSms"];
        $subscriptionPricingProfile->max_sms_messages = $parameters["maxSmsMessages"];
        $subscriptionPricingProfile->enable_annual_discount = $parameters["enableAnnualDiscount"];
        $subscriptionPricingProfile->annual_discount = $parameters["annualDiscount"];
        $subscriptionPricingProfile->pricing_details = $parameters["pricingDetails"];
        if (!$subscriptionPricingProfile->create()) {
            return false;
        }
        
        return true;
    }
        
    private function appendPricingParameterLists($id, $parameters) {
        
        foreach($parameters as $segment => $params) {    
            
            if(substr($segment,0,7) !== "segment") {
                continue;
            }
            
            $pricingParameterList = $this->createPricingParameterList($params);
            if(!$pricingParameterList) {
                return false;
            }
            
        }
        
        return false;
    }
    
    private function createPricingParameterList($id, $parameters) {
        
        $subscriptionPricingPlanParameterList = new SubscriptionPricingPlanParameterList();
        $subscriptionPricingPlanParameterList->subscription_pricing_plan_id = $id;
        $subscriptionPricingPlanParameterList->min_locations = $parameters['minLocations'];
        $subscriptionPricingPlanParameterList->max_locations = $parameters['maxLocations'];
        $subscriptionPricingPlanParameterList->location_discount_percentage = $parameters['locationDiscountPercentage'];
        $subscriptionPricingPlanParameterList->base_price = $parameters['basePrice'];
        $subscriptionPricingPlanParameterList->sms_charge = $parameters['smsCharge'];
        $subscriptionPricingPlanParameterList->total_price = $parameters['totalPrice'];
        $subscriptionPricingPlanParameterList->location_discount = $parameters['locationDiscount'];
        $subscriptionPricingPlanParameterList->upgrade_discount = $parameters['upgradeDiscount'];
        $subscriptionPricingPlanParameterList->sms_messages = $parameters['smsMessages'];
        $subscriptionPricingPlanParameterList->sms_cost = $parameters['smsCost'];
        $subscriptionPricingPlanParameterList->profit_per_location = $parameters['profitPerLocation'];
        if($subscriptionPricingPlanParameterList->create()) {
            return false;
        }
        
        return true;
    }
    
}
