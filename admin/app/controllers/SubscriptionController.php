<?php

namespace Vokuro\Controllers;

use DateTime;
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
     * Change subscription 
     */
    public function changePlanAction() {
        
        $responseParameters = [];
        if ($this->request->isPost()) {
        
            /* Get services */
            $userManager = $this->di->get('userManager');
            $paymentService = $this->di->get('paymentService');
        
            /* Get the user id */
            $userId = $userManager->getUserId($this->session);
        
            /* Get the subscription parameters */
            $subscriptionParameters = [
                'userId' => $userId,
                'locations' => $this->request->getPost('locations', 'striptags'),
                'messages' => $this->request->getPost('messages', 'striptags'),
                'planType' => $this->request->getPost('planType', 'striptags'),
                'price' => $this->request->getPost('price', 'striptags'),
                'provider' => ServicesConsts::$PAYMENT_PROVIDER_AUTHORIZE_DOT_NET
            ];
            
            /* 
             * If they already have a customer profile, simply update their subscription,
             * otherwise direct them to the credit card form.  
             */
            $paymentParams = [
                'userId' => $userId,
                'provider' => ServicesConsts::$PAYMENT_PROVIDER_AUTHORIZE_DOT_NET
            ];
            
            $changePlanSucceeded = false;
            $hasPaymentProfile = $paymentService->hasPaymentProfile($paymentParams);
            if (!$hasPaymentProfile)  {
                $responseParameters['status'] = 'Failed';    
            } else {
                $changePlanSucceeded = $paymentService->changeSubscription($subscriptionParameters);
                if(!$changePlanSucceeded) {
                    $responseParameters['status'] = 'ChangeFailed';
                }
            } 
            
            /* 
             * Construct the response  
             */
            $this->view->disable();
                
        }
        
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
    
    /**
     * Update cc 
     */
    public function updateCCAction() {
        
        $responseParameters = [];
        if ($this->request->isPost()) {
        
            /* Get services */
            $userManager = $this->di->get('userManager');
            $paymentService = $this->di->get('paymentService');
        
            /* Get the user id */
            $userId = $userManager->getUserId($this->session);
        
            /*
             * REFACTOR - Move to utils class 
             */
            $date = $this->formatCCDate($this->request->getPost('expirationDate', 'striptags'));
            
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
             * If they have a customer profile, then update.  If not, create 
             * a new one.  
             */
            $paymentParams = [
                'userId' => $userId,
                'provider' => ServicesConsts::$PAYMENT_PROVIDER_AUTHORIZE_DOT_NET
            ];
            
            $responseParameters['status'] = 'Succeeded';
            $hasPaymentProfile = $paymentService->hasPaymentProfile($paymentParams);
            if($hasPaymentProfile) {
                $status = $paymentService->updatePaymentProfile($ccParameters);
            } else {
                $status = $paymentService->createPaymentProfile($ccParameters);    
            }
            
            /* 
             * Construct the response  
             */
            $this->view->disable();
            
            if (!$status) {
                $responseParameters['status'] = 'Failed';
            }
               
        }
        
        $response = new \Phalcon\Http\Response();
        $response->setContent(json_encode($responseParameters));
        return $response;
    }
    
    private function formatCCDate($date) {
        $stripped = str_replace(' ', '', $date); // Remove whitespace
        $date = DateTime::createFromFormat('m/y', $stripped);
        return $date->format('Y-m');
    }
    
}
