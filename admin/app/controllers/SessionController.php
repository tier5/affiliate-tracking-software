<?php

namespace Vokuro\Controllers;
use Vokuro\Models\SubscriptionPricingPlan;
use Vokuro\Services\SubscriptionManager;
use Vokuro\Utils;
use Vokuro\ArrayException;
use Phalcon\Tag;
use Vokuro\Forms\LoginForm;
use Vokuro\Forms\SignUpForm;
use Vokuro\Forms\CreditCardForm;
use Vokuro\Forms\ForgotPasswordForm;
use Vokuro\Auth\Exception as AuthException;
use Vokuro\Models\Agency;
use Vokuro\Models\FacebookScanning;
use Vokuro\Models\GoogleScanning;
use Vokuro\Models\Location;
use Vokuro\Models\LocationReviewSite;
use Vokuro\Models\ResetPasswords;
use Vokuro\Models\Users;
use Vokuro\Models\EmailConfirmations;

/**
 * Controller used handle non-authenticated session actions like login/logout, user signup, and forgotten passwords
 */
class SessionController extends ControllerBase {

    public $validSubDomains = [ 'my', 'www', 'reviewvelocity', '104', 'dev', 'stage', 'dev2', 'localhost'];

    public $facebook_access_token;


    /**
     * Default action. Set the public layout (layouts/private.volt)
     */
    public function initialize() {
        if (!$this->facebook_access_token) {
            $face = new FacebookScanning();
            $this->facebook_access_token = $face->getAccessToken();
        }
        parent::initialize();
    }

    public function indexAction() {
        $this->view->setTemplateBefore('login');

        $this->tag->setTitle('Get Mobile Reviews | Subscription');
    }

    // Also will 404 on invalid subdomain
    protected function DetermineParentIDAndSetViewVars($objPricingPlan = null) {
        // First try to determine parent id from pricing plan if present
        if($objPricingPlan) {
            $objUser = \Vokuro\Models\Users::findFirst("id = {$objPricingPlan->user_id}");
            return $objUser->agency_id;
        }
        // Determine if business under an agency or Get Mobile Reviews
            $parts = explode(".", $_SERVER['SERVER_NAME']);
            if(count($parts) == 3 && $parts[0] != 'www') { // Index loaded from getmobilereviews subdomain
                $subdomain = $parts[0];

                $objParentAgency = Agency::findFirst([
                        "custom_domain = :custom_domain:",
                        "bind" => ["custom_domain" => $subdomain]
                    ]);

                // Subdomain must exist
                if(!$objParentAgency) {
                    $this->response->setStatusCode(404, "Not Found");
                    echo "<h1>404 Page Not Found</h1>";
                    $this->view->disable();
                    return;
                }

                $ParentID = $objParentAgency->agency_id;
                //echo '<pre>';print_r($objParentAgency);exit;
                $this->view->main_color_setting = $this->view->PrimaryColor = !empty($objParentAgency->main_color) ? $objParentAgency->main_color : '#2a3644';
                $this->view->SecondaryColor = !empty($objParentAgency->secondary_color) ? $objParentAgency->secondary_color : '#65CE4D';
                //$this->view->logo_path = (!empty($objParentAgency->logo_path)) ? '/img/agency_logos/'.$objParentAgency->logo_path : '';

            } else {
                // Get Mobile Reviews
                $ParentID = \Vokuro\Models\Agency::BUSINESS_UNDER_RV;
                $this->view->main_color_setting = $this->view->PrimaryColor = '#2a3644';
                $this->view->SecondaryColor = '#65CE4D';
                //$this->view->logo_path =  '';
            }

        return $ParentID;
    }

    public function submitSignupAction() {
        try {
            $subscription_id = null;
            if($this->request->getPost('short_code'))
            {
                $short_code = $this->request->getPost('short_code');
            }
           
            $subscription_pricing_plan = '';

            $ssp = new SubscriptionPricingPlan();

            if($this->request->getPost('sharing_code'))
            {
                $sharing_code = $this->request->getPost('sharing_code', 'striptags');
            }
            

            $parent_id = null;
            if ($short_code) {
                $subscription_pricing_plan = $ssp->findOneBy(['short_code' => $short_code]);
                if($subscription_pricing_plan) {
                    /**
                     * @var $subscription_pricing_plan \Vokuro\Models\SubscriptionPricingPlan
                     */
                    /**** 17.01.2017 ***/
                   // $subscription_id = $subscription_pricing_plan->id;
                    $subscription_id = 0;
                    
                }
            }

            /* Get services */
            $subscriptionManager = $this->di->get('subscriptionManager');

            if($subscription_id =='' && $sharing_code) {
               
                // Viral signup, get viral subscription
                $objBusiness = \Vokuro\Models\Agency::findFirst("viral_sharing_code = '{$sharing_code}'");
                if(!$objBusiness)
                    throw new \Exception("Viral code not set properly.  Please contact customer support.");

                $objSuperUser = \Vokuro\Models\Users::findFirst("agency_id = {$objBusiness->parent_id} and role = 'Super Admin'");

                $objSubscription = \Vokuro\Models\SubscriptionPricingPlan::findFirst("is_viral = 1 AND user_id = {$objSuperUser->id} AND name NOT LIKE 'deleted-%'");
                if($objSubscription)
                    $subscription_id = $objSubscription->id;
                $parent_id = $objBusiness->parent_id;
               
            }
            //echo $subscription_id;exit;
            if($subscription_id=='' && $subscription_id!=0) {
                //echo "2";
                /**
                 * @var $subscriptionManager \Vokuro\Services\SubscriptionManager
                 */

                $default = $subscriptionManager->getActiveSubscriptionPlan();
                if($default){
                   /**
                    * @var $default \Vokuro\Models\SubscriptionPricingPlan
                    */
                    $short_code = $default->getShortCode();
                    $subscription_id = $default->id;
                }
            }
            if($subscription_id=='')
            {
                $subscription_id=0;
               ///echo $subscription_id;exit;
            }
            echo $subscription_id;exit;

            // Start transaction
            $this->db->begin();

            if (!$this->request->isPost()) {
                throw new ArrayException("", 0, null, ['POST request required!!!']);
            }

            $form = new SignUpForm();

            // Check user email unuique
            $user = new Users();
            $name = $this->request->getPost('name', 'striptags');
            if(strpos($name,' ') > -1) {
                $names = explode(' ', $name);
            }
            if($names){
                $first_name = $names[0];
                $last_name = $names[1];
            }
            if(!$last_name) $last_name = ' ';
         
            $user->assign(array(
                'name' => $name,
                'last_name'=>$last_name,
                'email' => $this->request->getPost('email'),
                'password' => $this->security->hash($this->request->getPost('password')),
                'profilesId' => 2,
                'role' => 'Super Admin',
                'is_employee' => 1,
            ));

            $_SESSION['password_save'] = $this->request->getPost('password');

            $isemailunuique = $user->validation();
            if (!$isemailunuique) {
                throw new ArrayException("Email address is not unique", 0, null, ['That email address is already taken.']);
            }


            $uservalid = $form->isValid($this->request->getPost());
            if (!$uservalid) {
                $messages = $form->getMessages();
                if(!$messages) $messages = ['Invalid User'];

                throw new ArrayException("The user was not valid", 0, null, $messages);
            }
            // First create an agency
            $agency_name = $this->request->getPost('agency_name', 'striptags');
            if(!$agency_name) $agency_name = $this->request->getPost('name','striptags');

            // Also will 404 on invalid subdomain.  If its a viral code, it will use that instead.  This is hacky, but it removes some reliance on the subdomain being correct.
            $ParentID = $parent_id ?: $this->DetermineParentIDAndSetViewVars($subscription_pricing_plan);

            $agency = new Agency();
            $agency_save_arr = [
                'name' => $agency_name,
                /*'referrer_code' => $this->request->getPost('sharecode'),*/
                'date_created' => date('Y-m-d H:i:s'),
                'signup_page' => 2, //go to the next page,
                'agency_type_id' => 2,
                'email' => $this->request->getPost('email'),
                'parent_id' => $ParentID,
            ];

            if($subscription_id){
                $agency_save_arr['subscription_id'] = $subscription_id;
            }

            $agency->assign($agency_save_arr);

            if (!$agency->save()) {
                throw new ArrayException('Could not save Agency', 0, null, $agency->getMessages());
            }

            if($this->request->getPost('sharing_code')) {
                $objSharingCode = new \Vokuro\Models\SharingCode();
                $objSharingCode->sharecode = $this->request->getPost('sharing_code');
                $objSharingCode->business_id = $agency->agency_id;
                $objSharingCode->created_at = date("Y-m-d H:i:s", strtotime('now'));
                $objSharingCode->subscription_id = $subscription_id;
                $objSharingCode->save();
            }

            $user->agency_id = $agency->agency_id;
            $user->send_confirmation = true;
            if (!$user->save() && $user->getMessages()) {

                throw new ArrayException('Could not save the user', 0, null, $user->getMessages());
            }
            $_SESSION['name'] = $this->request->getPost('name', 'striptags');
            $_SESSION['email'] = $this->request->getPost('email');

            
            $an=$this->request->getPost('name', 'striptags');
            $msgx=$this->request->getPost('name', 'striptags')." is register under You with email ID ".$this->request->getPost('email', 'striptags');
            $createdxx=date('Y-m-d H:i:s');
            $result=$this->db->query(" INSERT INTO notification ( `to`, `from`, `message`, `read`,`created`,`updated`) VALUES ( '".$ParentID."', '".$an."', '".$msgx."', '0','".$createdxx."','".$createdxx."')");  
                      
            
            $this->db->commit();

            /*** notification mail ***/
            $objSuperAdminUser = \Vokuro\Models\Users::findFirst('agency_id = ' . $ParentID . ' AND role="Super Admin"');

            if(isset($subscription_pricing_plan->name)){
                $planName = $subscription_pricing_plan->name;
            }else{
                $planName = 'Free';
            }

            $EmailFrom = 'no-reply@reviewvelocity.co';
            $EmailFromName = "Zach Anderson";
            $subject="New Business Registered Successfully";
            $mail_body='Dear '.$objSuperAdminUser->name.',';
            $mail_body=$mail_body.'<p>Congratulations a new business has registered successfully with following details:
                </p>';
            $mail_body .= '<p>Name: '.$an.'</p>';
            $mail_body .= '<p>Email: '.$this->request->getPost('email', 'striptags').'</p>';
            $mail_body .= '<p>Subscription: '.$planName.'</p>';
            $mail_body=$mail_body."Thanks";

                $Mail = $this->getDI()->getMail();
            $Mail->setFrom($EmailFrom, $EmailFromName);
            $Mail->send($objSuperAdminUser->email, $subject, '', '', $mail_body);
                /*** notification mail end ***/

            $expire = time() + 86400 * 30;
            setcookie( "short_code",'', $expire );
            setcookie( "sharing_code",'', $expire );

            return $this->response->redirect('/session/thankyou');

        } catch(ArrayException $e) {

            $this->db->rollback();

            if($e->getOptions()) foreach($e->getOptions() as $message) {
                $this->flash->error($message);
            }

        }

    }

