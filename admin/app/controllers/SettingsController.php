<?php
    namespace Vokuro\Controllers;

    use Phalcon\Image\Adapter\GD;
    use Phalcon\Tag;
    use Phalcon\Mvc\Model\Criteria;
    use Phalcon\Mvc\Model\Resultset\Simple as Resultset;
    use Phalcon\Paginator\Adapter\Model as Paginator;
    use Vokuro\Forms\AgencyForm;
    use Vokuro\Forms\ChangePasswordForm;
    use Vokuro\Forms\SettingsForm;
    use Vokuro\Models\Agency;
    use Vokuro\Models\Location;
    use Vokuro\Models\UsersLocation;
    use Vokuro\Models\LocationNotifications;
    use Vokuro\Models\LocationReviewSite;
    use Vokuro\Models\PasswordChanges;
    use Vokuro\Models\ReviewInvite;
    use Vokuro\Models\ReviewsMonthly;
    use Vokuro\Models\ReviewSite;
    use Vokuro\Models\SharingCode;
    use Vokuro\Services\ServicesConsts;
    use Vokuro\Models\Users;
use Services_Twilio;
use Services_Twilio_RestException;
use Pricing_Services_Twilio;
    /**
     * Vokuro\Controllers\UsersController
     * CRUD to manage users
     */
    class SettingsController extends ControllerBase {

        public function initialize() {
            $this->tag->setTitle('Get Mobile Reviews | Settings');
            $this->view->setTemplateBefore('private');
            parent::initialize();

        }


        /**
         * Searches for users
         */
        public function indexAction() {
            $identity = $this->auth->getIdentity();
            if (!is_array($identity)) {
                $this->response->redirect('/session/login?return=/settings/');
                $this->view->disable();
                return;
            }
            // Query binding parameters with string placeholders
            $conditions = "id = :id:";
            $parameters = array("id" => $identity['id']);
            $userObj = Users::findFirst(array($conditions, "bind" => $parameters));

            if($userObj->agency_id) {
                $conditions = "agency_id = :agency_id:";
                $parameters = array("agency_id" => $userObj->agency_id);
                $agency = Agency::findFirst(array($conditions, "bind" => $parameters));
                if (!$agency) {
                    $this->flash->error("No settings were found");
                }
            }

            if ($this->request->isPost()) {

                $form = new SettingsForm($agency);
                $agencyform = new AgencyForm($agency);
                $form->bind($_POST, $agency);
                $agencyform->bind($_POST, $agency);

                $formvalid = $form->isValid($_POST);
                $agencyformvalid = $agencyform->isValid($_POST);

                if (!$formvalid || !$agencyformvalid) {
                    foreach ($agencyform->getMessages() as $message) {
                        $this->flash->error($message);
                    }
                    foreach ($form->getMessages() as $message) {
                        $this->flash->error($message);
                    }
                    
                    //} else if ($this->request->getPost('twilio_auth_messaging_sid')=='' && $this->request->getPost('twilio_from_phone')=='') {
                    // $this->flash->error('Either the Twilio Messaging Service SID or the Twilio Phone number is required. ');
                } else {
                    
                  //echo 'kk';exit;
                    $agency->assign(array(
                        'review_invite_type_id' => $this->request->getPost('review_invite_type_id', 'int'),
                        'review_goal' => $this->request->getPost('review_goal', 'int'),
                        'custom_domain' => $this->request->getPost('custom_domain'),
                        'lifetime_value_customer' => str_replace("$", "", str_replace(",", "", $this->request->getPost('lifetime_value_customer'))),
                        'SMS_message' => $this->request->getPost('SMS_message'),
                       /* 'twitter_message' => $this->request->getPost('twitter_message'),*/
                        'message_tries' => $this->request->getPost('message_tries'),
                        'notifications' => $this->request->getPost('notifications'),
                        'rating_threshold_star' => $this->request->getPost('rating_threshold_star'),
                        'rating_threshold_nps' => $this->request->getPost('rating_threshold_nps'),
                        'twilio_api_key' => $this->request->getPost('twilio_api_key'),
                        'twilio_auth_token' => $this->request->getPost('twilio_auth_token'),
                        'twilio_auth_messaging_sid' => $this->request->getPost('twilio_auth_messaging_sid'),
                        'twilio_from_phone' => $this->request->getPost('twilio_from_phone'),
                        'main_color' => $this->request->getPost('main_color'),
                        'stripe_account_id' => $this->request->getPost('stripe_account_id'),
                        'stripe_account_secret' => $this->request->getPost('stripe_account_secret'),
                        'stripe_publishable_keys' => $this->request->getPost('stripe_publishable_keys'),
                        'viral_sharing_code' => $this->request->getPost('viral_sharing_code'),
                        'review_order_facebook' => $this->request->getPost('review_order_facebook'),
                        'review_order_google' => $this->request->getPost('review_order_google'),
                        'review_order_yelp' => $this->request->getPost('review_order_yelp'),
                        'message_frequency' => $this->request->getPost('message_frequency'),
                        'name' => $this->request->getPost('name', 'striptags'),
                        'email' => $this->request->getPost('email', 'striptags'),
                        'address' => $this->request->getPost('address', 'striptags'),
                    	'address2' => $this->request->getPost('address2', 'striptags'),
                        'locality' => $this->request->getPost('locality', 'striptags'),
                        'state_province' => $this->request->getPost('state_province', 'striptags'),
                        'postal_code' => $this->request->getPost('postal_code', 'striptags'),
                        'country' => $this->request->getPost('country', 'striptags'),
                        'phone' => $this->request->getPost('phone', 'striptags'),
                        'welcome_email' => $this->request->getPost('welcome_email', 'striptags'),
                        'viral_email' => $this->request->getPost('viral_email', 'striptags'),
                 
                       
                    ));
                    //$file_location = $this->uploadAction($agency->agency_id);
                    if ($file_location != '') $agency->logo_path = $file_location;

                    //delete all notification users for this agency
                    $conditions = "location_id = :location_id:";
                    $parameters = array("location_id" => $this->session->get('auth-identity')['location_id']);
                    $notificationdelete = LocationNotifications::find(array($conditions, "bind" => $parameters));
                    //$notificationdelete->delete();

                    if (!empty($_POST['users'])) {
                        foreach ($_POST['users'] as $check) {
                            $agencyInsert = new LocationNotifications();
                            $agencyInsert->location_id = $this->session->get('auth-identity')['location_id'];
                            $agencyInsert->user_id = $check;
                            $agencyInsert->save();
                        }
                    }

                    if (!$agency->save()) {
                        $this->flash->error($agency->getMessages());
                    } else {
                        $this->flash->success("The settings were updated successfully");
                        Tag::resetInput();
                    }
                }
            }

            $location_id = $this->session->get('auth-identity')['location_id'];
            if($location_id) {
                // Query binding parameters with string placeholders
                $conditions = "agency_id = :agency_id:";
                $parameters = array("agency_id" => $userObj->agency_id);
                $users = Users::find(array($conditions, "bind" => $parameters));
                $this->view->users = $users;

                $conditions = "location_id = :location_id:";
                $parameters = array("location_id" => $this->session->get('auth-identity')['location_id']);
                $agencynotifications = LocationNotifications::find(array($conditions, "bind" => $parameters));

                $this->view->agency = $agency;
                $this->view->location = $agency;

                $this->view->form = new SettingsForm($agency, array(
                    'edit' => true
                ));
                $this->view->agencyform = new AgencyForm($agency, array(
                    'edit' => true
                ));
            }
            $this->getSMSReport();

        }

        // These are used in StoreSettings() because we save both an agency and location there but some fields differ.
        protected $tLocationFields = [
            'review_invite_type_id'         => 'int',
            'review_goal'                   => 'int',
            'lifetime_value_customer'       => 'replace_commas_dollars',
            'SMS_message'                   => 'string',
            'message_tries'                 => 'int',
            /*'twitter_message'               => 'string',*/
            'rating_threshold_star'         => 'int',
            'rating_threshold_nps'          => 'int',
            'message_frequency'             => 'int',
            'name'                          => 'string',
            'email'                         => 'string',
            'address'                       => 'string',
            'locality'                      => 'string',
            'state_province'                => 'string',
            'postal_code'                   => 'string',
            'country'                       => 'string',
            'phone'                         => 'string',
            
        ];

        protected $tAgencyFields = [
            'review_invite_type_id'         => 'int',
            'review_goal'                   => 'int',
            'lifetime_value_customer'       => 'replace_commas_dollars',
            'SMS_message'                   => 'string',
          /*  'twitter_message'               => 'string',*/
            'message_tries'                 => 'int',
            'rating_threshold_star'         => 'int',
            'rating_threshold_nps'          => 'int',
            'message_frequency'             => 'int',
            'name'                          => 'string',
            'email'                         => 'string',
            'address'                       => 'string',
        	'address2'                      => 'string',
            'locality'                      => 'string',
            'state_province'                => 'string',
            'postal_code'                   => 'string',
            'country'                       => 'string',
            'phone'                         => 'string',
            'main_color'                    => 'string',
            'secondary_color'               => 'string',
            'viral_email'                   => 'html',
            'welcome_email'                 => 'html',
            'welcome_email_employee'        => 'html',
        ];

        public function dismissstripeAction() {
            $this->session->StripePopupDisabled = 1;

            $this->view->disable();
            return "SUCCESS";
        }

        protected function storeLogo($objAgency) {
            if($this->request->hasFiles()) {

                foreach ($this->request->getUploadedFiles() as $file) {
                    // This is for handling page reloads.
                    if($file->getTempName()) {
                        if(isset($objAgency->logo_path) && $objAgency->logo_path) {
                            $logoFilename = __DIR__ . DIRECTORY_SEPARATOR . "..". DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "public" . DIRECTORY_SEPARATOR . "img" . DIRECTORY_SEPARATOR . "agency_logos" . DIRECTORY_SEPARATOR  . $this->session->AgencySignup['LogoFilename'];
                            if (is_file($logoFilename)) {
                              unlink($logoFilename);
                            }
                            //$objAgency->logo_path = '';
                            $objAgency->save();
                        }

                        $FileName = uniqid('logo') . '.' . $file->getExtension();
                        file_put_contents(__DIR__ .  DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "public" . DIRECTORY_SEPARATOR . "img" . DIRECTORY_SEPARATOR . "agency_logos" . DIRECTORY_SEPARATOR . "{$FileName}", file_get_contents($file->getTempName()));
                        $objAgency->logo_path = $FileName;

                        $objAgency->save();
                        $this->view->logo_path = DIRECTORY_SEPARATOR . "img" . DIRECTORY_SEPARATOR . "agency_logos" . DIRECTORY_SEPARATOR . "{$FileName}";
                        break;
                    }
                }
            }
        }

        protected function storeSettings($entity, $type) {
            //echo $type;exit;
            if ($this->request->isPost()) {
           
                $form = new SettingsForm($entity);
                //dd($form);
                $agencyform = new AgencyForm($entity);
                $form->bind($_POST, $entity);
                $agencyform->bind($_POST, $entity);

                $formvalid = $form->isValid($_POST);
                $agencyformvalid = $agencyform->isValid($_POST);


                if (!$formvalid || !$agencyformvalid) {
                    foreach ($agencyform->getMessages() as $message) {
                        $this->flash->error($message);
                    }
                    foreach ($form->getMessages() as $message) {
                        $this->flash->error($message);
                    }
                    //} else if ($this->request->getPost('twilio_auth_messaging_sid')=='' && $this->request->getPost('twilio_from_phone')=='') {
                    // $this->flash->error('Either the Twilio Messaging Service SID or the Twilio Phone number is required. ');
                } else {
                   // echo $this->request->getPost('welcome_email');
                  // echo 'k';exit;
                    $identity = $this->auth->getIdentity();
                    $conditions = "id = :id:";
                    $parameters = array("id" => $identity['id']);
                    $userObj = Users::findFirst(array($conditions, "bind" => $parameters));
                    $Agency_id=$userObj->agency_id; 
                  
                    $tEntityArray = [];
                    $tFieldArray = $type == 'agency' ? 'tAgencyFields' : 'tLocationFields';
                  // echo  $this->request->getPost('welcome_email', 'striptags');exit;
                    foreach($this->$tFieldArray as $Field => $DataType) {
                       /* echo $this->request->getPost($Field, 'striptags');
                        echo "<br>";*/
                       // echo $this->request->getPost($Field);echo '<br>';
                        switch($DataType) {
                            case 'int':
                                //$tEntityArray[$Field] = (is_int($this->request->getPost($Field, 'int')) ?$this->request->getPost($Field, 'int'): 0  );
                                $tEntityArray[$Field] = $this->request->getPost($Field, 'int');
                                break;
                            case 'string':
                                $tEntityArray[$Field] = $this->request->getPost($Field, 'striptags');
                                break;
                             case 'html':
                                $tEntityArray[$Field] = trim(htmlentities($this->request->getPost($Field)));
                                break;
                            case 'replace_comma_dollars':
                                $tEntityArray[$Field] = $this->request->str_replace(["$", ","], ["", ""], $this->request->getPost($Field));
                                break;
                            default:
                                $tEntityArray[$Field] = $this->request->getPost($Field, 'striptags');
                                break;
                        }
                    }
                   // exit;

                    //dd($tEntityArray);

                    $entity->assign($tEntityArray);
                   // dd($entity);
                    // Don't hate me for this -- GG
                    $PrimaryID = $type == 'agency' ? 'agency_id' : 'location_id';
                    $Prefix = $type == 'agency' ? 'a' : 'l';
                    //$file_location = $this->uploadAction($Prefix . $entity->$PrimaryID);
                    // This works because agencies and locations have the same column.
                    //$entity->save();
                    if ($file_location != '')
                        //echo 'ok';exit;
                        $entity->sms_message_logo_path = $file_location;
                    $saveentity=$entity->save();
                    
                    return $saveentity;
                }
            }
            return true;
        }


        /**
         * Updates settings for locations
         */
        public function locationAction() {
            
            $location_id = $this->getLocationId();
            $userObj = $this->getUserObject();
            // If there is no identity available the user is redirected to index/index.  User must be a super admin or admin to view settings page.
            if (!$userObj) {
                $this->response->redirect('/session/login?return=/settings/location/');
                $this->view->disable();
                return;
            }

            if($userObj->role != \Vokuro\Models\Users::ROLE_SUPER_ADMIN && $userObj->role != \Vokuro\Models\Users::ROLE_ADMIN) {
                 $this->flash->error("You do not have permission to this page.");
                 $this->view->disable();
            }
            //echo $userObj->agency_id;exit;
            $conditions = "agency_id = :agency_id:";
            $parameters = array("agency_id" => $userObj->agency_id);
            $agency = Agency::findFirst(array($conditions, "bind" => $parameters));
            //echo $agency->custom_sms;exit;
            $this->view->custom_sms=$agency->custom_sms;
            $subscription_plan = $subscription = \Vokuro\Models\BusinessSubscriptionPlan::findFirst("user_id = " .$userObj->agency_id);
            $this->view->planSubscribe=$subscription_plan->payment_plan;
            $this->view->subscription_id=$agency->subscription_id;

             $subscriptionManager = $this->di->get('subscriptionManager');
         /*** trial account checking **/
         $objSuperUser = \Vokuro\Models\Users::findFirst('agency_id = ' . $userObj->agency_id . ' AND role="Super Admin"');

         //echo $objSuperUser->id;exit;
            $subscriptionPlanData = $subscriptionManager->getSubscriptionPlan($objSuperUser->id, $agency->subscription_id);
                //echo $agency->subscription_id;exit;
            if($agency->subscription_id):
             $objSubscriptionPricingPlan = \Vokuro\Models\SubscriptionPricingPlan::findFirst("id = {$agency->subscription_id}");
                endif;

              $subscriptionPlanData['pricingPlan']['pricing_details'] = '';
        $this->view->subscriptionPlanData = $subscriptionPlanData;
        switch($this->view->subscriptionPlanData['subscriptionPlan']['payment_plan']) {
            // GARY_TODO:  Pretty sure this doesn't work the way it was supposed to due to handoff from Michael.
            case ServicesConsts::$PAYMENT_PLAN_TRIAL :
                $this->view->paymentPlan = "TRIAL";
                $this->view->DisplaySubPopup = true;
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

                $subscriptionPlanData['pricingPlan']['enable_trial_account'] ? "TRIAL" : "UNPAID";//exit;
               
        }


            if (!$agency) {
                $this->flash->error("No settings were found");
            }
            if($location_id) {
                //find the location
                $conditions = "location_id = :location_id:";
                $parameters = array("location_id" => $this->session->get('auth-identity')['location_id']);
                $location = Location::findFirst(array($conditions, "bind" => $parameters));
                $location->message_frequency = ($location->message_frequency == "" ? 0: $location->message_frequency );
            }
            if(!$location)
                $location = new Location();

                // Save the sort order of the review sites

                if (!empty($_POST['review_order'])) {
                	
                	$order = 0;
                	$pieces = explode(",", $_POST['review_order']);
                	foreach ($pieces as $siteid) {
                		$order++;
                		$conditions = "location_review_site_id = :location_review_site_id:";
                		$parameters = array("location_review_site_id" => $siteid);
                		$Obj = LocationReviewSite::findFirst(array($conditions, "bind" => $parameters));
                		$Obj->sort_order = $order;
                		$Obj->save();
                	}
                }
                
            if (!$this->storeSettings($location, 'location')) {
                $this->flash->error($location->getMessages());
            } elseif($this->request->isPost()) {
                $this->flash->success("The settings were updated successfully");
                Tag::resetInput();
            }
            if($location_id) {
                $notificationdelete = LocationNotifications::find("location_id = " . $location_id);
                //$notificationdelete->delete();
            }
            if (!empty($_POST['users'])) {
                foreach ($_POST['users'] as $check) {
                    $agencyInsert = new LocationNotifications();
                    $agencyInsert->location_id = $this->session->get('auth-identity')['location_id'];
                    $agencyInsert->user_id = $check;
                    $agencyInsert->save();
                }
            }



            $this->view->users = \Vokuro\Models\Users::find("agency_id = {$userObj->agency_id}");
            $this->view->agency = $agency;
            $this->view->location = $location;

            if($location) {
                $conditions = "location_id = :location_id:";
                $parameters = array("location_id" => $this->session->get('auth-identity')['location_id']);

                $agencynotifications = $this->view->agencynotifications = LocationNotifications::find(array($conditions, "bind" => $parameters));

                //find the location review sites
                $conditions = "location_id = :location_id:";
            
                $parameters = array("location_id" => $this->session->get('auth-identity')['location_id']);
                $this->view->review_site_list = LocationReviewSite::find(array($conditions, "bind" => $parameters, "order" => "sort_order ASC"));
            }

            $this->view->review_sites = ReviewSite::find();

            $this->view->form = new SettingsForm($location, array(
                'edit' => true
            ));
            $this->view->agencyform = new AgencyForm($location, array(
                'edit' => true
            ));

            $this->getSMSReport();
            
            
            $identity = $this->auth->getIdentity();

            $idxcx=$identity['id'];
            $db = $this->di->get('db');
            $db->begin();
            $result=$this->db->query(" SELECT * FROM `twilio_number_to_business` WHERE `buisness_id`='".$idxcx."'");
            $xcd=$result->numRows();
            if($xcd!=0){
                $this->view->twilio_details=$result->fetchAll();
                
            }else{
                $this->view->twilio_details=0;
                $Twillioset=$this->getTwilioDetails();
                //dd($Twillioset);
                $twilio_api_key=$Twillioset['twilio_api_key'];
                if(($Twillioset['twilio_api_key']==""|| $twilio_api_key==NULL)||($Twillioset['twilio_auth_token']==""|| $Twillioset['twilio_auth_token']==NULL)){

                }
                $client = new Services_Twilio($Twillioset['twilio_api_key'], $Twillioset['twilio_auth_token']);
            $uri = '/'. $client->getVersion() . '/Accounts/' . $twilio_api_key . '/AvailablePhoneNumbers.json';
            $numbers = $client->retrieveData($uri);
            
            $country=array();
            foreach ($numbers as $key => $value) {
                foreach ($value as $key => $nox) {
                
                $country[$nox->country_code]=$nox->country;
                
                }
                
            }
            asort($country);
            $this->view->countries=$country;
            //dd($country);
            }

            $this->view->pick("settings/index");
        }

      public function sendSampleEmailAction($EmployeeID) {
            $this->view->disable();

            $Identity = $this->session->get('auth-identity');
            //echo $Identity['id'];exit;
            //echo $this->session->get('auth-identity')['location_id'];exit;
            $objCurrentUser = \Vokuro\Models\Users::findFirst("id = " . $Identity['id']);
            $objRecipient = \Vokuro\Models\Users::findFirst("id = {$EmployeeID}");

            $objlocationinfo = \Vokuro\Models\UsersLocation::findFirst("user_id = {$EmployeeID}");
                if($objlocationinfo)
                {
                     $objReview= \Vokuro\Models\Location::findFirst("location_id = {$objlocationinfo->location_id}");
                     $review_invite_type_id=$objReview->review_invite_type_id;
                }
                else
                {
                    
                    $objReview= \Vokuro\Models\Location::findFirst("location_id = {$this->session->get('auth-identity')['location_id']}");
                     $review_invite_type_id=$objReview->review_invite_type_id;
                }

           
            $objBusiness =  \Vokuro\Models\Agency::findFirst("agency_id = {$objCurrentUser->agency_id}");
            /*echo $objReview->review_invite_type_id;
            exit;*/

            $Start = date("Y-m-01", strtotime('now'));
            $End = date("Y-m-t", strtotime('now'));
           // $dbEmployees = \Vokuro\Models\Users::getEmployeeListReport($objBusiness->agency_id, $Start, $End, $Identity['location_id'], $objReview->review_invite_type_id, 0, 1);

            

            $dbEmployees = \Vokuro\Models\Users::getEmployeeListReportGenerate($objBusiness->agency_id, $Start, $End, $Identity['location_id'], $objReview->review_invite_type_id, 0, 1);
            //echo '<pre>';print_r($dbEmployees);exit;
            $objLocation = \Vokuro\Models\Location::findFirst('location_id = ' . $Identity['location_id']);
            $this->view->review_invite_type_id=$objReview->review_invite_type_id;

                /*** new start generator ***/

        $rating_array_set_all=array();
        $YNrating_array_set_all=array();
       // echo 'kk';exit;
        
        foreach($dbEmployees as $ux){
            $sql = "SELECT COUNT(*) AS  `numberx`,`review_invite_type_id`,`rating` FROM `review_invite` WHERE  `sent_by_user_id` =".$ux->id." AND `review_invite_type_id` =1 GROUP BY  `rating`";

        // Base model
        $list = new ReviewInvite();

        // Execute the query
        $params = null;
        $rs = new Resultset(null, $list, $list->getReadConnection()->query($sql, $params));
        $YNrating_array_set_all[$ux->id] = $rs->toArray();
        }
       // print_r($YNrating_array_set_all);exit;
        $this->view->YNrating_array_set_all=$YNrating_array_set_all;
        foreach($dbEmployees as $ux){
            $sql = "SELECT COUNT(*) AS `numberx` ,`review_invite_type_id` , SUM(  `rating` ) AS  `totalx` FROM  `review_invite` WHERE  `sent_by_user_id` =".$ux->id." GROUP BY  `review_invite_type_id` ";

        // Base model
        $list = new ReviewInvite();

        // Execute the query
        $params = null;
        $rs = new Resultset(null, $list, $list->getReadConnection()->query($sql, $params));
        $rating_array_set_all[$ux->id] = $rs->toArray();
        }
        $this->view->rating_array_set_all=$rating_array_set_all;
                /*** new start generator ***/


            $objEmail = new \Vokuro\Services\Email();
            return $objEmail->sendEmployeeReport($dbEmployees, $objLocation, [$objRecipient]) ? 1 : 0;
            
        }


        public function agencyAction() {
          if($_POST){
              //echo '<pre>'; print_r($_POST);
          }
            $Identity = $this->auth->getIdentity();
            if (!is_array($Identity)) {
                $this->response->redirect('/session/login?return=/settings/agency/');
                $this->view->disable();
                return;
            }

            $this->view->tab = $this->request->get('tab');
            if($this->request->get('tab') == 'Stripe') {
                $this->view->ShowAgencyStripePopup = false;
            }
                $conditions = "location_id = :location_id:";
            $parameters = array("location_id" => $this->session->get('auth-identity')['location_id']);
            $location = Location::findFirst(array($conditions, "bind" => $parameters));
       


            $objUser = Users::findFirst("id = " . $Identity['id']);

            $objAgency = Agency::findFirst("agency_id = {$objUser->agency_id}");
           
            if (!$objAgency)
                $this->flash->error("Agency not found.  Contact customer support.");

            $SettingsForm  = new SettingsForm($objAgency, array(
                'edit' => true
            ));
            
            
            $AgencyForm = new AgencyForm($objAgency, array(
                'edit' => true
            ));
            
            $blankemailPosted = false;
            
            // Set default message welcome_email
            if(!$objAgency->welcome_email || ($this->request->isPost() && $this->htmlTrim($this->request->getPost('welcome_email','striptags')) == '')) {
              $blankemailPosted = true;
              $AgencyForm->welcome_email =  $_POST['welcome_email'] = "Hey {firstName},<br /> <P>Congratulations on joining us at {AgencyName}, I know you'll love it when you see how easy it is to generate 5-Star reviews from recent customers.</P>

                    <P>If you wouldn't mind, I'd love it if you answered one quick question: Why did you decide to join us at {AgencyName} ?</P>

                    <P>I’m asking because knowing what made you sign up is really helpful for us in making sure that we’re delivering on what our users want. Just hit reply and let me know.
                   </P>  To get started just confirm your email by {link} Clicking Here</a><br/><br/>Thanks,<br/><br/>

                    {AgencyUser}<br/>
                    {AgencyName}";
            }
            
            // Set default message for welcome_email_employee
            if(!$objAgency->welcome_email_employee || ($this->request->isPost() && $this->htmlTrim($this->request->getPost('welcome_email_employee','striptags')) == '')) {
              $blankemailPosted = true;
              $AgencyForm->welcome_email_employee = $_POST['welcome_email_employee'] = 'Hi {employeeName},
            	<p>
            		We’ve just created your profile for {BusinessName} within our software. 
            	</p>
                <p style="font-size: 13px;line-height:24px;font-family:HelveticaNeue,Helvetica Neue,Helvetica,Arial,sans-serif">

                	When you {clickHereAndActivateYourProfileNowLink} you’ll gain instant access and the ability to generate customer feedback via text messages through your own personalized dashboard. 
                    <p>
                  {link}
                  </p>
                   <p>Looking forward to working with you.</p>

                    {AgencyUser}<br/>
                    {AgencyName}
                    <br>
                </p>';
            }
            
            // Set default message for viral_email
            if(!$objAgency->viral_email || ($this->request->isPost() && $this->htmlTrim($this->request->getPost('viral_email','striptags')) == '')) {
              $blankemailPosted = true;
              $AgencyForm->viral_email =  $_POST['viral_email'] = "I just started using this amazing new software for my business.  They are giving away a trial account here: {share_link}";
            }
            
            if($this->request->isPost() && $SettingsForm->isValid($_POST) && $AgencyForm->isValid($_POST)) {
                if (!$this->storeSettings($objAgency, 'agency')) {
                    $this->flash->error($objAgency->getMessages());
                } else {
                    // Only agencies can store logos
                    if($objAgency->parent_id == \Vokuro\Models\Agency::AGENCY)
                        $this->storeLogo($objAgency);

                    // Somehow the agency is getting updated at this point.  It looks like isValid is saving the object?  It shouldn't...  I have no idea what's going on, so doing this manually.
                    $objAgency->twilio_api_key = trim($objAgency->twilio_api_key);
                    $objAgency->twilio_auth_token = trim($objAgency->twilio_auth_token);
                    $objAgency->twilio_auth_messaging_sid = trim($objAgency->twilio_auth_messaging_sid);
                    $objAgency->twilio_from_phone = trim($objAgency->twilio_from_phone);
                    $objAgency->save();

                    $this->flash->success("The settings were updated successfully");
                    Tag::resetInput();
                }
            } else {
                foreach ($AgencyForm->getMessages() as $message) {
                    $this->flash->error($message);
                }
                foreach ($SettingsForm->getMessages() as $message) {
                    $this->flash->error($message);
                }
            }
           
            $this->view->form = $SettingsForm;
            $this->view->agencyform = $AgencyForm;
            $this->view->objgetuser=$objUser ;
            $this->view->objAgency = $objAgency;
            $this->view->id=$Identity['id'];
            $this->view->location_id=$location->location_id;
        }
        
        // use only for null checking trim unicode spaces        
        private function htmlTrim($content) {
          return str_replace('+','',preg_replace('/%26|nbsp%3B|nbsp| /u','',urlencode($content)));
        }

        public function siteaddAction($location_id = 0, $review_site_id = 0) {
           // $this->checkIntegerOrThrowException($location_id ,"not integer");
           // $this->checkIntegerOrThrowException($review_site_id, "not integer");

            if ($location_id > 0 && $review_site_id > 0) {
                $lrs = new LocationReviewSite();
                $lrs->location_id = $location_id;
                $lrs->review_site_id = $review_site_id;
                $lrs->url = $_GET['url'];
                $lrs->date_created = date('Y-m-d H:i:s');
                $lrs->is_on = 1;
                $lrs->save();

                $conditions = "review_site_id = :review_site_id:";
                $parameters = array("review_site_id" => $review_site_id);
                $site = ReviewSite::findFirst(array($conditions, "bind" => $parameters));

                $this->view->disable();
                echo json_encode(array('location_review_site_id' => $lrs->location_review_site_id,
                    'img_path' => $site->icon_path,
                    'name' => $site->name));
            } else {
                $this->view->disable();
                echo 'false';
            }
        }


        public function onAction($id = 0) {
            //$this->checkIntegerOrThrowException($id,'$id was invalid');
            $conditions = "location_review_site_id = :location_review_site_id:";
            $parameters = array("location_review_site_id" => $id);
            $Obj = LocationReviewSite::findFirst(array($conditions, "bind" => $parameters));

            $location_id = $Obj->location_id;
            $edit_permissions = $this->getPermissions()->canUserEditLocationId($this->getUserObject(),$location_id);
            if(!$edit_permissions) throw new \Exception("You cannot edit the parent location for id of:{$location_id}, so you cannot
            turn off or on a location review site belonging to this location");


            $Obj->is_on = 0;
            $Obj->save();

            $this->view->disable();
            echo 'true';
        }

        public function offAction($id = 0) {
            $conditions = "location_review_site_id = :location_review_site_id:";
            $parameters = array("location_review_site_id" => $id);
            $Obj = LocationReviewSite::findFirst(array($conditions, "bind" => $parameters));

            $location_id = $Obj->location_id;
            $edit_permissions = $this->getPermissions()->canUserEditLocationId($this->getUserObject(), $location_id);
            if (!$edit_permissions) throw new \Exception("You cannot edit the parent location for id of:{$location_id}, so you cannot
            turn off or on a location review site belonging to this location");



            $Obj->is_on = 1;
            $Obj->save();
            $this->view->disable();
            echo 'true';
        }


        public function notificationAction($id = 0, $fieldname, $value) {

            $conditions = "location_id = :location_id: AND user_id = :user_id:";
            $parameters = array("location_id" => $this->session->get('auth-identity')['location_id'], "user_id" => $id);

            $Obj = LocationNotifications::findFirst(array($conditions, "bind" => $parameters));
            if (isset($Obj) && isset($Obj->user_id) && $Obj->user_id == $id) {
                //lets edit the field and save the changes
                if ($fieldname == 'ea') $Obj->email_alert = $value;
                if ($fieldname == 'sa') $Obj->sms_alert = $value;
                if ($fieldname == 'ar') $Obj->all_reviews = $value;
                if ($fieldname == 'ir') $Obj->individual_reviews = $value;
                if ($fieldname == 'el') $Obj->employee_leaderboards = $value;

                $Obj->save();
            } else {
                //else we need to create a record
                $locationNotification = new LocationNotifications();

                $loc_array = array(
                    'location_id' => $parameters['location_id'],
                    'user_id' => $id,
                );
                if ($fieldname == 'ea') { $loc_array['email_alert'] = $value; }
                if ($fieldname == 'sa') { $loc_array['sms_alert'] = $value; }
                if ($fieldname == 'ar') { $loc_array['all_reviews'] = $value; }
                if ($fieldname == 'ir') { $loc_array['individual_reviews'] = $value; }
                if ($fieldname == 'el') { $loc_array['employee_leaderboards'] = $value; }
                //print_r($loc_array);
                $locationNotification->assign($loc_array);
                $locationNotification->save();
            }

            $this->view->disable();
            echo 'true';
        }


        public function uploadAction($agencyid) {
            // Check if the user has uploaded files
            if ($this->request->hasFiles() == true) {
                //echo '<p>hasFiles() == true!</p>';
                $baseLocation = __DIR__ . '/../../public/img/agency_logos/';

                // Print the real file names and sizes
                foreach ($this->request->getUploadedFiles() as $file) {
                    if ($file->getName() != '') {
                        //Move the file into the application
                        $filepath = $baseLocation . uniqid('logo');
                        $file->moveTo($filepath);

                        //resize
                        $image = new \Phalcon\Image\Adapter\GD($filepath);
                        $image->resize(200, 30)->save($filepath);

                        $tFilepath = explode('/', $filepath);
                        $filepath = "/img/agency_logos/" . array_pop($tFilepath);

                        $this->view->logo_path = $filepath;
                        return $filepath;
                    }
                }
            }
        }
        public function getTwilioDetails(){
            $twilio_api_key = "";
            $twilio_auth_token = "";
            $twilio_auth_messaging_sid = "";
            $twilio_from_phone = "";
            $identity = $this->auth->getIdentity();
            
            $conditions = "id = :id:";
            $parameters = array("id" => $identity['id']);
            $userObj = Users::findFirst(array($conditions, "bind" => $parameters));
            $conditions = "agency_id = :agency_id:";
            $parameters = array("agency_id" => $userObj->agency_id);
            $agency = Agency::findFirst(array($conditions, "bind" => $parameters));
            if ($agency) {
                $this->view->agency = $agency;
                if (isset($agency->twilio_api_key) && $agency->twilio_api_key != "" && isset($agency->twilio_auth_token) && $agency->twilio_auth_token != ""  && isset($agency->twilio_from_phone) && $agency->twilio_from_phone != "") {
                        $conditionsUser = "agency_id = :agency_id:";
                        $userParam=$parameters = array("agency_id" => $agency->agency_id);
                        $userObjNew = Users::findFirst(array($conditionsUser, "bind" => $userParam));
                        $twilio_user_id=$userObjNew->id;
                        $twilio_api_key = $agency->twilio_api_key;
                        $twilio_auth_token = $agency->twilio_auth_token;
                        $twilio_from_phone = $agency->twilio_from_phone;
                } 
                if ($twilio_api_key  == "" && $twilio_auth_token == ""  && $twilio_from_phone == "") {
                    $parameters1 = array("agency_id" => $agency->parent_id);
                    $agency1 = Agency::findFirst(array($conditions, "bind" => $parameters1));
                    $conditionsUser = "agency_id = :agency_id:";
                    $userParam=$parameters = array("agency_id" => $agency1->agency_id);
                    $userObjNew = Users::findFirst(array($conditionsUser, "bind" => $userParam));
                    $twilio_user_id=$userObjNew->id;
                    $twilio_api_key = $agency1->twilio_api_key;
                    $twilio_auth_token = $agency1->twilio_auth_token;
                    $twilio_from_phone = $agency1->twilio_from_phone;   
                }
            }
            $Twiio=array();
            $Twiio['twilio_user_id']=$twilio_user_id;
            $Twiio['twilio_api_key']=$twilio_api_key;
            $Twiio['twilio_auth_token']=$twilio_auth_token;
            $Twiio['twilio_from_phone']=$twilio_from_phone;
            return($Twiio);
        }
    }
