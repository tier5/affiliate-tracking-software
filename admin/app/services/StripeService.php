<?php

namespace Vokuro\Services;

use Vokuro\Auth\Auth;
use Vokuro\Models\Agency;
use Vokuro\Models\Users;
use Stripe;

/**
 * Class Email
 * @package Vokuro\Services
 */
class StripeService extends BaseService
{
    public function __construct($config = null, $di = null)
    {
    	parent::__construct($config, $di);
    	$this->config = $this->di->get('config');
    	$this->auth = $this->di->get('auth');
    }

	/**
	 * Connect to Stripe
	 *
	 * @return void
	 */

	private function connectToStripe() 
	{
		$this->stripePublic = $this->config->stripe->publishable_key;
		$this->stripeSecret = $this->config->stripe->secret_key;

		\Stripe\Stripe::setApiKey($this->stripeSecret);
	}

	/**
	 * Get current Agency Stripe keys
	 *
	 * @return void
	 */

	public function setAgencyStripeKeys()
	{
		$stripeKeys = $this->getCurrentAgency()
						   ->getStripeKeys();

        $this->stripePublic = $stripeKeys['public'];
        $this->stripeSecret = $stripeKeys['secret'];
	}

	public function getStripeAccountInfo()
	{
		$this->setAgencyStripeKeys();
    	$this->connectToStripe();
		return \Stripe\Account::retrieve('acct_17FTSpKdAtMKiGb0')->currencies_supported;
	}

    public function getCurrentAgency()
    {
    	$userId = $this->auth->getIdentity()['id'];

        $agencyId = Users::findFirst(
            "id = $userId"
        )->agency_id;

        return Agency::findFirst(
            "agency_id = $agencyId"
        );
    }

    public function getAvailableCurrencies()
    {
    	$this->setAgencyStripeKeys();
    	$this->connectToStripe();
    	// get account country
    	$accountCountry = 'US';

    	return \Stripe\CountrySpec::retrieve($accountCountry)->supported_payment_currencies;
    }

}