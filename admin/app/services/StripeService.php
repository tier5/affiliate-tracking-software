<?php

namespace Vokuro\Services;

use Vokuro\Auth\Auth;
use Vokuro\Models\Agency;
use Vokuro\Models\Users;
use Vokuro\Models\StripeSubscriptions;
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

	public function connectToStripe() 
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

	public function setAgencyStripeKeys($agencyId = null)
	{
        if ($agencyId === null) {
            $stripeKeys = $this->getCurrentAgency()
                               ->getStripeKeys();
        } else {
            $stripeKeys = Agency::findFirst(
                "agency_id = $agencyId"
            )->getStripeKeys();
        }

        $this->stripePublic = $stripeKeys['public'];
        $this->stripeSecret = $stripeKeys['secret'];

        if (!isset($this->stripeSecret) || empty($this->stripeSecret)) {
            return false;
        }

        \Stripe\Stripe::setApiKey($this->stripeSecret);
	}

	public function getStripeAccountInfo()
	{
		$this->setAgencyStripeKeys();

        $account = \Stripe\Account::retrieve();

        return $account;
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

        $country = $this->getStripeAccountInfo()->country;
        $currencies = \Stripe\CountrySpec::retrieve($country)->supported_payment_currencies;

    	return $currencies;
    }

    public function getCurrencySymbols($currencies)
    {
        $symbols = [];

        foreach ($currencies as $currency) {
            $symbols[$currency] = $this->getCurrencySymbol($currency);
        }

        return $symbols;
    }

    public function getCurrencySymbol($currency)
    {
        $currency_symbols = array(
            'AED' => '&#1583;.&#1573;', // ?
            'AFN' => '&#65;&#102;',
            'ALL' => '&#76;&#101;&#107;',
            'AMD' => '',
            'ANG' => '&#402;',
            'AOA' => '&#75;&#122;', // ?
            'ARS' => '&#36;',
            'AUD' => '&#36;',
            'AWG' => '&#402;',
            'AZN' => '&#1084;&#1072;&#1085;',
            'BAM' => '&#75;&#77;',
            'BBD' => '&#36;',
            'BDT' => '&#2547;', // ?
            'BGN' => '&#1083;&#1074;',
            'BHD' => '.&#1583;.&#1576;', // ?
            'BIF' => '&#70;&#66;&#117;', // ?
            'BMD' => '&#36;',
            'BND' => '&#36;',
            'BOB' => '&#36;&#98;',
            'BRL' => '&#82;&#36;',
            'BSD' => '&#36;',
            'BTN' => '&#78;&#117;&#46;', // ?
            'BWP' => '&#80;',
            'BYR' => '&#112;&#46;',
            'BZD' => '&#66;&#90;&#36;',
            'CAD' => '&#36;',
            'CDF' => '&#70;&#67;',
            'CHF' => '&#67;&#72;&#70;',
            'CLF' => '', // ?
            'CLP' => '&#36;',
            'CNY' => '&#165;',
            'COP' => '&#36;',
            'CRC' => '&#8353;',
            'CUP' => '&#8396;',
            'CVE' => '&#36;', // ?
            'CZK' => '&#75;&#269;',
            'DJF' => '&#70;&#100;&#106;', // ?
            'DKK' => '&#107;&#114;',
            'DOP' => '&#82;&#68;&#36;',
            'DZD' => '&#1583;&#1580;', // ?
            'EGP' => '&#163;',
            'ETB' => '&#66;&#114;',
            'EUR' => '&#8364;',
            'FJD' => '&#36;',
            'FKP' => '&#163;',
            'GBP' => '&#163;',
            'GEL' => '&#4314;', // ?
            'GHS' => '&#162;',
            'GIP' => '&#163;',
            'GMD' => '&#68;', // ?
            'GNF' => '&#70;&#71;', // ?
            'GTQ' => '&#81;',
            'GYD' => '&#36;',
            'HKD' => '&#36;',
            'HNL' => '&#76;',
            'HRK' => '&#107;&#110;',
            'HTG' => '&#71;', // ?
            'HUF' => '&#70;&#116;',
            'IDR' => '&#82;&#112;',
            'ILS' => '&#8362;',
            'INR' => '&#8377;',
            'IQD' => '&#1593;.&#1583;', // ?
            'IRR' => '&#65020;',
            'ISK' => '&#107;&#114;',
            'JEP' => '&#163;',
            'JMD' => '&#74;&#36;',
            'JOD' => '&#74;&#68;', // ?
            'JPY' => '&#165;',
            'KES' => '&#75;&#83;&#104;', // ?
            'KGS' => '&#1083;&#1074;',
            'KHR' => '&#6107;',
            'KMF' => '&#67;&#70;', // ?
            'KPW' => '&#8361;',
            'KRW' => '&#8361;',
            'KWD' => '&#1583;.&#1603;', // ?
            'KYD' => '&#36;',
            'KZT' => '&#1083;&#1074;',
            'LAK' => '&#8365;',
            'LBP' => '&#163;',
            'LKR' => '&#8360;',
            'LRD' => '&#36;',
            'LSL' => '&#76;', // ?
            'LTL' => '&#76;&#116;',
            'LVL' => '&#76;&#115;',
            'LYD' => '&#1604;.&#1583;', // ?
            'MAD' => '&#1583;.&#1605;.', //?
            'MDL' => '&#76;',
            'MGA' => '&#65;&#114;', // ?
            'MKD' => '&#1076;&#1077;&#1085;',
            'MMK' => '&#75;',
            'MNT' => '&#8366;',
            'MOP' => '&#77;&#79;&#80;&#36;', // ?
            'MRO' => '&#85;&#77;', // ?
            'MUR' => '&#8360;', // ?
            'MVR' => '.&#1923;', // ?
            'MWK' => '&#77;&#75;',
            'MXN' => '&#36;',
            'MYR' => '&#82;&#77;',
            'MZN' => '&#77;&#84;',
            'NAD' => '&#36;',
            'NGN' => '&#8358;',
            'NIO' => '&#67;&#36;',
            'NOK' => '&#107;&#114;',
            'NPR' => '&#8360;',
            'NZD' => '&#36;',
            'OMR' => '&#65020;',
            'PAB' => '&#66;&#47;&#46;',
            'PEN' => '&#83;&#47;&#46;',
            'PGK' => '&#75;', // ?
            'PHP' => '&#8369;',
            'PKR' => '&#8360;',
            'PLN' => '&#122;&#322;',
            'PYG' => '&#71;&#115;',
            'QAR' => '&#65020;',
            'RON' => '&#108;&#101;&#105;',
            'RSD' => '&#1044;&#1080;&#1085;&#46;',
            'RUB' => '&#1088;&#1091;&#1073;',
            'RWF' => '&#1585;.&#1587;',
            'SAR' => '&#65020;',
            'SBD' => '&#36;',
            'SCR' => '&#8360;',
            'SDG' => '&#163;', // ?
            'SEK' => '&#107;&#114;',
            'SGD' => '&#36;',
            'SHP' => '&#163;',
            'SLL' => '&#76;&#101;', // ?
            'SOS' => '&#83;',
            'SRD' => '&#36;',
            'STD' => '&#68;&#98;', // ?
            'SVC' => '&#36;',
            'SYP' => '&#163;',
            'SZL' => '&#76;', // ?
            'THB' => '&#3647;',
            'TJS' => '&#84;&#74;&#83;', // ? TJS (guess)
            'TMT' => '&#109;',
            'TND' => '&#1583;.&#1578;',
            'TOP' => '&#84;&#36;',
            'TRY' => '&#8356;', // New Turkey Lira (old symbol used)
            'TTD' => '&#36;',
            'TWD' => '&#78;&#84;&#36;',
            'TZS' => '',
            'UAH' => '&#8372;',
            'UGX' => '&#85;&#83;&#104;',
            'USD' => '&#36;',
            'UYU' => '&#36;&#85;',
            'UZS' => '&#1083;&#1074;',
            'VEF' => '&#66;&#115;',
            'VND' => '&#8363;',
            'VUV' => '&#86;&#84;',
            'WST' => '&#87;&#83;&#36;',
            'XAF' => '&#70;&#67;&#70;&#65;',
            'XCD' => '&#36;',
            'XDR' => '',
            'XOF' => '',
            'XPF' => '&#70;',
            'YER' => '&#65020;',
            'ZAR' => '&#82;',
            'ZMK' => '&#90;&#75;', // ?
            'ZWL' => '&#90;&#36;',
        );

        $currency = strtoupper($currency);

        if (isset($currency_symbols[$currency]) && !empty($currency_symbols[$currency])) {
            return html_entity_decode($currency_symbols[$currency]);
        }

        return '';
    }

    public function getStripeSubscription($agencyId)
    {
        $admin = $this->getSuperUser($agencyId);
        $stripeSubscriptionId = '';
        $stripeSubscription = '';
    }

    /**
     * Get Super admin for Business/agency
     *
     * @param (int) $businessId
     * @return mixed
     */

    private function getSuperUser($businessId) 
    {
        $superUser = Users::findFirst(array(
            'conditions' => 'agency_id = ' . $businessId . ' AND role="Super Admin"'
        ));

        if($superUser) {
            $superUser = $superUser->toArray();
        }

        return $superUser;
    }

    /**
     * Get Stripe subscription data in our db
     *
     * @param (int) $businessId
     * @return mixed array or false
     */

    private function getSubscriptionFromDb($businessId) 
    {
        $superUser = $this->getSuperUser($businessId);
        
        if (!$superUser) {
            return false;
        }

        $userId = $superUser['id'];

        $subscription = StripeSubscriptions::findFirst(
            'user_id = ' . $userId
        );

        if (count($subscription) === 0 || $subscription === false) {
            return false;
        }

        return $subscription->toArray();
    }

    /**
     * Does subscription exist in Stripe and is it active
     *
     * @param (string) $subscriptionId
     * @return (bool)
     */

    private function subscriptionExistsInStripe($subscriptionId) 
    {
        // is status active, trialing, active, past_due, canceled, or unpaid
        try {
            $status = \Stripe\Subscription::retrieve($subscriptionId)->status;
            
            if($status === 'active') {
                return true;
            }

            return false;
        } catch (\Stripe\Error\InvalidRequest $e) {
            $err = $e->getJsonBody();
            $message = $err['error']['message'];

            if (strpos($message, 'No such subscription') !== false) {
                return false;
            }

            // attribute to Stripe error
            return true;
        } catch (Exception $e) {
            // stripe error
            return true;
        }
    }


    public function isStripeSubscriptionActive($agencyId)
    {
        $subscriptionDB = $this->getSubscriptionFromDb($agencyId);
        $subscriptionId = $subscriptionDB['stripe_subscription_id'];

        if ($subscriptionId === false || $subscriptionId === 'N' || empty($subscriptionId)) {
            return false;
        }

        $subscription = $this->subscriptionExistsInStripe($subscriptionId);

        if ($subscription) {
            return true;
        }

        return false;
    }

    public function pauseSubscription($agencyId)
    {
        $subscriptionDB = $this->getSubscriptionFromDb($agencyId);
        $subscriptionId = $subscriptionDB['stripe_subscription_id'];

        try {
            // retrieve pause coupon
            \Stripe\Coupon::retrieve("pause");
        } catch(\Stripe\Error\Base $e) {
            // create if doesn't exist
            \Stripe\Coupon::create(array(
                "percent_off" => 100,
                "duration" => "forever",
                "id" => "pause"
            ));
        } catch(Exception $e) {
            // create if doesn't exist
            \Stripe\Coupon::create(array(
                "percent_off" => 100,
                "duration" => "forever",
                "id" => "pause"
            ));
        }

        // retrieve subscription
        if (!empty($subscriptionId)) {
            try {
                $subscription = \Stripe\Subscription::retrieve($subscriptionId);
                $subscription->coupon = 'pause';
                $subscription->save();
            } catch (\Stripe\Error\InvalidRequest $e) {
                return 0;
            } catch(\Stripe\Error\Base $e) {
                return 0;
            } catch(Exception $e) {
                return 0;
            }
        } else {
            return 0;
        }
    }

    public function unpauseSubscription($agencyId)
    {
        $subscriptionDB = $this->getSubscriptionFromDb($agencyId);
        $subscriptionId = $subscriptionDB['stripe_subscription_id'];

        try {
            // retrieve pause coupon
            \Stripe\Coupon::retrieve("pause");
        } catch(\Stripe\Error\Base $e) {
            // create if doesn't exist
            \Stripe\Coupon::create(array(
                "percent_off" => 100,
                "duration" => "forever",
                "id" => "pause"
            ));
        } catch(Exception $e) {
            // create if doesn't exist
            \Stripe\Coupon::create(array(
                "percent_off" => 100,
                "duration" => "forever",
                "id" => "pause"
            ));
        }

        // retrieve subscription
        if (!empty($subscriptionId)) {
            try {
                $subscription = \Stripe\Subscription::retrieve($subscriptionId);
                $subscription->coupon = null;
                $subscription->save();
            } catch (\Stripe\Error\InvalidRequest $e) {
                return 0;
            } catch(\Stripe\Error\Base $e) {
                return 0;
            } catch(Exception $e) {
                return 0;
            }
        }


    }
}