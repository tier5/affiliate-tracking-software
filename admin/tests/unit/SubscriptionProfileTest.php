<?php

use Vokuro\Models\SubscriptionPlan;
use Vokuro\Models\SubscriptionProfile;
use Vokuro\Models\SubscriptionProfileParameterList;
use Vokuro\Models\SubscriptionProfileHasParameterList;

class SubscriptionProfileTest extends \Codeception\TestCase\Test
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    
    public function testCreateSubscriptionProfileParameterList()
    {
        $parameterList = new SubscriptionProfileParameterList();
        $parameterList->setMinLocations(1);
        $parameterList->setMaxLocations(100);
        $parameterList->setDiscount(0.20);
        if (!$parameterList->create()) {
            foreach ($parameterList->getMessages() as $message) {
                \Codeception\Util\Debug::debug($message);
            }
            $this->assertTrue(false, "Create SubscriptionProfileParameterList failed!!!");
       }
       $this->assertTrue(true, "Create SubscriptionProfileParameterList succeeded!!!");
    }
    
    public function testUpdateSubscriptionProfileParameterList()
    {
        // Create test parameter list 
        $parameterList = new SubscriptionProfileParameterList();
        $parameterList->setMinLocations(1);
        $parameterList->setMaxLocations(100);
        $parameterList->setDiscount(0.20);
        if (!$parameterList->create()) 
        {
            foreach ($parameterList->getMessages() as $message) {
                \Codeception\Util\Debug::debug($message);
            }
            $this->assertTrue(false, "Create initial SubscriptionProfileParameterList failed!!!");
        }
       
        // Fetch newly created parameter list
        $newParameterList = SubscriptionProfileParameterList::query()
            ->where("id = :id:")
            ->bind(array("id" => $parameterList->id))
            ->execute()
            ->getFirst();
        $newParameterList->setDiscount(0.30);
        $newParameterList->setUpdatedAt(time());
        if (!$newParameterList->update()) 
        {
            foreach ($newParameterList->getMessages() as $message) {
                \Codeception\Util\Debug::debug($message);
            }
            $this->assertTrue(false, "Update SubscriptionProfileParameterList failed!!!");
        }
        
        $this->assertTrue(true, "Update SubscriptionProfileParameterList succeeded!!!");

    }
    
    public function testCreateSubscriptionProfile()
    {
        $subscriptionProfile = new SubscriptionProfile();        
        $subscriptionProfile->setEnableFreeAccount(true);
        $subscriptionProfile->setEnableDiscountOnUpgrade(true);
        $subscriptionProfile->setBasePrice(49.00);             
        $subscriptionProfile->setCostPerSms(0.0075);           
        $subscriptionProfile->setChargePerSms(0.10);          
        $subscriptionProfile->setAnnualPlanDiscount(0.1);      
        $subscriptionProfile->setTrialPeriod(false);           
        $subscriptionProfile->setMaxSmsDuringTrialPeriod(10);  
        $subscriptionProfile->setMaxMessagesOnFreeAccount(100);
        $subscriptionProfile->setMaxLocationsOnFreeAccount(1); 
        $subscriptionProfile->setUpdgradeDiscount(0.10);       
        $subscriptionProfile->setMaxSmsMessages(1000);         
        $subscriptionProfile->setTrialNumberOfDays(5);         
        $subscriptionProfile->setCollectCreditCardOnSignUp(true);
        $subscriptionProfile->setPricingDetails("Pricing details???");             
        $subscriptionProfile->setAgencyId(1);
        if (!$subscriptionProfile->create()) {
            foreach ($subscriptionProfile->getMessages() as $message) 
            {
                \Codeception\Util\Debug::debug($message);
            }
            $this->assertTrue(false, "Create SubscriptionProfile failed!!!");
        }
        
        // Create test parameter list 
        $parameterList = new SubscriptionProfileParameterList();
        $parameterList->setMinLocations(1);
        $parameterList->setMaxLocations(100);
        $parameterList->setDiscount(0.20);
        if (!$parameterList->create()) 
        {
            foreach ($parameterList->getMessages() as $message) 
            {
                \Codeception\Util\Debug::debug($message);
            }
            $this->assertTrue(false, "Create test parameter list for SubscriptionProfile failed!!!");
        }
        
        // Attach paramater list to subscription profile
        $subscriptionProfileHasParameterList = new SubscriptionProfileHasParameterList();
        $subscriptionProfileHasParameterList->setSubscriptionProfileId($subscriptionProfile->getId());
        $subscriptionProfileHasParameterList->setParameterListId($parameterList->getId());
        if (!$subscriptionProfileHasParameterList->create()) 
        {
            foreach ($parameterList->getMessages() as $message) 
            {
                \Codeception\Util\Debug::debug($message);
            }
            $this->assertTrue(false, "Associate test parameter list to SubscriptionProfile failed!!!");
        }
        
        $this->assertTrue(true, "Create SubscriptionProfile succeeded!!!");
    }
    
    public function testUpdateSubscriptionProfile()
    {
        /* TODO: Improve by using test */
        $subscriptionProfile = new SubscriptionProfile();        
        $subscriptionProfile->setEnableFreeAccount(true);
        $subscriptionProfile->setEnableDiscountOnUpgrade(true);
        $subscriptionProfile->setBasePrice(49.00);             
        $subscriptionProfile->setCostPerSms(0.0075);           
        $subscriptionProfile->setChargePerSms(0.10);          
        $subscriptionProfile->setAnnualPlanDiscount(0.1);      
        $subscriptionProfile->setTrialPeriod(false);           
        $subscriptionProfile->setMaxSmsDuringTrialPeriod(10);  
        $subscriptionProfile->setMaxMessagesOnFreeAccount(100);
        $subscriptionProfile->setMaxLocationsOnFreeAccount(1); 
        $subscriptionProfile->setUpdgradeDiscount(0.10);       
        $subscriptionProfile->setMaxSmsMessages(1000);         
        $subscriptionProfile->setTrialNumberOfDays(5);         
        $subscriptionProfile->setCollectCreditCardOnSignUp(true);
        $subscriptionProfile->setPricingDetails("Pricing details");             
        $subscriptionProfile->setAgencyId(1);
        if (!$subscriptionProfile->create()) {
            foreach ($subscriptionProfile->getMessages() as $message) 
            {
                \Codeception\Util\Debug::debug($message);
            }
            $this->assertTrue(false, "Create SubscriptionProfile failed!!!");
        }
        
        // Create test parameter list 
        $parameterList = new SubscriptionProfileParameterList();
        $parameterList->setMinLocations(1);
        $parameterList->setMaxLocations(100);
        $parameterList->setDiscount(0.20);
        if (!$parameterList->create()) 
        {
            foreach ($parameterList->getMessages() as $message) 
            {
                \Codeception\Util\Debug::debug($message);
            }
            $this->assertTrue(false, "Create test parameter list for SubscriptionProfile failed!!!");
        }
        
        // Attach paramater list to subscription profile
        $subscriptionProfileHasParameterList = new SubscriptionProfileHasParameterList();
        $subscriptionProfileHasParameterList->setSubscriptionProfileId($subscriptionProfile->getId());
        $subscriptionProfileHasParameterList->setParameterListId($parameterList->getId());
        if (!$subscriptionProfileHasParameterList->create()) 
        {
            foreach ($parameterList->getMessages() as $message) 
            {
                \Codeception\Util\Debug::debug($message);
            }
            $this->assertTrue(false, "Associate test parameter list to SubscriptionProfile failed!!!");
        }
        
        $fecthedSubscriptionProfile = SubscriptionProfile::query()
            ->where("id = :id:")
            ->bind(array("id" => $subscriptionProfile->id))
            ->execute()
            ->getFirst();
        $fecthedSubscriptionProfile->enable_discount_on_upgrade = 0;
        $fecthedSubscriptionProfile->base_price = 30.00;             
        $fecthedSubscriptionProfile->cost_per_sms = 0.10;           
        $fecthedSubscriptionProfile->charge_per_sms = 0.30;
        if (!$fecthedSubscriptionProfile->update()) 
        {
            foreach ($fecthedSubscriptionProfile->getMessages() as $message) 
            {
                \Codeception\Util\Debug::debug($message);
            }
            $this->assertTrue(false, "Associate test parameter list to SubscriptionProfile failed!!!");
        }
        
        $this->assertTrue(true, "Update SubscriptionProfile succeeded!!!");
    }
    
    public function testCreateSubscriptionPlan()
    {
        /* TODO: Find more robust fashion to set up and tear down model dependencies
         * Use default profile association for now
         *  */
        $subscriptionPlan = new SubscriptionPlan();        
        $subscriptionPlan->setLocations(10);
        $subscriptionPlan->setSmsMessagesPerLocation(100);
        $subscriptionPlan->setPaymentPlan("monthly");
        $subscriptionPlan->setSubscriptionProfileId(1);
        $subscriptionPlan->setUserId(1);    
        if (!$subscriptionPlan->create()) 
        {
            foreach ($subscriptionPlan->getMessages() as $message) 
            {
                \Codeception\Util\Debug::debug($message);
            }
            $this->assertTrue(false, "Create SubscriptionPlan failed!!!");
        }
        
        $this->assertTrue(true, "Create SubscriptionPlan succeeded!!!");
    }
    
    public function testUpdateSubscriptionPlan()
    {
        /* TODO: Find more robust fashion to set up and tear down model dependencies
         * Use default profile association for now
         *  */
        $subscriptionPlan = new SubscriptionPlan();        
        $subscriptionPlan->setLocations(10);
        $subscriptionPlan->setSmsMessagesPerLocation(100);
        $subscriptionPlan->setPaymentPlan("monthly");
        $subscriptionPlan->setSubscriptionProfileId(1);
        $subscriptionPlan->setUserId(1);    
        if (!$subscriptionPlan->create()) 
        {
            foreach ($subscriptionPlan->getMessages() as $message) 
            {
                \Codeception\Util\Debug::debug($message);
            }
            $this->assertTrue(false, "Create SubscriptionPlan failed!!!");
        }
        
        $fecthedSubscriptionPlan = SubscriptionPlan::query()
            ->where("id = :id:")
            ->bind(array("id" => $subscriptionPlan->id))
            ->execute()
            ->getFirst();
        $fecthedSubscriptionPlan->payment_plan = "annually";
        if (!$fecthedSubscriptionPlan->update()) 
        {
            foreach ($fecthedSubscriptionPlan->getMessages() as $message) 
            {
                \Codeception\Util\Debug::debug($message);
            }
            $this->assertTrue(false, "Update SubscriptionPlan failed!!!");
        }
        
        $this->assertTrue(true, "Update SubscriptionPlan succeeded!!!");
    }
    
}
            