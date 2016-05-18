<?php
namespace Vokuro\Controllers;

use Phalcon\Mvc\Controller;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Resultset\Simple as Resultset;
use Phalcon\Paginator\Adapter\Model as Paginator;

use Vokuro\Models\Agency;
use Vokuro\Models\FacebookScanning;
use Vokuro\Models\GoogleScanning;
use Vokuro\Models\Location;
use Vokuro\Models\LocationNotifications;
use Vokuro\Models\Review;
use Vokuro\Models\ReviewInvite;
use Vokuro\Models\ReviewsMonthly;
use Vokuro\Models\SharingCode;
use Vokuro\Models\Users;
use Vokuro\Models\UsersSubscription;
use Vokuro\Models\YelpScanning;

use Services_Twilio;
use Services_Twilio_RestException;

//use Vokuro\Controllers\Facebook\Facebook;
// Skip these two lines if you're using Composer
define('FACEBOOK_SDK_V4_SRC_DIR', '/var/www/html/velocity/admin/vendor/facebook/php-sdk-v4/src/Facebook/');
require '/var/www/html/velocity/admin/vendor/facebook/php-sdk-v4/autoload.php';

use Facebook\FacebookSession;
use Facebook\FacebookRequest;
use Facebook\GraphUser;
use Facebook\FacebookRequestException;

//TURN ON PRETTY ERRORS!!!
error_reporting(E_ALL);
ini_set("display_errors","on");

/**
 * ControllerBase
 * This is the base controller for all controllers in the application
 */
class ControllerBase extends Controller
{
    public function initialize()
    {
      //find the settings and set them on the layout
      $foundsomething = false;
            
      //get the user id, to find the settings
      $identity = $this->auth->getIdentity();
      //echo '<pre>$identity:'.print_r($identity,true).'</pre>';
      // If there is no identity available the user is redirected to index/index
      if (is_array($identity)) {
        // Query binding parameters with string placeholders
        $conditions = "id = :id:";
        $parameters = array("id" => $identity['id']);
        $userObj = Users::findFirst(array($conditions, "bind" => $parameters));
        //echo '<pre>$userObj:'.print_r($userObj->agency_id,true).'</pre>';
        
        //find the agency 
        $conditions = "agency_id = :agency_id:";
        $parameters = array("agency_id" => $userObj->agency_id);
        $agency = Agency::findFirst(array($conditions, "bind" => $parameters));
        if ($agency) {
          $this->view->main_color_setting = $agency->main_color;
          $this->view->logo_setting = $agency->logo_path;
          $foundsomething = true;
        }
      }
      
      //find white label info based on the url
      $sub = array_shift((explode(".",$_SERVER['HTTP_HOST'])));
      if ($sub && $sub != '' && $sub != 'my' && $sub != 'www' && $sub != 'reviewvelocity' && $sub != '104') {
        //find the agency object
        $conditions = "custom_domain = :custom_domain:";
        $parameters = array("custom_domain" => $sub);
        $agency = Agency::findFirst(array($conditions, "bind" => $parameters));
        if ($agency) {
          $this->view->agency_id = $agency->agency_id;
          $this->view->main_color_setting = $agency->main_color;
          $this->view->logo_setting = $agency->logo_path;
          $foundsomething = true;
        }
      }
      
      if ($this->request->getPost('main_color')) {
        $this->view->main_color_setting = $this->request->getPost('main_color');
      }

      

      //###  START: check to see if this user has paid   #####
      $haspaid = true;
      //get the user id
      $identity = $this->auth->getIdentity();
      // If there is no identity available the user is redirected to index/index
      if (is_array($identity)) {
        // Query binding parameters with string placeholders
        $conditions = "id = :id:";
        $parameters = array("id" => $identity['id']);
        $userObj = Users::findFirst(array($conditions, "bind" => $parameters));
        //echo '<pre>$userObj:'.print_r($userObj->agency_id,true).'</pre>';
      
        $conditions = "agency_id = :agency_id:";
        $parameters = array("agency_id" => $userObj->agency_id);
        $agency = Agency::findFirst(array($conditions, "bind" => $parameters));

        //echo '<pre>$agency->subscription_id:'.print_r($agency->subscription_id,true).'</pre>';
        if ($agency->subscription_id > 0) {
          //check to see if the user has paid
          $conditions = "agency_id = :agency_id:";
          $parameters = array("agency_id" => $userObj->agency_id);
          $subs = UsersSubscription::findFirst(array($conditions, "bind" => $parameters));

          if (isset($subs) && isset($subs->users_subscription_id) && $subs->users_subscription_id > 0) {
            $haspaid = true;
          } else {
            $haspaid = false;
          }
          if (!$haspaid && (strpos($_SERVER['REQUEST_URI'],'session')<=0) && (strpos($_SERVER['REQUEST_URI'],'session')<=0)) {
            $this->response->redirect('/admin/session/signup/'.$agency->subscription_id);
            $this->view->disable();
            return;
          }
        }
        $this->view->haspaid = $haspaid;
      }
      //###  END: check to see if this user has paid   #####

      
      if (is_array($identity)) {
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
        $this->getShareInfo($agency);
        $this->getTotalSMSSent($agency);
      }

    }



