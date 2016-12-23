<?php

namespace Vokuro\Controllers;

use Exception;
use Vokuro\Models\BusinessSubscriptionPlan;
use Vokuro\Models\Users;
use Vokuro\Models\Agency;
use Vokuro\Services\ServicesConsts;

/**
 * Vokuro\Controllers\BusinessSubscriptionController
 * CRUD to manage users
 */
class BusinessSubscriptionController extends ControllerBase {

    public function initialize() {
        //echo 'k';exit;
        $identity = $this->session->get('auth-identity');
        if ($identity && $identity['profile'] != 'User') {
            $this->tag->setTitle('Get Mobile Reviews | Subscription');
            $this->view->setTemplateBefore('private');
        } else {
            $this->response->redirect('/session/login?return=/');
            $this->view->disable();
            return;
        }
        parent::initialize();

        //add needed css
        $this->assets
            ->addCss('/assets/global/plugins/bootstrap-summernote/summernote.css')
            ->addCss('/css/subscription.css')
            ->addCss('/css/slider-extended.css')
            ->addCss('/assets/global/plugins/card-js/card-js.min.css');

        //add needed js
        $this->assets
            ->addJs('/assets/global/plugins/bootstrap-summernote/summernote.min.js')
            ->addJs('/assets/global/plugins/card-js/card-js.min.js');
    }

    public function indexAction() {


        /* Get services */
        $userManager = $this->di->get('userManager');
        $subscriptionManager = $this->di->get('subscriptionManager');
        $smsManager = $this->di->get('smsManager');
        $paymentService = $this->di->get('paymentService');

        /* Get the role type */
        $isBusiness = $userManager->isBusiness($this->session);

        /* Show sms quota? */
        $this->view->showSmsQuota = $isBusiness;
        if ($isBusiness) {

            /* Get sms quota parameters */
            $smsQuotaParams = $smsManager->getBusinessSmsQuotaParams(
                $userManager->getLocationId($this->session)
            );


            if ($smsQuotaParams['hasUpgrade']) {
                 //print_r($smsQuotaParams);exit;
                // REFACTOR: DOESN'T SEEM TO BE GETTING CALLED
                // $percent = ($total_sms_month > 0 ? number_format((float)($sms_sent_this_month_total / $total_sms_month) * 100, 0, '.', ''):100);
                // if ($percent > 100) $percent = 100;
            } else {
                $this->view->showBarText = $smsQuotaParams['percent'] > 60 ? "style=\"display: none;\"" : "";
            }
            $this->view->smsQuotaParams = $smsQuotaParams;
        }
        $this->getSMSReport();
        /* Get subscription paramaters */
        $userId = $userManager->getUserId($this->session);
        $objUser = \Vokuro\Models\Users::findFirst('id = ' . $userId);
        $objSuperUser = \Vokuro\Models\Users::findFirst('agency_id = ' . $objUser->agency_id . ' AND role="Super Admin"');
        $objAgency = \Vokuro\Models\Agency::findFirst('agency_id = ' . $objUser->agency_id);

        $this->view->businessEmail = $objAgency->email;
        $this->view->TypeSubscriptionId=$objAgency->subscription_id;

        $Provider = ServicesConsts::$PAYMENT_PROVIDER_STRIPE;
        $paymentParams = [
            'userId' => $objSuperUser->id,
            'provider' => $Provider
        ];

        $this->view->hasPaymentProfile = $paymentService->hasPaymentProfile($paymentParams);

        /* Get the subscription plan */
        $subscriptionPlanData = $subscriptionManager->getSubscriptionPlan($objSuperUser->id, $objAgency->subscription_id);
        //print_r($subscriptionPlanData);exit;

        /* Filter out the pricing plan details into its own view because it contains markup */
        $this->view->pricingDetails = $subscriptionPlanData['pricingPlan']['pricing_details'];


        $objSubscriptionPricingPlan = \Vokuro\Models\SubscriptionPricingPlan::findFirst("id = {$objAgency->subscription_id}");
        $this->view->MaxSMSTrial = $objSubscriptionPricingPlan->max_messages_on_trial_account;
        $this->view->MaxLocationTrial = 1;

        /* Set pricing plan details to empty so it doesn't display when attaching the json string to the data attribute */
        $subscriptionPlanData['pricingPlan']['pricing_details'] = '';
        $this->view->subscriptionPlanData = $subscriptionPlanData;
        switch($this->view->subscriptionPlanData['subscriptionPlan']['payment_plan']) {
            // GARY_TODO:  Pretty sure this doesn't work the way it was supposed to due to handoff from Michael.
            case ServicesConsts::$PAYMENT_PLAN_TRIAL :
                $this->view->paymentPlan = "TRIAL";
                break;
            case ServicesConsts::$PAYMENT_PLAN_FREE :
                $this->view->paymentPlan = "FREE";
                break;
            case ServicesConsts::$PAYMENT_PLAN_PAID :
                $this->view->paymentPlan = "PAID";
                break;
            case ServicesConsts::$PAYMENT_PLAN_MONTHLY :
            case ServicesConsts::$PAYMENT_PLAN_YEARLY :
                $this->view->paymentPlan = 
                number_format(
                			floatval($subscriptionManager->getSubscriptionPrice(
                				$objSuperUser->id, 
                				$this->view->subscriptionPlanData['subscriptionPlan']['payment_plan'])
                			), 0, '', ',');
                break;
            default:
            
                // No subscription currently in use.
                $this->view->paymentPlan = $subscriptionPlanData['pricingPlan']['enable_trial_account'] ? "TRIAL" : "UNPAID";
                break;
        }

        /* Payments paramaters */
        $provider = ServicesConsts::$PAYMENT_PROVIDER_STRIPE;

        $this->view->registeredCardType = $paymentService->getRegisteredCardType($userId, $provider);
    }