    public function inviteAction($short_code = null) {
        $this->view->short_code = $short_code;

            if($short_code!=null)
            {
            $expire = time() + 86400 * 8;
            setcookie( "short_code",$short_code, $expire );
            }
            else
            {
                $this->view->short_code =$short_code=$_COOKIE['short_code'];
            }
            //code_generate

            $shar_code=$_COOKIE['code_generate_normal'];
            if($shar_code)
            {
                $this->view->code=$_COOKIE['code_generate_normal'];
            }

        
        $this->signupAction();

        $this->view->pick('session/signup');
        $subscription = new SubscriptionPricingPlan();

        if($short_code) {
            $plan = $subscription->findOneBy(['short_code' => $this->view->short_code]);

            if ($plan) {
                $this->enable_trial_account = $plan->enable_trial_account;
                /**
                 * @var $plan \Vokuro\Models\SubscriptionPricingPlan
                 */
                $objUser = \Vokuro\Models\Users::findFirst("id = {$plan->user_id}");
                $objAgency = \Vokuro\Models\Agency::findFirst("agency_id = {$objUser->agency_id}");
                //$this->view->logo_path = $objAgency->logo_path;
                //$this->view->logo_path = "/img/agency_logos/{$objAgency->logo_path}";
                $this->view->agency_name = $objAgency->name;
                $status = $plan->enabled;

                if(!$status) {
                    //get the active plan
                    $service = new SubscriptionManager();
                    $active = $service->getActiveSubscriptionPlan();
                    if($active){
                        $this->view->short_code = $active->getShortCode();
                        $this->view->setTemplateBefore('login');

                        $this->view->pick('businessPricingPlan/inactive');
                        return;
                    }
                }
            }
        }
    }