    /**
     * Execute before the router so we can determine if this is a provate controller, and must be authenticated, or a
     * public controller that is open to all.
     *
     * @param Dispatcher $dispatcher
     * @return boolean
     */
    public function beforeExecuteRoute(Dispatcher $dispatcher)
    {
        $controllerName = $dispatcher->getControllerName();

        // Only check permissions on private controllers
        if ($this->acl->isPrivate($controllerName)) {

            // Get the current identity
            $identity = $this->auth->getIdentity();

            // If there is no identity available the user is redirected to index/index
            if (!is_array($identity)) {

                $this->flash->notice('You don\'t have access to this module: private');

                $dispatcher->forward(array(
                    'controller' => 'index',
                    'action' => 'index'
                ));
                return false;
            }

            // Check if the user have permission to the current option
            $actionName = $dispatcher->getActionName();
            if (!$this->acl->isAllowed($identity['profile'], $controllerName, $actionName)) {

                $this->flash->notice('You don\'t have access to this module: ' . $controllerName . ':' . $actionName);

                if ($this->acl->isAllowed($identity['profile'], $controllerName, 'index')) {
                    $dispatcher->forward(array(
                        'controller' => $controllerName,
                        'action' => 'index'
                    ));
                } else {
                    $dispatcher->forward(array(
                        'controller' => 'user_control',
                        'action' => 'index'
                    ));
                }
                 
                return false;
            }
        }
    }

    
    public function getShareInfo($agency) {
      //Get the sharing code
      $conditions = "agency_id = :agency_id:";
      $parameters = array("agency_id" => $agency->agency_id);
      $share = SharingCode::findFirst(array($conditions, "bind" => $parameters));

      if (!(isset($share) && isset($share->sharecode))) {
        //we don't have a share code, so get one now
        $share = SharingCode::findFirst(array('order' => 'RAND()'));
        $share->agency_id = $agency->agency_id;
        $share->save();
      }
      $this->view->share = $share;

      //build share links
      $share_link = $this->googleShortenURL('http://'.$_SERVER['HTTP_HOST'].'/admin/session/signup?code='.$share->sharecode);
      $this->view->share_message = 'Click this link to sign up for a great new way to get reviews: '.$share_link;
      $this->view->share_link = $share_link;
      $this->view->share_subject = 'Sign Up and Get Reviews!';

      //calculate how many sms messages sent and how many are remaining
      //$sms_sent_this_month
      //how many allowed, count the people signed up with our code
      $base_sms_allowed = 100;
      $additional_allowed = 25;
      $num_signed_up = Agency::count(
            array(
              "column"     => "agency_id",
              "conditions" => "referrer_code = '".$share->sharecode."' ",
            )
          );
      $num_discount = (int) ($num_signed_up / 3); //find how many three
      $total_sms_month = $base_sms_allowed + ($num_discount * $additional_allowed);
      //echo '<p>$total_sms_month:'.$total_sms_month.':$num_discount:'.$num_discount.':$num_signed_up:'.$num_signed_up.':$share->sharecode:'.$share->sharecode.'</p>';
      $this->view->total_sms_month = $total_sms_month;
      $this->view->num_discount = $num_discount;
      $this->view->num_signed_up = $num_signed_up;
      $this->view->base_sms_allowed = $base_sms_allowed;
      $this->view->additional_allowed = $additional_allowed;
      //end calculating how many sent and how many allowed
      
      //end getting the sharing code
    }


    
    public function getSMSReport() {
      
      //check if the user should get the upgrade message (Only "business" agency_types who are signed up for Free accounts,
      //get the upgrade message)
      $this->view->is_upgrade = false;
      if (!(isset($this->session->get('auth-identity')['agencytype']) && $this->session->get('auth-identity')['agencytype'] == 'agency')) {
        //we have a business, so check if free
        //echo '<p>$agency->subscription_id:'.$agency->subscription_id.'</p>';
        //echo '<p>$agency->agency_id:'.$agency->agency_id.'</p>';
        if (isset($agency->subscription_id) && $agency->subscription_id > 0) {
          //we have a subscription, check if free
          $conditions = "subscription_id = :subscription_id:";
          $parameters = array("subscription_id" => $agency->subscription_id);
          $subscriptionobj = Subscription::findFirst(array($conditions, "bind" => $parameters));
          if ($subscriptionobj->amount > 0) {
            $this->view->is_upgrade = false;
          } else {
            $this->view->is_upgrade = true;
          }
        } else {
          $this->view->is_upgrade = true;
        }
      }

      if (isset($this->session->get('auth-identity')['agencytype']) && $this->session->get('auth-identity')['agencytype'] == 'business') {
        if ($this->view->is_upgrade) {
          $identity = $this->auth->getIdentity();
          //find user
          $conditions = "id = :id:";
          $parameters = array("id" => $identity['id']);
          $userObj = Users::findFirst(array($conditions, "bind" => $parameters));
          //find the agency 
          $conditions = "agency_id = :agency_id:";
          $parameters = array("agency_id" => $userObj->agency_id);
          $agency = Agency::findFirst(array($conditions, "bind" => $parameters));
          //get total sent
          $this->getTotalSMSSent($agency);
          //get share info
          $this->getShareInfo($agency);
        } 

        //Last month!
        $start_time = date("Y-m-d", strtotime("first day of previous month"));
        $end_time = date("Y-m-d 23:59:59", strtotime("last day of previous month"));
        $sms_sent_last_month = ReviewInvite::count(
              array(
                "column" => "review_invite_id",
                "conditions" => "date_sent >= '".$start_time."' AND date_sent <= '".$end_time."' AND location_id = ".$this->session->get('auth-identity')['location_id'],
              )
            );
        $this->view->sms_sent_last_month = $sms_sent_last_month; 

        //This month!
        $start_time = date("Y-m-d", strtotime("first day of this month"));
        $end_time = date("Y-m-d 23:59:59", strtotime("last day of this month"));
        $sms_sent_this_month = ReviewInvite::count(
              array(
                "column" => "review_invite_id",
                "conditions" => "date_sent >= '".$start_time."' AND date_sent <= '".$end_time."' AND location_id = ".$this->session->get('auth-identity')['location_id'],
              )
            );
        $this->view->sms_sent_this_month = $sms_sent_this_month;
          
        //Last month!
        $this->view->num_reviews_last_month = ReviewsMonthly::sum(
              array(
                "column" => "COALESCE(facebook_review_count, 0) + COALESCE(google_review_count, 0) + COALESCE(yelp_review_count, 0)",
                "conditions" => "month = ".date("m", strtotime("first day of previous month"))." AND year = '".date("Y", strtotime("first day of previous month"))."' AND location_id = ".$this->session->get('auth-identity')['location_id'],
              )
            );
        $this->view->num_reviews_two_months_ago = ReviewsMonthly::sum(
              array(
                "column" => "COALESCE(facebook_review_count, 0) + COALESCE(google_review_count, 0) + COALESCE(yelp_review_count, 0)",
                "conditions" => "month = ".date("m", strtotime("-2 months", time()))." AND year = '".date("Y", strtotime("-2 months", time()))."' AND location_id = ".$this->session->get('auth-identity')['location_id'],
              )
            );
        $this->view->total_reviews_last_month = $this->view->num_reviews_last_month - $this->view->num_reviews_two_months_ago;

        //This month!
        $this->view->num_reviews_this_month = ReviewsMonthly::sum(
              array(
                "column" => "COALESCE(facebook_review_count, 0) + COALESCE(google_review_count, 0) + COALESCE(yelp_review_count, 0)",
                "conditions" => "month = ".date("m", strtotime("first day of this month"))." AND year = '".date("Y", strtotime("first day of this month"))."' AND location_id = ".$this->session->get('auth-identity')['location_id'],
              )
            );
        //echo '<p>num_reviews_this_month:'.$this->view->num_reviews_this_month.':total_reviews_last_month:'.$this->view->total_reviews_last_month.'</p>';
        $this->view->total_reviews_this_month = $this->view->num_reviews_this_month - $this->view->total_reviews_last_month;

        
        //find the location
        $conditions = "location_id = :location_id:";
        $parameters = array("location_id" => $this->session->get('auth-identity')['location_id']);
        $location = Location::findFirst(array($conditions, "bind" => $parameters));


        //set the agency SMS limit
        $this->view->review_goal = $location->review_goal;
        //calculate how many sms messages we need to send to meet this goal.
        //$percent_needed = ($sms_sent_last_month>0?($this->view->total_reviews_last_month / $sms_sent_last_month)*100:0);
        //if ($percent_needed <= 0) 
        $percent_needed = 10;
        $this->view->percent_needed = $percent_needed;
        //echo '<p>$sms_sent_last_month:'.$sms_sent_last_month.':total_reviews_last_month:'.$this->view->total_reviews_last_month.'</p>';
        //echo '<p>percent_needed:'.$percent_needed.':review_goal:'.$location->review_goal.'</p>';
        $this->view->total_sms_needed = round($location->review_goal / ($percent_needed / 100));

      } //end checking for business vs agency
    }



    
    public function getTotalSMSSent($agency) {    
      //Total SMS Sent this month
      $start_time = date("Y-m-d", strtotime("first day of this month"));
      $end_time = date("Y-m-d 23:59:59", strtotime("last day of this month"));
      $sql = "SELECT review_invite_id
              FROM review_invite 
                INNER JOIN location ON location.location_id = review_invite.location_id
              WHERE location.agency_id = ".$agency->agency_id."  AND date_sent >= '".$start_time."' AND date_sent <= '".$end_time."'";

      // Base model
      $list = new ReviewInvite();

      // Execute the query
      $params = null;
      $rs = new Resultset(null, $list, $list->getReadConnection()->query($sql, $params));
      $this->view->sms_sent_this_month_total = $rs->count(); 
    }