    /**
     * Check whether a customer profile exists for the current user
     */
    public function hasPaymentProfileAction() {
        $this->view->disable();

        $responseParameters['status'] = false;

        try {

            if (!$this->request->isPost()) {
                throw new \Exception();
            }

            /* Get services */
            $userManager = $this->di->get('userManager');
            $paymentService = $this->di->get('paymentService');

            /* Get the user id */
            $userId = $userManager->getUserId($this->session);

            $agency = Agency::query()
                ->where("agency_id = :agency_id:")
                ->bind(["agency_id" => $user->agency_id])
                ->execute()
                ->getFirst();

            $objSuperUser = \Vokuro\Models\Users::findFirst("agency_id = {$agency->agency_id} AND role='Super Admin'");

            $paymentParams = [
                'userId' => $objSuperUser->id,
                'provider' => ServicesConsts::$PAYMENT_PROVIDER_STRIPE
            ];

            $hasPaymentProfile = $paymentService->hasPaymentProfile($paymentParams);

            if (!$hasPaymentProfile) {
                throw new \Exception();
            }

            $responseParameters['status'] = true;

        } catch(Exception $e) {}

        $this->response->setContentType('application/json', 'UTF-8');
        $this->response->setContent(json_encode($responseParameters));
        return $this->response;
    }

    /**
     * Update credit card
     */
    public function updatePaymentProfileAction() {
        $this->view->disable();

        $responseParameters['status'] = false;

        try {

            if (!$this->request->isPost()) {
                throw new \Exception();
            }

            /* Get services */
            $userManager = $this->di->get('userManager');
            $paymentService = $this->di->get('paymentService');

            /* Get the user id */
            $userId = $userManager->getUserId($this->session);

            $user = Users::query()
                ->where("id = :id:")
                ->bind(["id" => $userId])
                ->execute()
                ->getFirst();
            $agency = Agency::query()
                ->where("agency_id = :agency_id:")
                ->bind(["agency_id" => $user->agency_id])
                ->execute()
                ->getFirst();

            $objSuperUser = \Vokuro\Models\Users::findFirst("agency_id = {$agency->agency_id} AND role='Super Admin'");


            $Provider = ServicesConsts::$PAYMENT_PROVIDER_STRIPE;

            // Card Number, Name and CSV aren't required for Stripe.  Just grab the token

            $tokenID = $this->request->getPost('tokenID', 'striptags');

            /* Create the payment profile */
            $paymentParams = [ 'userId' => $objSuperUser->id, 'provider' => $Provider];
            $ccParameters = [
                'userId'                => $objSuperUser->id,
                //'cardNumber'            => str_replace(' ', '', $cardNumber),
                'provider'              => $Provider,
                'userEmail'             => $user->email,
                'userName'              => $user->name,
                'agencyName'            => $agency->name,
                'agencyAddress'         => $agency->address,
                'agencyCity'            => '', //$agency->city,  This field doesn't exist yet.  Will add later  GARY_TODO:  Fix this!
                'agencyStateProvince'   => $agency->state_province,
                'agencyPostalCode'      => $agency->postal_code,
                'agencyCountry'         => $agency->country,
                'tokenID'               => $tokenID,
            ];

            if ($paymentService->hasPaymentProfile($paymentParams)) {
                $profile = $paymentService->updatePaymentProfile($ccParameters);
                if (!$profile) {
                    throw new \Exception('Payment Profile Could not be updated');
                }
            } else {
                $profile = $paymentService->createPaymentProfile($ccParameters);
                if (!$profile) {
                    throw new \Exception('Payment Profile Could not be created');
                }
            }

            /*
             * Success!!!
             */
            $responseParameters['status'] = true;

        }  catch(Exception $e) {die($e->getMessage());}

        /*
         * Construct the response
         */
        $this->response->setContentType('application/json', 'UTF-8');
        $this->response->setContent(json_encode($responseParameters));
        return $this->response;
    }

