<?php

use Vokuro\Payments\AuthorizeDotNet;

class AuthorizeDotNetRecurringPaymentsTest extends \Codeception\TestCase\Test
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    
    static $customerProfileId;
    static $customerPaymentProfileId;
    static $shippingAddressId;
    static $subscriptionId;
            
    protected function _before()
    {
        
    }

    protected function _after()
    {
        
    }

    public function testCreateSubscriptionForCustomer()
    {
        $this->createCustomerProfile();
        
        $config = $this->getModule('Phalcon2')->getApplication()->getDi()->get('config');
        
        $parameters = [];
        $parameters['environment'] = 'dev';
        $parameters['customerProfileId'] = self::$customerProfileId;
        $parameters['customerPaymentProfileId'] = self::$customerPaymentProfileId;
        $parameters['subscriptionName'] = "Sample Subscription";
        $parameters['intervalLength'] = "23";
        $parameters['unit'] = "days";
        $parameters['startDate'] = '2020-08-30';
        $parameters['totalOccurences'] = '12';
        $parameters['trialOccurences'] = '1';
        $parameters['amount'] = rand(1,99999)/12.0*12;
        $parameters['trialAmount'] = "0.00";

        $authorizeDotNet = new AuthorizeDotNet($config->authorizeDotNet->apiLoginId, $config->authorizeDotNet->transactionKey);
        $subscriptionId = $authorizeDotNet->createSubscriptionForCustomer($parameters);
         
        $this->assertTrue($subscriptionId !== false, "Create subscription for customer on authorize.net platform failed!!!");
        
        self::$subscriptionId = $subscriptionId;
    }
     
    public function testGetSubscription()
    {
        $config = $this->getModule('Phalcon2')->getApplication()->getDi()->get('config');
        
        $parameters = [];
        $parameters['environment'] = 'dev';
        $parameters['subscriptionId'] = self::$subscriptionId;
        
        $authorizeDotNet = new AuthorizeDotNet($config->authorizeDotNet->apiLoginId, $config->authorizeDotNet->transactionKey);
        $subscription = $authorizeDotNet->getSubscriptionForCustomer($parameters);
         
        $this->assertTrue($subscription !== false, "Get subscription for customer on authorize.net platform failed!!!");
    }
    
    public function testUpdateSubscriptionForCustomer()
    {
        $config = $this->getModule('Phalcon2')->getApplication()->getDi()->get('config');
        
        $parameters = [];
        $parameters['environment'] = 'dev';
        $parameters['subscriptionId'] = self::$subscriptionId;
        $parameters['subscriptionName'] = "Sample Subscription1";
        $parameters['intervalLength'] = "22";
        $parameters['unit'] = "months";
        $parameters['startDate'] = '2020-09-30';
        $parameters['totalOccurences'] = '11';
        $parameters['trialOccurences'] = '2';
        $parameters['amount'] = rand(1,99999)/12.0*11;
        $parameters['trialAmount'] = "1.00";
        
        $authorizeDotNet = new AuthorizeDotNet($config->authorizeDotNet->apiLoginId, $config->authorizeDotNet->transactionKey);
        $updated = $authorizeDotNet->updateSubscriptionForCustomer($parameters);
         
        $this->assertTrue($updated !== false, "Update subscription for customer on authorize.net platform failed!!!");
    }
    
    public function testCancelSubscription()
    {
        $config = $this->getModule('Phalcon2')->getApplication()->getDi()->get('config');
        
        $parameters = [];
        $parameters['environment'] = 'dev';
        $parameters['subscriptionId'] = self::$subscriptionId;
        
        $authorizeDotNet = new AuthorizeDotNet($config->authorizeDotNet->apiLoginId, $config->authorizeDotNet->transactionKey);
        $cancelled = $authorizeDotNet->cancelSubscriptionForCustomer($parameters);
         
        $this->assertTrue($cancelled !== false, "Cancel subscription for customer on authorize.net platform failed!!!");
        
        $this->deleteCustomerProfile();
    }
     
    
    private function createCustomerProfile() {
        
        $config = $this->getModule('Phalcon2')->getApplication()->getDi()->get('config');

        /* Create a working customer profile with a payment profile */
        $parameters = [];
        $parameters['environment'] = 'dev';
        $parameters['email'] = 'john.smith@test.com';
        $parameters['cardNumber'] = '4111111111111111';
        $parameters['cardExpiryDate'] = '2038-12';
        $parameters['cardCode'] = '123';
        $parameters['firstName'] = "John"; 
        $parameters['lastName'] = "Smith";
        $parameters['companyName'] = "Widgets Inc.";
        $parameters['companyAddress'] = "555 Somewhere Rd.";
        $parameters['city'] = "Houston";
        $parameters['state'] = "TX";
        $parameters['zip'] = '44628';
        $parameters['country'] = 'USA';
        $parameters['customerType'] = 'individual';
        $parameters['customerProfileDescription'] = 'Test Customer';

        $authorizeDotNet = new AuthorizeDotNet($config->authorizeDotNet->apiLoginId, $config->authorizeDotNet->transactionKey);
        $customerProfile = $authorizeDotNet->createCustomerProfile($parameters);

        $this->assertTrue($customerProfile !== false, "Create working customer profile for testCreateNewPaymentProfile failed!!!");

        self::$customerProfileId = $customerProfile['customerProfileId'];
        self::$customerPaymentProfileId = $customerProfile['customerPaymentProfileId'];
        self::$shippingAddressId = $customerProfile['shippingAddressId'];
        
    }
    
    private function deleteCustomerProfile() {
    
        $config = $this->getModule('Phalcon2')->getApplication()->getDi()->get('config');
        
        $parameters = [];
        $parameters['environment'] = 'dev';
	$parameters['customerProfileId'] = self::$customerProfileId;
        
        $authorizeDotNet = new AuthorizeDotNet($config->authorizeDotNet->apiLoginId, $config->authorizeDotNet->transactionKey);
        $deleted = $authorizeDotNet->deleteCustomerProfile($parameters);
        
        $this->assertTrue($deleted, "Delete working customer profile for testCreateNewPaymentProfile failed!!!");
        
    }
}
            