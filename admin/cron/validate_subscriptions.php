<?php

require 'bootstrap.php';

use Vokuro\Services\SubscriptionManager;
use Vokuro\Models\Agency as Agency;


$SubscriptionManager = new SubscriptionManager();

// Business id

/*


select * from stripe_subscriptions where stripe_subscription_id IN(null,'N');
cancel them
then iterate over ids, see if they’re valid and active in stripe
if not then cancel them?

Select all businesses

Check to see if a subscription exists which means it either doesn't have a row, or its set to NULL / ’N'

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

//$agencyId = 1;

//var_dump($SubscriptionManager->GetBusinessSubscriptionLevel($agencyId));
/**
* 
*/
class ValidateSubscriptions
{
	
	function __construct() {
		// generate report dry run
		
		$this->getBusinesses();
		$this->getAgencies();
	}

	function getBusinesses() {
		// parent_id > 0 or parent_id = -1

		$agency = Agency::find(array(
			"conditions" => "parent_id > 0 OR parent_id = -1"
		));

		$num = count($agency);

		if($num === 0) {
			print "no businesses\n";
			return false;
		}

		print "$num businesses\n";
	}

	function getAgencies() {
		// parent_id == 0
		$agency = Agency::find(array(
			"conditions" => "parent_id = 0"
		));

		$num = count($agency);

		if($num === 0) {
			print "no agencies\n";
			return false;
		}

		print "$num agencies\n";
	}

	// Check to see if a subscription exists which means it either doesn't have a row, or its set to NULL / ’N'

	private function subscriptionExists($BusinessId) {

		// IN(NULL,’N')
	}

	// see if its a trial account

	private function isTrial($BusinessId) {
		# code...
	}

	// if its a trial account and its been active for more than X (I think 45, but need to ask) days

	private function trialDays() {

	}
}

new ValidateSubscriptions();
