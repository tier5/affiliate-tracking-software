<?php

namespace Vokuro\Controllers;

use Vokuro\Services\SubscriptionManager;

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
            $this->view->subscriptionPlan['payment_plan'] === SubscriptionManager::$PAYMENT_PLAN_TRIAL ? 'TRIAL' : 'PAID';
        
        /* Get pricing plan */
        $pricingPlanId = $this->view->subscriptionPlan['subscription_pricing_plan_id'];
        $this->view->pricingPlan = $subscriptionManager->getPricingPlan($pricingPlanId);
        
        /* Payments paramaters */
        $this->view->upgradeCreditCardStatus = 
            !$paymentService->hasRegisteredCreditCard($userId) ? 'disabled' : '';
            
        /* Render template */
        $this->view->pick("subscription/business");
    }
    
    public function agencyAction() {
        $this->view->pick("subscription/agency");
    }

    /**
     * Searches for subscriptions
     */
    public function indexAction() {
        // TODO: Original code
        // $this->view->subscriptions = Subscription::find();
        if ($this->session->get('auth-identity')['location_id']) {
            $this->getSMSReport();
        }
    }

    /**
     * Show invoices 
     */
    public function invoicesAction() {
        if ($this->request->isGet()) {
            
        }
    }
    
    /**
     * Creates a subscriptions
     */
    public function createAction() {
        if ($this->request->isPost()) {

            $sub = new Subscription();

            $sub->assign(array(
                'name' => $this->request->getPost('name', 'striptags'),
                'subscription_interval_id' => $this->request->getPost('subscription_interval_id', 'int'),
                'duration' => $this->request->getPost('duration', 'int'),
                'amount' => $this->request->getPost('amount'),
                'trial_amount' => $this->request->getPost('trial_amount'),
                'trial_length' => $this->request->getPost('trial_length', 'int'),
            ));

            if (!$sub->save()) {
                $messages = array();
                foreach ($sub->getMessages() as $message) {
                    $messages[] = str_replace("subscription_interval_id", "Interval", $message->getMessage());
                }

                $this->flash->error($messages);
            } else {
                $this->flash->success("The subscription was created successfully");

                Tag::resetInput();
            }
        }

        // find all subscription intervals for the form
        $this->view->subscription_intervals = SubscriptionInterval::find();
        //end finding subscription intervals        

        $this->view->subscription = new Subscription();
        $this->view->form = new SubscriptionForm(null);
    }
    
    private function computePercentMessagesRemaining($smsQuotaParams) {
        $percent = 
           ($smsQuotaParams['totalSmsNeeded'] > 0 ? 
            number_format((float)($smsQuotaParams['smsSentThisMonth'] / $smsQuotaParams['totalSmsNeeded']) * 100, 0, '.', '') : 
            100);
        return $percent > 100 ? 100 : $percent;
    }

}
