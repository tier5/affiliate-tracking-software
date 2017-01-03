<?php

namespace Vokuro\Services;

use Vokuro\ArrayException;
use Vokuro\Services\ServicesConsts;
use Vokuro\Models\BusinessSubscriptionPlan;
use Vokuro\Models\SubscriptionPricingPlan;
use Vokuro\Models\SubscriptionPricingPlanParameterList;
use Vokuro\Models\BusinessSubscriptionInvitation;
use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;

class SubscriptionManager extends BaseService {

    function __construct($config = null, $di = null) {
        parent::__construct($config, $di);
    }

    public function creditCardInfoRequired($session) {
        $userManager = $this->di->get('userManager');
        $paymentService = $this->di->get('paymentService');

        $userId = $userManager->getUserId($session);

        $objUser = \Vokuro\Models\Users::findFirst('id = ' . $userId);

        // Get super admin user id
        $objSuperUser = \Vokuro\Models\Users::findFirst('agency_id = ' . $objUser->agency_id . ' AND role="Super Admin"');

        $objAgency = \Vokuro\Models\Agency::findFirst('agency_id = ' . $objUser->agency_id);

        $subscriptionPlan = $this->getSubscriptionPlan($objSuperUser->id, $objAgency->subscription_id);
        $payment_plan = $subscriptionPlan['subscriptionPlan']['payment_plan'];

        // GARY_TODO:  Somehow all payment_plans are getting started at Monthly.
        if (!$payment_plan || $payment_plan === ServicesConsts::$PAYMENT_PLAN_FREE || $payment_plan == ServicesConsts::$PAYMENT_PLAN_TRIAL || $subscriptionPlan['pricing_plan']['enable_trial_account']) {
            return false;
        }

        $provider = ServicesConsts::$PAYMENT_PROVIDER_STRIPE;
        $paymentProfile = $paymentService->getPaymentProfile([ 'userId' => $objSuperUser->id, 'provider' => $provider ]);

        // GARY_TODO:  Add cron script to reset customer_id on expired / invalid cards.
        if(!$paymentProfile || !$paymentProfile['customer_id'])
            return true;

        return false;
    }

    public function GetBusinessSubscriptionLevel($BusinessID) {
        $subscriptionManager = $this->di->get('subscriptionManager');

        /* Get Super Admin */
        $objBusiness = \Vokuro\Models\Agency::findFirst("agency_id = {$BusinessID}");
        $objSuperUser = \Vokuro\Models\Users::findFirst('agency_id = ' . $objBusiness->agency_id . ' AND role="Super Admin"');

        /* Get the subscription plan */
        $subscriptionPlanData = $subscriptionManager->getSubscriptionPlan($objSuperUser->id, $objBusiness->subscription_id);
        

        //echo 'yyy';print_r($subscriptionPlanData);exit;
        if($subscriptionPlanData['subscriptionPlan']['payment_plan']) {
            return $subscriptionPlanData['subscriptionPlan']['payment_plan'];
        } else {
            if($subscriptionPlanData === false)
                return ServicesConsts::$PAYMENT_PLAN_FREE;

            return  $subscriptionPlanData['pricingPlan']['enable_trial_account'] ? ServicesConsts::$PAYMENT_PLAN_TRIAL : ServicesConsts::$PAYMENT_PLAN_PAID;
        }
    }

