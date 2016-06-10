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
	$parameters['customerProfileDescription'] = 'Test Customer';
        $parameters['email'] = 'john.smith@test.com';
        
        $authorizeDotNet = new AuthorizeDotNet($config->authorizeDotNet->apiLoginId, $config->authorizeDotNet->transactionKey);
        $customerProfile = $authorizeDotNet->createCustomerProfile($parameters);
        
        self::$customerProfileId = $customerProfile['customerProfileId'];
         
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
            