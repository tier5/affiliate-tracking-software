<?php

namespace Vokuro\Controllers;

use Exception;
use Phalcon\Filter;
use Vokuro\Utils;
use Vokuro\Services\ServicesConsts;

/**
 * Vokuro\Controllers\BusinessPricingPlanController
 * CRUD to manage users
 */
class BusinessPricingPlanController extends ControllerBase {

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
    
    public function indexAction() {
        
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
    
    public function editPricingPlanAction($pricingPlanId) {
        $this->view->name = "My New Subscription";
        $this->view->enableTrialAccount = true; 
        $this->view->enableDiscountOnUpgrade = true;
        $this->view->basePrice = "0.00";
        $this->view->costPerSms = "0.00";
        $this->view->maxMessagesOnTrialAccount = "10";
        $this->view->updgradeDiscount = "1";
        $this->view->chargePerSms = "0.00";
        $this->view->maxSmsMessages = "100";
        $this->view->enableAnnualDiscount = true;
        $this->view->annualDiscount = "1";
        $this->view->pricingDetails = "";
        
        $this->view->pick("businessPricingPlan/pricingPlan");
    }
    
    public function createPricingPlanAction() {
        $this->view->name = "My New Subscription";
        $this->view->enableTrialAccount = true; 
        $this->view->enableDiscountOnUpgrade = true;
        $this->view->basePrice = "0.00";
        $this->view->costPerSms = "0.00";
        $this->view->maxMessagesOnTrialAccount = "10";
        $this->view->updgradeDiscount = "1";
        $this->view->chargePerSms = "0.00";
        $this->view->maxSmsMessages = "100";
        $this->view->enableAnnualDiscount = true;
        $this->view->annualDiscount = "1";
        $this->view->pricingDetails = "";
        
        $this->view->pick("businessPricingPlan/pricingPlan");
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
            $pricingPlan = $subscriptionManager->getPricingPlanByName($validatedParams['userId'], $validatedParams['name']);
            if($pricingPlan) {  
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
            $sql = "UPDATE subscription_pricing_plan SET name='deleted-". time() ."', deleted_at='" . date('Y-m-h h:m:s') . "' WHERE id=" . $pricingPlanId;
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
    
    /*
     * REFACTOR 
     * Needs to moved into a generic request validation systems 
     */
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
            if ($key === "pricingDetails"){
                $validated[$key] = htmlentities($string);
            } else {
                $validated[$key] = $filter->sanitize($value, "string");
            }
            
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