    public function GetBusinessSubscriptionUpgradeDiscount($BusinessID) {
        $subscriptionManager = $this->di->get('subscriptionManager');

        /* Get Super Admin */
        $objBusiness = \Vokuro\Models\Agency::findFirst("agency_id = {$BusinessID}");
        $objSuperUser = \Vokuro\Models\Users::findFirst('agency_id = ' . $objBusiness->agency_id . ' AND role="Super Admin"');

        /* Get the subscription plan */
        $subscriptionPlanData = $subscriptionManager->getSubscriptionPlan($objSuperUser->id, $objBusiness->subscription_id);

        // GARY_TODO:  Fix this stupid typo in upgrade_discount.
        if($subscriptionPlanData['pricingPlan']['updgrade_discount']) {
            return intval($subscriptionPlanData['pricingPlan']['updgrade_discount']);
        } else {
            return 0;
        }
    }
    public function GetMaxSMS($BusinessID, $LocationID) {
        $objSuperAdmin = \Vokuro\Models\Users::findFirst("agency_id = {$BusinessID} and role='Super Admin'");
        $objBusiness = \Vokuro\Models\Agency::findFirst("agency_id = {$BusinessID}");

        if(!$objBusiness->subscription_id) {
            // This mean plan is "Unpaid" or free basically
            $objSubscriptionPlan = \Vokuro\Models\BusinessSubscriptionPlan::findFirst("user_id = {$objSuperAdmin->id}");
            $MaxAllowed = 100;
            if($objSubscriptionPlan) {
                // We are a paid member, get subscription details.
                $MaxAllowed = $objSubscriptionPlan->sms_messages_per_location;
            } else {
                // We're in a trial state, use trial numbers
                $objSubscriptionPricingPlan = \Vokuro\Models\SubscriptionPricingPlan::findFirst("id = {$objBusiness->subscription_id}");
                $MaxAllowed = $objSubscriptionPricingPlan->max_messages_on_trial_account;
            }
        }
        else {
            $objSubscriptionPlan = \Vokuro\Models\BusinessSubscriptionPlan::findFirst("user_id = {$objSuperAdmin->id}");
            if($objSubscriptionPlan) {
                // We are a paid member, get subscription details.
                $MaxAllowed = $objSubscriptionPlan->sms_messages_per_location;
            } else {
                // We're in a trial state, use trial numbers
                $objSubscriptionPricingPlan = \Vokuro\Models\SubscriptionPricingPlan::findFirst("id = {$objBusiness->subscription_id}");
                $MaxAllowed = $objSubscriptionPricingPlan->max_messages_on_trial_account;
            }
        }

        return $MaxAllowed + $this->GetViralSMSCount($BusinessID);
    }

    public function UpdateStripeSubscription($UserID, $Price) {
        $objStripeSubscription = \Vokuro\Models\StripeSubscriptions::findFirst("user_id = {$UserID}");
        echo "<PRE>";
        print_r($objStripeSubscription);
        die();
    }

    public function ReachedMaxSMS($BusinessID, $LocationID) {
        $objSuperAdmin = \Vokuro\Models\Users::findFirst("agency_id = {$BusinessID} and role='Super Admin'");
        $objBusiness = \Vokuro\Models\Agency::findFirst("agency_id = {$BusinessID}");

        $start_time = date("Y-m-d", strtotime("first day of this month"));
        $end_time = date("Y-m-d 23:59:59", strtotime("last day of this month"));
        $sms_sent_this_month = 0;
        $FreePlan = false;
        if ($LocationID) {
            $CurrentCount = \Vokuro\Models\ReviewInvite::count(
                array(
                    "column" => "review_invite_id",
                    "conditions" => "date_sent >= '" . $start_time . "' AND date_sent <= '" . $end_time . "' AND location_id = {$LocationID} AND sms_broadcast_id IS NULL",
                )
            );
        } else {
            return false;
        }

        if(!$objBusiness->subscription_id) {
            if($this->GetBusinessSubscriptionLevel($objBusiness->agency_id) == ServicesConsts::$PAYMENT_PLAN_FREE) {
                // Free
                $objSubscriptionPlan = \Vokuro\Models\BusinessSubscriptionPlan::findFirst("user_id = {$objSuperAdmin->id}");
                $MaxAllowed = $objSubscriptionPlan->sms_messages_per_location;
                $FreePlan = true;
            } else {
                // EDGE_CASE #1:  This case should ever happen.  If so, we're defaulting to 100
                $MaxAllowed = 100;
            }
        }
        else {
            $objSubscriptionPlan = \Vokuro\Models\BusinessSubscriptionPlan::findFirst("user_id = {$objSuperAdmin->id}");
            if($objSubscriptionPlan) {
                // We are a paid member, get subscription details.
                $MaxAllowed = $objSubscriptionPlan->sms_messages_per_location;
            } else {
                // We're in a trial state, use trial numbers
                $objSubscriptionPricingPlan = \Vokuro\Models\SubscriptionPricingPlan::findFirst("id = {$objBusiness->subscription_id}");
                $MaxAllowed = $objSubscriptionPricingPlan->max_messages_on_trial_account;
            }
        }

        return ['FreePlan' => $FreePlan, 'ReachedLimit' => $CurrentCount >= $MaxAllowed];
    }

