<?php

namespace Vokuro\Controllers;

use Exception;
use Phalcon\Filter;
use Vokuro\Utils;
use Vokuro\Services\ServicesConsts;

/**
 * Vokuro\Controllers\SubscriptionController
 * CRUD to manage users
 */
class SubscriptionController extends ControllerBase {

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
    }
    
    public function businessAction() {   
        
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
        $this->view->pricingPlan = $subscriptionManager->getPricingPlan($pricingPlanId);
        
        /* Payments paramaters */
        $paymentParams = [
            'userId' => $userId,
            'provider' => ServicesConsts::$PAYMENT_PROVIDER_AUTHORIZE_DOT_NET
        ];
        $this->view->upgradeCreditCardStatus = 
            !$paymentService->hasPaymentProfile($paymentParams) ? 'disabled' : '';
            
        /* Render template */
        $this->view->pick("subscription/business");
    }
    
    public function agencyAction() {
        $this->view->pick("subscription/agency");
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
        
            /* Format the date accordingly  */
            $date = Utils::formatCCDate($this->request->getPost('expirationDate', 'striptags'));
            
            /* Get the subscription parameters */
            $ccParameters = [
                'userId' => $userId,
                'cardNumber' => $this->request->getPost('cardNumber', 'striptags'),
                'cardName' => $this->request->getPost('cardName', 'striptags'),
                'expirationDate' => $date,
                'csv' => $this->request->getPost('csv', 'striptags'),
                'provider' => ServicesConsts::$PAYMENT_PROVIDER_AUTHORIZE_DOT_NET
            ];
            
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
                throw new \Exception();
            }
            
            /* 
             * Create the payment 
             */
            $status = $paymentService->createPaymentProfile($ccParameters);
            if (!$status) {
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
     * Add plan with payment profile 
     */
    public function addPlanWithPaymentProfileAction() {
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
        
            /* Format the date accordingly  */
            $date = Utils::formatCCDate($this->request->getPost('expirationDate', 'striptags'));

            /* Get the subscription parameters */
            $ccParameters = [
                'userId' => $userId,
                'cardNumber' => $this->request->getPost('cardNumber', 'striptags'),
                'cardName' => $this->request->getPost('cardName', 'striptags'),
                'expirationDate' => $date,
                'csv' => $this->request->getPost('csv', 'striptags'),
                'locations' => $this->request->getPost('locations', 'striptags'),
                'messages' => $this->request->getPost('messages', 'striptags'),
                'planType' => $this->request->getPost('planType', 'striptags'),
                'price' => $this->request->getPost('price', 'striptags'),
                'provider' => ServicesConsts::$PAYMENT_PROVIDER_AUTHORIZE_DOT_NET
            ];
            
            /* 
             * If they don't have a customer profile, then create one (they shouldn't have one if calling this action,
             * but check just to be safe) 
             */
            $paymentParams = [
                'userId' => $userId,
                'provider' => ServicesConsts::$PAYMENT_PROVIDER_AUTHORIZE_DOT_NET
            ];
            
            $hasPaymentProfile = $paymentService->hasPaymentProfile($paymentParams);
            if($hasPaymentProfile) {
                throw new \Exception();
            }
            
            /* 
             * Create the payment profile 
             */
            // print_r($ccParameters);

            $status = $paymentService->createPaymentProfile($ccParameters);

            if (!$status) {
                throw new \Exception();
            }
            
            /* 
             * Add the plan 
             */
            $changePlanSucceeded = $paymentService->changeSubscription($ccParameters);
            if(!$changePlanSucceeded) {
                $responseParameters['status'] = false;
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
                throw new \Exception();
            }
        
            /* Get services */
            $userManager = $this->di->get('userManager');
            $paymentService = $this->di->get('paymentService');
        
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
                throw new \Exception();
            }
            
            /* 
             * Create the subscription 
             */
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
                throw new \Exception();
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
    
    public function showPricingPlanListAction() {
        
        $responseParameters['status'] = false;
        
        try {
        
            if (!$this->request->isGet()) {
                throw new \Exception('GET request required!!!');
            }
            
            /* Get services */
            $userManager = $this->di->get('userManager');
            $subscriptionManager = $this->di->get('subscriptionManager');
        
            /* Get the user id */
            $userId = $userManager->getUserId($this->session);
            
            /* Get pricing profiles */
            $pricingProfiles = $subscriptionManager->getAllPricingPlansByUserId($userId);        
            
            $this->view->pricingProfiles = $pricingProfiles;
            
        }  catch(Exception $e) {
            
            $responseParameters['message'] = $e->getMessage();
                    
        }
        
    }
    
    public function createPricingPlanAction() {    
        $this->view->pick("subscription/pricingPlan");
    }
    
    public function savePricingPlanAction() {
        $this->view->disable();
        
        $responseParameters['status'] = false;
        
        try {
        
            if (!$this->request->isPost()) {
                throw new \Exception('POST request required!!!');
            }
            
            /* Format the request body to an array */
            $validatedParams = $this->validatePricingPlanInput($this->request);
            if (!$validatedParams) {
                throw new \Exception('One or more request parameters are not valid!!!');
            }
        
            /* Get services */
            $userManager = $this->di->get('userManager');
            $subscriptionManager = $this->di->get('subscriptionManager');
        
            /* Get the user id */
            $validatedParams['userId'] = $userManager->getUserId($this->session);
            
            /* Ensure the name of the pricing profile is unique for this user */
            if($subscriptionManager->getPricingPlanByName($validatedParams['userId'], $validatedParams['name'])) {
                throw new \Exception('Another pricing profile with that name already exists! Please choose a unique name and try again.');
            }
            
            /* Save the profile */
            $this->db->begin();
            if(!$subscriptionManager->createPricingProfile($validatedParams)) {
                throw new \Exception('Unable to save pricing profile!!!');
            }
            $this->db->commit();
            
            /* 
             * Success!!! 
             */
            $responseParameters['status'] = true;
            
        }  catch(Exception $e) {
            
            /* 
             * Failure :( 
             */
            if ($this->db->isUnderTransaction()) {
                $this->db->rollback();
            }
            
            $responseParameters['message'] = $e->getMessage();
                    
        }
        
        $this->response->setContentType('application/json', 'UTF-8');
        $this->response->setContent(json_encode($responseParameters));
        return $this->response;
    }
    
    public function updateEnablePricingPlanAction($pricingPlanId, $enable) {
        $this->view->disable();
        
        $responseParameters['status'] = false;
    
        try {
        
            if (!$this->request->isPut()) {
                throw new \Exception('PUT request required!!!');
            }
            
            /* Get services */
            $subscriptionManager = $this->di->get('subscriptionManager');
        
            /* Ensure the name of the pricing profile is unique for this user */
            if(!$subscriptionManager->enablePricingPlanById($pricingPlanId, $enable)) {
                throw new \Exception('Failed to enable/disable pricing plan.');
            }
            
            /* 
             * Success!!! 
             */
            $responseParameters['status'] = true;
            
        }  catch(Exception $e) {
            
            $responseParameters['message'] = $e->getMessage();
                    
        }
        
        $this->response->setContentType('application/json', 'UTF-8');
        $this->response->setContent(json_encode($responseParameters));
        return $this->response;
        
    }
    
    public function deletePricingPlanAction($pricingPlanId) {
        $this->view->disable();
        
        $responseParameters['status'] = false;
    
        try {
        
            if (!$this->request->isDelete()) {
                throw new \Exception('DELETE request required!!!');
            }
            
            /* Get services */
            // $subscriptionManager = $this->di->get('subscriptionManager');
        
            /* Ensure the name of the pricing profile is unique for this user */
            
            /* REFACTOR - Temporary raw sql.  Soft deletes don't appear to be working via Phalcon */
            $sql = "UPDATE subscription_pricing_plan SET deleted_at='" . date('Y-m-h h:m:s') . "' WHERE id=" . $pricingPlanId;
            $result = $this->db->query($sql); // Working now
            if (!$result) {
                return false;
            }
            /*
            if(!$subscriptionManager->deletePricingPlanById($pricingPlanId)) {
                throw new \Exception('Failed to delete pricing plan.');
            }*/
            
            /* 
             * Success!!! 
             */
            $responseParameters['status'] = true;
            
        }  catch(Exception $e) {
            
            $responseParameters['message'] = $e->getMessage();
                    
        }
        
        $this->response->setContentType('application/json', 'UTF-8');
        $this->response->setContent(json_encode($responseParameters));
        return $this->response;
        
    }
    
    private function validatePricingPlanInput($request) {
        
        $validated = [];
        
        $filter = new Filter();
        
        /* Get raw json body */ 
        $rawJsonBody = $this->request->getJsonRawBody();
        if (!$rawJsonBody) {
            return false;
        }
        
        /* Format the json into an array */ 
        $params = Utils::objectToArray($rawJsonBody);
        
        /* Check the correct parameter values are supplied */
        $suppliedKeys = array_keys($params);
        $referenceValueKeys = [
            "name",
            "enableTrialAccount",
            "enableDiscountOnUpgrade",
            "basePrice",
            "costPerSms",
            "maxMessagesOnTrialAccount",
            "upgradeDiscount",
            "chargePerSms",
            "maxSmsMessages",
            "enableAnnualDiscount",     
            "annualDiscount",
            "pricingDetails"
        ];
        /* All value parameters found */
        $keysFound = array_intersect($suppliedKeys, $referenceValueKeys);
        if (count($keysFound) !== count($referenceValueKeys)) {
            return false;
        }
        
        /* Sanitize */
        foreach($params as $key => $value){
            if (!array_key_exists($key, $validated)) {
                $validated[$key] = [];
            }
            $validated[$key] = $filter->sanitize($value, "string");
        }
        
        /* Minimum segments found */
        $referenceProgressionKeys = [
            "minLocations",
            "maxLocations",
            "locationDiscountPercentage",
            "basePrice",
            "smsCharge",
            "totalPrice",
            "locationDiscount",
            "upgradeDiscount",
            "smsMessages",
            "smsCost",
            "profitPerLocation"
        ];
        foreach($params as $key => $segment){
            
            if(substr($key,0,7) !== "segment") {
                continue;
            }
            
            /* Check segment keys */
            $progressionKeys = array_keys($segment);
            $keysFound = array_intersect($progressionKeys, $referenceProgressionKeys);
            if (count($keysFound) !== count($progressionKeys)) {
                return false;
            }
            
            /* Sanitize */
            if (!array_key_exists($key, $validated)) {
                $validated[$key] = [];
            }
            foreach($segment as $segmentKey => $value) {
                $validated[$key][$segmentKey] = $filter->sanitize($value, "string");
            }
            
        }
        
        return $validated;
    }
    
}
