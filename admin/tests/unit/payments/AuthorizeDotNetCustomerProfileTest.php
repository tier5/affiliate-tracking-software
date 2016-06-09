<?php

use Vokuro\Payments\AuthorizeDotNet;

class AuthorizeDotNetCustomerProfileTest extends \Codeception\TestCase\Test
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    
    static $customerProfileId;

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    public function testCreateNewCustomerProfile()
    {
        $config = $this->getModule('Phalcon2')->getApplication()->getDi()->get('config');
        
        $parameters = [];
        $parameters['environment'] = 'dev';
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
        $parameters['email'] = 'john.smith@test.com';
        
        $authorizeDotNet = new AuthorizeDotNet($config->authorizeDotNet->apiLoginId, $config->authorizeDotNet->transactionKey);
        self::$customerProfileId = $authorizeDotNet->createCustomerProfile($parameters);
        
        $this->assertTrue(self::$customerProfileId !== false, "Create new customer profile on authorize.net platform failed!!!");
    }
    
    public function testGetCustomerProfile() 
    {
        $config = $this->getModule('Phalcon2')->getApplication()->getDi()->get('config');
        
        $parameters = [];
        $parameters['environment'] = 'dev';
	$parameters['customerProfileId'] = self::$customerProfileId;
        
        $authorizeDotNet = new AuthorizeDotNet($config->authorizeDotNet->apiLoginId, $config->authorizeDotNet->transactionKey);
        $customerProfile = $authorizeDotNet->getCustomerProfile($parameters);
        
        $this->assertTrue($customerProfile != false, "Get existing customer profile on authorize.net platform failed!!!");
    }
    
    public function testDeleteCustomerProfile()
    {
        $config = $this->getModule('Phalcon2')->getApplication()->getDi()->get('config');
        
        $parameters = [];
        $parameters['environment'] = 'dev';
	$parameters['customerProfileId'] = self::$customerProfileId;
        
        $authorizeDotNet = new AuthorizeDotNet($config->authorizeDotNet->apiLoginId, $config->authorizeDotNet->transactionKey);
        $deleted = $authorizeDotNet->deleteCustomerProfile($parameters);
        
        $this->assertTrue($deleted, "Delete existing customer profile on authorize.net platform failed!!!");
    }
    
}
            