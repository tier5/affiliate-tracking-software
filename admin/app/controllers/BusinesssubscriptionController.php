<?php

namespace Vokuro\Controllers;

use Exception;
use Vokuro\Utils;
use Vokuro\Models\Users;
use Vokuro\Models\Agency;
use Vokuro\Services\ServicesConsts;

/**
 * Vokuro\Controllers\BusinessSubscriptionController
 * CRUD to manage users
 */
class BusinessSubscriptionController extends ControllerBase {

    public function initialize() {
        
        $identity = $this->session->get('auth-identity');
        if ($identity && $identity['profile'] != 'Employee') {
            $this->tag->setTitle('Review Velocity | Subscription');
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
        if ($this->view->showSmsQuota) {
            
            /* Get sms quota parameters */
            $smsQuotaParams = $smsManager->getBusinessSmsQuotaParams(
                $userManager->getLocationId($this->session)
            ); 
            
            if ($smsQuotaParams['hasUpgrade']) {
                // REFACTOR: DOESN'T SEEM TO BE GETTING CALLED
                // $percent = ($total_sms_month > 0 ? number_format((float)($sms_sent_this_month_total / $total_sms_month) * 100, 0, '.', ''):100);
                // if ($percent > 100) $percent = 100;
            } else {
                $this->view->showBarText = $smsQuotaParams['percent'] > 60 ? "style=\"display: none;\"" : "";
            }
            $this->view->smsQuotaParams = $smsQuotaParams;
        }
        
        /* Get subscription paramaters */
        $userId = $userManager->getUserId($this->session);
        $this->view->subscriptionPlan = $subscriptionManager->getSubscriptionPlan($userId);
        $this->view->paymentPlan = 
            $this->view->subscriptionPlan['payment_plan'] === ServicesConsts::$PAYMENT_PLAN_TRIAL ? 'TRIAL' : 'PAID';
        
        /* Get pricing plan */
        $pricingPlanId = $this->view->subscriptionPlan['subscription_pricing_plan_id'];
        $this->view->pricingPlan = $subscriptionManager->getPricingPlanById($pricingPlanId);
             
        /* Payments paramaters */
        $provider = ServicesConsts::$PAYMENT_PROVIDER_AUTHORIZE_DOT_NET;
        if ($userManager->isWhiteLabeledBusiness($this->session)) {
            $provider = ServicesConsts::$PAYMENT_PROVIDER_STRIPE;
        }
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
            
            $paymentParams = [
                'userId' => $userId,
                'provider' => ServicesConsts::$PAYMENT_PROVIDER_AUTHORIZE_DOT_NET
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

            /* Format the date accordingly  */
            $date = Utils::formatCCDate($this->request->getPost('expirationDate', 'striptags'));
            
            /* Create the payment profile */
            $paymentParams = [ 'userId' => $userId, 'provider' => ServicesConsts::$PAYMENT_PROVIDER_AUTHORIZE_DOT_NET ];
            $ccParameters = [
                'userId' => $userId,
                'cardNumber' => $this->request->getPost('cardNumber', 'striptags'),
                'cardName' => $this->request->getPost('cardName', 'striptags'),
                'expirationDate' => $date,
                'csv' => $this->request->getPost('csv', 'striptags'),
                'provider' => ServicesConsts::$PAYMENT_PROVIDER_AUTHORIZE_DOT_NET,
                'userEmail'             => $user->email,
                'userName'              => $user->name,
                'agencyName'            => $agency->name,
                'agencyAddress'         => $agency->address,
                'agencyCity'            => '', //$agency->city,  This field doesn't exist yet.  Will add later  TODO:  Fix this!
                'agencyStateProvince'   => $agency->state_province,
                'agencyPostalCode'      => $agency->postal_code,
                'agencyCountry'         => $agency->country,
            ];
            
            if ($paymentService->hasPaymentProfile($paymentParams)) {
                $profile = $paymentService->updatePaymentProfile($ccParameters);
            } else {
                $profile = $paymentService->createPaymentProfile($ccParameters);
            }
            if (!$profile) {
                throw new \Exception();
            }
            
            /* 
             * Success!!! 
             */
            $responseParameters['status'] = true;
               
        }  catch(Exception $e) {}
        
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
        
            /* 
             * If they don't have a customer profile, then create one (they shouldn't have one if calling this action,
             * but check just to be safe) 
             */
            $paymentParams = [
                'userId' => $userId,
                'provider' => ServicesConsts::$PAYMENT_PROVIDER_AUTHORIZE_DOT_NET
            ];
            
            $hasPaymentProfile = $paymentService->hasPaymentProfile($paymentParams);
            if(!$hasPaymentProfile) {
                throw new \Exception('Payment information not found!');
            }
            
            /* Create the subscription */
            $subscriptionParameters = [
                'userId' => $userId,
                'locations' => $this->request->getPost('locations', 'striptags'),
                'messages' => $this->request->getPost('messages', 'striptags'),
                'planType' => $this->request->getPost('planType', 'striptags'),
                'price' => $this->request->getPost('price', 'striptags'),
                'provider' => ServicesConsts::$PAYMENT_PROVIDER_AUTHORIZE_DOT_NET
            ];
            $changePlanSucceeded = $paymentService->changeSubscription($subscriptionParameters);
            if(!$changePlanSucceeded) {
                throw new \Exception('Payment information not found!');
            }            
            if(!$subscriptionManager->changeSubscriptionPlan($subscriptionParameters)) {
                throw new \Exception('Payment information not found!');
            }
            
            /* 
             * Success!!! 
             */
            $responseParameters['status'] = true;
               
        }  catch(Exception $e) {}
        
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