    public function signupAction($subscriptionToken = '0')
    {
        $objAgency = '';
        $objUser = '';
        $Domain = $this->config->application->domain;

        $host = $_SERVER['HTTP_HOST'];
        $ex = explode(".", $host);
        $subdomain = $ex[0];

        // Also will 404 on invalid subdomain
        $this->DetermineParentIDAndSetViewVars();

        $agency = new Agency();

        $record = $agency->findOneBy(
            ['custom_domain' => $subdomain]
        );

        $white_label = 'Sign Up';

        if ($record) {
            if ($record->agency_id) {
                $this->view->agencyId = $record->agency_id;
            }

            //if($record->logo_path) $this->view->logo_path = "/img/agency_logos/".$record->logo_path;
            if ($record->name) {
                $this->view->agency_name = $record->name;
            }
            
            $this->view->agency_white_label = true;

            if ($record->main_color) {
                $this->view->main_color_setting = $record->main_color;
            }
        } else if (!empty($objUser) && $objUser->name) {
            $this->view->agency_name = $objUser->name;
        } else if ($this->request->getQuery("code")) {
            $code = $this->request->getQuery("code");

            $expire = time() + 86400 * 30;
            setcookie("code", $code, $expire );
            
            $objAgency = \Vokuro\Models\Agency::findFirst("viral_sharing_code = '{$code}'");
            $objUser = \Vokuro\Models\Users::findFirst("id = {$objAgency->parent_id}");
            $this->view->agencyId = $objAgency->agency_id;
            $this->view->agency_name = $objAgency->name;

            // echo $objAgency->parent_id;exit;
            
            if ($objAgency->parent_id == 0) {
                setcookie("code_generate_normal",$code, $expire,'/');
                $custom_domain=$objAgency->custom_domain;
                $this->response->redirect('http://'.$custom_domain . '.' . $Domain);
                //$this->view->disable();
                return;
            }

            if ($objAgency->parent_id) {
                setcookie("code_generate_normal",$code, $expire,'/',$custom_domain . '.' . $Domain);

                $objAgency1 = \Vokuro\Models\Agency::findFirst("agency_id = {$objAgency->parent_id}");

                $this->view->agencyId = $objAgency1->agency_id;
                $this->view->agency_name = $objAgency1->name;
                $custom_domain=$objAgency1->custom_domain;
                $this->response->redirect('http://'.$custom_domain . '.' . $Domain);
                $this->view->disable();
                return;
             } else {
                $code = $_COOKIE['code'];
                $objAgency = \Vokuro\Models\Agency::findFirst("viral_sharing_code = '{$code}'");
                $objUser = \Vokuro\Models\Users::findFirst("id = {$objAgency->parent_id}");

                $this->view->agencyId = $objAgency->agency_id;
                $this->view->agency_name = $objAgency->name;

                if($objAgency->parent_id) {
                    $objAgency1 = \Vokuro\Models\Agency::findFirst("agency_id = {$objAgency->parent_id}");

                    $this->view->agencyId = $objAgency1->agency_id;
                    $this->view->agency_name = $objAgency1->name;
                 }

                 //$this->view->agency_name ='';
            }
        }
        //dd($record->agency_id);

        if (!$this->view->short_code) {
            $this->view->short_code = $_COOKIE['short_code'];
        }

        //see invite action above
        if ($this->view->short_code) {
            $subscription = new SubscriptionPricingPlan();
            $plan = $subscription->findOneBy(['short_code' => $this->view->short_code]);
            if ($plan) {
                /**
                 * @var $plan \Vokuro\Models\SubscriptionPricingPlan
                 */
                $this->view->subscription_plan_name = $plan->name;
                $white_label = 'Sign Up '.$plan->name;

            }
        }

        $this->tag->setTitle('Get Mobile Reviews | Plan: '.$white_label);
        $this->view->setTemplateBefore('login');

        /* Get services */
        $userManager = $this->di->get('userManager');
        $subscriptionManager = $this->di->get('subscriptionManager');

        /* Are we are logged in? */
        $userId = $userManager->getUserId($this->session);

        /* Is this a valid subscription? */
        $isValid = $subscriptionManager->isValidInvitation($subscriptionToken);

        /* Simply redirect to the home if we are logged in or the form is invalid  */
        if ($userId || (!$isValid && $this->request->isPost())) {
            //$this->response->redirect('/');
            // $this->view->setTemplateBefore('private');
        }

        //Utils::noSubDomains(1, $this->validSubDomains, $subscriptionToken);
        $form = new SignUpForm();
        $ccform = new CreditCardForm();

        $this->view->userId = $userId;
        $this->view->maxLimitReached = false;
        $this->view->token = $subscriptionToken;
        if (!$userId) {
            $this->view->maxLimitReached = $userManager->isMaxLimitReached();
        }

        $this->view->form = $form;
        $this->view->ccform = $ccform;
        $this->view->current_step = 1;
    }

    /**
     * Sign up form, Step 2 (Add Location)
     */
    /* public function signup2Action($subscription_id = 0) { */
    public function signup2Action($pricingProfileToken = 0) {

        /* $this->noSubDomains(2, $subscription_id); */
        if($this->request->getPost('short_code')){
            $short_code = $this->request->getPost('short_code');
        }
        $this->view->setTemplateBefore('signup');
        $this->tag->setTitle('Get Mobile Reviews | Sign up | Step 2 | Add Location');

        //get the user id, to find the settings
        $identity = $this->auth->getIdentity();
        //echo '<pre>$identity:'.print_r($identity,true).'</pre>';
        // If there is no identity available the user is redirected to index/index
        if (!is_array($identity)) {
            /* $this->response->redirect('/session/login?return=/session/signup2/' . ($pricingProfileToken > 0 ? $subscription_id : '')); */
            $this->response->redirect('/session/login');
            $this->view->disable();
            return;
        }
        $conditions = "id = :id:";
        $parameters = array("id" => $identity['id']);
        $userObj = Users::findFirst(array($conditions, "bind" => $parameters));

        $conditions = "agency_id = :agency_id:";
        $parameters = array("agency_id" => $userObj->agency_id);
        $agency = Agency::findFirst(array($conditions, "bind" => $parameters));
        
        $conditions = "agency_id = :agency_id:";
        $parameters = array("agency_id" => $agency->parent_id);
        $parent_agency = Agency::findFirst(array($conditions, "bind" => $parameters));
        
        //$this->view->logo_path = "/img/agency_logos/" . $parent_agency->logo_path;
        $this->view->parent_agency = $parent_agency->name;
        
        if ($this->request->isPost()) {
            $loc = new Location();
            $loc->assign(array(
                'name' => $this->request->getPost('name', 'striptags'),
                'agency_id' => $agency->agency_id,
                'phone' => $this->request->getPost('phone', 'striptags'),
                'address' => $this->request->getPost('address', 'striptags'),
                'locality' => $this->request->getPost('locality', 'striptags'),
                'state_province' => $this->request->getPost('state_province', 'striptags'),
                'postal_code' => $this->request->getPost('postal_code', 'striptags'),
                'country' => $this->request->getPost('country', 'striptags'),
                'latitude' => $this->request->getPost('latitude', 'striptags'),
                'longitude' => $this->request->getPost('longitude', 'striptags'),
                'region_id' => $this->request->getPost('region_id', 'striptags'),
                'date_created' => date('Y-m-d H:i:s'),
            ));


            if (!$loc->save()) {
                $this->flash->error($loc->getMessages());
            } else {
                $foundagency = array();

                //check for yelp
                $yelp_api_id = $this->request->getPost('yelp_id', 'striptags');
                $yelp_id = $this->yelpId($yelp_api_id);
                if ($yelp_api_id != '' && !(strpos($yelp_api_id, '>') !== false)) {
                    $lrs = new LocationReviewSite();
                    $lrs->assign(array(
                        'location_id' => $loc->location_id,
                        'review_site_id' => \Vokuro\Models\Location::TYPE_YELP,
                        'external_id' => $yelp_id,
                        'api_id' => $yelp_api_id,
                        'date_created' => date('Y-m-d H:i:s'),
                        'is_on' => 1,
                    ));

                    //find the review info
                    $this->importYelp($lrs, $loc, $foundagency);
                }

                //check for google
                $google_place_id = $this->request->getPost('google_place_id', 'striptags');

                $google_api_id = $this->request->getPost('google_api_id', 'striptags');

                if ($google_place_id != '') {
                    $googleScan = new GoogleScanning();
                    $lrs = new LocationReviewSite();
                    $lrs->assign(array(
                        'location_id' => $loc->location_id,
                        'review_site_id' => \Vokuro\Models\Location::TYPE_GOOGLE,
                        'external_id' => $google_place_id,
                        'api_id' => $google_api_id,
                        'date_created' => date('Y-m-d H:i:s'),
                        'is_on' => 1,
                        'lrd' => $googleScan->getLRD($google_place_id),
                    ));

                    //find the review info
                    $this->importGoogle($lrs, $loc, $foundagency);
                }

                //check for facebook
                $facebook_page_id = $this->request->getPost('facebook_page_id', 'striptags');
                if ($facebook_page_id != '') {
                    $lrs = new LocationReviewSite();
                    $lrs->assign(array(
                        'location_id' => $loc->location_id,
                        'review_site_id' => \Vokuro\Models\Location::TYPE_FACEBOOK,
                        'external_id' => $facebook_page_id,
                        'date_created' => date('Y-m-d H:i:s'),
                        'is_on' => 1,
                    ));


                    $this->importFacebook($lrs);
                }


                //$this->flash->success("The location was created successfully");
                $agency->assign(array(
                    'signup_page' => 3, //go to the next page
                ));
                if (!$agency->save()) {
                    //$this->flash->error($agency->getMessages());
                }
                
                $this->auth->setLocation($loc->location_id);

                return $this->response->redirect('/location/edit/' . $loc->location_id . '/0/1');
            }
        }

        
        $this->view->SignupProcess = true;
        $this->view->facebook_access_token = $this->facebook_access_token;

        $this->view->current_step = 2;   
    }