    public function ReachedMaxLocations($BusinessID) {
        $objSuperAdmin = \Vokuro\Models\Users::findFirst("agency_id = {$BusinessID} and role='Super Admin'");
        $objBusiness = \Vokuro\Models\Agency::findFirst("agency_id = {$BusinessID}");
        $dbLocations = \Vokuro\Models\Location::find("agency_id = {$BusinessID}");
        $CurrentCount = count($dbLocations);
        $FreePlan = false;
        if(!$objBusiness->subscription_id) {
            if($this->GetBusinessSubscriptionLevel($objBusiness->agency_id) == ServicesConsts::$PAYMENT_PLAN_FREE) {
                $objSubscriptionPlan = \Vokuro\Models\BusinessSubscriptionPlan::findFirst("user_id = {$objSuperAdmin->id}");
                $MaxAllowed = $objSubscriptionPlan->locations;
                $FreePlan = true;
            } else {
                // EDGE_CASE #2:  This case should ever happen.  If so, we're defaulting to 100
                $MaxAllowed = 1;
            }
        }
        else {
            $objSubscriptionPlan = \Vokuro\Models\BusinessSubscriptionPlan::findFirst("user_id = {$objSuperAdmin->id}");
            $MaxAllowed = $objSubscriptionPlan->locations;
        }

        return ['FreePlan' => $FreePlan, 'ReachedLimit' => $CurrentCount >= $MaxAllowed];
    }

    public function getSubscriptionPricingPlans($tUserIDs = []) {
        if(count($tUserIDs) > 0) {
            return $subscriptionPricingPlans = SubscriptionPricingPlan::query()
            ->where("enabled = true")
            ->andWhere("deleted_at = '0000-00-00 00:00:00'")
            ->andWhere("user_id IN (" . implode(',', $tUserIDs) . ")")
            ->execute();
        } else {
            return $subscriptionPricingPlans = SubscriptionPricingPlan::query()
            ->where("enabled = true")
            ->andWhere("deleted_at = '0000-00-00 00:00:00'")
            ->execute();
        }
    }

    public function getActiveSubscriptionPlans($user_id = null) {

        $plans = SubscriptionPricingPlan::query()
            ->where('enabled = true');
        if($user_id) $plans->andWhere('user_id = :user_id',['user_id'=>$user_id]);
            $plans->order('id');
            return $plans->execute();
    }

    public function getActiveSubscriptionPlan() {
        $results = $this->getActiveSubscriptionPlans();
        //echo '<pre>';print_r($results);exit;
        //if we only have one active.. or the one with the latest id.. then we return that one
        if($results && $results[0]) return $results[0];
        throw new \Exception('No active subscription plans found');
    }

    /**
     * @param $UserID
     * @param $RecurringPayment Monthly fee
     * @param int $InitialFee Initial fee
     * @param bool $SkipInitial
     * @return bool
     * @throws \Exception
     */
    public function createAgencySubscription($UserID, $PlanID, $RecurringPayment, $InitialFee = 0, $SkipInitial = false) {
        try {
            $objPaymentService = $this->di->get('paymentService');

            $tParameters = [
                'userId'                    => $UserID,
                'provider'                  => ServicesConsts::$PAYMENT_PROVIDER_STRIPE,
                'amount'                    => $RecurringPayment * 100,
                'initial_amount'            => $SkipInitial ? 0 : $InitialFee * 100,
                'type'                      => 'Agency',
            ];

            // GARY_TODO:  Refactor:  No reason to have to query the DB here.
            $objUser = \Vokuro\Models\Users::findFirst("id = " . $UserID);
            $objAgency = \Vokuro\Models\Agency::findFirst("agency_id = {$objUser->agency_id}");

            // This method is potentially called twice (To upgrade in the thank you action)
            $objAgencySubscriptionPlan = \Vokuro\Models\AgencySubscriptionPlan::findFirst("agency_id = {$objAgency->agency_id}");
            if(!$objAgencySubscriptionPlan)
                $objAgencySubscriptionPlan = new \Vokuro\Models\AgencySubscriptionPlan();

            $objAgencySubscriptionPlan->agency_id = $objAgency->agency_id;
            $objAgencySubscriptionPlan->pricing_plan_id = $PlanID;
            $objAgencySubscriptionPlan->save();

            if (!$objPaymentService->changeSubscription($tParameters)) {
                $this->flashSession->error('Could not create subscription.  Contact customer support.');
                return false;
            }

        } catch (Exception $e) {
            $this->flashSession->error($e->getMessage());
            return false;
        }
        return true;
    }

