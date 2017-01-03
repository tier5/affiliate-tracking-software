<?php
    namespace Vokuro\Controllers;

    use Phalcon\Tag;
    use Phalcon\Mvc\Model\Criteria;
    use Phalcon\Paginator\Adapter\Model as Paginator;
    use Phalcon\Mvc\Model\Resultset\Simple as Resultset;
    use Vokuro\Forms\ChangePasswordForm;
    use Vokuro\Models\Location;
    use Vokuro\Forms\UsersForm;
    use Vokuro\Models\Users;
    use Vokuro\Models\UsersLocation;
    use Vokuro\Models\PasswordChanges;
    use Vokuro\Services\Email;
    use Vokuro\Forms\LocationForm;
    use Vokuro\Models\Agency;
    use Vokuro\Models\FacebookScanning;
    use Vokuro\Models\GoogleScanning;
    use Vokuro\Models\LocationReviewSite;
    use Vokuro\Models\Region;
    use Vokuro\Models\ReviewInvite;
    use Vokuro\Models\Review;
    use Vokuro\Models\ReviewsMonthly;
    use Vokuro\Models\UsersSubscription;
    use Vokuro\Models\YelpScanning;
    use Vokuro\Services\Reviews;

    use Services_Twilio;
    use Services_Twilio_RestException;


    require_once __DIR__ . '/../../vendor/autoload.php';
    use net\authorize\api\contract\v1 as AnetAPI;
    use net\authorize\api\controller as AnetController;

    /**
     * Vokuro\Controllers\UsersController
     * CRUD to manage users
     */
    class LinkController extends ControllerBase
    {
        

         public $facebook_access_token;

         public function initialize() {

            $this->tag->setTitle('Get Mobile Reviews | Link');
            
            $path_to_admin = realpath(__DIR__ . '/../../');
            include_once $path_to_admin . '/app/library/Google/mybusiness/Mybusiness.php';
            define('APPLICATION_NAME', 'User Query - Google My Business API');
            define('CLIENT_SECRET_PATH', $path_to_admin . '/app/models/client_secrets.json');


            parent::initialize();
        }



        public function createlinkAction($uid)
        {
         /* if ($this->session->has('auth-identity')) 
          {
              $this->view->setTemplateBefore('private');   
          }*/

            $getcode=base64_decode($uid);
            $getarray=explode('-',$getcode);
            $id=$getarray[0];
            
            $conditions_user = "id = :id:";
            $parameters_user = array("id" => $id);
            $userinfo = Users::findFirst(array($conditions_user, "bind" => $parameters_user));


            if(empty($userinfo))
            {
                echo 'sorry this page does not exists';
                exit;
            }
            $conditions = "user_id = :user_id:";
            $parameters = array("user_id" => $id);
            $userObj = UsersLocation::find(array($conditions, "bind" => $parameters));
           /*if($userObj->location_id!='')
           {
            $conditions1 = "location_id = :location_id:";
            $parameters1 = array("location_id" => $userObj->location_id);
            $userObj1 = Location::findFirst(array($conditions1, "bind" => $parameters1));

            echo $userObj1->name;exit;
           }
            */
           $make_location_array=array();

           if(!empty($userObj))
           {
                foreach($userObj as $obj)
                {
                $conditions1 = "location_id = :location_id:";
                $parameters1 = array("location_id" => $obj->location_id);
                $userObj1 = Location::findFirst(array($conditions1, "bind" => $parameters1)); 
                    $make_location_array[$obj->location_id]=$userObj1->name;
                }

                //print_r($make_location_array);exit;
           }
                $agency_id=$userinfo->agency_id;
               $this->view->userlocations = $make_location_array;
               $this->view->agency = $agency_id;
               $this->view->user_id = $id;
               $this->view->userID = $uid;
               $this->view->render('users', 'sendreviewlink');
               $this->view->disable();
               return;  
        }


    

             public function send_review_invite_employeeAction($uid=null) {

                /*$identity = $this->auth->getIdentity();*/
                // If there is no identity available the user is redirected to index/index
                
                /*** get post value ***/

                if ($this->request->isPost()) {

                    

            $conditions_user = "agency_id = :agency_id:";
            $parameters_user = array("agency_id" => $_POST['agency_id']);
            
             $agencyLocationInfo = Location::findFirst(array($conditions_user, "bind" => $parameters_user));
            //echo '<pre>';print_r($agencyInfo);exit;

            $agency_location_id=$agencyLocationInfo->location_id;//exit;
            $agency_location_name=$agencyLocationInfo->name;

            $AgencyID=$_POST['agency_id'];
            $objAgency = Agency::findFirst("agency_id = {$AgencyID}");
        // Are we a business?
        if($objAgency->parent_id > 0) {
            // Return parent's keys.
            $objParentAgency = \Vokuro\Models\Agency::findFirst("agency_id = " . $objAgency->parent_id);
            
            $TwilioToken = $objParentAgency->twilio_auth_token;
            // We use the businesses' from number if it exists, otherwise use the agency's.
            $TwilioFrom = $objAgency->twilio_from_phone ?: $objParentAgency->twilio_from_phone;
            $TwilioAPI = $objParentAgency->twilio_api_key;
        } elseif($objAgency->parent_id == \Vokuro\Models\Agency::BUSINESS_UNDER_RV || $IsAdmin) {
            // Business under RV.  Return default from config.
            
            $TwilioToken = $this->config->twilio->twilio_auth_token;
            $TwilioFrom = $this->config->twilio->twilio_from_phone;
            $TwilioAPI = $this->config->twilio->twilio_api_key;
        }
            

            $twilio_api_key=$TwilioAPI;
            $twilio_auth_token=$TwilioToken;
            
            $twilio_from_phone=$TwilioFrom;
                   
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
                        $location_name=$agency_location_name;
                        $location_id=$agency_location_id;
                        }
                        else
                        {
                             $location_name=$_POST['location_name'];
                             $location_id=$_POST['location_id'];
                        }
                    
                    //else we have a phone number, so send the message

                        $start_time = date("Y-m-d", strtotime("first day of this month"));
        $end_time = date("Y-m-d 23:59:59", strtotime("last day of this month"));
        $sql = "SELECT review_invite_id
              FROM review_invite
                INNER JOIN location ON location.location_id = review_invite.location_id
              WHERE location.agency_id = " . $objAgency->agency_id . "  AND date_sent >= '" . $start_time . "' AND date_sent <= '" . $end_time ."' AND sms_broadcast_id IS NULL";
               // Base model
        $list = new ReviewInvite();

        // Execute the query
        $params = null;
        $rs = new Resultset(null, $list, $list->getReadConnection()->query($sql, $params));
        $total_sms_sent=$rs->count();//exit;

        $objSubscriptionManager = new \Vokuro\Services\SubscriptionManager();
       // $identity = $this->session->get('auth-identity');
        //echo $objAgency->agency_id;exit;

        if($objAgency->parent_id == \Vokuro\Models\Agency::BUSINESS_UNDER_RV || $objAgency->parent_id > 0)
            $MaxSMS = $objSubscriptionManager->GetMaxSMS($objAgency->agency_id, $location_id);
        else
            $MaxSMS = 0;
        $NonViralSMS = $MaxSMS;
        $ViralSMS = $objSubscriptionManager->GetViralSMSCount($objAgency->agency_id);
       $MaxSMS += $ViralSMS;
       if($total_sms_sent<$MaxSMS){

                    $name = $_POST['name'];
                    $message = $_POST['SMS_message'].'  Reply stop to be removed';
                    //replace out the variables
                    $message = str_replace("{location-name}", $location_name, $message);
                    $message = str_replace("{name}", $name, $message);
                    $guid = $this->GUID();//exit;
                   $message = str_replace("{link}", $this->googleShortenURL('http://' . $_SERVER['HTTP_HOST'] . '/review/?a=' . $guid), $message);

                    

                    $phone = $_POST['phone'];
                   $uid=$_POST['userID'];//exit;
                    //save the message to the database before sending the message

                    $invite = new ReviewInvite();
                    $invite->assign(array(
                        'name' => $name,
                        'location_id' => $location_id,
                        'phone' => $phone,
                        //TODO: Added google URL shortener here
                        'api_key' => $guid,
                        'sms_message' => $message.'  Reply stop to be removed',
                        /*'date_sent' => date('Y-m-d H:i:s'),*/
                        'date_last_sent' => date('Y-m-d H:i:s'),
                        'sent_by_user_id' => $_POST['user_id']
                    ));

                    if (!$invite->save()) {
                        $this->view->disable();
                        echo $invite->getMessages();
                        return;
                    } else {
                       

                        /*echo $twilio_api_key;
                        echo '<br>';
                        echo $twilio_auth_token;
                        echo '<br>';
                        echo $twilio_auth_messaging_sid;
                        echo '<br>';
                        echo $twilio_from_phone;
                        echo '<br>';
                        echo 'Agency Id: '.$AgencyID;
                         echo '<br>';exit;*/
                        //The message is saved, so send the SMS message now

                     /*$twilio_api_key='AC68cd1cc8fe2ad03d2aa4d388b270577d' ;
                        $twilio_auth_token='42334ec4880d850d6c9683a4cd9d94b8'; 
                        $twilio_auth_messaging_sid='MGa8510e68cd75433880ba6ea48c0bd81e';
                        $twilio_from_phone='+18582120211';*/
                        //$phone='(559) 425-4015';

                       /* $twilio_api_key='AC00b855893dab69e458170cc524233f47' ;
                        $twilio_auth_token='8a314c3ff7e285dfb4c02c93c257025e'; 
                        $twilio_auth_messaging_sid='MG28424d425f7128e97d4c96f2fdc44f2d';
                        $twilio_from_phone='4253654160';*/

                        //echo $message;
                        //exit;
                        
                        //echo $this->twilio_api_key;exit;
                        if ($this->SendSMS($phone, $message, $twilio_api_key, $twilio_auth_token,  $twilio_from_phone)) {
                            //echo $uid;exit;

                            //$this->flash->success("The SMS was sent successfully to: " . $phone);
                            //$this->view->render('users', 'reviewmsg');

                             $last_insert_id=$invite->review_invite_id;

                            $update_review = ReviewInvite::FindFirst('review_invite_id ='.$last_insert_id);
                            $update_review->date_sent = date('Y-m-d H:i:s');
                            $update_review->update();
                            
                            $update_review->sms_message;
                            $nolengthmessage=strlen($update_review->sms_message);
                            $no=ceil($nolengthmessage/140)-1;
                                if($no!=0){
                                    for($i=1;$i<=$no;$i++){
                                        
                                        $invitex = new ReviewInvite();
                                        $invitex->assign(array(
                                        'date_sent' => $update_review->date_sent,
                                        'phone' => $update_review->phone,
                                        'name' => $update_review->name,
                                        'followed_link' => $update_review->followed_link,
                                        'api_key' => $update_review->api_key,
                                        'location_id' => $update_review->location_id,
                                        'date_viewed' => $update_review->date_viewed,
                                        'review_invite_type_id' => $update_review->review_invite_type_id,
                                        'rating' => $update_review->rating,
                                        'comments' => $update_review->comments,
                                        'sms_message' => $update_review->sms_message,
                                        'recommend' => $update_review->recommend,
                                        'sent_by_user_id' => $update_review->sent_by_user_id,
                                        'times_sent' => $update_review->times_sent,
                                        'date_last_sent' => $update_review->date_last_sent,
                                        'is_resolved' => $update_review->is_resolved,
                                        'sms_broadcast_id' => $update_review->sms_broadcast_id,
                                        'link' => $update_review->link
                                        ));
                                        $invitex->save();
                                    }
                                }
                            $this->flashSession->success("The SMS was sent successfully to: " . $phone.".This page will automatically refresh in 5 seconds.".$message);
                            $this->view->disable();
                            return $this->response->redirect('link/send_review_invite_employee/'.$uid);
                            
                           
                        }
                    }
                     } // end of total checking

                        else
                        {
                            
                            $this->flashSession->error("Sorry!! this message will not be sent as You have exceeded the total sms allowed for your business to sent.");
                            
                            $this->view->disable();
                            return $this->response->redirect('link/send_review_invite_employee/'.$uid);

                        }
                }
            }

                /*** get post value ***/
                else
                {
                    //echo $uid;exit;
                    //echo 'fgggggggggggggggggg';exit;
                    if($uid)
                    {
                        $this->view->linkId = $uid;
                    }

                    $this->view->render('users', 'reviewmsg');
                    
                }

             }


            protected $fb;

        public function getAccessTokenAction($LocationID, $RedirectToSession ='') {
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
                        $this->getRedirectUrl($LocationID)
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

                    $this->response->redirect("/link/getFacebookPages/{$LocationID}");
                } catch (\Services\Facebook\Exceptions\FacebookSDKException $e) {
                    $this->flash->error($e->getMessage());
                }
            } else {
                //else we have no code, so redirect the user to get one
                $helper = $this->fb->getRedirectLoginHelper();

                $url = 'http://' . $_SERVER['HTTP_HOST'];// . '&auth_type=reauthenticate';

                $this->response->redirect($url);
                $this->view->disable();
                return;
            }
        }
             protected function getRedirectUrl($LocationID, $RedirectToSession=0) {
            return 'http://' . $_SERVER['HTTP_HOST'] . "/link/getAccessToken/{$LocationID}";
        }


        public function getFacebookPagesAction($LocationID, $RedirectToSession ='') {
           
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

         public function getYelpPagesAction($LocationID, $RedirectToSession ='') {
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

    }