    public function SendSMS($phone, $smsBody, $AccountSid, $AuthToken, $twilio_auth_messaging_sid, $twilio_from_phone, $agency) {
      // this line loads the library 
      require_once("/var/www/html".$this->config->webpathfolder->path."admin/vendor/twilio/sdk/Services/Twilio.php"); 

      // set your AccountSid and AuthToken from www.twilio.com/user/account
      //new tokens
      //$AccountSid = "AC68cd1cc8fe2ad03d2aa4d388b270577d";
      //$AuthToken = "42334ec4880d850d6c9683a4cd9d94b8";
      //old tokens
      //$AccountSid = "AC42c4f42d8076602844b3b226bdf74fd8";
      //$AuthToken = "09d64c23112f28d29ac1ded2fd61672c";

      //if this is a business not under a custom agency, then use global twillio settings
      if ((!isset($agency->parent_agency_id) || $agency->parent_agency_id == '') && $agency->agency_type_id = 2) {
        $AccountSid = "AC68cd1cc8fe2ad03d2aa4d388b270577d";
        $AuthToken = "42334ec4880d850d6c9683a4cd9d94b8";
        $twilio_auth_messaging_sid = 'MGa8510e68cd75433880ba6ea48c0bd81e';
        $twilio_from_phone = '+16197363100';
      }
      //echo '<p>$AccountSid:'.$AccountSid.':$AuthToken:'.$AuthToken.':$twilio_auth_messaging_sid:'.$twilio_auth_messaging_sid.':$twilio_from_phone:'.$twilio_from_phone.'</p>';

      //prepare twilio to send the message
      $client = new Services_Twilio($AccountSid, $AuthToken);
      
      //send the message now
      try {
        if (isset($twilio_auth_messaging_sid) && $twilio_auth_messaging_sid != '') {
           $message = $client->account->messages->create(array(
              //"From" => $this->formatTwilioPhone("213-725-2500"),
              //'MessagingServiceSid' => "MGa8510e68cd75433880ba6ea48c0bd81e", 
              "MessagingServiceSid" => $twilio_auth_messaging_sid,
              "To" => $phone,
              "Body" => $smsBody,
           ));
        } else {
           $message = $client->account->messages->create(array(
              "From" => $this->formatTwilioPhone($twilio_from_phone),
              "To" => $phone,
              "Body" => $smsBody,
           ));
        }
      } catch (Services_Twilio_RestException $e) {
        $this->flash->error('There was an error sending the SMS message to '.$phone.'.  Please check your Twilio configuration and try again. ');
        return false;
      }
      return true;
    }
    