    /**
     * Sign up form, Step 3 (Customize Survey)
     */
    /* public function signup3Action($subscription_id = 0) { */
    public function signup3Action($pricingProfileToken = 0) {
        $this->view->setTemplateBefore('signup');
        $this->tag->setTitle('Get Mobile Reviews | Sign up | Step 3 | Customize Survey');

        $identity = $this->auth->getIdentity();
        // If there is no identity available the user is redirected to index/index
        if (!is_array($identity)) {
            $this->response->redirect('/session/login?return=/session/signup3/' . ($subscription_id > 0 ? $subscription_id : ''));
            $this->view->disable();
            return;
        }
        // Query binding parameters with string placeholders
        $conditions = "id = :id:";
        $parameters = array("id" => $identity['id']);
        $userObj = Users::findFirst(array($conditions, "bind" => $parameters));

        $conditions = "agency_id = :agency_id:";
        $parameters = array("agency_id" => $userObj->agency_id);
        $agency = Agency::findFirst(array($conditions, "bind" => $parameters));
        
        $conditions = "agency_id = :agency_id:";
        $parameters = array("agency_id" => $agency->parent_id);
        $parent_agency = Agency::findFirst(array($conditions, "bind" => $parameters));
        //$this->view->logo_path = "/img/agency_logos/" . $parent_agency->logo_path;
        $this->view->parent_agency = $parent_agency->name;
        
        $conditions = "location_id = :location_id:";
        $parameters = array("location_id" => $this->session->get('auth-identity')['location_id']);
        $location = Location::findFirst(array($conditions, "bind" => $parameters));

        

        if ($this->request->isPost()) {
            $location->assign(array(
                'name' => $this->request->getPost('agency_name', 'striptags'),
                'sms_button_color' => $this->request->getPost('sms_button_color', 'striptags'),
                'sms_top_bar' => $this->request->getPost('sms_top_bar', 'striptags'),
            	'SMS_message' => $this->request->getPost('sms_text_message_default', 'striptags'),
            ));
            $file_location = $this->uploadAction($agency->agency_id);
            if ($file_location != '')
                $location->sms_message_logo_path = $file_location;
            if (!$location->save()) {
                $this->flash->error($location->getMessages());
            } else {
                $agency->signup_page = 4;
                $agency->save();
                return $this->response->redirect('/session/signup4/' . ($subscription_id > 0 ? $subscription_id : ''));
            }
        } else {
        	
        }


        $this->view->agency = $agency;
        $this->view->location = $location;
        $this->view->current_step = 3;
        $this->view->id = $agency->agency_id;
        $this->view->default_sms_agency=$parent_agency->SMS_message;
    }

    /**
     * Sign up form, Step 4 (Add Employee)
     */
    /* public function signup4Action($subscription_id = 0) { */
    public function signup4Action($pricingProfileToken = 0) {

        /* $this->noSubDomains(4, $subscription_id); */
        Utils::noSubDomains(4, $this->validSubDomains, $pricingProfileToken);

        $this->view->setTemplateBefore('signup');
        $this->tag->setTitle('Get Mobile Reviews | Sign up | Step 4 | Add Employee');

        //get the user id, to find the settings
        $identity = $this->auth->getIdentity();
        // If there is no identity available the user is redirected to index/index
        if (!is_array($identity)) {
            $this->response->redirect('/session/login?return=/session/signup4/' . ($subscription_id > 0 ? $subscription_id : ''));
            $this->view->disable();
            return;
        }

        // Query binding parameters with string placeholders
        $conditions = 'id = :id:';
        $parameters = array('id' => $identity['id']);
        $userObj = Users::findFirst(array($conditions, 'bind' => $parameters));

        // Get employees
        $this->view->employees = Users::find("agency_id = {$userObj->agency_id} AND is_employee = 1");

        //find the agency
        $conditions = "agency_id = :agency_id:";
        $parameters = array("agency_id" => $userObj->agency_id);
        $agency = Agency::findFirst(array($conditions, "bind" => $parameters));

        $conditions = "agency_id = :agency_id:";
        $parameters = array("agency_id" => $agency->parent_id);
        $parent_agency = Agency::findFirst(array($conditions, "bind" => $parameters));
        //$this->view->logo_path = "/img/agency_logos/" .$parent_agency->logo_path;
        $this->view->parent_agency = $parent_agency->name;
        
        //find the location
        $conditions = "location_id = :location_id:";
        $parameters = array("location_id" => $this->session->get('auth-identity')['location_id']);
        $location = Location::findFirst(array($conditions, "bind" => $parameters));

        if ($this->request->isPost()) {
            $agency->assign(array(
                'signup_page' => 5, //go to the next page
            ));
            $location->assign(array(
                'lifetime_value_customer' => $this->request->getPost('lifetime_value_customer', 'striptags'),
                'review_goal' => $this->request->getPost('review_goal', 'striptags'),
            ));

            if (!$location->save()) {
                $this->flash->error($location->getMessages());
            } else {
                $agency->save();
                return $this->response->redirect('/session/signup5/' . ($subscription_id > 0 ? $subscription_id : ''));
                $this->view->disable();
                return;
            }
        }


        $this->usersFunctionality(3, $this->session->get('auth-identity')['location_id']);
        $this->view->location = $location;
        $this->view->current_step = 4;
    }