    /**
     * Change plan
     */
    public function changePlanAction() {
        $this->view->disable();

        $responseParameters['status'] = false;

        try {
            if (!$this->request->isPost()) {
                throw new \Exception('POST request is required!');
            }

            /* Get services */
            $userManager = $this->di->get('userManager');
            $paymentService = $this->di->get('paymentService');
            $subscriptionManager = $this->di->get('subscriptionManager');

            /* Get the user id */
            $userId = $userManager->getUserId($this->session);
            //these are fine from a security standpoint because they are pulled from the session, and not the request
            $objUser = \Vokuro\Models\Users::findFirst("id = {$userId}");
            $objAgency = \Vokuro\Models\Agency::findFirst("agency_id = {$objUser->agency_id}");
            $objSuperUser = \Vokuro\Models\Users::findFirst("agency_id = {$objAgency->agency_id} AND role='Super Admin'");

            $objSubscriptionPlan = \Vokuro\Models\BusinessSubscriptionPlan::findFirst('user_id = ' . $objSuperUser->id);
            if(!$objSubscriptionPlan)
                $objSubscriptionPlan = new \Vokuro\Models\BusinessSubscriptionPlan();

            // Current location count
            $dbLocations = \Vokuro\Models\Location::find("agency_id = {$objAgency->agency_id}");
            if(count($dbLocations) > $this->request->getPost('locations', 'striptags'))
                throw new \Exception('New location count (' . $this->request->getPost('locations', 'striptags') . ') is lower than current number of locations (' . count($dbLocations) . ')');

            $sms_sent_this_month = 0;
            if(count($dbLocations)) {
                foreach ($dbLocations as $objLocation) {
                    $start_time = date("Y-m-d", strtotime("first day of this month"));
                    $end_time = date("Y-m-d 23:59:59", strtotime("last day of this month"));

                    $CurrentCount = \Vokuro\Models\ReviewInvite::count(
                        array(
                            "column" => "review_invite_id",
                            "conditions" => "date_sent >= '" . $start_time . "' AND date_sent <= '" . $end_time . "' AND location_id = {$objLocation->location_id} AND sms_broadcast_id IS NULL",
                        )
                    );
                    if($CurrentCount > $this->request->getPost('messages', 'striptags')) {
                        throw new \Exception('New messages count (' . $this->request->getPost('messages', 'striptags') . ') is lower than the number of current messages (' . $CurrentCount . ') sent for Location (' . $objLocation->location_id . ' - ' . $objLocation->name . ')');
                    }
                }
            }

            $objSubscriptionPlan->user_id = $objSuperUser->id;
            $objSubscriptionPlan->sms_messages_per_location = $this->request->getPost('messages', 'striptags');
            $objSubscriptionPlan->locations = $this->request->getPost('locations', 'striptags');
            $objSubscriptionPlan->subscription_pricing_plan_id = $objAgency->subscription_id;
            if(!$objSubscriptionPlan->save())
                throw new \Exception('Could not save subscription plan.');

            /*
             * If they don't have a customer profile, then create one (they shouldn't have one if calling this action,
             * but check just to be safe)
             */
            $Provider = ServicesConsts::$PAYMENT_PROVIDER_STRIPE;
            $paymentParams = [
                'userId' => $objSuperUser->id,
                'provider' => $Provider
            ];

            $hasPaymentProfile = $paymentService->hasPaymentProfile($paymentParams);
            if(!$hasPaymentProfile) {
                throw new \Exception('Payment information not found!');
            }

            $intervalLength = $this->request->getPost('planType', 'striptags') === 'Annually' ? 12 : 1;

            /* Create the subscription */
            $subscriptionParameters = [
                'userId'            => $userId,
                'locations'         => $this->request->getPost('locations', 'striptags'),
                'messages'          => $this->request->getPost('messages', 'striptags'),
                'planType'          => $this->request->getPost('planType', 'striptags'),
                'price'             => $subscriptionManager->getSubscriptionPrice($userId, $this->request->getPost('planType', 'striptags')),
                'provider'          => $Provider,
                'intervalLength'    => $intervalLength,
            ];
            $changePlanSucceeded = $paymentService->changeSubscription($subscriptionParameters);
            if(!$changePlanSucceeded) {
                throw new \Exception('Could not change subscription.');
            }
            if(!$subscriptionManager->changeSubscriptionPlan($subscriptionParameters)) {
                throw new \Exception('Could not change subscription plan.');
            }

            /*
             * Success!!!
             */
            $responseParameters['status'] = true;

        }  catch(Exception $e) {$responseParameters['error'] = $e->getMessage();}

        $this->response->setContentType('application/json', 'UTF-8');
        $this->response->setContent(json_encode($responseParameters));
        return $this->response;
    }

    /**
     * Show invoices
     */
    public function invoicesAction() {
        if ($this->request->isGet()) {

        }
    }

}
