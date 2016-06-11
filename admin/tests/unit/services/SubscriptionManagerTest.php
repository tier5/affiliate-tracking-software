<?php

class SubscriptionManagerTest extends \Codeception\TestCase\Test
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

    
    public function testCreateSubscriptionProfile()
    {
        $subscriptionManager = $this->getModule('Phalcon2')
            ->getApplication()
            ->getDi()
            ->get('subscriptionManager');
        
        $newSubscriptionParameters = [
            'userAccountId' => '1', 
            'freeLocations' => '2',
            'freeSmsMessagesPerLocation' => '200',
            'pricingPlanId' => 'Unpaid'
        ];
        $created = $subscriptionManager->createSubscriptionPlan($newSubscriptionParameters);
        
        $this->assertTrue($created, "Create SubscriptionProfile failed!!!");  
    }
    
}
            