    public function createSubscriptionPlan($newSubscriptionParameters) {
        try {

            $userId = $newSubscriptionParameters['userAccountId'];

            /* Configure subscription parameters */
            if ($newSubscriptionParameters['pricingPlanId']) {

                $subscriptionPricingPlan = SubscriptionPricingPlan::query()
                    ->where("id = :id:")
                    ->bind(["id" => $newSubscriptionParameters['pricingPlanId']])
                    ->execute()
                    ->getFirst();
                $pricingPlanId = $subscriptionPricingPlan->id;
                if ($subscriptionPricingPlan->enable_trial_account) {
                    $paymentPlan = ServicesConsts::$PAYMENT_PLAN_TRIAL;
                    $locations = 1;
                    $smsMessagesPerLocation = $subscriptionPricingPlan->max_messages_on_trial_account;
                } else {
                    $paymentPlan = ServicesConsts::$PAYMENT_PLAN_MONTHLY;
                    $locations = 0;
                    $smsMessagesPerLocation = 0;
                }

            } else  {
                $pricingPlanId = 0;
                $locations = $newSubscriptionParameters['freeLocations'];
                $smsMessagesPerLocation = $newSubscriptionParameters['freeSmsMessagesPerLocation'];
                $paymentPlan = ServicesConsts::$PAYMENT_PLAN_FREE;

            }

            $db = $this->di->get('db');
            $db->begin();

            /* Create the subscription plan */
            $subscriptionPlan = new BusinessSubscriptionPlan();
            $subscriptionPlan->user_id = intval($userId);
            $subscriptionPlan->locations = intval($locations);
            $subscriptionPlan->sms_messages_per_location = intval($smsMessagesPerLocation);
            $subscriptionPlan->payment_plan = $paymentPlan;
            $subscriptionPlan->subscription_pricing_plan_id = intval($pricingPlanId);
            if (!$subscriptionPlan->create()) {
                throw new ArrayException("", 0, null, $subscriptionPlan->getMessages());
            }

            $db->commit();

        } catch(ArrayException $e) {

            if (isset($db)) {
                $db->rollback();
            }
            return $e->getOptions();

        }

        return true;
    }

    public function changeSubscriptionPlan($subscriptionParameters) {

        $subscriptionPlan = BusinessSubscriptionPlan::query()
            ->where("user_id = :userId:")
            ->bind(["userId" => $subscriptionParameters['userId']])
            ->execute()
            ->getFirst();
        if (!$subscriptionPlan) {
            throw new \Exception("Could not find subscription plan for user id " . $subscriptionParameters['userId']);
            return false;
        }

        $subscriptionPlan->locations = $subscriptionParameters['locations'];
        $subscriptionPlan->sms_messages_per_location = $subscriptionParameters['messages'];
        $subscriptionPlan->payment_plan = $subscriptionParameters['planType'];
        if (!$subscriptionPlan->save()) {
            throw new \Exception("Could not save subscription plan - " . implode('.  ', $subscriptionPlan->getMessages()));
            return false;
        }

        return true;
    }

    public function getSubscriptionPlan($userId, $subscription_pricing_plan_id) {
        /* Get subscription plan */
        $subscriptionPlan = BusinessSubscriptionPlan::query()
            ->where("user_id = :user_id:")
            ->bind(["user_id" => intval($userId)])
            ->execute()
            ->getFirst();
        /*if(!$subscriptionPlan) {
            return false;
        }*/

        /* Get the pricing plan */
        $pricingPlan = SubscriptionPricingPlan::query()
            ->where("id = :id:")
            ->bind(["id" => intval($subscription_pricing_plan_id)])
            ->execute()
            ->getFirst();
        if (!$pricingPlan) {
            return false;
        }

        /* Get the parameter lists */
        $parameterLists = SubscriptionPricingPlanParameterList::query()
            ->where("subscription_pricing_plan_id = :subscription_pricing_plan_id:")
            ->bind(["subscription_pricing_plan_id" => intval($pricingPlan->id)])
            ->execute();
        if (!$parameterLists) {
            return false;
        }

        /* Build the plan data */
        $subscriptionPlanData = [];
        $subscriptionPlanData['subscriptionPlan'] = $subscriptionPlan ? $subscriptionPlan->toArray() : [];
        $subscriptionPlanData['pricingPlan'] = $pricingPlan->toArray();


        $subscriptionPlanData['pricingPlanParameterLists'] = [];
        foreach($parameterLists as $parameterList) {
            $subscriptionPlanData['pricingPlanParameterLists'][$parameterList->max_locations] = $parameterList->toArray();
        }

        return $subscriptionPlanData;
    }

