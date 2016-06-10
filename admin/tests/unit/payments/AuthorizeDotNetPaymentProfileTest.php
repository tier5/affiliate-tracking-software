<?php

use Vokuro\Payments\AuthorizeDotNet;

class AuthorizeDotNetPaymentProfileTest extends \Codeception\TestCase\Test
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    
    static $customerProfileId;

    protected function _before()
    {
        $config = $this->getModule('Phalcon2')->getApplication()->getDi()->get('config');
        
        /* Create a working customer profile with a payment profile */
        $parameters = [];
        $parameters['environment'] = 'dev';
	$parameters['customerType'] = 'individual';
	$parameters['customerProfileDescription'] = 'Test Customer';
        $parameters['email'] = 'john.smith@test.com';
        
        $authorizeDotNet = new AuthorizeDotNet($config->authorizeDotNet->apiLoginId, $config->authorizeDotNet->transactionKey);
        $customerProfile = $authorizeDotNet->createCustomerProfile($parameters);
        
        self::$customerProfileId = $customerProfile['customerProfileId'];
        
        $this->assertTrue($customerProfile !== false, "Create working customer profile for testCreateNewPaymentProfile failed!!!");
    }

    protected function _after()
    {
        $config = $this->getModule('Phalcon2')->getApplication()->getDi()->get('config');
        
        $parameters = [];
        $parameters['environment'] = 'dev';
	$parameters['customerProfileId'] = self::$customerProfileId;
        
        $authorizeDotNet = new AuthorizeDotNet($config->authorizeDotNet->apiLoginId, $config->authorizeDotNet->transactionKey);
        $deleted = $authorizeDotNet->deleteCustomerProfile($parameters);
        
        $this->assertTrue($deleted, "Delete working customer profile for testCreateNewPaymentProfile failed!!!");
    }

    public function testCreateNewPaymentProfile()
    {
        $paymentProfile = $this->createNewPaymentProfile();
        $this->assertTrue($paymentProfile !== false, "Create new payment profile for existing customer failed!!!");
    }
    
    public function testUpdatePaymentProfile()
    {
        $config = $this->getModule('Phalcon2')->getApplication()->getDi()->get('config');
        
        $paymentProfileId = $this->createNewPaymentProfile();
        $this->assertTrue($paymentProfileId !== false, "Create new working payment profile for existing customer failed!!!");
        
        /* Update parameters */
        $parameters['environment'] = 'dev';
        $parameters['customerProfileId'] = self::$customerProfileId;
	$parameters['customerPaymentProfileId'] = $paymentProfileId;
	$parameters['cardNumber'] = '4111111111111112';
	$parameters['cardExpiryDate'] = '2019-12';
	$parameters['cardCode'] = '124';
        $parameters['firstName'] = "Johny"; 
	$parameters['lastName'] = "Smithens";
	$parameters['companyName'] = "Widgets2 Inc.";
	$parameters['companyAddress'] = "555 Somewhere Blvd.";
	$parameters['city'] = "Los Angeles";
	$parameters['state'] = "CA";
	$parameters['zip'] = '90001';
	$parameters['country'] = 'USA';
        $authorizeDotNet = new AuthorizeDotNet($config->authorizeDotNet->apiLoginId, $config->authorizeDotNet->transactionKey);
        $updated = $authorizeDotNet->updatePaymentProfileForCustomer($parameters);
        
        $this->assertTrue($updated !== false, "Update payment profile for existing customer failed!!!");
    }
    
    public function testGetPaymentProfile()
    {
        $config = $this->getModule('Phalcon2')->getApplication()->getDi()->get('config');
        
        $paymentProfileId = $this->createNewPaymentProfile();
        $this->assertTrue($paymentProfileId !== false, "Create new working payment profile for existing customer failed!!!");
        
        /* Update parameters */
        $parameters['environment'] = 'dev';
        $parameters['customerProfileId'] = self::$customerProfileId;
	$parameters['customerPaymentProfileId'] = $paymentProfileId;
        
        $authorizeDotNet = new AuthorizeDotNet($config->authorizeDotNet->apiLoginId, $config->authorizeDotNet->transactionKey);
        $profile = $authorizeDotNet->getPaymentProfileForCustomer($parameters);
        
        $this->assertTrue($profile !== false, "Get payment profile for existing customer failed!!!");
    }
    
    public function testValidatePaymentProfile()
    {
        $config = $this->getModule('Phalcon2')->getApplication()->getDi()->get('config');
        
        $paymentProfileId = $this->createNewPaymentProfile();
        $this->assertTrue($paymentProfileId !== false, "Create new working payment profile for existing customer failed!!!");
        
        /* Update parameters */
        $parameters['environment'] = 'dev';
        $parameters['customerProfileId'] = self::$customerProfileId;
	$parameters['customerPaymentProfileId'] = $paymentProfileId;
        
        $authorizeDotNet = new AuthorizeDotNet($config->authorizeDotNet->apiLoginId, $config->authorizeDotNet->transactionKey);
        $validation = $authorizeDotNet->validatePaymentProfileForCustomer($parameters);
        
        $this->assertTrue($validation !== false, "Payment profile validation failed for existing customer failed!!!");
    }
    
    public function testDeletePaymentProfile()
    {
        $config = $this->getModule('Phalcon2')->getApplication()->getDi()->get('config');
        
        $paymentProfileId = $this->createNewPaymentProfile();
        $this->assertTrue($paymentProfileId !== false, "Create new working payment profile for existing customer failed!!!");
        
        /* Update parameters */
        $parameters['environment'] = 'dev';
        $parameters['customerProfileId'] = self::$customerProfileId;
	$parameters['customerPaymentProfileId'] = $paymentProfileId;
        
        $authorizeDotNet = new AuthorizeDotNet($config->authorizeDotNet->apiLoginId, $config->authorizeDotNet->transactionKey);
        $deleted = $authorizeDotNet->deletePaymentProfileForCustomer($parameters);
        
        $this->assertTrue($deleted !== false, "Delete payment profile existing customer failed!!!");
    }
    
    private function createNewPaymentProfile() {    
        $config = $this->getModule('Phalcon2')->getApplication()->getDi()->get('config');
        
        /* Create a new payment profile for the customer profile */
        $parameters = [];
        $parameters['environment'] = 'dev';
        $parameters['customerProfileId'] = self::$customerProfileId;
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
        
        $authorizeDotNet = new AuthorizeDotNet($config->authorizeDotNet->apiLoginId, $config->authorizeDotNet->transactionKey);
        $paymentProfile = $authorizeDotNet->createPaymentProfileForCustomer($parameters);
     
        return $paymentProfile;
    }
    
}
            