    public function formatTwilioPhone($phone) {
      $phone = preg_replace('/\D+/', '', $phone);
      if (strlen($phone) == 10) $phone = '1'.$phone;
      return '+'.$phone;
    }
    

    
    public function sendFeedback($agency, $message, $location_id, $subject, $user_id = false)
    {
//echo '<pre>sendFeedback START !!!</pre>';
      $conditions = "location_id = :location_id:";
      $parameters = array("location_id" => $location_id);
      $notifications = LocationNotifications::find(array($conditions, "bind" => $parameters));

      foreach($notifications as $an) { 
        //check if the user wants new reviews
        if (($an->all_reviews == 1 || ($an->individual_reviews == 1 && $an->user_id == $user_id)) && ($an->email_alert == 1 || $an->sms_alert == 1)) {
          //find the user
          $conditions = "id = :id:";
          $parameters = array("id" => $an->user_id);
          $user = Users::findFirst(array($conditions, "bind" => $parameters));

          if ($an->email_alert == 1 && isset($user->email)) {
            //the user wants an email, so send it now
            $this->getDI()
              ->getMail()
              ->send('kevin_revie@hotmail.com', $subject, '', '', $message);
              //->send($user->email, 'Notification: New Review', '', '', $message);
          } 
          if ($an->sms_alert == 1 && isset($user->phone) && $user->phone != '') {
            //the user wants a text message       
    //echo '<pre>$user:'.print_r($user,true).'</pre>';
            //if (isset($user->phone) && $user->phone != '' && $agency->twilio_api_key != '' && $agency->twilio_auth_token != '') {
              //we have a phone, so send the SMS
              $this->SendSMS($this->formatTwilioPhone($user->phone), $message, $agency->twilio_api_key, $agency->twilio_auth_token, $agency->twilio_auth_messaging_sid, $agency->twilio_from_phone, $agency);
            //}
          }  
        }
      }
    }
  



    
    /**
     * Gets the site URL for the agency
     */
    public function getURL($subdomain) {
      //if we don't have the subdomain, then we need to find it

    }
    
    

