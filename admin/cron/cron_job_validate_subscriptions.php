<?php

require 'bootstrap.php';

use Phalcon\Mvc\Controller;
use Vokuro\Services\SubscriptionManager;
use Vokuro\Models\Agency as Agency;
use Vokuro\Models\StripeSubscriptions as Subscription;
use Vokuro\Models\Users as User;
use Phalcon\Db\Adapter\Pdo\Mysql as Connection;
use Phalcon\Events\Manager;
use Vokuro\Controllers\Stripe;

/*


Select all businesses

Check to see if a subscription exists which means it has a row and stripe_subscription_id is not set to NULL / ’N'

Then check stripe for subscription

if it doesn't exist, you need to see if its a trial account
if its a trial account and its been active for more than X (I think 45, but need to ask) days, then disable the business
if its a free account, just skip it
if its a paid account and we have no record of a subscription, that's an error and I think its happening
if its a paid account, it should have a subscription, so check with stripe to make sure the subscription is still active and paid for.  If it isn't, disable the business
You can just set the status to off, don't delete it.  Which leads to another issue.  The status of the business isn't working :)

Off the top of my head, those are the cases that need to be handled
There's probably a couple others
I believe there is a method in the SubscriptionManager service that determins what type of plan it is
Just look at whatever is called on the business subscription plan page


*/

/* 
parent_id = 0 // Agency
parent_id > 0 // Business under an agency
parent_id = -1 // Business under RV (not a case right now I don't think)
*/

/**
* 
*/
class ValidateSubscriptions extends Controller
{
	public function index() 
	{

		$this->connectToStripe();

		$testBusinessId = 77; // 77,75
		$testUserId = 157;// 157, 155

		// generate report dry run

		/*$businesses = $this->getAllBusinesses();

		if($businesses) {
			$this->checkEntities($businesses);
		}*/

		$agencies = $this->getAllAgencies();

		if ($agencies) {
			$disableThese = $this->checkEntities($agencies);
		}

		// disable accounts

		var_dump($disableThese);		
	}

	/**
	 * Look for paid accounts not paying us
	 *
	 * @param (array) businesses and/or agencies
	 * @return mixed array of agency ids or false
	 */

	private function checkEntities($entities)
	{
		$paid = 0;
		$cancelAccounts = [];
		
		foreach ($entities as $entity) {
			$entityId = $entity['agency_id'];
			$type = $this->getSubscriptionType($entityId);

			if ($type === 'PD') {
				$paid++;

				// check db
				if (!$this->subscriptionExistsInDb($entityId)) {
					// send report
				} else if ($subscription = $this->getSubscriptionFromDb($entityId)) {
					$subscriptionId = $subscription['stripe_subscription_id'];
					$customerId = $subscription['stripe_customer_id'];
					
					if (!empty($subscriptionId) && $subscriptionId != 'N') {
						if(!$this->subscriptionExistsInStripe($subscriptionId)) {
							array_push($cancelAccounts, $entityId);
						}
					} else if (!empty($customerId) && $customerId != 'N') {
						// get subscription by customer id
					}
				}
			}

			
			/*PD = if its a paid account and we have no record of a subscription, that's an error and I think its happening*/
			// check db
			// if no record in db send report
			// check stripe
			// if not active or doesn't exist disable account
			/*PD = if its a paid account, it should have a subscription, so check with stripe to make sure the subscription is still active and paid for.*/


		}

		print $paid . ' paid entities' . "\n";
		
		return $cancelAccounts;
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
	 * Get All businesses
	 *
	 * @return mixed business array or false
	 **/

	private function getAllBusinesses() 
	{
		// parent_id > 0 or parent_id = -1

		$businesses = Agency::find(array(
			"conditions" => "parent_id > 0 OR parent_id = -1"
		));

		$num = count($businesses);

		if ($num === 0) {
			return false;
		}

		return $businesses->toArray();
	}

	/**
	 * Get All Agencies
	 *
	 * @return mixed business array or false
	 */

	private function getAllAgencies() 
	{
		// parent_id == 0

		$agencies = Agency::find(array(
			"conditions" => "parent_id = 0"
		));

		$num = count($agencies);

		if ($num === 0) {
			return false;
		}

		return $agencies->toArray();
	}

	/**
	 * Does the subscription exist in our Database
	 *
	 * @param (int) $businessId
	 * @return (bool)
	 */

	private function subscriptionExistsInDb($businessId)
	{	
		// find stripe subscription in database
		$subscriptionDb = $this->getSubscriptionFromDb($superUser['id']);

		// no stripe subscription record found
		if(!$subscriptionDb) {
			return false;
		}

		$subscriptionId = $subscriptionDb['stripe_subscription_id'];
		
		// if no stripe_subscription_id subscription does not exist in our db
		if($subscriptionId === NULL || $subscriptionId === 'N') {
			return false;
		};

		return true;
	}

	/**
	 * Get type of subscription
	 *
	 * @param (int) $businessId
	 * @return 
	 */

	private function getSubscriptionType($businessId) 
	{
		$subscriptionManager = new SubscriptionManager();
		return $subscriptionManager->GetBusinessSubscriptionLevel($businessId);
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

		$subscription = Subscription::findFirst(
			'user_id = '.$userId
		);

		if (count($subscription) === 0) {
			return false;
		}

		return $subscription->toArray();
	}

	private function skipMessage($agency, $type) 
	{
		return $agency['name'].' is account type ' . $type . ' and will be skipped'."\n";
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


	function cancelSubcriptionInDb() {
		// set to NULL
	}

	/**
	 * Get Super admin for Business/agency
	 *
	 * @param (int) $businessId
	 * @return mixed
	 */

	private function getSuperUser($businessId) 
	{
		$superUser = User::findFirst(array(
            'conditions' => 'agency_id = ' . $businessId . ' AND role="Super Admin"'
        ));

        if($superUser) {
        	$superUser = $superUser->toArray();
        }

        return $superUser;
	}

	private function addStripeSubscriptionToDb($customerId) 
	{
		// Subscription
	}

	/**
	 * Disable Business
	 *
	 * @param (int) $businessId
	 * @return bool
	 */

	private function disableBusiness($businessId) 
	{
		// set agency.status to 0
		
		$business = Agency::findFirst(
			'agency_id = ' . $businessId
		);

		$business->status = 0;

		$business->save();
	}

	/**
	 * See if its a trial account
	 * @param (int) $BusinessId
	 * @return bool
	 **/

	private function isTrial($BusinessId) 
	{
		# code...
	}
}

 $ValidateSubscriptions = new ValidateSubscriptions();
 $ValidateSubscriptions->index();
