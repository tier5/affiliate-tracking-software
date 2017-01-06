<?php

namespace Vokuro\Controllers;

use Exception;
use Phalcon\Filter;
use Vokuro\Models\Agency;
use Vokuro\Services\Container;
use Vokuro\Utils;
use Vokuro\Forms\SignUpForm;
use Vokuro\Forms\CreditCardForm;
use Vokuro\Services\StripeManager;

/**
 * Vokuro\Controllers\BusinessPricingPlanController
 * CRUD to manage users
 */
class BusinessPricingPlanController extends ControllerBase {

    const MAX_PROGRESSION_SEGMENTS = 10;

    public function initialize() {

        $identity = $this->session->get('auth-identity');
        /*if ($identity && $identity['profile'] != 'Employee') {
            $this->tag->setTitle('Get Mobile Reviews | Subscription');
            $this->view->setTemplateBefore('private');
        } else {
            $this->response->redirect('/session/login?return=/');
            $this->view->disable();
            return;
        }*/
        $this->tag->setTitle('Get Mobile Reviews | Subscription');
        $this->view->setTemplateBefore('private');
        parent::initialize();

        //add needed css
        $this->assets
            ->addCss('/assets/global/plugins/bootstrap-summernote/summernote.css')
            ->addCss('/css/subscription.css')
            ->addCss('/css/slider-extended.css')
            ->addCss('/assets/global/plugins/card-js/card-js.min.css')
            ->addCss('/css/login.css');

        //add needed js
        $this->assets
            ->addJs('/assets/global/plugins/bootstrap-summernote/summernote.min.js')
            ->addJs('/assets/global/plugins/card-js/card-js.min.js');
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

            $objUser = \Vokuro\Models\Users::findFirst("id = {$userId}");
            $objAgency = \Vokuro\Models\Agency::findFirst("agency_id = {$objUser->agency_id}");

            /* Get pricing profiles */
            $pricingProfiles = $subscriptionManager->getAllPricingPlansByUserId($userId);

            $this->view->pricingProfiles = $pricingProfiles;
            $this->view->custom_domain = $objAgency->custom_domain;

        }  catch(Exception $e) {

            $responseParameters['message'] = $e->getMessage();

        }

    }

    public function editExistingPricingPlanAction($pricingPlanId) {
        if(!is_numeric($pricingPlanId)) throw new \Exception("Invalid pricing plan id");

        $agency = new Agency();
        $records = $agency->findBy(['subscription_id'=>$pricingPlanId, 'agency_type_id'=>2]);


        /*if($records){
            $this->view->attached_agencies = $records;
            $this->view->pick('businessPricingPlan/attached');
            return;
        } */


        /* Get services */
        $subscriptionManager = $this->di->get('subscriptionManager');

        /* Ensure the name of the pricing profile is unique for this user */
        $pricingPlan = (object)$subscriptionManager->getPricingPlanById($pricingPlanId);
        if(!$pricingPlan) {
            $this->flash->error("Could not open pricing plan for editing.");
            return;
        }
        
        /* Set top level parameters */
        $this->view->name = $pricingPlan->name;
        $this->view->enableTrialAccount = $pricingPlan->enable_trial_account;
        $this->view->enableDiscountOnUpgrade = $pricingPlan->enable_discount_on_upgrade;
        $this->view->basePrice = $pricingPlan->base_price;
        $this->view->costPerSms = $pricingPlan->cost_per_sms;
        $this->view->maxMessagesOnTrialAccount = $pricingPlan->max_messages_on_trial_account;
        $this->view->upgradeDiscount = $pricingPlan->updgrade_discount;
        $this->view->chargePerSms = $pricingPlan->charge_per_sms;
        $this->view->maxSmsMessages = $pricingPlan->max_sms_messages;
        $this->view->enableAnnualDiscount = $pricingPlan->enable_annual_discount;
        $this->view->annualDiscount = $pricingPlan->annual_discount;
        $this->view->pricingDetails = $pricingPlan->pricing_details;
        $this->view->canEdit = true;
        $this->view->gridEditStatus = "";
        $this->view->isCreateMode = false;
        $this->view->subscription_id_plan=$pricingPlanId;
        //$this->view->progressions = $subscriptionManager->getPricingParameterListsByPricingPlanId($pricingPlanId);

        $progressions = $subscriptionManager->getPricingParameterListsByPricingPlanId($pricingPlanId);

        foreach($progressions as $key => $list){
            $progressions[$key]['location_discount_percentage'] = (int)$list['location_discount_percentage'];

        }

        $this->view->progressions = $progressions;


        $pricingPlanLocked = $subscriptionManager->isPricingPlanLocked($pricingPlanId);
        if($pricingPlanLocked) {
            $this->view->gridEditStatus = "disabled";
            // $this->flash->notice("This plan is currently associated to active an business subscription.  Grid parameters may not be edited.");
        }

        $this->view->pick("businessPricingPlan/pricingPlan");

    }
    public function updateSubcriptionNameAction(){
        $this->view->disable();
        $subscription_id=$_POST['subscription_id'];
        $subcription_name=$_POST['subcription_name'];
         $dbQuery=$this->db->query("select * from `subscription_pricing_plan` WHERE `name`='".$subcription_name."' and `id` !=".$subscription_id);
          $countquery=$dbQuery->fetch();
           if(empty($countquery))
           {
        $this->db->query(" UPDATE `subscription_pricing_plan` SET `name`='".$subcription_name."' WHERE `id`=".$subscription_id);
        echo 1;
            }
            else
            {
                echo 2;
            }
        
        
    }
    public function updateSubcriptionPricingDetailsAction(){
        //echo '<pre>';print_r($_POST);exit;
        $subscription_id=$_POST['subscription_id'];
        $subcription_pricing_details=$_POST['subcription_pricing_details'];
        $this->db->query(" UPDATE `subscription_pricing_plan` SET `pricing_details`='".$subcription_pricing_details."' WHERE `id`=".$subscription_id);
        return "done";
        
        
    }
    public function showNewPricingPlanAction() {
        $this->view->name = "My New Subscription";
        $this->view->enableTrialAccount = true;
        $this->view->enableDiscountOnUpgrade = true;
        $this->view->basePrice = "29.00";
        $this->view->costPerSms = "0.0075";
        $this->view->maxMessagesOnTrialAccount = "100";
        $this->view->upgradeDiscount = "10";
        $this->view->chargePerSms = "0.10";
        $this->view->maxSmsMessages = "1000";
        $this->view->enableAnnualDiscount = true;
        $this->view->annualDiscount = "10";
        $this->view->pricingDetails = "";
        $this->view->canEdit = true;
        $this->view->gridEditStatus = "";
        $this->view->isCreateMode = true;
        $this->view->isNewRecord = true;

        /* Add progression parameters */
        $progressions = [];
        for ($i = 0; $i < self::MAX_PROGRESSION_SEGMENTS; $i++) {
            $min = ($i * self::MAX_PROGRESSION_SEGMENTS) + 1;
            $max = (($i + 1) * self::MAX_PROGRESSION_SEGMENTS);

            $progressions[] =
                [
                    "min_locations" => $min,
                    "max_locations" => $max,
                    "base_price" => 29,
                    "sms_charge" => 100,
                    "total_price" => 129,
                    "location_discount" => 0,
                    "upgrade_discount" => 0,
                    "discount_price" => 0,
                    "sms_messages" => 0,
                    "sms_cost" => 0,
                    "profit_per_location" => 0,
                    "location_discount_percentage" => $i * 5
                ];
        }
        $this->view->progressions = $progressions;

        $this->view->pick("businessPricingPlan/pricingPlan");
    }

    public function createPricingPlanAction() {
        $this->view->disable();

        $responseParameters = $this->savePricingPlanAction(false);

        $this->response->setContentType('application/json', 'UTF-8');
        $this->response->setContent(json_encode($responseParameters));
        return $this->response;
    }

    public function updatePricingPlanAction() {
        $this->view->disable();

        $responseParameters = $this->savePricingPlanAction(true);

        $this->response->setContentType('application/json', 'UTF-8');
        $this->response->setContent(json_encode($responseParameters));
        return $this->response;
    }

    private function savePricingPlanAction($isUpdate) {

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


            /* If we are creating a new plan, ensure the name of the pricing profile is unique for this user */
            $pricingPlan = $subscriptionManager->getPricingPlanByName($validatedParams['userId'], $validatedParams['name']);
            if($pricingPlan && !$isUpdate) {
                throw new \Exception('Another pricing profile with that name already exists! Please choose a unique name and try again.');
            }

            if($validatedParams) foreach($validatedParams as $key => $value) if($key !== 'name'){
                $validatedParams[$key] = str_replace('$','',$value);
            }

            /* Save the profile */
            $this->db->begin();
            if(!$subscriptionManager->savePricingProfile($validatedParams, $isUpdate)) {
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

        return $responseParameters;
    }

    public function previewSignUpPageAction() {
        // $this->view->setTemplateBefore('private');
        $this->view->setTemplateBefore('private');
        $form = new SignUpForm();
        $ccform = new CreditCardForm();

        /* Get services */
        $userManager = $this->di->get('userManager');

        $user_id = $userManager->getUserId($this->session);
        $this->view->user_id = $user_id;
        $this->view->maxlimitreached = false;

        $this->view->form = $form;
        $this->view->ccform = $ccform;
        $this->view->current_step = 1;
        $this->view->pick("businessPricingPlan/signupPreview");
    }



    public function updateViralSwitchAction($pricingPlanId, $enable) {

        $this->view->disable();

        $responseParameters['status'] = false;

        try {
            if (!$this->request->isPut()) {
                throw new \Exception('PUT request required!!!');
            }

            /* Get services */
            $subscriptionManager = $this->di->get('subscriptionManager');

            /* Ensure the name of the pricing profile is unique for this user */
            if(!$subscriptionManager->toggleViralPlanById($pricingPlanId)) {
                throw new \Exception('Failed to set plan to viral.');
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

        if(!is_numeric($pricingPlanId)) throw new \Exception('$pricingPlanId is expected to be an integer');
        $this->view->disable();

        $responseParameters['status'] = false;

        try {

            if (!$this->request->isDelete()) {
                throw new \Exception('DELETE request required!!!');
            }

            /* Get services */
            // $subscriptionManager = $this->di->get('subscriptionManager');

            /* Ensure the name of the pricing profile is unique for this user */

            $BusinessCount = \Vokuro\Models\Agency::count("subscription_id = {$pricingPlanId}");
            if($BusinessCount > 0) {
                throw new \Exception('Cannot delete a subscription that businesses are subscribed to.');
            }

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
    private function validatePricingPlanInput() {

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
                $validated[$key] = Utils::purifyHtml($value);
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