    /**
     * Calls the Google API to shorten a URL
     */
    public function googleShortenURL($longUrl) {
      // Get API key from : http://code.google.com/apis/console/
      $apiKey = 'AIzaSyAPisblAqZJJ7mGWcORf4FBjNMQKV20J20';

      $postData = array('longUrl' => $longUrl);
      $jsonData = json_encode($postData);
      //echo '<pre>$jsonData:'.print_r($jsonData,true).'</pre>';

      $curlObj = curl_init();

      curl_setopt($curlObj, CURLOPT_URL, 'https://www.googleapis.com/urlshortener/v1/url?key='.$apiKey);
      curl_setopt($curlObj, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($curlObj, CURLOPT_SSL_VERIFYPEER, 0);
      curl_setopt($curlObj, CURLOPT_HEADER, 0);
      curl_setopt($curlObj, CURLOPT_HTTPHEADER, array('Content-type:application/json'));
      curl_setopt($curlObj, CURLOPT_POST, 1);
      curl_setopt($curlObj, CURLOPT_POSTFIELDS, $jsonData);

      $response = curl_exec($curlObj);

      // Change the response json string to object
      $json = json_decode($response);
      //uncomment below to see exactly what google sends back
      //echo '<pre>$json:'.print_r($json,true).'</pre>';

      curl_close($curlObj);

      //echo 'Shortened URL is: '.$json->id;
      return (isset($json->id)?$json->id:$longUrl);
    }


    
    


    /**
     * Searches for users
     */
    public function usersFunctionality($profilesId, $locationid = 0)
    {
      $this->view->profilesId = $profilesId;

      //get the user id
      $identity = $this->auth->getIdentity();
      // If there is no identity available the user is redirected to index/index
      if (!is_array($identity)) {
        $this->response->redirect('/admin/session/login?return=/admin/users/'.($profilesId==3?'':'admin'));
        $this->view->disable();
        return;
      }
      // Query binding parameters with string placeholders
      $conditions = "id = :id:";
      $parameters = array("id" => $identity['id']);
      $userObj = Users::findFirst(array($conditions, "bind" => $parameters));
      //echo '<pre>$userObj:'.print_r($userObj->agency_id,true).'</pre>';

      if ($locationid > 0) {
        //else only show the user the employees from the locations that they have access to
        $users = Users::getEmployeesByLocation($locationid);
      } else { //if ($userObj->profilesId == 1 || $userObj->profilesId == 4) {
        // Query binding parameters with string placeholders
        //$conditions = "agency_id = :agency_id: AND profilesId = ".$profilesId;
        //$parameters = array("agency_id" => $userObj->agency_id);
        
        $conditions = "location_id = :location_id:";
        $parameters = array("location_id" => $this->session->get('auth-identity')['location_id']);
        $loc = Location::findFirst(array($conditions, "bind" => $parameters));

        //default this month
        $now = new \DateTime('now');
        $start_time = $now->format('Y').'-'.$now->format('m').'-01';
        $end_time = date("Y-m-d 23:59:59", strtotime("last day of this month"));
        
        if (isset($_GET['t']) && $_GET['t'] == 'lm') {
          $start_time = date("Y-m-d", strtotime("first day of previous month"));
          $end_time = date("Y-m-d 23:59:59", strtotime("last day of previous month"));
        } else if (isset($_GET['t']) && $_GET['t'] == 'l') {
          $start_time = false;
          $end_time = false;
        } else if (isset($_GET['t']) && $_GET['t'] == 'c') {
          $start_time = $_POST['start'];
          $end_time = $_POST['end'];
          $start_time = date("Y-m-d H:i:s", strtotime($start_time));
          $end_time = date("Y-m-d H:i:s", strtotime($end_time));
        }

        $users_report = Users::getEmployeeListReport($userObj->agency_id, $start_time, $end_time, $this->session->get('auth-identity')['location_id'], $loc->review_invite_type_id, false);
        $this->view->users_report = $users_report;

        $users = Users::getEmployeeListReport($userObj->agency_id, false, false, $this->session->get('auth-identity')['location_id'], $loc->review_invite_type_id, $profilesId);
      //} else {
        //else only show the user the employees from the locations that they have access to
      //  $users = Users::getEmployeesByUser($userObj, $profilesId);
      }
      if (count($users) == 0) {
        if ($locationid <= 0) $this->flash->notice("The search did not find any ".($profilesId==3?'employees':'admin users'));
      }
      
      $this->view->users = $users;
    }


    
    public function importGoogle($Obj, $location, &$foundagency)
    {
      $google = new GoogleScanning();

      $google_reviews = $google->get_business($Obj->api_id);
            
      //import data from the feed into the database, first update the location
      $Obj->rating = $google_reviews['rating'];
      $Obj->review_count = $google_reviews['user_ratings_total'];
      if (!isset($Obj->original_review_count) || (!($Obj->original_review_count > 0)) || $Obj->original_review_count > $Obj->review_count) {
        $Obj->original_rating = $Obj->rating;
        $Obj->original_review_count = $Obj->review_count;
      }
      $Obj->save();

//echo '<pre>reviews:'.print_r($google_reviews['reviews'],true).'</pre>';
          
      //now import the reviews (if not already in the database)
      //loop through reviews
      foreach ($google_reviews['reviews'] as $reviewDetails) {
        //check if the review is already in the db
        $conditions = "time_created = :time_created: AND rating_type_id = 3 AND location_id = ".$location->location_id;
        $parameters = array("time_created" => date("Y-m-d H:i:s", $reviewDetails['time']));
        $googlerev = Review::findFirst(array($conditions, "bind" => $parameters));
        if (!$googlerev) {
          //we didn't find the review, so assign the values
          $r = new Review();
          $r->assign(array(
              'rating_type_id' => 3, //3 = Google
              'rating' => $reviewDetails['rating'],
              'review_text' => $reviewDetails['text'],
              'time_created' => date("Y-m-d H:i:s", $reviewDetails['time']),
              'user_name' => $reviewDetails['author_name'],
              'user_id' => $reviewDetails['author_url'],
              'user_image' => (isset($reviewDetails['profile_photo_url'])?$reviewDetails['profile_photo_url']:''),
              //'external_id' => $reviewDetails['id'],  google has no review id
              'location_id' => $location->location_id,
          ));
          //save now
          $r->save();

          //add agency to our found array
          if (isset($foundagency[$location->agency_id])) {
            $foundagency[$location->agency_id] .= ', ';
          } else {
            $foundagency[$location->agency_id] = '';
          }
          $foundagency[$location->agency_id] .= $location->name;
        }
      } // go to the next google review

      return $Obj;
    }
    
    
    public function importYelp($Obj, $location, &$foundagency)
    {
      //first initialize our scanners
      $yelp = new YelpScanning();
      $yelp->construct();

      $yelp_reviews = $yelp->get_business($Obj->api_id);
      $yelpreviews = json_decode($yelp_reviews);
      //echo '<pre>$yelpreviews:'.print_r($yelpreviews,true).'</pre>';

      //import data from the feed into the database, first update the location
      $Obj->rating = $yelpreviews->rating;
      $Obj->review_count = $yelpreviews->review_count;
      if (!isset($Obj->original_review_count) || (!($Obj->original_review_count > 0)) || $Obj->original_review_count > $Obj->review_count) {          
        $Obj->original_rating = $yelpreviews->rating;
        $Obj->original_review_count = $yelpreviews->review_count;
      }
      $Obj->save();
          
      //now import the review (if not already in the database)
      //loop through reviews

//echo '<pre>$yelpreviews->reviews:'.print_r($yelpreviews->reviews,true).'</pre>';
      foreach ($yelpreviews->reviews as $rev) {
        //check if the review is already in the db
        $conditions = "external_id = :external_id: AND rating_type_id = 1 AND location_id = ".$location->location_id;
        $parameters = array("external_id" => $rev->id);
        $yelprev = Review::findFirst(array($conditions, "bind" => $parameters));
        if (!$yelprev) {
          //we didn't find the review, so assign the values
          $r = new Review();
          $r->assign(array(
              'rating_type_id' => 1, //1 = Yelp
              'rating' => $rev->rating,
              'review_text' => $rev->excerpt,
              'time_created' => date ("Y-m-d H:i:s", $rev->time_created),
              'user_name' => $rev->user->name,
              'user_id' => $rev->user->id,
              'user_image' => $rev->user->image_url,
              'external_id' => $rev->id,
              'location_id' => $location->location_id,
          ));
          //save now
          $r->save();

          //add agency to our found array
          if (isset($foundagency[$location->agency_id])) {
            $foundagency[$location->agency_id] .= ', ';
          } else {
            $foundagency[$location->agency_id] = '';
          }
          $foundagency[$location->agency_id] .= $location->name;
        }
      } // go to the next yelp review
      
      return $Obj;
    }
    
    
    
    public function importFacebook($Obj, $location, &$foundagency)
    {

      $face = new FacebookScanning();
      $this->facebook_access_token = $face->getAccessToken();
      
      //first initialize our scanners
      $yelp = new YelpScanning();
      $yelp->construct();

      //$facebook_reviews = $face->getBusinessDetails($Obj->external_id, $this->facebook_access_token);
      //echo '<pre>$facebook_reviews:'.print_r($facebook_reviews,true).'</pre>';
      //Facebook has special permissions on public reviews, so lets try to scrape them             
      $url = 'https://www.facebook.com/'.$Obj->external_id.'/reviews/';
      $results = $yelp->getHTML($url);
      //echo '<pre>$facebook_reviews:'.$results.'</pre>';

      //get the review info from the html
      //<meta content="#" itemprop="ratingValue" />
      $pos = strpos($results, '" itemprop="ratingValue"');
      $rating = substr($results, 0, $pos);
      $pos2 = strrpos($rating, '"');
      $rating = substr($rating, $pos2+1);
      //echo '<pre>$rating:'.$rating.'</pre>';
      //<meta content="6" itemprop="ratingCount" />
      $pos = strpos($results, '" itemprop="ratingCount');
      $rating_count = substr($results, 0, $pos);
      $pos2 = strrpos($rating_count, '"');
      $rating_count = substr($rating_count, $pos2+1);
      //echo '<pre>$rating_count:'.$rating_count.'</pre>';
            
      //import data from the feed into the database, first update the location
      $Obj->rating = $rating;
      $Obj->review_count = $rating_count;
      if (!isset($Obj->original_review_count) || (!($Obj->original_review_count > 0)) || $Obj->original_review_count > $Obj->review_count) {
        $Obj->original_rating = $Obj->rating;
        $Obj->original_review_count = $Obj->review_count;
      }
      $Obj->save();
      
      //if we have a facebook page token, try to import reviews
      if (isset($Obj->access_token) && $Obj->access_token != '') {
        //use the graph api to get facebook "ratings" aka reviews  
        require_once "/var/www/html".$this->config->webpathfolder->path."admin/app/controllers/Facebook/autoload.php"; 
        require_once "/var/www/html".$this->config->webpathfolder->path."admin/app/controllers/Facebook/Facebook.php"; 
        require_once "/var/www/html".$this->config->webpathfolder->path."admin/app/controllers/Facebook/FacebookApp.php"; 
        require_once "/var/www/html".$this->config->webpathfolder->path."admin/app/controllers/Facebook/FacebookClient.php"; 
        require_once "/var/www/html".$this->config->webpathfolder->path."admin/app/controllers/Facebook/FacebookRequest.php"; 
        require_once "/var/www/html".$this->config->webpathfolder->path."admin/app/controllers/Facebook/FacebookResponse.php"; 
        require_once "/var/www/html".$this->config->webpathfolder->path."admin/app/controllers/Facebook/Authentication/AccessToken.php"; 
        require_once "/var/www/html".$this->config->webpathfolder->path."admin/app/controllers/Facebook/Authentication/OAuth2Client.php"; 
        require_once "/var/www/html".$this->config->webpathfolder->path."admin/app/controllers/Facebook/Helpers/FacebookRedirectLoginHelper.php"; 
        require_once "/var/www/html".$this->config->webpathfolder->path."admin/app/controllers/Facebook/PersistentData/PersistentDataInterface.php"; 
        require_once "/var/www/html".$this->config->webpathfolder->path."admin/app/controllers/Facebook/PersistentData/FacebookSessionPersistentDataHandler.php"; 
        require_once "/var/www/html".$this->config->webpathfolder->path."admin/app/controllers/Facebook/Url/UrlDetectionInterface.php"; 
        require_once "/var/www/html".$this->config->webpathfolder->path."admin/app/controllers/Facebook/Url/FacebookUrlDetectionHandler.php"; 
        require_once "/var/www/html".$this->config->webpathfolder->path."admin/app/controllers/Facebook/Url/FacebookUrlManipulator.php"; 
        require_once "/var/www/html".$this->config->webpathfolder->path."admin/app/controllers/Facebook/PseudoRandomString/PseudoRandomStringGeneratorTrait.php"; 
        require_once "/var/www/html".$this->config->webpathfolder->path."admin/app/controllers/Facebook/PseudoRandomString/PseudoRandomStringGeneratorInterface.php"; 
        require_once "/var/www/html".$this->config->webpathfolder->path."admin/app/controllers/Facebook/PseudoRandomString/OpenSslPseudoRandomStringGenerator.php"; 
        require_once "/var/www/html".$this->config->webpathfolder->path."admin/app/controllers/Facebook/HttpClients/FacebookHttpClientInterface.php"; 
        require_once "/var/www/html".$this->config->webpathfolder->path."admin/app/controllers/Facebook/HttpClients/FacebookCurl.php"; 
        require_once "/var/www/html".$this->config->webpathfolder->path."admin/app/controllers/Facebook/HttpClients/FacebookCurlHttpClient.php"; 
        require_once "/var/www/html".$this->config->webpathfolder->path."admin/app/controllers/Facebook/Http/RequestBodyInterface.php"; 
        require_once "/var/www/html".$this->config->webpathfolder->path."admin/app/controllers/Facebook/Http/RequestBodyUrlEncoded.php"; 
        require_once "/var/www/html".$this->config->webpathfolder->path."admin/app/controllers/Facebook/Http/GraphRawResponse.php";  
        require_once "/var/www/html".$this->config->webpathfolder->path."admin/app/controllers/Facebook/Exceptions/FacebookSDKException.php"; 
        require_once "/var/www/html".$this->config->webpathfolder->path."admin/app/controllers/Facebook/Exceptions/FacebookAuthorizationException.php"; 
        require_once "/var/www/html".$this->config->webpathfolder->path."admin/app/controllers/Facebook/Exceptions/FacebookAuthenticationException.php";
        require_once "/var/www/html".$this->config->webpathfolder->path."admin/app/controllers/Facebook/Exceptions/FacebookResponseException.php"; 
        
        $this->fb = new \Services\Facebook\Facebook(array(
          'app_id' => '628574057293652',
          'app_secret' => '95e89ebac7173ba0980c36d8aa5777e4'
        ));
        /*
        $result = $this->fb->get('/'. $Obj->external_id.'/ratings?limit=10000', $Obj->access_token)->getDecodedBody();
        $reviews = $result['data'];
        while(!empty($result['data'])) {
          $result = $this->fb->get('/'.$Obj->external_id.'/ratings?limit=10000&after='.$result['paging']['cursors']['after'], $this->facebook_access_token)->getDecodedBody();
          $reviews = array_merge($reviews, $result['data']);
        }
  //echo '<pre>$reviews:'.print_r($reviews,true).'</pre>';
        
        //$token = str_replace("access_token=", "", $this->facebook_access_token);
  //echo '<pre>$this->facebook_access_token:'.print_r($this->facebook_access_token,true).'</pre>';
  //echo '<pre>$token:'.print_r($token,true).'</pre>';
        // Disable app secret proof
        FacebookSession::enableAppSecretProof(false);
        $session = new FacebookSession($Obj->access_token);
        $request = new FacebookRequest(
          $session,
          'GET',
          '/'.$Obj->external_id.'/ratings'
        );
        $response = $request->execute();
        $graphObject = $response->getGraphObject();
  echo '<pre>$graphObject:'.print_r($graphObject,true).'</pre>';
  */
        $url = '/me/accounts';
        $pages = $this->fb->get($url, $Obj->access_token)->getDecodedBody();
        //echo '<pre>$pages:'.print_r($pages,true).'</pre>';

        $page_access_token = '';
        if(!empty($pages['data'])) {
          foreach($pages['data'] as $page) {
            if($page['id'] == $Obj->external_id) {
              $page_access_token = $page['access_token'];
              echo '<p><strong>$page_access_token:'.$page_access_token.'</strong></p>';
            }
          }
        }

        //if we found a page access token, try to find reviews
        if ($page_access_token != '') {
          $reviews = $face->getBusinessReviews($Obj->external_id, $page_access_token);
          //echo '<pre>$reviews:'.print_r($reviews,true).'</pre>';

          if (isset($reviews) && $reviews != '') {
            $reviews = json_decode($reviews);
          }

          //now import the reviews (if not already in the database)
          //loop through reviews
          foreach ($reviews->data as $reviewDetails) {
            //check if the review is already in the db
            $conditions = "time_created = :time_created: AND rating_type_id = 2 AND location_id = ".$location->location_id;
            $phpdate = strtotime($reviewDetails->created_time);
            $parameters = array("time_created" => date("Y-m-d H:i:s", $phpdate));
            $googlerev = Review::findFirst(array($conditions, "bind" => $parameters));
            if (!$googlerev) {
              //we didn't find the review, so assign the values
              $r = new Review();
              $r->assign(array(
                  'rating_type_id' => 2, //2 = Facebook
                  'rating' => $reviewDetails->rating,
                  'review_text' => $reviewDetails->review_text,
                  'time_created' => date("Y-m-d H:i:s", $phpdate),
                  'user_name' => $reviewDetails->reviewer->name,
                  'user_id' => $reviewDetails->reviewer->id,
                  //'external_id' => $reviewDetails->id,  facebook has no review id
                  'location_id' => $location->location_id,
              ));
              //save now
              $r->save();

              //add agency to our found array
              if (isset($foundagency[$location->agency_id])) {
                $foundagency[$location->agency_id] .= ', ';
              } else {
                $foundagency[$location->agency_id] = '';
              }
              $foundagency[$location->agency_id] .= $location->name;
            }
          } // go to the next facebook review
        }
      } //end checking for an access token
          
      return $Obj;
    }


    
  /**
    * Searches for yelp id
    */
  public function yelpId($id)
  {
    //$this->view->disable();
      
    $yelp = new YelpScanning();
    $url = 'http://yelp.com/biz/'.$id;
    //echo '<p>url:'.$url.'</p>';
    $results = $yelp->getHTML($url);
      
    //get the id from the html
    $pos = strpos($results, 'href="/writeareview/biz/');
    $results = substr($results, $pos + 24);
    $pos2 = strpos($results, '?');
    $results = substr($results, 0, $pos2);

    //echo '<p>$url:'.$url.'</p>';
    //echo 'results:'.$results;
    return $results;
  }





}