    public function isValidInvitation($subscriptionToken) {
        $businessSubscriptionInvitation = BusinessSubscriptionInvitation::query()
            ->where("token = :token:")
            ->bind(["token" => $subscriptionToken])
            ->execute()
            ->getFirst();
        if(!$businessSubscriptionInvitation) {
            return false;
        }
        return true;
    }

    public function invalidateInvitation($subscriptionToken) {
        $businessSubscriptionInvitation = BusinessSubscriptionInvitation::query()
            ->where("token = :token:")
            ->bind(["token" => $subscriptionToken])
            ->execute()
            ->getFirst();
        if (!$businessSubscriptionInvitation) {
            return ['There is no invitation associated to that token'];
        }
        $businessSubscriptionInvitation->deleted_at = date('Y-m-d');
        if (!$businessSubscriptionInvitation->update()) {
            return $businessSubscriptionInvitation->getMessages();
        }
        return true;
    }

    public function getPricingPlanById($pricingPlanId) {
        $subscriptionPricingPlan = SubscriptionPricingPlan::query()
            ->where("id = :id:")
            ->bind(["id" => intval($pricingPlanId)])
            ->execute()
            ->getFirst();
        if(!$subscriptionPricingPlan) {
            return false;
        }
        return $subscriptionPricingPlan->toArray();
    }

    public function isPricingPlanLocked($pricingPlanId) {
        $objBusinessSubscription = \Vokuro\Models\Agency::findFirst("subscription_id = {$pricingPlanId} AND parent_id != " . \Vokuro\Models\Agency::AGENCY);

        if(!$objBusinessSubscription) {
            return false;
        }

        return true;
    }

    public function getPricingPlanByName($userId, $pricingPlanName) {
        $subscriptionPricingPlan = SubscriptionPricingPlan::query()
            ->where("user_id = :userId:")
            ->andWhere("name = :pricingPlanName:")
            ->bind(["userId" => $userId, "pricingPlanName" => $pricingPlanName])
            ->execute()
            ->getFirst();
        if(!$subscriptionPricingPlan) {
            return false;
        }
        return $subscriptionPricingPlan->toArray();
    }

    public function savePricingProfile($parameters, $isUpdate) {

        $status = false;

        try {
            $id = $this->saveSubscriptionPricingPlan($parameters, $isUpdate);
            if (!$id) {
                throw new \Exception("Unable to obtain pricing plan ID");
            }

            if (!$this->appendPricingParameterLists($id, $parameters, $isUpdate)) {
                throw new \Exception("Unable to append pricing parameter list");
            }

            $status = true;

        } catch(Exception $e) {}

        return $status;

    }

    public function getAllPricingPlansByUserId($userId) {
        $subscriptionPricingPlans = SubscriptionPricingPlan::query()
            ->where("user_id = :userId:")
            ->andWhere("deleted_at = '0000-00-00 00:00:00'")
            ->bind(["userId" => $userId])
            ->execute();
        return $subscriptionPricingPlans;
    }

    public function toggleViralPlanById ($pricingPlanId) {
        $subscriptionPricingPlan = SubscriptionPricingPlan::findFirst("id = {$pricingPlanId}");
        if(!$subscriptionPricingPlan)
            return false;

        $subscriptionPricingPlan->is_viral = $subscriptionPricingPlan->is_viral ? 0 : 1;
        $subscriptionPricingPlan->save();

        return true;
    }