    /**
     * Sign up form, Step 5 (Share)
     */
    /* public function signup5Action($subscription_id = 0) { */
    public function signup5Action($pricingProfileToken = 0,$email = null) {
        /* $this->noSubDomains(5, $subscription_id); */
        Utils::noSubDomains(5, $this->validSubDomains, $pricingProfileToken);

        $this->view->setTemplateBefore('signup');
        $this->tag->setTitle('Get Mobile Reviews | Sign up | Step 5 | Share');
        $this->view->messages_sent = false;;
        //get the user id, to find the settings
        $identity = $this->auth->getIdentity();

        // If there is no identity available the user is redirected to index/index
        if (!is_array($identity)) {
            $this->response->redirect('/session/login?return=/session/signup5/' . ($subscription_id > 0 ? $subscription_id : ''));
            $this->view->disable();
            return;
        }

        // Query binding parameters with string placeholders
        $conditions = "id = :id:";
        $parameters = array("id" => $identity['id']);
        $userObj = Users::findFirst(array($conditions, "bind" => $parameters));
        //echo '<pre>$userObj:'.print_r($userObj->agency_id,true).'</pre>';
        //find the agency
        $conditions = "agency_id = :agency_id:";
        $parameters = array("agency_id" => $userObj->agency_id);
        $agency = Agency::findFirst(array($conditions, "bind" => $parameters));

        $conditions = "agency_id = :agency_id:";
        $parameters = array("agency_id" => $agency->parent_id);
        $parent_agency = Agency::findFirst(array($conditions, "bind" => $parameters));
        //$this->view->logo_path = "/img/agency_logos/" .$parent_agency->logo_path;
        $this->view->parent_agency = $parent_agency->name;
        //Get the sharing code
        $this->getShareInfo($agency);

        $AgencyUser = $this->view->AgencyUser;
        $AgencyName = $this->view->AgencyName;
        //end getting the sharing code
        // Check if the user wants to send emails
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Get the form fields and remove whitespace.
            $subject = $this->view->share_subject;
            $message = $this->view->share_message;

            if($agency->parent_id > 0) {
                $objParentAgency = \Vokuro\Models\Agency::findFirst("agency_id = {$agency->parent_id}");
                if(!$objParentAgency->email_from_address && !$objParentAgency->custom_domain)
                    throw \Exception("Contact customer support.  Email configuration not setup correctly");

                $Domain = $this->config->application->domain;


                $EmailFrom = $objParentAgency->email_from_address ?: 'no-reply@' . $objParentAgency->custom_domain . ".{$Domain}";
                $EmailFromName = $objParentAgency->email_from_name ?: 'No Reply';
            }

            $Domain = $this->config->application->domain;

            if($agency->parent_id == \Vokuro\Models\Agency::BUSINESS_UNDER_RV) {
                $EmailFrom = 'zacha@reviewvelocity.co';
                $EmailFromName = "Zach Anderson";
            }

            if($agency->parent_id == \Vokuro\Models\Agency::AGENCY) {
                if(!$agency->email_from_address && !$agency->custom_domain)
                    throw \Exception("Contact customer support.  Email configuration not setup correctly");
                $EmailFrom = $agency->email_from_address ?: "no-reply@{$agency->custom_domain}.{$Domain}";
                $EmailFromName = $agency->email_from_name ?: 'No Reply';
            }

            //loop through all the emails
            for ($i = 1; $i < 16; $i++) {
                if ($_POST['email_' . $i]) {
                    $email = $_POST['email_' . $i];
                    if ($email != '') {
                        try {
                            $Email_set=explode('@',$email);
                            $header_name="Hey ".$Email_set[0].",";
                            $body_message=$header_name.$message;
                            $Mail = $this->getDI()->getMail();
                            $Mail->setFrom($EmailFrom, $EmailFromName);
                            $Mail->send($email, $subject, '', '', $message);
                        } catch (Exception $e) {
                            // do nothing, just ignore
                        }
                    }
                }
            }
            $this->view->messages_sent = true;
            $this->flash->success('The emails have been sent.  Use the form to send some more.  Click the "FINISHED" button to leave this page.');
            Tag::resetInput();
        }

        if (isset($_GET['q']) && $_GET['q'] == 's') {
          //print_r($_GET);
            $agency->assign(array(
                'signup_page' => '0', //go to the next page
            ));

            $Domain = $this->config->application->domain;

            if (!$agency->save()) {
                $this->flash->error($agency->getMessages());
            } else {
                    if($agency->parent_id > 0) {
                        $objParentAgency = \Vokuro\Models\Agency::findFirst("agency_id = {$agency->parent_id}");
                        if(!$objParentAgency->email_from_address && !$objParentAgency->custom_domain)
                            throw \Exception("Contact customer support.  Email configuration not setup correctly");
                        $EmailFrom = $objParentAgency->email_from_address ?: "no-reply@{$objParentAgency->custom_domain}.{$Domain}";
                        $EmailFromName = $objParentAgency->email_from_name ?: "";
                    }

                    if($agency->parent_id == \Vokuro\Models\Agency::BUSINESS_UNDER_RV) {
                        $EmailFrom = 'zacha@reviewvelocity.co';
                        $EmailFromName = "Zach Anderson";
                    }
                    if($agency->parent_id == \Vokuro\Models\Agency::AGENCY) {
                        if(!$agency->email_from_address && !$agency->custom_domain)
                            throw \Exception("Contact customer support.  Email configuration not setup correctly");
                        $EmailFrom = $agency->email_from_address ?: "no-reply@{$agency->custom_domain}.{$Domain}";
                        $EmailFromName = $agency->email_from_name ?: "";

                    }

                    $Domain = $this->config->application->domain;

                    $publicUrl="http://{$Domain}";
                    $code=$userObj->id."-".$userObj->name;
                    $link=$publicUrl.'/link/createlink/'.base64_encode($code);
                    $feed_back_email=$userObj->email;
                    $feed_back_subj='Feedback Form';
                    $feed_back_body='Hi '.$userObj->name.',';
                    $feed_back_body=$feed_back_body.'<p>Thank you for activating your account, we have created a mobile landing page so that you can request feedback from your customers in person from your mobile phone.</p><p>Click on the link below and add the the page to your home screen so that you can easily access this page. This link is customized to you so that all feedback and reviews will be tracked back to your account. 
                        </p>

                        <p>The best practices is to ask your customer for feedback right after you have completed the services for them. We recommend that you ask them to please leave a review on one of the sites we suggest and to mention your name in the review online. </p>';
                        $feed_back_body=$feed_back_body.'<a href="'.$link.'">Personalized Feedback Form - Click Here </a>
                            <p>
                            Do not give this link out to any one else it is a personalized link for you and will track all your feedback requests. Each employee has their own personalized feedback form. 
                            </p>
                        <p>Looking forward to helping you build a strong online reputation.</p>';

                        if($_SESSION['password_save'])
                        {   
                             $feed_back_body=$feed_back_body.'<p>Please view the Login Credentials Below: </p>';
                           $feed_back_body=$feed_back_body."Login Password: ". $_SESSION['password_save']."<br>";
                           $feed_back_body=$feed_back_body."Login Email: ".$feed_back_email."<br>";
                        }

                        $feed_back_body=$feed_back_body."<br>".$AgencyUser."<br>".$AgencyName;
                    $Mail = $this->getDI()->getMail();
                    $Mail->setFrom($EmailFrom, $EmailFromName);
                    $Mail->send($feed_back_email, $feed_back_subj, '', '', $feed_back_body);
                    $_SESSION['password_save']='';
                return $this->response->redirect('/');
                $this->view->disable();
                return;
            }
        }

