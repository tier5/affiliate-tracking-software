<?php

namespace Vokuro\Services;

use Vokuro\ArrayException;
use Vokuro\Services\ServicesConsts;
use Vokuro\Models\BusinessSubscriptionPlan;
use Vokuro\Models\SubscriptionPricingPlan;
use Vokuro\Models\SubscriptionPricingPlanParameterList;
use Vokuro\Models\BusinessSubscriptionInvitation;
use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;
    
class SubscriptionManager extends BaseService {
    
    function __construct($config, $di) {
        parent::__construct($config, $di);
    }
    
    public function creditCardInfoRequired($session) { 
        
        $userManager = $this->di->get('userManager');
        $paymentService = $this->di->get('paymentService');
   
        $userId = $userManager->getUserId($session);
        $subscriptionPlan = $this->getSubscriptionPlan($userId);
        $payment_plan = $subscriptionPlan['payment_plan'];
        if (!$subscriptionPlan  || $payment_plan === ServicesConsts::$PAYMENT_PLAN_FREE) {
            return false;
        }
        
        $provider = ServicesConsts::$PAYMENT_PROVIDER_AUTHORIZE_DOT_NET;
        if ($userManager->isWhiteLabeledBusiness($session)) {
            $provider = ServicesConsts::$PAYMENT_PROVIDER_STRIPE;
        }  
        return !$paymentService->hasPaymentProfile([ 'userId' => $userId, 'provider' => $provider ]);         
    }
        
    public function getSubscriptionPricingPlans() {
        return $subscriptionPricingPlans = SubscriptionPricingPlan::query()  
            ->where("enabled = true")
            ->andWhere("deleted_at = '0000-00-00 00:00:00'")
            ->execute();
    }
    
    public function createSubscriptionPlan($newSubscriptionParameters) {
        
        try {
        
            $userId = $newSubscriptionParameters['userAccountId'];

            /* Configure subscription parameters */
            if ($newSubscriptionParameters['pricingPlanId'] !== 'Unpaid') {
                
                $subscriptionPricingPlan = SubscriptionPricingPlan::query()  
                    ->where("id = :id:")
                    ->bind(["id" => $newSubscriptionParameters['pricingPlanId']])
                    ->execute()
                    ->getFirst();
                $pricingPlanId = $subscriptionPricingPlan->id;   
                if ($subscriptionPricingPlan->enable_trial_account) {
                    $paymentPlan = ServicesConsts::$PAYMENT_PLAN_TRIAL;  
                    $locations = 1;
                    $smsMessagesPerLocation = $subscriptionPricingPlan->max_messages_on_trial_account;
                } else {
                    $paymentPlan = ServicesConsts::$PAYMENT_PLAN_MONTHLY;;
                    $locations = 0;
                    $smsMessagesPerLocation = 0;;
                }
                
            } else  {
                
                $pricingPlanId = 0;
                $locations = $newSubscriptionParameters['freeLocations'];
                $smsMessagesPerLocation = $newSubscriptionParameters['freeSmsMessagesPerLocation'];
                $paymentPlan = ServicesConsts::$PAYMENT_PLAN_FREE;
                
            }
            
            $db = $this->di->get('db'); 
            $db->begin();
            
            /* Create the subscription plan */
            $subscriptionPlan = new BusinessSubscriptionPlan();
            $subscriptionPlan->user_id = intval($userId);
            $subscriptionPlan->locations = intval($locations);
            $subscriptionPlan->sms_messages_per_location = intval($smsMessagesPerLocation);
            $subscriptionPlan->payment_plan = $paymentPlan;
            $subscriptionPlan->subscription_pricing_plan_id = intval($pricingPlanId);
            if (!$subscriptionPlan->create()) {
                throw new ArrayException("", 0, null, $subscriptionPlan->getMessages());
            }
                        
            $db->commit();
            
        } catch(ArrayException $e) {
            
            if (isset($db)) {
                $db->rollback();
            }
            return $e->getOptions();
            
        }
        
        return true;
    }
    
    public function changeSubscriptionPlan($subscriptionParameters) {
        
        $subscriptionPlan = BusinessSubscriptionPlan::query()
            ->where("user_id = :userId:")
            ->bind(["userId" => $subscriptionParameters['userId']])
            ->execute()
            ->getFirst();
        if (!$subscriptionPlan) {
            return false;
        }
        
        $subscriptionPlan->setLocations($subscriptionParameters['locations']);
        $subscriptionPlan->setSmsMessagesPerLocation($subscriptionParameters['messages']);
        $subscriptionPlan->setPaymentPlan($subscriptionParameters['planType']);
        if (!$subscriptionPlan->save()) {
            return false;
        }
        
        return true;
    }
    