    /**
     * This method is semi faulty but not in use currently.
     * @param $pricingPlanId
     * @param $enable
     * @return bool
     */
    public function enableViralPlanById ($pricingPlanId, $enable) {
        $subscriptionPricingPlan = SubscriptionPricingPlan::findFirst("id = {$pricingPlanId}");
        if(!$subscriptionPricingPlan)
            return false;

        $subscriptionPricingPlan->is_viral = 1;
        $subscriptionPricingPlan->save();

        // Disable other plans.  All plans under an agency should have the same user id
        $dbSubscriptionPlansToDisable = SubscriptionPricingPlan::find("user_id = {$subscriptionPricingPlan->id} AND id != {$pricingPlanId}");
        foreach($dbSubscriptionPlansToDisable as $objSubscriptionPlan) {
            $objSubscriptionPlan->is_viral = 0;
            $objSubscriptionPlan->save();
        }
        return true;
    }

    public function enablePricingPlanById($pricingPlanId, $enable) {  // Second param is a dirty filthy hack :(, See comment below for details
        $subscriptionPricingPlan = SubscriptionPricingPlan::query()
            ->where("id = :id:")
            ->bind(["id" => $pricingPlanId])
            ->execute()
            ->getFirst();
        if (!$subscriptionPricingPlan) {
            return false;
        }

        $subscriptionPricingPlan->enabled = $enable === 'true' ? 1 : 0;
        $subscriptionPricingPlan->updated_at = time();
        if (!$subscriptionPricingPlan->update()) {
            return false;
        }

        return true;
    }

    public function deletePricingPlanById($pricingPlanId) {
        $subscriptionPricingPlan = SubscriptionPricingPlan::query()
            ->where("id = :id:")
            ->bind(["id" => $pricingPlanId])
            ->execute()
            ->getFirst();
        if (!$subscriptionPricingPlan) {
            return false;
        }

        $subscriptionPricingPlan->deleted_at = time();
        // $result = $db->query("DELETE FROM subscription_pricing_plan WHERE id=" . $subscriptionPricingPlan->id ); // Working now
        if (!$subscriptionPricingPlan->update()) {
            return false;
        }

        return true;
    }

    public function getPricingParameterListsByPricingPlanId($pricingPlanId) {
        return SubscriptionPricingPlanParameterList::find("subscription_pricing_plan_id = ".$pricingPlanId)->toArray();
    }

    private function saveSubscriptionPricingPlan($parameters, $isUpdate) {

        /*
         * REFACTOR: This function is half baked crap - but we're in a rush.
         * Must fix.  MT June 23, 2016
         *
         */
        if ($isUpdate) {
            $subscriptionPricingPlan = SubscriptionPricingPlan::findFirst("name = '" . $parameters['name'] . "' AND user_id = " . $parameters['userId']);
        } else {
            $subscriptionPricingPlan = new SubscriptionPricingPlan();
        }

        if (!$subscriptionPricingPlan) {
            return false;
        }

        $subscriptionPricingPlan->getShortCode();
        $subscriptionPricingPlan->user_id = $parameters["userId"];
        $subscriptionPricingPlan->name = $parameters["name"];
        $subscriptionPricingPlan->enabled = $isUpdate ? $subscriptionPricingPlan->enabled : true;
        $subscriptionPricingPlan->enable_trial_account = $parameters["enableTrialAccount"] ? $parameters["enableTrialAccount"] : 0;
        $subscriptionPricingPlan->enable_discount_on_upgrade = $parameters["enableDiscountOnUpgrade"] ? $parameters["enableDiscountOnUpgrade"] : 0;
        $subscriptionPricingPlan->base_price = $parameters["basePrice"];
        $subscriptionPricingPlan->cost_per_sms = $parameters["costPerSms"];
        $subscriptionPricingPlan->max_messages_on_trial_account = $parameters["maxMessagesOnTrialAccount"];
        $subscriptionPricingPlan->updgrade_discount = $parameters["upgradeDiscount"];
        $subscriptionPricingPlan->charge_per_sms = $parameters["chargePerSms"];
        $subscriptionPricingPlan->max_sms_messages = $parameters["maxSmsMessages"];
        $subscriptionPricingPlan->enable_annual_discount = $parameters["enableAnnualDiscount"] ? $parameters["enableAnnualDiscount"] : 0;
        $subscriptionPricingPlan->annual_discount = $parameters["annualDiscount"];
        $subscriptionPricingPlan->pricing_details = $parameters["pricingDetails"] ? : new \Phalcon\Db\RawValue('default');
        if(!$isUpdate)
            $subscriptionPricingPlan->is_viral = $parameters['isViral'] ?: false;

        if ($isUpdate && !$subscriptionPricingPlan->save()) {
            return false;
        } else if (!$isUpdate && !$subscriptionPricingPlan->create()) {
            return false;
        }

        return $subscriptionPricingPlan->id;
    }