        $this->view->current_step = 5;
    }

    /**
     * thankyou page
     */
    public function thankyouAction() {
        $this->view->setTemplateBefore('login');
        $this->tag->setTitle('Get Mobile Reviews | Thank You');

        //test code below, uncomment to test
        //$_SESSION['name']='Test Tester';
        //$_SESSION['email']='test@tester.com';
    }

    public function changePasswordAction() {
    	$this->tag->setTitle('Get Mobile Reviews | Change Password');
    	$this->view->setTemplateBefore('login');
    }
    /**
     * privacy page
     */
    public function resetPasswordAction($code = 0, $userId = 0) {
    	//$this->view->setTemplateBefore('login');
    	$this->tag->setTitle('Get Mobile Reviews | Change Password');

    	$resetPassword = ResetPasswords::findFirstByCode($code);
    	$conditions = "id = :id:";
    	$parameters = array("id" => $resetPassword->usersId);
    	$User = Users::findFirst(array($conditions, "bind" => $parameters));

    	$conditions = "agency_id = :agency_id:";
    	$parameters = array("agency_id" => $User->agency_id);
    	$agency = Agency::findFirst(array($conditions, "bind" => $parameters));

    	//$this->view->logo_path = "/img/agency_logos/" . $agency->logo_path;

    	if (!$resetPassword) {
    		return $this->dispatcher->forward(array(
    				'controller' => 'index',
    				'action' => 'index'
    		));
    	}
    	if ($resetPassword->reset != 'N') {
    		return $this->dispatcher->forward(array(
    				'controller' => 'session',
    				'action' => 'login'
    		));
    	}
    	$resetPassword->reset = 'Y';
    	
    	/**
    	 * Change the confirmation to 'reset'
    	 */
    	if (!$resetPassword->save()) {
    		foreach ($resetPassword->getMessages() as $message) {
    			$this->flash->error($message);
    		}
    	
    		return $this->dispatcher->forward(array(
    				'controller' => 'session',
    				'action' => 'changePassword'
    		));
    	}
    	
    	/**
    	 * Identify the user in the application
    	 */

    	$this->auth->authUserById($resetPassword->usersId);
    	
    	$this->flash->success('Please reset your password');
    	
    	return $this->dispatcher->forward(array(
    			'controller' => 'session',
    			'action' => 'changePassword'
    	));
    	
    }
    

    /**
     * privacy page
     */
    public function privacyAction() {
    	$this->view->setTemplateBefore('login');
    	$this->tag->setTitle('Get Mobile Reviews | Privacy');
    
    }

    /**
     * reseller page
     */
    public function resellerAction() {
    	$this->view->setTemplateBefore('login');
    	$this->tag->setTitle('Get Mobile Reviews | Reseller Agreement');
    
    }
    
    /**
     * terms page
     */
    public function termsAction() {

        

         

        $this->view->setTemplateBefore('login');
        $this->tag->setTitle('Get Mobile Reviews | Terms');
    }

    /**
     * Anti-spam Policy page
     */
    public function antispamAction() {
        $this->view->setTemplateBefore('login');
        $this->tag->setTitle('Get Mobile Reviews | Anti-spam Policy');
    }

    /**
     * RVprivacy page
     */
    public function RVprivacyAction() {
    	$this->view->setTemplateBefore('login');
    	$this->tag->setTitle('Get Mobile Reviews | Privacy');
    
    }
    
    /**
     * RVreseller page
     */
    public function RVresellerAction() {
    	$this->view->setTemplateBefore('login');
    	$this->tag->setTitle('Get Mobile Reviews | Reseller Agreement');
    
    }
    
    /**
     * RVterms page
     */
    public function RVtermsAction() {
    	$this->view->setTemplateBefore('login');
    	$this->tag->setTitle('Get Mobile Reviews | Terms');
    }
    
    /**
     * RVAnti-spam Policy page
     */
    public function RVantispamAction() {
    	$this->view->setTemplateBefore('login');
    	$this->tag->setTitle('Get Mobile Reviews | Anti-span Policy');
    }
    
    
    /**
     * Starts a session in the admin backend
     */
    public function loginAction() {
        $this->view->setTemplateBefore('login');
        $this->tag->setTitle('Get Mobile Reviews | Login');
        $form = new LoginForm();
        $email = $this->dispatcher->getParam('email');
        $this->view->email = $email;
        $this->DetermineParentIDAndSetViewVars();
        try {
            if (!$this->request->isPost()) {
                if ($this->auth->hasRememberMe()) {
                    return $this->auth->loginWithRememberMe();
                }
            } else {
                if ($form->isValid($this->request->getPost()) == false) {
                    foreach ($form->getMessages() as $message) {
                        $this->flash->error($message);
                    }
                } else {
                    $this->auth->check(array(
                        'email' => $this->request->getPost('email'),
                        'password' => $this->request->getPost('password'),
                        'remember' => $this->request->getPost('remember')
                    ));



                    $return = '/';
                    if (isset($_GET['return']) && strpos($_GET['return'], '/') !== false)
                        $return = $_GET['return'];

                    //get the user id, to find the settings
                    $identity = $this->auth->getIdentity();

                    // If there is no identity available the user is redirected to index/index
                    if (is_array($identity)) {
                        // Query binding parameters with string placeholders
                        $conditions = "id = :id:";
                        $parameters = array("id" => $identity['id']);
                        $userObj = Users::findFirst(array($conditions, "bind" => $parameters));

                        $conditions = "agency_id = :agency_id:";
                        $parameters = array("agency_id" => $userObj->agency_id);
                        $agency = Agency::findFirst(array($conditions, "bind" => $parameters));
                       /* if ($agency->signup_page > 0 && $userObj->role=='Super Admin')
                        {
                            
                             $return = '/agencysignup/step' . $agency->signup_page . '/' . ($agency->subscription_id > 0 ? $subscription_id : '');
                        }*/


                        if ($agency->signup_page > 0 && $agency->parent_id!=0)

                        {
                            $_SESSION['password_save']=$this->request->getPost('password');
                            $return = '/session/signup' . $agency->signup_page . '/' . ($agency->subscription_id > 0 ? $subscription_id : '');
                        }
                        elseif($agency->signup_page > 0 && $agency->parent_id==0)
                        {
                             $return = '/agencysignup/step' . $agency->signup_page . '/' . ($agency->subscription_id > 0 ? $subscription_id : ''); 
                        }


                            
                    }
                    return $this->response->redirect($return);
                }
            }
        } catch (AuthException $e) {
            $this->flash->error($e->getMessage());
        }
        if (isset($_GET['n']) && $_GET['n'] == 1)
            $this->flash->success('Your account has been created and a confirmation email has been sent to your email address.');
        $this->view->form = $form;
    }

    /**
     * Shows the forgot password form
     */
    public function forgotPasswordAction() {

        $this->view->setTemplateBefore('login');
        $this->tag->setTitle('Get Mobile Reviews | Forgot password');
        $form = new ForgotPasswordForm();

        if ($this->request->isPost()) {
            if ($form->isValid($this->request->getPost()) == false) {
                foreach ($form->getMessages() as $message) {
                    $this->flash->error($message);
                }
            } else {
                $user = Users::findFirstByEmail($this->request->getPost('email'));
                if (!$user) {
                    $this->flash->success('There is no account associated with this email');
                } else {
                    if ($user->active == 'N') {
                      $email = new \Vokuro\Services\Email();
                      $email->sendActivationEmailByUserId($user->id);
                      $this->flash->success('Success! Please check your messages for a confirmation email');

                    } else {
                      $resetPassword = new ResetPasswords();
                      $resetPassword->usersId = $user->id;
                      if ($resetPassword->save()) {
                          $this->flash->success('Success! Please check your messages for an email reset password');
                      } else {
                          foreach ($resetPassword->getMessages() as $message) {
                              $this->flash->error($message);
                          }
                      }
                    }
                }
            }
        }

        $this->view->form = $form;
    }

    /**
     * Closes the session
     */
    public function logoutAction() {
        $this->auth->remove();
        return $this->response->redirect('/');
    }

    function curl_get_contents($url) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }

    /**
     * This is Search using Google place API.
     * This is used at: /session/signup2/
     * @return Json
     */
    public function googlesearchapiAction() {

        #check form post
        $request = $this->request;

        if ($this->request->isPost()) {

            #get data from post
            $businessName = trim($request->getPost('location_name'));
            $zip = trim($request->getPost('zip'));

            try {
                #call map api
                $googleApiKey = 'AIzaSyAPisblAqZJJ7mGWcORf4FBjNMQKV20J20';
                $keyword = urlencode($businessName . ', ' . $zip);

                if (!empty($keyword)) {

                    #get place of an business with business name and zip using Google API
                    $strFindPlaceUrl = "https://maps.googleapis.com/maps/api/place/textsearch/json?query=" . $keyword . "&key=" . $googleApiKey;
                    //echo '<p>$strFindPlaceUrl:'.$strFindPlaceUrl.'</p>';
                    $resultFindPlace = $this->curl_get_contents($strFindPlaceUrl);
                    $arrResultFindPlace = json_decode($resultFindPlace, true);

                    //echo '<pre>'.print_r($arrResultFindPlace,true).'</pre>';
                    //die();

                    $admincode = '0';
                    //echo '<pre>'.print_r($_POST,true).'</pre>';
                    if (isset($_POST['admin']) && $_POST['admin'] != '') {
                        //we need to save results
                        $admincode = '1';
                    }

                    #get place details from place ID using Google API
                    if ($arrResultFindPlace['status'] == "OK" && !empty($arrResultFindPlace['results'])) {

                        #initialize variables
                        $intCounter = 0;
                        $strHTML = "<h3>Choose the most relevant result</h3>";

                        foreach ($arrResultFindPlace['results'] as $singleResultFindPlace) {
                            #break after 10 result
                            if ($intCounter == 10)
                                break;

                            $strPlaceId = $singleResultFindPlace['place_id'];

                            $strFindPlaceDetail = "https://maps.googleapis.com/maps/api/place/details/json?placeid=" . $strPlaceId . "&key=" . $googleApiKey;
                            $resultFindPlaceDetail = $this->curl_get_contents($strFindPlaceDetail);
                            $arrResultFindPlaceDetail = json_decode($resultFindPlaceDetail, true);

                            $returnAddress = @$arrResultFindPlaceDetail['result']['formatted_address'];

                            $returnPhoneNumber = @$arrResultFindPlaceDetail['result']['formatted_phone_number'];
                            $returnBusinessName = @$arrResultFindPlaceDetail['result']['name'];

                            $street_number = $this->extractFromAdress(@$arrResultFindPlaceDetail['result']['address_components'], 'street_number');
                            $route = $this->extractFromAdress(@$arrResultFindPlaceDetail['result']['address_components'], 'route');
                            $locality = $this->extractFromAdress(@$arrResultFindPlaceDetail['result']['address_components'], 'locality');
                            $administrative_area_level_1 = $this->extractFromAdress(@$arrResultFindPlaceDetail['result']['address_components'], 'administrative_area_level_1');
                            $postal_code = $this->extractFromAdress(@$arrResultFindPlaceDetail['result']['address_components'], 'postal_code');
                            $country = $this->extractFromAdress(@$arrResultFindPlaceDetail['result']['address_components'], 'country');

                            $strURL = "";
                            $strButton = "";

                            if (isset($returnBusinessName) && $returnBusinessName != '') {

                                //check to see if this location is already in the database, by checking the place id
                                $conditions = "api_id = :api_id: AND review_site_id = " . \Vokuro\Models\Location::TYPE_GOOGLE;
                                $parameters = array("api_id" => @$arrResultFindPlaceDetail['result']['place_id']);
                                // Skip duplicate check if we're dev environment.
                                $SkipCheck = $this->config->application->environment == 'dev' ? true : false;

                                $loc = LocationReviewSite::findFirst(array($conditions, "bind" => $parameters));
                                if (!$loc || $SkipCheck) {
                                    $strURL = "onclick=\"selectLocation('" . $this->encode(@$arrResultFindPlaceDetail['result']['place_id']) . "', '" .
                                      $this->encode(@$arrResultFindPlaceDetail['result']['url']) . "', '" . $this->encode($returnBusinessName) . "', '" .
                                      $this->encode($street_number) . "', '" . $this->encode($route) . "', '" . $this->encode($locality) . "', '" .
                                      $this->encode($administrative_area_level_1) . "', '" . $this->encode($postal_code) . "', '" .
                                      $this->encode($country) . "', '" . $this->encode(@$arrResultFindPlaceDetail['result']['formatted_phone_number']) . "', '" .
                                      $this->encode(@$arrResultFindPlaceDetail['result']['geometry']['location']['lat']) . "', '" .
                                      $this->encode(@$arrResultFindPlaceDetail['result']['geometry']['location']['lng']) . "');return false;\" href=\"javascript:void(0);\"";
                                    $strButton = "<a class=\"business-name-link btnLink btnSecondary\" id=\"business-name-link\" " . $strURL . " style=\"float: right; height: 40px; line-height: 24px;\" >Choose This Listing</a>";
                                } else {
                                    //the location was found, so tell the user that
                                    $strURL = "href=\"javascript:void(0);\"";
                                    $strButton = "<div style=\"float: right; margin-top: -10px; padding: 5px; text-align: center; width: 215px;\">Already Registered Contact Support</div>";
                                }


                                $strHTML .= "<div class=\"border-box-s\" style=\"min-height: 110px;\">
                    <p class=\"business-name\"><a class=\"business-name-link\" id=\"business-name-link\" " . $strURL . ">" . $returnBusinessName . "</a></p>
                    " . $strButton . "
                    <ul>
                    <li>" . $returnAddress . "</li>
                    <li>" . $returnPhoneNumber . "</li>
                    </ul>
                    </div>";

                                #increment counter
                                $intCounter++;
                            }
                        }

                        #set response
                        $responseArr = array('HTML' => $strHTML);
                    } else {
                        //echo '<pre>'.print_r($arrResultFindPlace,true).'</pre>';
                        #set response
                        $responseArr = array('errorMsg' => 'Result not found.');
                    }
                } else {
                    #set response
                    $responseArr = array('errorMsg' => 'Result not found');
                }
            } catch (Exception $e) {
                #set response
                $responseArr = array('errorMsg' => 'There was an error processing your request.');
            }

            echo json_encode($responseArr);
            exit;
        }
    }

    
    public function checkForAvailableEmailAction() {
    	
    	$testThisEmail =  $_POST['email'];
    	//find the User
    	$conditions = "email = :email:";
    	$parameters = array("email" => $testThisEmail);
    	$Users = Users::findfirst(array($conditions, "bind" => $parameters));
    	if ((isset($Users)) && ($Users->id > 0)) {
    		echo  $Users->id;
    	} else {
    		echo  $Users->id;
    	}
    }
    
    
    public function checkForAvailableSubDomainAction() {
    	 
    	$testThisCustomDomain =  $_POST['custom_domain'];
    	//find the User
    	$conditions = "custom_domain = :custom_domain:";
    	$parameters = array("custom_domain" => $testThisCustomDomain);
    	$Agency = Agency::findfirst(array($conditions, "bind" => $parameters));
    	if (isset($Agency) && $Agency->agency_id > 0) {
    		echo  1;
    	} else {
    		echo  0;
    	}
    }
    
    /**
     * Sends a review invite to the selected location
     */
    public function sendsmsAction() {
        //$results = 'There was a problem sending the message.';
        $results ='';
        $message = $_GET['body'].'  Reply stop to be removed';
        $original_message = $message;
        $name = $_GET['name'];
        $cell_phone = $_GET['cell_phone'];
        $id = intval($_GET['id']);
        $locationID = intval($_GET['location_id']);
        $this->checkIntegerOrThrowException($id);
        $message = str_replace("%7D", "}", $message);
        $message = str_replace("%7B", "{", $message);
        $message = str_replace("{location-name}", $name, $message);
        $message = str_replace("{name}", 'Name', $message);
        $message = str_replace("{link}", 'Link', $message);

        $identity = $this->auth->getIdentity();
        $objUser = \Vokuro\Models\Users::findFirst("id = {$identity['id']}");
        $objBusiness = \Vokuro\Models\Agency::findFirst("agency_id = {$objUser->agency_id}");

        if($objBusiness->parent_id == \Vokuro\Models\Agency::BUSINESS_UNDER_RV) {
            $TwilioAPIKey = $this->config->twilio->twilio_api_key;
            $TwilioAuthToken = $this->config->twilio->twilio_auth_token;

            $TwilioFromPhone = $objBusiness->twilio_from_phone ?: $this->config->twilio->twilio_from_phone;
        } else {
            if($objBusiness->parent_id!=0)
            {
            $objAgency = \Vokuro\Models\Agency::findFirst("agency_id = {$objBusiness->parent_id}");
            $TwilioAPIKey = $objAgency->twilio_api_key;
            $TwilioAuthToken = $objAgency->twilio_auth_token;

            $TwilioFromPhone = $objBusiness->twilio_from_phone ?: $objAgency->twilio_from_phone;
            }
            else
            {
               $TwilioAPIKey = $objBusiness->twilio_api_key;
            $TwilioAuthToken = $objBusiness->twilio_auth_token;

            $TwilioFromPhone = $objBusiness->twilio_from_phone; 
            }
        }

            

      // echo $TwilioAPIKey."-".$TwilioAuthToken."-".$TwilioAuthMessagingSID."-".$TwilioFromPhone;exit;


        if(!$TwilioAPIKey || !$TwilioAuthToken || !$TwilioFromPhone) {
            $this->flash->error("Twilio configuration error.  Please contact customer support.");
        }
        

        if($locationID)
        {
        $conditions = "location_id = :location_id:";
        $parameters = array("location_id" => $locationID);
        $location = Location::findFirst(array($conditions, "bind" => $parameters));
        if (isset($location)) {
            $location->SMS_message = $original_message;
            $location->save();
        }
        }
        //The message is saved, so send the SMS message now
        if ($this->SendSMS($this->formatTwilioPhone($cell_phone), $message, $TwilioAPIKey, $TwilioAuthToken,  $TwilioFromPhone)) {
            $this->flash->success("The message was sent!");
        }
        else
        {
            $this->flash->error("There was a problem sending messages");
        }
        $this->view->disable();
        echo $results;
    }

}