    public function getSubscriptionPlan($userId) {
        $subscriptionPlan = BusinessSubscriptionPlan::query()  
            ->where("user_id = :user_id:")
            ->bind(["user_id" => intval($userId)])
            ->execute()
            ->getFirst();
        if(!$subscriptionPlan) {
            return false;
        }
        return $subscriptionPlan->toArray();
    }
    
    public function isValidInvitation($subscriptionToken) {
        $businessSubscriptionInvitation = BusinessSubscriptionInvitation::query()  
            ->where("token = :token:")
            ->bind(["token" => $subscriptionToken])
            ->execute()
            ->getFirst();
        if(!$businessSubscriptionInvitation) {
            return false;
        }
        return true;
    }
    
    public function invalidateInvitation($subscriptionToken) {
        $businessSubscriptionInvitation = BusinessSubscriptionInvitation::query()  
            ->where("token = :token:")
            ->bind(["token" => $subscriptionToken])
            ->execute()
            ->getFirst();
        if (!$businessSubscriptionInvitation) {
            return ['There is no invitation associated to that token'];
        }
        $businessSubscriptionInvitation->deleted_at = date('Y-m-d');
        if (!$businessSubscriptionInvitation->update()) {
            return $businessSubscriptionInvitation->getMessages();
        }
        return true;
    }
    