    public function getAgencySubscriptionPricingPlan($Name) {
        $objPricingPlan = \Vokuro\Models\AgencyPricingPlan::findFirst("name='{$Name}'");

        if(!$objPricingPlan)
            $this->flashSession->error("Could not find subscription plan.  Contact customer support.");

        return [
            'InitialFee' => $objPricingPlan->initial_fee,
            'RecurringPayment' => $objPricingPlan->number_of_businesses * $objPricingPlan->price_per_business,
            'PlanID' => $objPricingPlan->id,
        ];
    }

    public function CancelSubscription($AgencyID) {
        $objPaymentService = $this->di->get('paymentService');
        $objSuperUser = \Vokuro\Models\Users::findFirst("role = 'Super Admin' AND agency_id = {$AgencyID}");
        $db = $this->di->get('db');
        $db->begin();
        try {
            $objStripeSubscription = \Vokuro\Models\StripeSubscriptions::findFirst("user_id = {$objSuperUser->id}");
            if ($objStripeSubscription) {
                if ($objStripeSubscription->stripe_subscription_id) {
                    // We have a subscription, remove from stripe
                    if($objPaymentService->cancelStripeSubscription($objStripeSubscription->stripe_subscription_id, $AgencyID)) {
                        $objStripeSubscription->stripe_subscription_id = 'N';
                        $objStripeSubscription->save();
                    }
                }

                // This works, but we don't want to do it.  Just leaving in for future reference.
                /*if($objStripeSubscription->stripe_customer_id) {
                    if($objPaymentService->deleteStripeCustomer($objStripeSubscription->stripe_customer_id, $AgencyID)) {
                        $objStripeSubscription->delete();
                    }
                }*/
            }
        } catch (Exception $e) {
            $db->rollback();
            return false;
        }
        $db->commit();
        return true;
    }
    private function appendPricingParameterLists($id, $parameters, $isUpdate) {

        /* Simply delete and refresh */
        if ($isUpdate) {

            $db = new DbAdapter(array(
                'host' => $this->config->database->host,
                'username' => $this->config->database->username,
                'password' => $this->config->database->password,
                'dbname' => $this->config->database->dbname
            ));
            $db->query("DELETE FROM subscription_pricing_plan_parameter_list WHERE subscription_pricing_plan_id=".$id);
            $db->close();
        }

        foreach($parameters as $segment => $params) {
            if(substr($segment,0,7) !== "segment") {
                continue;
            }

            $pricingParameterList = $this->createPricingParameterList($id, $params);
            if(!$pricingParameterList) {
                return false;
            }
        }

        return true;
    }



    public function CreateDefaultSubscriptionPlan($AgencyID, $IsViral = true) {
        $objSuperUser = \Vokuro\Models\Users::findFirst("agency_id = {$AgencyID} AND role = 'Super Admin'");
        $tParameters = [
            'userId' => $objSuperUser->id,
            'name' => "Default Subscription",
            'enabled' => true,
            'enableTrialAccount' => true,
            'basePrice' => 29.00,
            'costPerSms' => 0.0075,
            'maxMessagesOnTrialAccount' => 100,
            'maxSmsMessages' => 1000,
            'upgradeDiscount' => 10,
            'chargePerSms' => 0.10,
            'enableAnnualDiscount' => true,
            'annualDiscount' => 10,
            'enableDiscountOnUpgrade' => true,
            'isViral' => $IsViral
        ];

        if($SubID = $this->saveSubscriptionPricingPlan($tParameters, false)) {
            for($c = 1; $c <= 10; $c++) {
                $tSegment = [
                    'minLocations' => 1 + (($c-1) * 10),
                    'maxLocations' => $c*10,
                    'locationDiscountPercentage' => 5 * ($c - 1),
                    'basePrice' => 29.00,
                    'smsCharge' => 100.00,
                    'totalPrice' => 129.00,
                    'locationDiscount' => ($c - 1) * 1.45,
                    'upgradeDiscount' => 0.00,
                    'smsMessages' => 1000,
                    'smsCost' => 7.50,
                    'profitPerLocation' => 118.6 - (($c - 1) * 1.45),
                ];

                $this->createPricingParameterList($SubID, $tSegment);
            }
        }

        return true;
    }