/**
     * Allow a user to signup to the system
     */
    /*
    public function subscribeAction($subscription_stripe_id = 0) {

          $this->view->setTemplateBefore('login');
          $this->tag->setTitle('Get Mobile Reviews | Sign up');
          $form = new SignUpForm();

          $this->view->maxlimitreached = $this->isMaxLimitReached();

          //find the agency
          $conditions = "agency_id = :agency_id:";
          $parameters = array("agency_id" => $this->view->agency_id);
          $agency = Agency::findFirst(array($conditions, "bind" => $parameters));
          if (!$agency) {
          $this->flash->error("No agency found");
          } else {
          $this->view->agency = $agency;

          if ($subscription_stripe_id > 0) {
          $conditions = "subscription_stripe_id = :subscription_stripe_id:";
          $parameters = array("subscription_stripe_id" => $subscription_stripe_id);
          $subscriptionobj = SubscriptionStripe::findFirst(array($conditions, "bind" => $parameters));
          $this->view->subscription = $subscriptionobj;

          if ($this->request->isPost()) {
          try {

          $ccformvalid = false;
          //check to make sure credit card values were filled out
          if (isset($_POST['stripeToken']) && $_POST['stripeToken'] != '' &&
          isset($_POST['stripeEmail']) && $_POST['stripeEmail'] != '') {
          //$_POST['email'] = $_POST['stripeEmail'];
          //we have values, assume valid
          $ccformvalid = true;
          }
          //check user email unuique
          $user = new Users();
          $user->assign(array(
          'name' => $this->request->getPost('name', 'striptags'),
          'email' => $this->request->getPost('email'),
          'password' => $this->security->hash($this->request->getPost('password')),
          'profilesId' => 1, //All new users will be "Agency Admin"
          ));
          $isemailunuique = $user->validation();

          if ($form->isValid($this->request->getPost()) != false && $ccformvalid && $isemailunuique) {
          //create the Stripe subscription account
          \Stripe\Stripe::setApiKey($agency->stripe_account_secret);
          $customer = \Stripe\Customer::create(array(
          'source' => $_POST['stripeToken'],
          'email' => $_POST['stripeEmail'],
          'plan' => $subscriptionobj->plan,
          ));
          //echo '<pre>$customer:'.print_r($customer,true).'</pre>';
          //first create an agency
          $agency_name = $this->request->getPost('agency_name', 'striptags');
          $agency2 = new Agency();
          $agency2->assign(array(
          'name' => $agency_name,
          'stripe_token' => $_POST['stripeToken'],
          'parent_agency_id' => $agency->agency_id,
          'stripe_customer_id' => $customer->id,
          'stripe_subscription_id' => $customer->subscriptions->data[0]->id,
          'date_created' => date('Y-m-d H:i:s'),
          ));
          if (!$agency2->save()) {
          $this->flash->error($agency2->getMessages());
          } else {
          $user->agency_id = $agency2->agency_id;

          if ($user->save()) {
          //$this->flash->error('A confirmation email has been sent to ' . $this->request->getPost('email'));
          //redirect
          return $this->response->redirect('/session/login?n=1');
          }
          }

          $this->flash->error($user->getMessages());
          } else {
          if (!$isemailunuique)
          $this->flash->error('That email address is already taken.');
          if (!$ccformvalid)
          $this->flash->error('Please enter your credit card information.');
          }
          } catch (Stripe_CardError $e) {
          $this->flash->error('There was a problem proccessing the credit card.  Please check the information and try again. <!--' . $e->getMessage() . '-->');
          } catch (Stripe_InvalidRequestError $e) {
          // Invalid parameters were supplied to Stripe's API
          $this->flash->error('There was a problem proccessing the credit card.  Please check the information and try again. <!--' . $e->getMessage() . '-->');
          } catch (Stripe_AuthenticationError $e) {
          // Authentication with Stripe's API failed
          // (maybe you changed API keys recently)
          $this->flash->error('There was a problem proccessing the credit card.  Please check the information and try again. <!--' . $e->getMessage() . '-->');
          } catch (Stripe_ApiConnectionError $e) {
          // Network communication with Stripe failed
          $this->flash->error('There was a problem proccessing the credit card.  Please check the information and try again. <!--' . $e->getMessage() . '-->');
          } catch (Stripe_Error $e) {
          // Display a very generic error to the user, and maybe send
          // yourself an email
          $this->flash->error('There was a problem proccessing the credit card.  Please check the information and try again. <!--' . $e->getMessage() . '-->');
          } catch (Exception $e) {
          // Something else happened, completely unrelated to Stripe
          $this->flash->error('There was a problem proccessing the credit card.  Please check the information and try again. <!--' . $e->getMessage() . '-->');
          } catch (\Stripe\Error\Base $e) {
          // Code to do something with the $e exception object when an error occurs
          $this->flash->error('There was a problem proccessing the credit card.  Please check the information and try again. <!--' . $e->getMessage() . '-->');
          }
          }
          }
          }
         *
    }
     *
     */