    public function getPricingPlanById($pricingPlanId) {
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
    
    public function isPricingPlanLocked($pricingPlanId) {
        $subscriptionPlan = BusinessSubscriptionPlan::query()  
            ->where("subscription_pricing_plan_id = :subscription_pricing_plan_id:")
            ->bind(["subscription_pricing_plan_id" => intval($pricingPlanId)])
            ->execute()
            ->getFirst();
        if(!$subscriptionPlan) {
            return false;
        }
        return true;        
    }
    
    public function getPricingPlanByName($userId, $pricingPlanName) {
        $subscriptionPricingPlan = SubscriptionPricingPlan::query()  
            ->where("user_id = :userId:")
            ->andWhere("name = :pricingPlanName:")
            ->bind(["userId" => $userId, "pricingPlanName" => $pricingPlanName])
            ->execute()
            ->getFirst();
        if(!$subscriptionPricingPlan) {
            return false;
        }
        return $subscriptionPricingPlan->toArray();
    }
    
    public function savePricingProfile($parameters, $isUpdate) {
        
        $status = false;
        
        try {
                
            $id = $this->saveSubscriptionPricingPlan($parameters, $isUpdate);
            if (!$id) {
                throw new \Exception();
            }
        
            if (!$this->appendPricingParameterLists($id, $parameters, $isUpdate)) {
                throw new \Exception();
            }
            
            $status = true;
        
        } catch(Exception $e) {}
        
        return $status;
        
    }
    
    public function getAllPricingPlansByUserId($userId) {
        $subscriptionPricingPlans = SubscriptionPricingPlan::query()  
            ->where("user_id = :userId:")
            ->andWhere("deleted_at = '0000-00-00 00:00:00'")
            ->bind(["userId" => $userId])
            ->execute();
        return $subscriptionPricingPlans;
    }
    
    public function enablePricingPlanById($pricingPlanId, $enable) {  // Second param is a dirty filthy hack :(, See comment below for details
        $subscriptionPricingPlan = SubscriptionPricingPlan::query()  
            ->where("id = :id:")
            ->bind(["id" => $pricingPlanId])
            ->execute()
            ->getFirst();
        if (!$subscriptionPricingPlan) {
            return false;
        }
        
        $subscriptionPricingPlan->enabled = $enable === 'true' ? 1 : 0;
        $subscriptionPricingPlan->updated_at = time();
        if (!$subscriptionPricingPlan->update()) {
            return false;
        }
        
        return true;
    }
    
    public function deletePricingPlanById($pricingPlanId) {
        $subscriptionPricingPlan = SubscriptionPricingPlan::query()  
            ->where("id = :id:")
            ->bind(["id" => $pricingPlanId])
            ->execute()
            ->getFirst(); 
        if (!$subscriptionPricingPlan) {
            return false;
        }
        
        $subscriptionPricingPlan->deleted_at = time();
        // $result = $db->query("DELETE FROM subscription_pricing_plan WHERE id=" . $subscriptionPricingPlan->id ); // Working now
        if (!$subscriptionPricingPlan->update()) {
            return false;
        }
        
        return true;
    }
    
    public function getPricingParameterListsByPricingPlanId($pricingPlanId) { 
        return SubscriptionPricingPlanParameterList::find("subscription_pricing_plan_id = ".$pricingPlanId)->toArray();
    }
    
    private function saveSubscriptionPricingPlan($parameters, $isUpdate) {
        
        /*
         * REFACTOR: This function is half baked crap - but we're in a rush.
         * Must fix.  MT June 23, 2016
         * 
         */
        if ($isUpdate) {
            $subscriptionPricingPlan = SubscriptionPricingPlan::query()  
                ->where("name = :name:")
                ->bind(["name" => $parameters["name"]])
                ->execute()
                ->getFirst();
        } else {
            $subscriptionPricingPlan = new SubscriptionPricingPlan();
        }
        
        if (!$subscriptionPricingPlan) {
            return false;
        }
        
        $subscriptionPricingPlan->user_id = $parameters["userId"];
        $subscriptionPricingPlan->name = $parameters["name"];                               
        $subscriptionPricingPlan->enabled = $isUpdate ? $subscriptionPricingPlan->enabled : true;
        $subscriptionPricingPlan->enable_trial_account = $parameters["enableTrialAccount"];
        $subscriptionPricingPlan->enable_discount_on_upgrade = $parameters["enableDiscountOnUpgrade"];
        $subscriptionPricingPlan->base_price = $parameters["basePrice"];
        $subscriptionPricingPlan->cost_per_sms = $parameters["costPerSms"];
        $subscriptionPricingPlan->max_messages_on_trial_account = $parameters["maxMessagesOnTrialAccount"];
        $subscriptionPricingPlan->updgrade_discount = $parameters["upgradeDiscount"];
        $subscriptionPricingPlan->charge_per_sms = $parameters["chargePerSms"];
        $subscriptionPricingPlan->max_sms_messages = $parameters["maxSmsMessages"];
        $subscriptionPricingPlan->enable_annual_discount = $parameters["enableAnnualDiscount"];
        $subscriptionPricingPlan->annual_discount = $parameters["annualDiscount"];
        $subscriptionPricingPlan->pricing_details = $parameters["pricingDetails"] ? : new \Phalcon\Db\RawValue('default');
        
        if ($isUpdate && !$subscriptionPricingPlan->update()) {
            return false;
        } else if (!$isUpdate && !$subscriptionPricingPlan->create()) {
            return false;
        }
        
        return $subscriptionPricingPlan->id;
    }
        
    private function appendPricingParameterLists($id, $parameters, $isUpdate) {
        
        /* Simply delete and refresh */
        if ($isUpdate) {
            
            $db = new DbAdapter(array(
                'host' => $this->config->database->host,
                'username' => $this->config->database->username,
                'password' => $this->config->database->password,
                'dbname' => $this->config->database->dbname
            ));
            $db->query("DELETE FROM subscription_pricing_plan_parameter_list WHERE subscription_pricing_plan_id=".$id);
            $db->close();
            
        }
        
        foreach($parameters as $segment => $params) {    
            
            if(substr($segment,0,7) !== "segment") {
                continue;
            }
            
            $pricingParameterList = $this->createPricingParameterList($id, $params);
            if(!$pricingParameterList) {
                return false;
            }
            
        }
        
        return true;
    }
    
    private function createPricingParameterList($id, $parameters) {
         
        $subscriptionPricingPlanParameterList = new SubscriptionPricingPlanParameterList();
        $subscriptionPricingPlanParameterList->subscription_pricing_plan_id = intval($id);
        $subscriptionPricingPlanParameterList->min_locations = intval($parameters['minLocations']);
        $subscriptionPricingPlanParameterList->max_locations = intval($parameters['maxLocations']);
        $subscriptionPricingPlanParameterList->location_discount_percentage = floatval($parameters['locationDiscountPercentage']);
        $subscriptionPricingPlanParameterList->base_price = floatval($parameters['basePrice']);
        $subscriptionPricingPlanParameterList->sms_charge = floatval($parameters['smsCharge']);
        $subscriptionPricingPlanParameterList->total_price = floatval($parameters['totalPrice']);
        $subscriptionPricingPlanParameterList->location_discount = floatval($parameters['locationDiscount']);
        $subscriptionPricingPlanParameterList->upgrade_discount = floatval($parameters['upgradeDiscount']);
        $subscriptionPricingPlanParameterList->sms_messages = intval($parameters['smsMessages']);
        $subscriptionPricingPlanParameterList->sms_cost = floatval($parameters['smsCost']);
        $subscriptionPricingPlanParameterList->profit_per_location = floatval($parameters['profitPerLocation']);
        if(!$subscriptionPricingPlanParameterList->create()) {
            return false;
        }
        
        return true;
    }
    
}