    public function GetViralSMSCount($AgencyID) {
        $objAgency = \Vokuro\Models\Agency::findFirst("agency_id = {$AgencyID}");
        if(!$objAgency->viral_sharing_code)
            return 0;

        $Referrals = \Vokuro\Models\SharingCode::count("sharecode = '{$objAgency->viral_sharing_code}'");
        return $Referrals > 4 ? 100 : $Referrals * 25;
    }


    public function getSubscriptionPrice($UserID, $PlanType) {
        $objSubscriptionPlan = \Vokuro\Models\BusinessSubscriptionPlan::findFirst('user_id = ' . $UserID);
        $objSubscriptionParameters = \Vokuro\Models\SubscriptionPricingPlanParameterList::find('subscription_pricing_plan_id = ' . $objSubscriptionPlan->subscription_pricing_plan_id);
        $objSubscriptionPricingPlan = \Vokuro\Models\SubscriptionPricingPlan::findFirst('id = ' . $objSubscriptionPlan->subscription_pricing_plan_id);

        $Locations = $objSubscriptionPlan->locations;
        $Messages = $objSubscriptionPlan->sms_messages_per_location;

        $PlanCost = 0;

        foreach($objSubscriptionParameters as $objParameter) {
            $tPricingPlanParameterList[$objParameter->max_locations] = $objParameter;
        }

        $tRangeMaximums = array_keys($tPricingPlanParameterList);

        $BreakOnNextIteration = false;

        for($c = 0 ; $c < count($tRangeMaximums) ; $c++) {
            $objParameterList = $tPricingPlanParameterList[$tRangeMaximums[$c]];

            $NextBatchOfLocations = $objParameterList->max_locations - $objParameterList->min_locations + 1;

            if(($Locations - $NextBatchOfLocations) <= 0) {
                $NextBatchOfLocations = $Locations;
                $BreakOnNextIteration = true;
            } else {
                $Locations -= $NextBatchOfLocations;
            }

            $Cost = $NextBatchOfLocations * $objParameterList->base_price + $NextBatchOfLocations * $Messages * $objSubscriptionPricingPlan->charge_per_sms;
            $Cost *= ((100 - $objParameterList->location_discount_percentage)) * 0.01;

            $PlanCost += $Cost;

            if($BreakOnNextIteration)
                break;
        }

        if($PlanType === 'Annually') {
            $PlanCost *= 12;
            $PlanCost *= (1 - $objSubscriptionPricingPlan->annual_discount / 100);
        }

        return number_format(round($PlanCost), 2);
    }

    private function createPricingParameterList($id, $parameters) {
        $subscriptionPricingPlanParameterList = new SubscriptionPricingPlanParameterList();
        $subscriptionPricingPlanParameterList->subscription_pricing_plan_id = intval($id);
        $subscriptionPricingPlanParameterList->min_locations = intval($parameters['minLocations']);
        $subscriptionPricingPlanParameterList->max_locations = intval($parameters['maxLocations']);
        $subscriptionPricingPlanParameterList->location_discount_percentage = floatval($parameters['locationDiscountPercentage']);
        $subscriptionPricingPlanParameterList->base_price = floatval($parameters['basePrice']);
        $subscriptionPricingPlanParameterList->sms_charge = floatval($parameters['smsCharge']);
        $subscriptionPricingPlanParameterList->total_price = floatval($parameters['totalPrice']);
        $subscriptionPricingPlanParameterList->location_discount = floatval($parameters['locationDiscount']);
        $subscriptionPricingPlanParameterList->upgrade_discount = floatval($parameters['upgradeDiscount']);
        $subscriptionPricingPlanParameterList->sms_messages = intval($parameters['smsMessages']);
        $subscriptionPricingPlanParameterList->sms_cost = floatval($parameters['smsCost']);
        $subscriptionPricingPlanParameterList->profit_per_location = floatval($parameters['profitPerLocation']);
        if(!$subscriptionPricingPlanParameterList->create()) {
            return false;
        }

        return true;
    }

}
