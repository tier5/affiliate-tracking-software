<?php
    namespace Vokuro\Controllers;

    use Phalcon\Tag;
    use Phalcon\Mvc\Model\Criteria;
    use Phalcon\Paginator\Adapter\Model as Paginator;
    use Vokuro\Forms\LocationForm;
    use Vokuro\Models\Agency;
    use Vokuro\Models\FacebookScanning;
    use Vokuro\Models\GoogleScanning;
    use Vokuro\Models\Location;
    use Vokuro\Models\LocationReviewSite;
    use Vokuro\Models\Region;
    use Vokuro\Models\ReviewInvite;
    use Vokuro\Models\Review;
    use Vokuro\Models\ReviewsMonthly;
    use Vokuro\Models\Users;
    use Vokuro\Models\UsersLocation;
    use Vokuro\Models\UsersSubscription;
    use Vokuro\Models\YelpScanning;
    use Vokuro\Services\Reviews;

    use Services_Twilio;
    use Services_Twilio_RestException;

//Authorize.net Settings
//require '../../vendor/autoload.php';
    require_once __DIR__ . '/../../vendor/autoload.php';
    use net\authorize\api\contract\v1 as AnetAPI;
    use net\authorize\api\controller as AnetController;

//end Authorize.net Settings

    /**
     * Vokuro\Controllers\LocationController
     * CRUD to manage locations
     */
    class LocationController extends ControllerBase {

        public $facebook_access_token;

        public function initialize() {

            $this->tag->setTitle('Get Mobile Reviews | Locations');
            if ($this->session->has('auth-identity')) {
                $this->view->setTemplateBefore('private');
            } else if (strpos($_SERVER['REQUEST_URI'], 'cron') > 0) {
                $this->view->setTemplateBefore('public');
            } else {
                $this->response->redirect('/session/login?return=/location/');
                $this->view->disable();
                return;
            }

            $path_to_admin = realpath(__DIR__ . '/../../');
            include_once $path_to_admin . '/app/library/Google/mybusiness/Mybusiness.php';
            define('APPLICATION_NAME', 'User Query - Google My Business API');
            define('CLIENT_SECRET_PATH', $path_to_admin . '/app/models/client_secrets.json');


            parent::initialize();
        }

        protected function disconnectReviewSite($LocationID, $Type) {
            $MethodName = null;
            switch($Type) {
                case  \Vokuro\Models\Location::TYPE_YELP:
                    $MethodName = "DeleteYelpReviews";
                    break;
                case  \Vokuro\Models\Location::TYPE_FACEBOOK:
                    $MethodName = "DeleteFacebookReviews";
                    break;
                case  \Vokuro\Models\Location::TYPE_GOOGLE:
                    $MethodName = "DeleteGoogleReviews";
                    break;
            }
            $objLocationReviewSite = \Vokuro\Models\LocationReviewSite::findFirst("location_id = {$LocationID} AND review_site_id = {$Type}");

            if($objLocationReviewSite) {
                $objLocationReviewSite->json_access_token = null;
                $objLocationReviewSite->external_location_id = null;
                $objLocationReviewSite->external_id = null;
                $objLocationReviewSite->access_token = null;
                $objLocationReviewSite->api_id = null;
                $objLocationReviewSite->review_count = 0;
                $objLocationReviewSite->original_count = 0;
                $objLocationReviewSite->date_created = null;
                $objLocationReviewSite->is_on = 0;
                //$objLocationReviewSite->lrd = null;
                $objLocationReviewSite->name = null;
                $objLocationReviewSite->url = null;
                $objLocationReviewSite->rating = 0;
                $objLocationReviewSite->address = '';
                $objLocationReviewSite->postal_code = '';
                $objLocationReviewSite->country = '';
                $objLocationReviewSite->state_province = '';
                $objLocationReviewSite->phone = '';
                $objLocationReviewSite->save();
            }

            if($MethodName) {
                $objReviewService = new \Vokuro\Services\Reviews();
                $objReviewService->$MethodName($LocationID);
            }
        }

        public function disconnectYelpAction($LocationID, $RedirectToSession) {
            $this->disconnectReviewSite($LocationID, \Vokuro\Models\Location::TYPE_YELP);
            $this->response->redirect("/location/edit/{$LocationID}/0/{$RedirectToSession}");
        }

        public function disconnectFacebookAction($LocationID, $RedirectToSession) {
            $this->disconnectReviewSite($LocationID, \Vokuro\Models\Location::TYPE_FACEBOOK);
            $this->response->redirect("/location/edit/{$LocationID}/0/{$RedirectToSession}");
        }

        public function disconnectGoogleAction($LocationID, $RedirectToSession) {
            $this->disconnectReviewSite($LocationID, \Vokuro\Models\Location::TYPE_GOOGLE);
            $this->response->redirect("/location/edit/{$LocationID}/0/{$RedirectToSession}");
        }

        public function pickYelpBusinessAction($BusinessID, $LocationID, $RedirectToSession = 0) {
        	
        	$objReviewsService = new \Vokuro\Services\Reviews();
            $objYelpBusiness = $objReviewsService->getYelpBusinessData($LocationID, $BusinessID);
           
            $objLocation = \Vokuro\Models\LocationReviewSite::findFirst("location_id = {$LocationID} AND review_site_id = " . \Vokuro\Models\Location::TYPE_YELP);
            if(!$objLocation) {
                $objLocation = new \Vokuro\Models\LocationReviewSite();
                $objLocation->review_site_id = \Vokuro\Models\Location::TYPE_YELP;
                $objLocation->location_id = $LocationID;
            }
            $tFields = ['name', 'external_id','external_location_id', 'url', 'address', 'postal_code', 'locality', 'country', 'state_province', 'phone'];
            foreach($tFields as $Field) 
                $objLocation->$Field = $objYelpBusiness->$Field;
            $objLocation->is_on = 1;
            $objLocation->save();

            $objReviewsService->DeleteYelpReviews($LocationID);
            $objReviewsService->importYelpReviews($LocationID);
 
            $this->response->redirect("/location/edit/{$LocationID}/0/{$RedirectToSession}"); 
        }

        public function pickGoogleBusinessAction($BusinessID, $LocationID, $RedirectToSession = 0) {
            $objReviewsService = new \Vokuro\Services\Reviews();
            $objGoogleBusiness = $objReviewsService->getGoogleMyBusinessData($LocationID, $BusinessID);

            $objLocation = \Vokuro\Models\LocationReviewSite::findFirst("location_id = {$LocationID} AND review_site_id = " . \Vokuro\Models\Location::TYPE_GOOGLE);
            $objLocation->is_on = 1;
     
            $objLocation->name = $objGoogleBusiness->name;
            $objLocation->external_location_id = $objGoogleBusiness->external_location_id;
            $objLocation->external_id = $objGoogleBusiness->id;
            $cid = explode("=", $objGoogleBusiness->url);
            $objLocation->cid = $cid[1];
            $address = preg_replace(" #[0-9]+","",$objGoogleBusiness->address);
            $objLocation->name .= " " .
              					  $address . " " .  
            					  $objGoogleBusiness->postal_code . " " . 
            					  $objGoogleBusiness->locality . " " .
            					  $objGoogleBusiness->state_province . " " .
            					  $objGoogleBusiness->country;
//print_r($objGoogleBusiness);
            $objLocation->url = "https://www.google.com/search?q=".str_replace(" ", "+", $objLocation->name)."&ludocid=".$objLocation->cid."#lrd=".$objLocation->lrd.",3,5";
            $objLocation->save();
			
            $objReviewsService->DeleteGoogleReviews($LocationID);
            $objReviewsService->importGoogleMyBusinessReviews($LocationID);

            $this->response->redirect("/location/edit/{$LocationID}/0/{$RedirectToSession}");
        }

        public function pickFacebookBusinessAction($BusinessID, $LocationID, $RedirectToSession = 0) {
            $objLocation = \Vokuro\Models\LocationReviewSite::findFirst("location_id = {$LocationID} AND review_site_id = " . \Vokuro\Models\Location::TYPE_FACEBOOK);

            $face = new FacebookScanning();
            $tResults = [];
            $face->setAccessToken($objLocation->access_token);

            $tobjBusinesses = $face->getBusinessAccounts();
            $Picked = false;

            foreach($tobjBusinesses as $objBusiness) {
                if($objBusiness->id == $BusinessID) {
                	
                    $Picked = true;
                    $objLocation->access_token = $objBusiness->access_token;
                    $objLocation->name = $objBusiness->name;
                    $objLocation->external_location_id = $objBusiness->id;
                    $objLocation->external_id = $objBusiness->id;
                    $objLocation->url = "http://www.facebook.com/" . $objLocation->external_id;
                    $objLocation->is_on = 1;
                    if($objLocation->save()) {
                        $this->flash->success("Your business has been successfully synced with our system.");
                        $objReviewService = new \Vokuro\Services\Reviews();
                        $objReviewService->DeleteFacebookReviews($LocationID);
                        $objReviewService->importFacebook($LocationID);
                    }
                    break;
                }
            }

            if(!$Picked)
                $this->flash->error("Your business could not be found in the subsequent facebook search.  Please contact customer support.");


            $this->response->redirect("/location/edit/{$LocationID}/0/{$RedirectToSession}");
        }


        public function getYelpPagesAction($LocationID, $RedirectToSession = 0) {
            if($RedirectToSession) {
                $this->view->setTemplateBefore('signup');
                $this->tag->setTitle('Get Mobile Reviews | Sign up | Step 2 | Add Location');
                $this->view->current_step = 2;
            }
            $objReviewsService = new \Vokuro\Services\Reviews();

            $this->view->RedirectToSession = $RedirectToSession;
            $this->view->tobjBusinesses = $objReviewsService->getYelpBusinessLocations($LocationID, $this->request->getPost('YelpBusinessName', 'striptags'), $this->request->getPost('YelpPostalCode', 'striptags'));
            $this->view->LocationID = $LocationID;
            $this->view->pick('location/getFacebookPages');
        }

        public function getGooglePagesAction($LocationID, $RedirectToSession = 0) {
            if($RedirectToSession) {
                $this->view->setTemplateBefore('signup');
                $this->tag->setTitle('Get Mobile Reviews | Sign up | Step 2 | Add Location');
                $this->view->current_step = 2;
            }
            $objReviewsService = new \Vokuro\Services\Reviews();

            $this->view->RedirectToSession = $RedirectToSession;

            $this->view->tobjBusinesses = $objReviewsService->getGoogleMyBusinessLocations($LocationID);

            $this->view->LocationID = $LocationID;
            $this->view->pick('location/getFacebookPages');
        }

        public function getFacebookPagesAction($LocationID, $RedirectToSession = 0) {
            if($RedirectToSession) {
                $this->view->setTemplateBefore('signup');
                $this->tag->setTitle('Get Mobile Reviews | Sign up | Step 2 | Add Location');
                $this->view->current_step = 2;
            }
            $objLocation = \Vokuro\Models\LocationReviewSite::findFirst("location_id = {$LocationID} AND review_site_id = " . \Vokuro\Models\Location::TYPE_FACEBOOK);

            $face = new FacebookScanning();
            $tResults = [];
            $face->setAccessToken($objLocation->access_token);

            $tobjBusinesses = $face->getBusinessAccounts();
            foreach($tobjBusinesses as &$objBusiness)
                $objBusiness->type = 'Facebook';

            $this->view->tobjBusinesses = $tobjBusinesses;
            $this->view->LocationID = $LocationID;
            $this->view->RedirectToSession = $RedirectToSession;
        }


        /**
         * Searches for yelp locations
         */
        public function yelpAction() {
            //yelp web service api call
            $term = $_GET['t'];
            $location = $_GET['l'];

            $yelp = new YelpScanning();
            $yelp->construct();
            $results = $yelp->search($term, $location);

            $this->view->disable();
            echo json_decode($results);
        }


        /**
         * Searches for yelp locations
         */
        public function yelpurlAction() {
            //yelp web service api call
            $id = $_GET['i'];
//echo $id;
            $yelp = new YelpScanning();
            $yelp->construct();
            $results = $yelp->get_business($id);

            $this->view->disable();
            echo $results;
        }


        static public function noformat($input) {
            return round($input, 1);
        }


        /**
         * Default index view
         */
        public function indexAction($DisplayLocationsPopup = 0) {
            $this->view->DisplayLocationsPopup = $DisplayLocationsPopup;

            //get the user id
            $identity = $this->auth->getIdentity();
            // If there is no identity available the user is redirected to index/index
            if (!is_array($identity)) {
                $this->response->redirect('/session/login?return=/location/');
                $this->view->disable();
                return;
            }
            // Query binding parameters with string placeholders
            $conditions = "id = :id:";
            $parameters = array("id" => $identity['id']);
            $userObj = Users::findFirst(array($conditions, "bind" => $parameters));
            //echo '<pre>$userObj:'.print_r($userObj->agency_id,true).'</pre>';

            $locs = Location::getLocations($userObj->agency_id);
            $this->view->locs = $locs;
            $this->getSMSReport();
        }

        /**
         * @param $objReviewLocation LocationReviewSite Object
         */
        protected function deleteYelpReviews($LocationID) {
            $dbArray = Review::find("rating_type_id = 1 AND location_id = {$LocationID}");
            foreach ($dbArray as $dbRow)
                $dbRow->delete();
        }

        /**
         * Ajax request that updates yelp location and review information
         */
        public function updateLocationAction() {
            $yelp_api_id = $this->request->get('yelp_id', 'striptags');
            $location_id = $this->request->get('location_id', 'striptags');
            if (!$yelp_api_id || !$location_id)
                die("ERROR:  Missing location_id and/or yelp id");

            $user_id = $this->session->get('auth-identity')['id'];
            $objUser = Users::findFirst("id = {$user_id}");

            // Validate user is editing correct location
            $objLocation = Location::findFirst("location_id = {$location_id}");
            if ($objLocation->agency_id != $objUser->agency_id)
                die("ERROR:  Invalid user ID");

            $objLocationReviewSite = LocationReviewSite::findFirst("location_id = {$location_id} AND review_site_id = " . \Vokuro\Models\Location::TYPE_YELP);
            if (!$objLocationReviewSite) {
                $objLocationReviewSite = new LocationReviewSite();
                $objLocationReviewSite->review_site_id = \Vokuro\Models\Location::TYPE_YELP;
            }

            $objLocationReviewSite->external_id = $this->yelpId($yelp_api_id);
            $objLocationReviewSite->api_id = $yelp_api_id;
            $objLocationReviewSite->date_created = date("Y-m-d H:i:s");
            $objLocationReviewSite->save();

            $tFoundAgency = [];
            $this->deleteYelpReviews($location_id);
            $this->importYelp($objLocationReviewSite, $objLocation, $tFoundAgency);

            die('SUCCESS');
        }

        /**
         * Creates a Location
         */
        public function createAction($DisplayLocationsPopup = 0) {
            $this->assets
                ->addCss('css/main.css')
                ->addCss('css/signup.css');

            $identity = $this->auth->getIdentity();

            // If there is no identity available the user is redirected to index/index
            if (!is_array($identity)) {
                $this->response->redirect('/session/login?return=/location/create');
                $this->view->disable();
                return;
            }

            // Query binding parameters with string placeholders
            $conditions = "id = :id:";
            $parameters = array(
                "id" => $identity['id']
            );

            $userObj = Users::findFirst(
                array(
                    $conditions,
                    "bind" => $parameters
                )
            );


            $conditions = "agency_id = :agency_id:";

            $parameters = array(
                "agency_id" => $userObj->agency_id
            );

            $agency = Agency::findFirst(
                array(
                    $conditions, "bind" => $parameters
                )
            );


            $objSubscriptionManager = new \Vokuro\Services\SubscriptionManager();
            $tLimit = $objSubscriptionManager->ReachedMaxLocations($userObj->agency_id);
            if($tLimit['ReachedLimit']) {
                return $tLimit['FreePlan'] ? $this->response->redirect('/location/index/2') : $this->response->redirect('/location/index/1');
            }

            $dbLocations = \Vokuro\Models\Location::find('agency_id = ' . $userObj->agency_id);


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


                    // Check for Yelp
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

                        $this->importYelp($lrs, $loc, $foundagency);
                    }

                    $agency->assign(array(
                        'signup_page' => 0, //go to the next page
                    ));
                    if (!$agency->save()) {
                        //$this->flash->error($agency->getMessages());
                    }

                    $this->auth->setLocation($loc->location_id);


                    $this->auth->setLocationList();
                    if (!(isset($this->session->get('auth-identity')['location_id']) && $this->session->get('auth-identity')['location_id'] > 0)) {
                        $this->auth->setLocation($loc->location_id) / me;
                    }
                    $this->flash->success("The location was created successfully");
                    $this->updateAgencySubscriptionPlan($loc->location_id, true);


                    return $this->response->redirect('/location/edit/' . $loc->location_id . '/1');
                    //return $this->response->redirect('/location/create2/' . ($loc->location_id > 0 ? $loc->location_id : ''));
                }
            }


            $this->view->facebook_access_token = $this->facebook_access_token;
            $this->view->form = new LocationForm(null);
            $this->view->SignupProcess = false;
            $this->view->pick("session/signup2");

        }

        /**
         * @param $LocationID
         * @param $Creating - Are we creating or deleting a location?  (Boolean)
         * @return bool
         */
        protected function updateAgencySubscriptionPlan($LocationID, $Creating) {

            if(!$LocationID)
                return false;

            $objSubscriptionManager = new \Vokuro\Services\SubscriptionManager();
            $objLocation = \Vokuro\Models\Location::findFirst("location_id = {$LocationID}");

            // First check this business subscription level.  If trial, we can ignore.  Trial accounts can only have 1 location and they are not paid.
            $SubscriptionLevel = $objSubscriptionManager->GetBusinessSubscriptionLevel($objLocation->agency_id);

            if($SubscriptionLevel == \Vokuro\Services\ServicesConsts::$PAYMENT_PLAN_TRIAL)
                return true;

            // Get a list of all businesses under the Agency and count total locations
            $objBusiness = \Vokuro\Models\Agency::findFirst("agency_id = {$objLocation->agency_id}");
            $objAgency = \Vokuro\Models\Agency::findFirst("agency_id = {$objBusiness->parent_id}");

            if($objBusiness->parent_id == \Vokuro\Models\Agency::BUSINESS_UNDER_RV)
                return true;

            $dbBusinesses = \Vokuro\Models\Agency::find("parent_id = {$objAgency->agency_id}");
            $LocationCount = 0;
            foreach($dbBusinesses as $objBusiness) {
                if($objSubscriptionManager->GetBusinessSubscriptionLevel($objLocation->agency_id) != \Vokuro\Services\ServicesConsts::$PAYMENT_PLAN_TRIAL) {
                    $LocationCount += \Vokuro\Models\Location::count("agency_id = {$objBusiness->agency_id}");
                }
            }

            // Determine if we need to expand / shrink agency subscription plan
            $objAgencySuperUser = \Vokuro\Models\Users::findFirst("agency_id = {$objAgency->agency_id} and role='Super Admin'");
            $objAgencySubscription = \Vokuro\Models\AgencySubscriptionPlan::findFirst("agency_id = {$objAgency->id}");
            $objAgencyPricingPlan = \Vokuro\Models\AgencyPricingPlan::findFirst("id = {$objAgencySubscription->pricing_plan_id}");


            $objPaymentService = new \Vokuro\Services\PaymentService();
            if(($Creating && $LocationCount > $objAgencyPricingPlan->number_of_businesses) || (!$Creating && $LocationCount > $objAgencyPricingPlan->number_of_businesses)) {
                $NewPayment = $Creating ? ($LocationCount * $objAgencyPricingPlan->price_per_business * 100) : (($LocationCount-1) * $objAgencyPricingPlan->price_per_business * 100);
                $ccParameters = [
                    'userId' => $objAgencySuperUser->id,
                    'type' => 'Agency',
                    'amount' => $NewPayment,
                    'provider' => \Vokuro\Services\ServicesConsts::$PAYMENT_PROVIDER_STRIPE,
                ];
                $objPaymentService->changeSubscription($ccParameters);
            }

            return true;
        }


        /**
         * Creates a Location, step 2
         */
        public function create2Action($location_id) {
            //add needed css
            $this->assets
                ->addCss('css/main.css')
                ->addCss('css/signup.css');

            //get the user id, to find the settings
            $identity = $this->auth->getIdentity();
            //echo '<pre>$identity:'.print_r($identity,true).'</pre>';
            // If there is no identity available the user is redirected to index/index
            if (!is_array($identity)) {
                $this->view->disable();
                return;
            }
            // Query binding parameters with string placeholders
            $conditions = "id = :id:";
            $parameters = array("id" => $identity['id']);
            $userObj = Users::findFirst(array($conditions, "bind" => $parameters));

            //find the agency
            $conditions = "agency_id = :agency_id:";
            $parameters = array("agency_id" => $userObj->agency_id);
            $agency = Agency::findFirst(array($conditions, "bind" => $parameters));

            //find the location
            $conditions = "location_id = :location_id: AND agency_id = :agency_id:";
            $parameters = array("location_id" => $location_id, "agency_id" => $userObj->agency_id);
            $location = Location::findFirst(array($conditions, "bind" => $parameters));

            if ($this->request->isPost()) {
                $location->assign(array(
                    'name' => $this->request->getPost('agency_name', 'striptags'),
                    'sms_button_color' => $this->request->getPost('sms_button_color', 'striptags'),
                    'sms_top_bar' => $this->request->getPost('sms_top_bar', 'striptags'),
                    'sms_text_message_default' => $this->request->getPost('sms_text_message_default', 'striptags'),
                ));
                $file_location = $this->uploadAction($agency->agency_id);
                if ($file_location != '') $location->sms_message_logo_path = $file_location;
                if (!$location->save()) {
                    $this->flash->error($location->getMessages());
                } else {
                    //we are done, go to the next page
                    return $this->response->redirect('/location/create3/' . ($location_id > 0 ? $location_id : ''));
                }
            }

            $this->view->agency = $agency;
            $this->view->location = $location;
            $this->view->current_step = 3;
            $this->view->id = $agency->agency_id;
            $this->view->location_id = $location_id;
            $this->view->pick("session/signup3");
        }


        /**
         * Creates a Location, step 3
         */
        public function create3Action($location_id) {
            $this->assets
                ->addCss('css/main.css')
                ->addCss('css/signup.css');

            $identity = $this->auth->getIdentity();
            // If there is no identity available the user is redirected to index/index
            if (!is_array($identity)) {
                $this->response->redirect('/session/login?return=/location/');
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

            $conditions = "location_id = :location_id: AND agency_id = :agency_id:";
            $parameters = array("location_id" => $location_id, "agency_id" => $userObj->agency_id);
            $location = Location::findFirst(array($conditions, "bind" => $parameters));

            if ($this->request->isPost()) {
                $location->assign(array(
                    'lifetime_value_customer' => $this->request->getPost('lifetime_value_customer', 'striptags'),
                    'review_goal' => $this->request->getPost('review_goal', 'striptags'),
                ));

                if (!$location->save()) {
                    $this->flash->error($location->getMessages());
                } else {
                    return $this->response->redirect('/location/');
                    $this->view->disable();
                    return;
                }
            }

            // Query binding parameters with string placeholders
            $conditions = "agency_id = :agency_id: AND is_employee = 1";
            $parameters = array("agency_id" => $userObj->agency_id);
            $this->view->employees = Users::find(array($conditions, "bind" => $parameters));

            $this->usersFunctionality(3, $location_id);
            $this->view->location = $location;
            $this->view->current_step = 4;
            $this->view->location_id = $location_id;
            $this->view->pick("session/signup4");
        }


        /**
         * Searches for users
         */
        public function selectemployeesAction($location_id) {
            //$('#reviewgoal').val($('#review_goal').val());
            //$('#lifetimevalue').val($('#lifetime_value_customer').val());
            $reviewgoal = $this->request->getPost('reviewgoal');
            $lifetimevalue = $this->request->getPost('lifetimevalue');
            $querystring = '?review_goal=' . $reviewgoal . '&lifetime_value_customer=' . $lifetimevalue;
            $url = '/location/create3/' . ($location_id > 0 ? $location_id : '') . $querystring;
//echo '<pre>post:'.print_r($_POST,true).'</pre>';

            //get the user id, to find the settings
            $identity = $this->auth->getIdentity();
            // If there is no identity available the user is redirected to index/index
            if (!is_array($identity)) {
                $this->response->redirect('/session/login?return=/location/create3/' . ($location_id > 0 ? $location_id : ''));
                $this->view->disable();
                return;
            }

            // Query binding parameters with string placeholders
            $conditions = "id = :id:";
            $parameters = array("id" => $identity['id']);
            $userObj = Users::findFirst(array($conditions, "bind" => $parameters));
            //echo '<pre>$userObj:'.print_r($userObj->agency_id,true).'</pre>';

            //first remove old links
            // Query binding parameters with string placeholders
            $conditions = "agency_id = :agency_id: AND profilesId = 3";
            $parameters = array("agency_id" => $userObj->agency_id);
            $employees = Users::find(array($conditions, "bind" => $parameters));
            if (isset($employees) && count($employees) > 0) {
                foreach ($employees as $user) {
                    foreach ($user->locations as $location) {
                        if ($location_id == $location->location_id) {
                            //echo '<p>$user->id:'.$user->id.'</p>';
                            $locInsert = new UsersLocation();
                            $locInsert->location_id = $location->location_id;
                            $locInsert->user_id = $user->id;
                            $locInsert->delete();
                        }
                    }
                }
            }

            if (!empty($_POST['employees'])) {
                foreach ($_POST['employees'] as $check) {
                    //echo '<p>$check:'.$check.'</p>';
                    $locInsert = new UsersLocation();
                    $locInsert->location_id = $location_id;
                    $locInsert->user_id = $check;
                    $locInsert->save();
                }
            }

            //echo $url;
            $this->response->redirect($url);
            $this->view->disable();
            return;
        }

        public function fbLoginAction() {

        }


        /**
         * Saves the location from the 'edit' action
         */
        public function editAction($location_id, $include_customize_survey = 0, $ComingFromSignup = 0) {
            if($ComingFromSignup) {
                $this->view->setTemplateBefore('signup');
                $this->tag->setTitle('Get Mobile Reviews | Sign up | Step 2 | Add Location');
                $this->view->current_step = 2;
            }




            $this->view->ComingFromSignup = $ComingFromSignup;
            $this->view->include_customize_survey = $include_customize_survey;

            $this->assets
                ->addCss('css/main.css')
                ->addCss('css/signup.css');


            $conditions = "location_id = :location_id:";

            $parameters = array(
                "location_id" => $location_id
            );

            $loc = Location::findFirst(
                array(
                    $conditions,
                    "bind" => $parameters
                )
            );

            if (!$loc) {
                $this->flash->error("Location was not found");
                return $this->dispatcher->forward(array(
                    'action' => 'index'
                ));
            }

            $this->view->location_id = $location_id;
			$this->view->location = $loc;
            //verify that the user is supposed to be here, by checking to make sure that
            //their agency_id matches the agency_id of the location they are trying to edit
            $agency_id_to_check = $loc->agency_id;

            if ($agency_id_to_check > 0) {

                // Get Agency Details from agency id
                $conditions = "agency_id = :agency_id:";
                $parameters = array("agency_id" => $agency_id_to_check);
                $agency = Agency::findFirst(array($conditions, "bind" => $parameters));

                if(isset($agency->parent_id)) {
                    $conditions = "agency_id = :agency_id:";
                    $parameters = array("agency_id" => $agency->parent_id);
                    $parent_agency = Agency::findFirst(array($conditions, "bind" => $parameters));
                    $this->view->logo_path = "/img/agency_logos/" . $parent_agency->logo_path;
                    $this->view->parent_agency = $parent_agency->name;
                }
                


                //get the user id
                $identity = $this->auth->getIdentity();
                // If there is no identity available the user is redirected to index/index
                if (!is_array($identity)) {
                    $this->response->redirect('/session/login?return=/location/');
                    $this->view->disable();
                    return;
                }
                // Query binding parameters with string placeholders
                $conditions = "id = :id:";
                $parameters = array("id" => $identity['id']);
                $userObj = Users::findFirst(array($conditions, "bind" => $parameters));

                if ($agency_id_to_check != $userObj->agency_id) {
                    $userObj->suspended = 'Y';
                    $userObj->save();
                    $this->auth->remove();
                    return $this->response->redirect('index');
                }
            }
            //end making sure the user should be here
			/////GetLocationReviewSite($location_id,\Vokuro\Models\Location::TYPE_GOOGLE);
            //GetLocationReviewSite($location_id, \Vokuro\Models\Location::TYPE_GOOGLE);
            //GetLocationReviewSite($location_id, \Vokuro\Models\Location::TYPE_FACEBOOK);
            //GetLocationReviewSite($location_id, \Vokuro\Models\Location::TYPE_YELP);
            
            //$objGoogleReviewSite = \Vokuro\Models\LocationReviewSite::findFirst("location_id = {$location_id} AND review_site_id = " . \Vokuro\Models\Location::TYPE_GOOGLE);
            $objGoogleReviewSite = $this->GetLocationReviewSite($location_id, \Vokuro\Models\Location::TYPE_GOOGLE);
            $this->view->GoogleMyBusinessConnected = $objGoogleReviewSite && $objGoogleReviewSite->json_access_token ? true : false;
            $this->view->objGoogleReviewSite = $objGoogleReviewSite;

            $objReviewService = new \Vokuro\Services\Reviews();
            $client = $objReviewService->getGoogleClient($location_id, $ComingFromSignup);
            $credentialsPath = CREDENTIALS_PATH;

            if (isset($_GET['code'])) {
                // Exchange authorization code for an access token.
                $client->setClientId('353416997303-7kan3ohck215dp0ca5mjjr63moohf66b.apps.googleusercontent.com');

                $accessToken = $client->authenticate($_GET['code']);
                $this->setAccessToken($accessToken, $location_id);
            }

            // Load previously authorized credentials from a file.
            $authUrl = $client->createAuthUrl();
            $this->view->authUrl = $authUrl;

            if ($accessToken) {
                $client->setAccessToken($accessToken['access_token']);
                // Refresh the token if it's expired.
                $access_token = $client->getAccessToken();
                $refreshToken = $client->getRefreshToken();

                return $this->response->redirect('/reviewfeeds/googlereviews');
            }

            // Find all regions for this agency
            $conditions = "agency_id = :agency_id:";
            $parameters = array("agency_id" => $userObj->agency_id);
            $this->view->regions = Region::find(array($conditions, "bind" => $parameters));
            // Find looking for regions

            $this->view->location = $loc;
            $this->view->facebook_access_token = $this->facebook_access_token;

            //look for a yelp review configuration;
            $this->view->yelp = $this->GetLocationReviewSite($location_id, \Vokuro\Models\Location::TYPE_YELP);
            //$this->view->yelp = LocationReviewSite::findFirst(array($conditions, "bind" => $parameters));
            $this->view->YelpConnected = isset($this->view->yelp->external_location_id) && $this->view->yelp->external_location_id && $this->view->yelp->external_location_id != '';


            //look for a facebook review configuration
            $this->view->facebook = $this->GetLocationReviewSite($location_id, \Vokuro\Models\Location::TYPE_FACEBOOK);
            $this->view->FacebookConnected = $this->view->facebook->access_token ? true : false;

            //look for a google review configuration
            $conditions = "location_id = :location_id: AND review_site_id = " . \Vokuro\Models\Location::TYPE_GOOGLE;
            $parameters = array("location_id" => $loc->location_id);
            $this->view->google = LocationReviewSite::findFirst(array($conditions, "bind" => $parameters));

            $this->view->form = new LocationForm($loc, array(
                'edit' => true
            ));
        }


        /**
         * Deletes a User
         *
         * @param int $id
         */
        public function deleteAction($location_id) {
            $conditions = "location_id = :location_id:";
            $parameters = array("location_id" => $location_id);
            $objLocation = Location::findFirst(array($conditions, "bind" => $parameters));
            if (!$objLocation) {
                $this->flash->error("The location was not found");
                return $this->dispatcher->forward(array(
                    'action' => 'index'
                ));
            }

            //verify that the user is supposed to be here, by checking to make sure that
            //their agency_id matches the agency_id of the location they are trying to edit
            $agency_id_to_check = $objLocation->agency_id;
            if ($agency_id_to_check > 0) {
                //get the user id
                $identity = $this->auth->getIdentity();
                // If there is no identity available the user is redirected to index/index
                if (!is_array($identity)) {
                    $this->response->redirect('/session/login?return=/location/');
                    $this->view->disable();
                    return;
                }
                // Query binding parameters with string placeholders
                $conditions = "id = :id:";
                $parameters = array("id" => $identity['id']);
                $userObj = Users::findFirst(array($conditions, "bind" => $parameters));

                //if the agency id numbers do not match, log them out
                if ($agency_id_to_check != $userObj->agency_id) {
                    $userObj->suspended = 'Y';
                    $userObj->save();
                    $this->auth->remove();
                    return $this->response->redirect('index');
                }
            }
            //end making sure the user should be here
            $dbLocationReviewSites = \Vokuro\Models\LocationReviewSite::find("location_id = {$objLocation->location_id}");

            // Review invites
            $dbReviewInvite = \Vokuro\Models\ReviewInvite::find("location_id = {$objLocation->location_id}");
            foreach($dbReviewInvite as $objReviewInvite) {
                $dbReviewInviteReviewSite = \Vokuro\Models\ReviewInviteReviewSite::find("review_invite_id = {$objReviewInvite->review_invite_id}");
                foreach($dbReviewInviteReviewSite as $objReviewInviteReviewSite)
                    $objReviewInviteReviewSite->delete();

                $objReviewInvite->delete();
            }

            // Review Sites
            foreach($dbLocationReviewSites as $objReviewSite)
                $objReviewSite->delete();


            $this->updateAgencySubscriptionPlan($objLocation->location_id, false);
            if (!$objLocation->delete()) {
                $this->flash->error($objLocation->getMessages());
            } else {
                $objFirstLocation = \Vokuro\Models\Location::findFirst("agency_id = {$agency_id_to_check}");
                if($objFirstLocation)
                    $this->auth->setLocation($objFirstLocation->location_id);
                else
                    $this->auth->setLocation('');
                $this->auth->setLocationList();
                $this->flash->success("The location was deleted");
            }

            return $this->dispatcher->forward(array(
                'action' => 'index'
            ));
        }


        /**
         * This is an ajax function that creates regions
         *
         * @param int $id
         */
        public function regionAction() {
            //get the user id
            $identity = $this->auth->getIdentity();
            // If there is no identity available the user is redirected to index/index
            if (!is_array($identity)) {
                $this->response->redirect('/session/login?return=/location/');
                $this->view->disable();
                return;
            }
            // Query binding parameters with string placeholders
            $conditions = "id = :id:";
            $parameters = array("id" => $identity['id']);
            $userObj = Users::findFirst(array($conditions, "bind" => $parameters));
            //echo '<pre>$userObj:'.print_r($userObj->agency_id,true).'</pre>';

            //create the region now
            $reg = new Region();
            $reg->assign(array(
                'name' => $_GET['name'],
                'agency_id' => $userObj->agency_id,
            ));

            $this->view->disable();
            $form_data = array(); //Pass back the data to ajax
            if (!$reg->save()) {
                $form_data['success'] = false;
                $form_data['posted'] = 'There was an error';
            } else {
                $form_data['success'] = true;
                $form_data['posted'] = 'Data Was Posted Successfully';
                $form_data['id'] = $reg->region_id;
            }
            echo json_encode($form_data);
        }


        /**
         * This is an ajax function that deletes regions
         *
         * @param int $id
         */
        public function regiondeleteAction($id) {
            //get the user id
            $identity = $this->auth->getIdentity();
            // If there is no identity available the user is redirected to index/index
            if (!is_array($identity)) {
                $this->response->redirect('/session/login?return=/location/');
                $this->view->disable();
                return;
            }
            // Query binding parameters with string placeholders
            $conditions = "id = :id:";
            $parameters = array("id" => $identity['id']);
            $userObj = Users::findFirst(array($conditions, "bind" => $parameters));
            //echo '<pre>$userObj:'.print_r($userObj->agency_id,true).'</pre>';

            //find the region to delete
            $conditions = "region_id = :region_id: AND agency_id = :agency_id:";
            $parameters = array("region_id" => $id, "agency_id" => $userObj->agency_id);
            $reg = Region::findFirst(array($conditions, "bind" => $parameters));

            $this->view->disable();
            if (!$reg->delete()) {
                echo 'false';
            } else {
                echo 'true';
            }
        }


        /**
         * Sends an email to the selected address
         */
        public function send_emailAction() {
            $Domain = $this->config->application->domain;
            // Only process POST reqeusts.
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $identity = $this->auth->getIdentity();
            $conditions = "id = :id:";
            $parameters = array("id" => $identity['id']);
            $user = Users::findFirst(array($conditions, "bind" => $parameters));
            $objAgency = \Vokuro\Models\Agency::findFirst("agency_id = {$user->agency_id}");
            if($objAgency->parent_id == \Vokuro\Models\Agency::BUSINESS_UNDER_RV) {

                $EmailFrom = "zacha@reviewvelocity.co";
                 $EmailFromName="Zacha Anderson";

            }
            elseif($objAgency->parent_id == \Vokuro\Models\Agency::AGENCY) { // Thinking about this... I don't think this case ever happens.  A user is created for a business, so I don't know when it would be an agency.
                $objAgencyUser = \Vokuro\Models\Users::findFirst("agency_id = {$objAgency->agency_id} AND role='Super Admin'");

                $EmailFrom =  $objAgency->email;
                $EmailFromName= $objAgency->name;

            }
            elseif($objAgency->parent_id > 0) {
                $objParentAgency = \Vokuro\Models\Agency::findFirst("agency_id = {$objAgency->parent_id}");
                $objAgencyUser = \Vokuro\Models\Users::findFirst("agency_id = {$objParentAgency->agency_id} AND role='Super Admin'");

                if(!$objParentAgency->email_from_address && !$objParentAgency->custom_domain)
                    throw new \Exception("Your email from address or your custom domain needs to be set to send email");
                $EmailFrom = $objParentAgency->email_from_address ?: "no_reply@{$objParentAgency->custom_domain}.{$Domain}";

                 $EmailFromName = $objParentAgency->email_from_name ?: "";
            }
            else {
               $EmailFrom = "zacha@reviewvelocity.co";
                $EmailFromName="Zacha Anderson";
            }


                /***  Email From  29.11.2012 ***/


                // Get the form fields and remove whitespace.
                $subject = strip_tags(trim($_POST["subject"]));
                $subject = str_replace(array("\r", "\n"), array(" ", " "), $subject);
                $emailArr = $_POST['email_to'];
                
                $message = trim($_POST["message"]);

                // Check that data was sent to the mailer.
                if (!$subject || !$message) {
                    // Set a 400 (bad request) response code and exit.
                    http_response_code(400);
                    echo "Oops! There was a problem with your submission. Please complete the form and try again.";
                    exit;
                }

                $filterEmailArr = [];
                foreach ($emailArr as $key => $email) {
                    $email = filter_var(trim($email), FILTER_SANITIZE_EMAIL);
                    if($email && !filter_var($email, FILTER_VALIDATE_EMAIL)){
                        http_response_code(400);
                        echo "Oops! There was a problem with your submission. Please complete the form and try again.";
                        exit;
                    }else if($email){
                        $filterEmailArr[] = $email;
                    }
                }
                if(!count($filterEmailArr)){
                    http_response_code(400);
                    echo "Oops! There was a problem with your submission. Please complete the form and try again.";
                    exit;
                }
                
                try {
                    //$EmailFrom='s@gmail.com';
                   // echo $message;exit;
                    foreach ($filterEmailArr as $key => $email) {
                        $mail = $this->getDI()->getMail();
                        $mail->setFrom($EmailFrom,$EmailFromName);
                        $mail->send($email, $subject, '', '', $message);
                    }
                   /* $this->getDI()
                        ->getMail()
                        ->send($email, $subject, '', '', $message);*/
                    // Set a 200 (okay) response code.
                    http_response_code(200);
                    echo "Thank You! Your message has been sent.";
                    exit;
                } catch (Exception $e) {
                    // Set a 500 (internal server error) response code.
                    http_response_code(500);
                    echo "Something went wrong and we couldn't send your message.";
                    exit;
                }

            } else {
                // Not a POST request, set a 403 (forbidden) response code.
                http_response_code(403);
                echo "There was a problem with your submission, please try again.";
                exit;
            }
        }


         public function send_emailfnAction() {
            // Only process POST reqeusts.
            if ($_POST) {
                echo 'kk';exit;
           // echo $_POST["email"];exit;

                /***  Email From  29.11.2012 ***/
                /***  Email From  29.11.2012 ***/


                // Get the form fields and remove whitespace.
                $subject = strip_tags(trim($_POST["subject"]));
                $subject = str_replace(array("\r", "\n"), array(" ", " "), $subject);
                $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
                $message = trim($_POST["message"]);

                // Check that data was sent to the mailer.
                if (empty($subject) OR empty($message) OR !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    // Set a 400 (bad request) response code and exit.
                    http_response_code(400);
                    echo "Oops! There was a problem with your submission. Please complete the form and try again.";
                    exit;
                }

                try {
                    //$EmailFrom='s@gmail.com';
                    /*$mail = $this->getDI()->getMail();
                    $mail->setFrom($EmailFrom);
                    $mail->send($email, $subject, '', '', $message);*/
                    $this->getDI()
                        ->getMail()
                        ->send($email, $subject, '', '', $message);
                    // Set a 200 (okay) response code.
                    http_response_code(200);
                    echo "Thank You! Your message has been sent.";
                    exit;
                } catch (Exception $e) {
                    // Set a 500 (internal server error) response code.
                    http_response_code(500);
                    echo "Something went wrong and we couldn't send your message.";
                    exit;
                }

            } else {
                // Not a POST request, set a 403 (forbidden) response code.
                http_response_code(403);
                echo "There was a problem with your submission, please try again.";
                exit;
            }
        }

             public function send_review_invite_employeeAction() {
               

                $identity = $this->auth->getIdentity();
                
                // If there is no identity available the user is redirected to index/index
                
                /*** get post value ***/

                if ($this->request->isPost()) {

                    //echo 'SMS starts';exit;
                //the user wants to send an SMS, so first save it in the database
                if (!$_POST['phone'] || $_POST['phone'] == '') {
                    //$this->flash->error('Please enter a Phone number.');
                    //throw new Exception('Please enter a Phone number.', 123);
                    $this->view->disable();
                    echo 'Please enter a Phone number.';
                    return;
                } else {
                        if($_POST['location_id']=='')
                        {
                        $location_name=$this->session->get('auth-identity')['location_name'];
                        $location_id=$this->session->get('auth-identity')['location_id'];
                        }
                        else
                        {
                             $location_name=$_POST['location_name'];
                             $location_id=$_POST['location_id'];
                        }
                    
                    //else we have a phone number, so send the message
                    $name = $_POST['name'];
                    $message = $_POST['SMS_message'].'  Reply stop to be removed';
                    //replace out the variables
                    $message = str_replace("{location-name}", $location_name, $message);
                    $message = str_replace("{name}", $name, $message);
                    $guid = $this->GUID();
                   $message = str_replace("{link}", $this->googleShortenURL('http://' . $_SERVER['HTTP_HOST'] . '/review/?a=' . $guid), $message);

                  // exit;

                    $phone = $_POST['phone'];

                    //save the message to the database before sending the message
                    $invite = new ReviewInvite();
                    $invite->assign(array(
                        'name' => $name,
                        'location_id' => $location_id,
                        'phone' => $phone,
                        //TODO: Added google URL shortener here
                        'api_key' => $guid,
                        'sms_message' => $message.'  Reply stop to be removed',
                        'date_sent' => date('Y-m-d H:i:s'),
                        'date_last_sent' => date('Y-m-d H:i:s'),
                        'sent_by_user_id' => $identity['id']
                    ));

                    if (!$invite->save()) {
                        $this->view->disable();
                        echo $invite->getMessages();
                        return;
                    } else {
                        //The message is saved, so send the SMS message now
                        //echo $message;exit;
                        //echo $this->twilio_api_key;exit;

                        /*echo $this->twilio_api_key;
                        echo '<br>';
                        echo $this->twilio_auth_token;
                        echo '<br>';
                        echo $this->twilio_auth_messaging_sid;
                        echo '<br>';
                        echo $this->twilio_from_phone;
                        echo '<br>';
                        //echo 'Agency Id: '.$AgencyID;
                         //echo '<br>';exit;
                        exit;*/

                        if ($this->SendSMS($this->formatTwilioPhone($phone), $message, $this->twilio_api_key, $this->twilio_auth_token, $this->twilio_auth_messaging_sid, $this->twilio_from_phone)) {
                            $this->flash->success("The SMS was sent successfully");
                        }
                            
                        }
                    }
                }
            }

                /*** get post value ***/

             
        /**
         * Sends a review invite to the selected location
         */
        public function send_review_inviteAction() {


            
            //don't do anything until we verify the user
            $identity = $this->auth->getIdentity();
            // If there is no identity available the user is redirected to index/index
            if (!is_array($identity)) {
                $this->response->redirect('/session/login?return=/');
                $this->view->disable();
                return;
            }

            // Query binding parameters with string placeholders
            $conditions = "id = :id:";
            $parameters = array("id" => $identity['id']);
            $userObj = Users::findFirst(array($conditions, "bind" => $parameters));
            //find the agency
            $conditions = "agency_id = :agency_id:";
            $parameters = array("agency_id" => $userObj->agency_id);
            $agency = Agency::findFirst(array($conditions, "bind" => $parameters));
            $this->view->agency = $agency;

            $conditions = "location_id = :location_id:";
            $parameters = array("location_id" => $this->session->get('auth-identity')['location_id']);
            $loc = Location::findFirst(array($conditions, "bind" => $parameters));
            $this->view->location = $loc;

            //Get the sharing code
            $this->getShareInfo($agency);
            //end getting the sharing code

            if ($this->request->isPost()) {
                //the user wants to send an SMS, so first save it in the database
                if (!$_POST['phone'] || $_POST['phone'] == '') {
                    //$this->flash->error('Please enter a Phone number.');
                    //throw new Exception('Please enter a Phone number.', 123);
                    $this->view->disable();
                    echo 'Please enter a Phone number.';
                    return;
                } else {
                    //else we have a phone number, so send the message
                    $name = $_POST['name'];
                    $message = $_POST['SMS_message'].'  Reply stop to be removed';
                    //replace out the variables
                    $message = str_replace("{location-name}", $this->session->get('auth-identity')['location_name'], $message);
                    $message = str_replace("{name}", $name, $message);
                    $guid = $this->GUID();
                    $message = str_replace("{link}", $this->googleShortenURL('http://' . $_SERVER['HTTP_HOST'] . '/review/?a=' . $guid), $message);

                    $phone = $_POST['phone'];

                    //save the message to the database before sending the message
                    $invite = new ReviewInvite();
                    $invite->assign(array(
                        'name' => $name,
                        'location_id' => $this->session->get('auth-identity')['location_id'],
                        'phone' => $phone,
                        //TODO: Added google URL shortener here
                        'api_key' => $guid,
                        'sms_message' => $message,
                        /*'date_sent' => date('Y-m-d H:i:s'),*/
                        'date_last_sent' => date('Y-m-d H:i:s'),
                        'sent_by_user_id' => $identity['id']
                    ));
                    


                    if (!$invite->save()) {
                        $this->view->disable();
                        echo $invite->getMessages();
                        return;
                    } else {

                       
                        //The message is saved, so send the SMS message now
                        //echo $message;exit;
                        if ($this->SendSMS($this->formatTwilioPhone($phone), $message, $this->twilio_api_key, $this->twilio_auth_token, $this->twilio_auth_messaging_sid, $this->twilio_from_phone)) {


                            $last_insert_id=$invite->review_invite_id;

                        $update_review = ReviewInvite::FindFirst('review_invite_id ='.$last_insert_id);
                        $update_review->date_sent = date('Y-m-d H:i:s');
                        $update_review->update();
                            $this->flash->success("The SMS was sent successfully");
                        }

                    }
                }
            }
            $this->view->disable();
            return;
        }




        protected $fb;

        public function getAccessTokenAction($LocationID, $RedirectToSession = 0) {
            require_once __DIR__ . "/../library/Facebook/autoload.php";
            require_once __DIR__ . "/../library/Facebook/Facebook.php";
            require_once __DIR__ . "/../library/Facebook/FacebookApp.php";
            require_once __DIR__ . "/../library/Facebook/FacebookClient.php";
            require_once __DIR__ . "/../library/Facebook/FacebookRequest.php";
            require_once __DIR__ . "/../library/Facebook/FacebookResponse.php";
            require_once __DIR__ . "/../library/Facebook/Authentication/AccessToken.php";
            require_once __DIR__ . "/../library/Facebook/Authentication/OAuth2Client.php";
            require_once __DIR__ . "/../library/Facebook/Helpers/FacebookRedirectLoginHelper.php";
            require_once __DIR__ . "/../library/Facebook/PersistentData/PersistentDataInterface.php";
            require_once __DIR__ . "/../library/Facebook/PersistentData/FacebookSessionPersistentDataHandler.php";
            require_once __DIR__ . "/../library/Facebook/Url/UrlDetectionInterface.php";
            require_once __DIR__ . "/../library/Facebook/Url/FacebookUrlDetectionHandler.php";
            require_once __DIR__ . "/../library/Facebook/Url/FacebookUrlManipulator.php";
            require_once __DIR__ . "/../library/Facebook/PseudoRandomString/PseudoRandomStringGeneratorTrait.php";
            require_once __DIR__ . "/../library/Facebook/PseudoRandomString/PseudoRandomStringGeneratorInterface.php";
            require_once __DIR__ . "/../library/Facebook/PseudoRandomString/OpenSslPseudoRandomStringGenerator.php";
            require_once __DIR__ . "/../library/Facebook/PseudoRandomString/McryptPseudoRandomStringGenerator.php";
            require_once __DIR__ . "/../library/Facebook/HttpClients/FacebookHttpClientInterface.php";
            require_once __DIR__ . "/../library/Facebook/HttpClients/FacebookCurl.php";
            require_once __DIR__ . "/../library/Facebook/HttpClients/FacebookCurlHttpClient.php";
            require_once __DIR__ . "/../library/Facebook/Http/RequestBodyInterface.php";
            require_once __DIR__ . "/../library/Facebook/Http/RequestBodyUrlEncoded.php";
            require_once __DIR__ . "/../library/Facebook/Http/GraphRawResponse.php";
            require_once __DIR__ . "/../library/Facebook/Exceptions/FacebookSDKException.php";
            require_once __DIR__ . "/../library/Facebook/Exceptions/FacebookAuthenticationException.php";
            require_once __DIR__ . "/../library/Facebook/Exceptions/FacebookResponseException.php";

            $this->fb = new \Services\Facebook\Facebook(array(
                'app_id' => $this->config->facebook['app_id'],
                'app_secret' => $this->config->facebook['app_secret']
            ));
            //check for a code

            if (isset($_GET['code']) && $_GET['code'] != '') {
                //we have a code, so proccess it now
                try {
                    $accessToken = $this->fb->getOAuth2Client()->getAccessTokenFromCode(
                        $_GET['code'],
                        $this->getRedirectUrl($LocationID, $RedirectToSession)
                    );

                    $accessTokenLong = $this->fb->getOAuth2Client()->getLongLivedAccessToken($accessToken);

                    $accessToken = $accessTokenLong->getValue();
                    $conditions = "location_id = :location_id: AND review_site_id = " . \Vokuro\Models\Location::TYPE_FACEBOOK;
                    $LocationID = $this->session->get('auth-identity')['location_id'];
                    $parameters = array("location_id" => $LocationID);
                    $Obj = LocationReviewSite::findFirst(array($conditions, "bind" => $parameters));
                    if(!$Obj) {
                        $Obj = new LocationReviewSite();
                        $Obj->location_id = $LocationID;
                        $Obj->review_site_id = \Vokuro\Models\Location::TYPE_FACEBOOK;
                    }
                    $Obj->access_token = $accessToken;
                    $Obj->save();
                    $this->flash->success("The Facebook code was saved");

                    $this->response->redirect("/location/getFacebookPages/{$LocationID}/{$RedirectToSession}");
                } catch (\Services\Facebook\Exceptions\FacebookSDKException $e) {
                    $this->flash->error($e->getMessage());
                }
            } else {
                //else we have no code, so redirect the user to get one
                $helper = $this->fb->getRedirectLoginHelper();

                $url = $helper->getLoginUrl($this->getRedirectUrl($LocationID, $RedirectToSession), array('manage_pages'));// . '&auth_type=reauthenticate';

                $this->response->redirect($url);
                $this->view->disable();
                return;
            }
        }

        protected function getRedirectUrl($LocationID, $RedirectToSession=0) {
            return 'http://' . $_SERVER['HTTP_HOST'] . "/location/getAccessToken/{$LocationID}/{$RedirectToSession}";
        }

        /**
         * Begin google my business implementation
         */
        public function googleReviewsAction($LocationID) {
            $objReviewsService = new \Vokuro\Services\Reviews();
            $objReviewsService->importGoogleMyBusinessReviews($LocationID);

            return $this->response->redirect("/location/edit/{$LocationID}");
        }

        
        public function GetLocationReviewSite($location_id, $ReviewSiteType) {
        	
        	$conditions = "location_id = :location_id: AND review_site_id = " . $ReviewSiteType;
        	$parameters = array("location_id" => $location_id);
        	$objLocationReviewSite = LocationReviewSite::findFirst(array($conditions, "bind" => $parameters));
        	
        	if (!$objLocationReviewSite) {
        		$objLocationReviewSite = new LocationReviewSite();
        		$objLocationReviewSite->review_site_id = $ReviewSiteType;
        		$objLocationReviewSite->location_id = $location_id;
        		$objLocationReviewSite->is_on = 0;
        		$objLocationReviewSite->access_token = "";
        		$objLocationReviewSite->json_access_token = "";
        		$objLocationReviewSite->save();
        	}
        	
        	return $objLocationReviewSite;
        }

        public function googlemybusinessAction() {
            /*$identity = $this->auth->getIdentity();
            $LocationID = $identity['location_id'];*/

            list($LocationID, $RedirectSession) = explode('|', $_GET['state']);
            $objReviewService = new \Vokuro\Services\Reviews();
            $client = $objReviewService->getGoogleClient($LocationID, $RedirectSession);


            /************************************************

            We are going to create the Google My Business API

            service, and query it.

             ************************************************/
            $credentialsPath = CREDENTIALS_PATH;

            if (isset($_GET['code'])) {
                // Exchange authorization code for an access token.
                $client->setClientId('353416997303-7kan3ohck215dp0ca5mjjr63moohf66b.apps.googleusercontent.com');

                $accessToken = $client->authenticate($_GET['code']);
            }


            // Load previously authorized credentials from a file.
            $authUrl = $client->createAuthUrl();
            $this->view->authUrl = $authUrl;
            $this->view->setMainView('google/auth');

            if (!$accessToken)
                return;

            //$client->setClientId('353416997303-7kan3ohck215dp0ca5mjjr63moohf66b.apps.googleusercontent.com');
            $client->setAccessToken($accessToken['access_token']);
            // Refresh the token if it's expired.
            $access_token = $client->getAccessToken();
            $refreshToken = $client->getRefreshToken();

            $objReviewService = new \Vokuro\Services\Reviews();

            $objReviewService->setGoogleAccessToken($accessToken, $LocationID);
            $objReviewService->setGoogleRefreshToken($accessToken, $LocationID);
			
            // We're successfully using google my business.  Remove current google reviews.
            $objReviewService = new \Vokuro\Services\Reviews();
            $objReviewService->DeleteGoogleReviews($LocationID);
            
            return $this->response->redirect("/location/getGooglePages/{$LocationID}/{$RedirectSession}");
        }
    }
