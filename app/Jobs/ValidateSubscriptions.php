<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Stripe;
use \App\Agency;
use \App\SubscriptionInvalidation;
use Illuminate\Support\Facades\DB;

class ValidateSubscriptions implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        $this->index();
    }

    public function index() 
    {

        $this->connectToStripe();
        // generate report dry run

        // get all active businesses
        /*$businesses = $this->getAllActiveBusinesses();

        if ($businesses) {
            $businesses = $this->checkBusinesses($businesses);
        }

        var_dump($businesses);

        foreach ($businesses as $business) {
            var_dump($business);
            SubscriptionInvalidation::create($business);
        }

        /*$agency = new Agency();
        
        foreach ($disableThese as $businessId) {
            $agency->disable($businessId);

            $business = Agency::findFirst($businessId);

            $business->subscription_valid = 'N';

            $business->save();
        }*/

        // get all active agencies
        $agencies = $this->getAllActiveAgencies();

        if ($agencies) {
            $agencies = $this->checkAgencies($agencies);
        }

        //var_dump($disableThese);

        foreach ($agencies as $agency) {
            SubscriptionInvalidation::updateOrCreate(
                ['agency_id' => $agency['agency_id']],
                $agency
            );
        }


        /*// deactivate agency and businesses owned by agency

        foreach ($disableThese as $agencyId) {
            $this->deactivateAgencyAndBusinesses($agencyId);
        }*/
    }

    /**
     * Look for paid accounts not paying us
     *
     * @param (array) businesses and/or agencies
     * @return mixed array of agency ids or false
     */

    private function checkAgencies($agencies)
    {
        $paid = 0;
        $cancelAccounts = [];
        
        foreach ($agencies as $agency) {
            $agencyId = $agency->agency_id;

            //$type = $this->getSubscriptionType($entityId);
            //print 'agency: '.$entityId.' is '.$type."\n";
 
            // check db
            if (!$this->subscriptionExistsInDb($agencyId)) {
                // send report
            } else if ($subscription = $this->getSubscriptionFromDb($agencyId)) {
                $subscriptionId = $subscription->stripe_subscription_id;
                $customerId = $subscription->stripe_customer_id;
                
                if (!empty($subscriptionId) && $subscriptionId != 'N') {
                    if (!$this->subscriptionExistsInStripe($subscriptionId)) {
                        print 'AgencyId ' . $agencyId . ' subscription invalid' . "\n";

                        $agencyData = [
                            'agency_type' => 'agency',
                            'agency_id' => $agency->agency_id,
                            'name' => $agency->name,
                            'email' => '',
                            'stripe_exists_in_db' => 1,
                            'stripe_subscription_exists' => 1,
                        ];

                        array_push($cancelAccounts, $agencyData);
                    }
                } else if (!empty($customerId) && $customerId != 'N') {
                    // get subscription by customer id
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
     * Deactivate agency and businesses underneath agency
     * 
     * @param (int) $agencyId
     * @return void
     **/

    private function deactivateAgencyAndBusinesses($agencyId)
    {
        $agency = new Agency();
        $agency->disable($agencyId);
        $agency->deactivateBusinesses($agencyId);

        $agency = Agency::findFirst($agencyId);

        $agency->subscription_valid = 'N';

        $agency->save();
    }

    /**
     * Activate agency and businesses underneath agency
     * 
     * @param (int) $agencyId
     * @return void
     **/

    private function activateAgencyAndBusinesses($agencyId)
    {
        $agency = new Agency();
        $agency->enable($agencyId);
        $agency->activateBusinesses($agencyId);
    }

    private function checkBusinesses($businesses)
    {
        $paid = 0;
        $cancelAccounts = [];
        
        foreach ($businesses as $business) {
            $entityId = $business->agency_id;

            //$type = $this->getBusinessSubscriptionLevel($entityId);
            //print $entityId . ' type is ' . $type . "\n";

            //if (in_array($type, array('PD', 'M', 'Y', 'TR', 'Monthly', 'Annually'))) {
            //  $paid++;

                // check db
                if (!$this->subscriptionExistsInDb($entityId)) {
                    //print $entityId . ' subscription not found in db'."\n";
                    // send report
                } else if ($subscription = $this->getSubscriptionFromDb($entityId)) {
                    print $entityId . 'subscription found in db';
                    $subscriptionId = $subscription->stripe_subscription_id;
                    $customerId = $subscription->stripe_customer_id;
                    
                    if (!empty($subscriptionId) && $subscriptionId != 'N') {

                        print $entityId . 'subscription db data not empty' . "\n";
                        
                        if (!$this->subscriptionExistsInStripe($subscriptionId)) {
                            print 'BusinessId ' . $entityId . ' subscription invalid' . "\n";

                            array_push($cancelAccounts, $entityId);
                        } else {
                        }
                    } else if (!empty($customerId) && $customerId != 'N') {
                        // get subscription by customer id
                    }
                }
            //} else {
                //print $entityId . ' is free' ."\n";
            //}

            
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

    public function connectToStripe() 
    {
        $this->stripePublic = 'pk_test_tF7h7sPZNH6RbzCPSuKAJtdn';
        $this->stripeSecret = 'sk_test_ArjGi2GlWz5pfmyezAqx8ZFC';

        \Stripe\Stripe::setApiKey($this->stripeSecret);

        //var_dump(\Stripe\Account::all());
    }

    /**
     * Get All Active businesses
     *
     * @return mixed business array or false
     **/

    public function getAllActiveBusinesses() 
    {
        // parent_id > 0 or parent_id = -1


        $businesses = DB::table('agency')
                        ->where('status', 1)
                        ->where(function ($query) {
                            $query->where('parent_id', '>', 0)
                                  ->orWhere('parent_id', -1);
                        })->get();

        $num = count($businesses);

        if ($num === 0) {
            return false;
        }

        return $businesses;
    }

    /**
     * Get All Active Agencies
     *
     * @return mixed business array or false
     */

    private function getAllActiveAgencies() 
    {
        // parent_id == 0

        $agencies = Agency::find(array(
            "conditions" => "status = 1 AND parent_id = 0"
            //"conditions" => "agency_id = 592"
        ));

        $agencies = DB::table('agency')
                      ->where('status', 1)
                      ->where('parent_id', 0)
                      ->get();

        $num = count($agencies);

        if ($num === 0) {
            return false;
        }

        return $agencies;
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
        $subscriptionDb = $this->getSubscriptionFromDb($businessId);

        // no stripe subscription record found
        if (!$subscriptionDb) {
            return false;
        }

        $subscriptionId = $subscriptionDb->stripe_subscription_id;
        
        // if no stripe_subscription_id subscription does not exist in our db
        if ($subscriptionId === NULL || $subscriptionId === 'N') {
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

    private function getBusinessSubscriptionLevel($businessId)
    {
        // get super user
        $user = $this->getSuperUser($businessId);

        if (!$user) {
            return false;
        }
        
        // get business subscription
        $subscription = BusinessSubscriptionPlan::findFirst(array(
            'conditions' => 'user_id = ' . $user['id']
        ));

        if (!$subscription) {
            return false;
        }

        // return subscription type
        return $subscription->payment_plan;
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

        $userId = $superUser->id;

        $subscription = DB::table('stripe_subscriptions')
                          ->where('user_id', $userId)
                          ->first();

        if (!$subscription) {
            return false;
        }

        return $subscription;
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
            
            if ($status === 'active' || $status === 'trialing') {
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

    /**
     * Get Super admin for Business/agency
     *
     * @param (int) $businessId
     * @return mixed
     */

    private function getSuperUser($businessId) 
    {
        $superUser = DB::table('users')
                       ->where('agency_id', $businessId)
                       ->where('role', 'Super Admin')
                       ->first();

        if ($superUser) {
            $superUser = $superUser;
        } else {
            return false;
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
