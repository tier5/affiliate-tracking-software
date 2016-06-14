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
class LocationController extends ControllerBase
{

  public $facebook_access_token;

  public function initialize()
  {
    $this->tag->setTitle('Review Velocity | Locations');
    if ($this->session->has('auth-identity')) {
      $this->view->setTemplateBefore('private');
    } else if (strpos($_SERVER['REQUEST_URI'],'cron')>0) {
      $this->view->setTemplateBefore('public');
    } else {
      $this->response->redirect('/session/login?return=/location/');
      $this->view->disable();
      return;     
    }
    if (!$this->facebook_access_token) {
      $face = new FacebookScanning();
      $this->facebook_access_token = $face->getAccessToken();
    }
    parent::initialize();
  }


    
  /**
    * Searches for yelp locations
    */
  public function yelpAction()
  {
    //yelp web service api call
    $term = $_GET['t'];
    $location = $_GET['l'];
      
    $yelp = new YelpScanning();
    $yelp->construct();
    $results = $yelp->search($term, $location);
      
    $this->view->disable();
    echo $results;
  }


    
  /**
    * Searches for yelp locations
    */
  public function yelpurlAction()
  {
    //yelp web service api call
    $id = $_GET['i'];
      
    $yelp = new YelpScanning();
    $yelp->construct();
    $results = $yelp->get_business($id);
      
    $this->view->disable();
    echo $results;
  }


  static public function noformat($input)
  {
    return round($input, 1);
  }


  /**
    * Default index view
    */
  public function indexAction()
  {
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
        foreach($dbArray as $dbRow)
            $dbRow->delete();
    }
    /**
     * Ajax request that updates yelp location and review information
     */
    public function updateLocationAction() {
        $yelp_api_id = $this->request->get('yelp_id', 'striptags');
        $location_id = $this->request->get('location_id', 'striptags');
        if(!$yelp_api_id || !$location_id)
            die("ERROR:  Missing location_id and/or yelp id");

        $user_id = $this->session->get('auth-identity')['id'];
        $objUser = Users::findFirst("id = {$user_id}");

        // Validate user is editing correct location
        $objLocation = Location::findFirst("location_id = {$location_id}");
        if($objLocation->agency_id != $objUser->agency_id)
            die("ERROR:  Invalid user ID");

        $objLocationReviewSite = LocationReviewSite::findFirst("location_id = {$location_id} AND review_site_id = 2");
        if(!$objLocationReviewSite) {
            $objLocationReviewSite = new LocationReviewSite();
            $objLocationReviewSite->review_site_id = 2;
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
  public function createAction()
  {

    //get the user id, to find the settings
    $identity = $this->auth->getIdentity();
    //echo '<pre>$identity:'.print_r($identity,true).'</pre>';
    // If there is no identity available the user is redirected to index/index
    if (!is_array($identity)) {
      $this->response->redirect('/session/login?return=/location/create');
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

        //print '<pre>' . print_r($_POST, true) . '</pre>';

        //check for yelp
        $yelp_api_id = $this->request->getPost('yelp_id', 'striptags');
        $yelp_id = $this->yelpId($yelp_api_id);
        //print '<pre>$yelp_api_id: ' . print_r($yelp_api_id, true) . '</pre>';
        if ($yelp_api_id != '' && !(strpos($yelp_api_id, '>') !== false)) {
          $lrs = new LocationReviewSite();
          $lrs->assign(array(
            'location_id' => $loc->location_id,
            'review_site_id' => 2, // yelp = 2
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
          //$google_reviews = $google->getLRD('15803962018122969779');

          $lrs = new LocationReviewSite();
          $lrs->assign(array(
            'location_id' => $loc->location_id,
            'review_site_id' => 3, // google = 3
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
            'review_site_id' => 1, // facebook = 1
            'external_id' => $facebook_page_id,
            'date_created' => date('Y-m-d H:i:s'),
            'is_on' => 1,
          ));
          
          //find the review info
          $this->importFacebook($lrs, $loc, $foundagency);
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
          $this->auth->setLocation($loc->location_id)/me ;
        }
        $this->flash->success("The location was created successfully");
        //we are done, go to the next page
        return $this->response->redirect('/location/create2/'.($loc->location_id > 0?$loc->location_id:''));
      }
    }
        
    $this->view->facebook_access_token = $this->facebook_access_token;
    $this->view->form = new LocationForm(null);
    $this->view->pick("session/signup2");
  }




  /**
    * Creates a Location, step 2
    */
  public function create2Action($location_id)
  {

    //get the user id, to find the settings
    $identity = $this->auth->getIdentity();
    //echo '<pre>$identity:'.print_r($identity,true).'</pre>';
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
        return $this->response->redirect('/location/create3/'.($location_id > 0?$location_id:''));
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
  public function create3Action($location_id)
  {

    //get the user id, to find the settings
    $identity = $this->auth->getIdentity();
    //echo '<pre>$identity:'.print_r($identity,true).'</pre>';
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
    $conditions = "agency_id = :agency_id: AND profilesId = 3";
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
    public function selectemployeesAction($location_id)
    {
      //$('#reviewgoal').val($('#review_goal').val());
      //$('#lifetimevalue').val($('#lifetime_value_customer').val());
      $reviewgoal = $this->request->getPost('reviewgoal');
      $lifetimevalue = $this->request->getPost('lifetimevalue');
      $querystring = '?review_goal='.$reviewgoal.'&lifetime_value_customer='.$lifetimevalue;
      $url = '/location/create3/'.($location_id > 0?$location_id:'').$querystring;
//echo '<pre>post:'.print_r($_POST,true).'</pre>';

      //get the user id, to find the settings
      $identity = $this->auth->getIdentity();
      // If there is no identity available the user is redirected to index/index
      if (!is_array($identity)) {
        $this->response->redirect('/session/login?return=/location/create3/'.($location_id > 0?$location_id:''));
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
        foreach($employees as $user) { 
          foreach($user->locations as $location) { 
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

      if(!empty($_POST['employees'])) {
        foreach($_POST['employees'] as $check) {
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





  /**
    * Saves the location from the 'edit' action
    */
  public function editAction($location_id)
  {
    $conditions = "location_id = :location_id:";
    $parameters = array("location_id" => $location_id);
    $loc = Location::findFirst(array($conditions, "bind" => $parameters));
    if (!$loc) {
      $this->flash->error("Location was not found");
      return $this->dispatcher->forward(array(
        'action' => 'index'
      ));
    }

    //verify that the user is supposed to be here, by checking to make sure that
    //their agency_id matches the agency_id of the location they are trying to edit
    $agency_id_to_check = $loc->agency_id;
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
      //echo '<pre>$userObj:'.print_r($userObj->agency_id,true).'</pre>';

      //if the agency id numbers do not match, log them out
//echo '<pre>$agency_id_to_check:'.$agency_id_to_check.':$userObj->agency_id:'.$userObj->agency_id.'</pre>';
      if ($agency_id_to_check != $userObj->agency_id) {
        $userObj->suspended = 'Y';
        $userObj->save();
        $this->auth->remove();
        return $this->response->redirect('index');               
      }
    }
    //end making sure the user should be here

    if ($this->request->isPost()) {

      $loc->assign(array(
        'name' => $this->request->getPost('name', 'striptags'),
        'agency_id' => $userObj->agency_id,
        'phone' => $this->request->getPost('phone', 'striptags'),
        'address' => $this->request->getPost('address', 'striptags'),
        'locality' => $this->request->getPost('locality', 'striptags'),
        'state_province' => $this->request->getPost('state_province', 'striptags'),
        'postal_code' => $this->request->getPost('postal_code', 'striptags'),
        'country' => $this->request->getPost('country', 'striptags'),
        'latitude' => $this->request->getPost('latitude', 'striptags'),
        'longitude' => $this->request->getPost('longitude', 'striptags'),
        'region_id' => $this->request->getPost('region_id', 'striptags'),
      ));

      if (!$loc->save()) {
        $this->flash->error($loc->getMessages());
      } else {
        $foundagency = array();
        
        //look for a yelp review configuration
        $conditions = "location_id = :location_id: AND review_site_id =  2";
        $parameters = array("location_id" => $loc->location_id);
        $yelp = LocationReviewSite::findFirst(array($conditions, "bind" => $parameters));
          
        //look for a facebook review configuration
        $conditions = "location_id = :location_id: AND review_site_id =  1";
        $parameters = array("location_id" => $loc->location_id);
        $facebook = LocationReviewSite::findFirst(array($conditions, "bind" => $parameters));
          
        //look for a google review configuration
        $conditions = "location_id = :location_id: AND review_site_id =  3";
        $parameters = array("location_id" => $loc->location_id);
        $google = LocationReviewSite::findFirst(array($conditions, "bind" => $parameters));

        //check for yelp
        $yelp_api_id = $this->request->getPost('yelp_id', 'striptags');
        $yelp_id = $this->yelpId($yelp_api_id);
        //print '<pre>$yelp_api_id: ' . print_r($yelp_api_id, true) . '</pre>';
        if ($yelp_api_id != '' && !(strpos($yelp_api_id, '>') !== false)) {
          if (isset($yelp) && isset($yelp->location_review_site_id) && $yelp->location_review_site_id > 0) {
            $yelp->external_id = $yelp_id;
            $yelp->api_id = $yelp_api_id;
            $yelp->save();
            //find the review info
            $this->importYelp($yelp, $loc, $foundagency);
          } else {            
            $lrs = new LocationReviewSite();
            $lrs->assign(array(
              'location_id' => $loc->location_id,
              'review_site_id' => 2, // yelp = 2
              'external_id' => $yelp_id,
              'api_id' => $yelp_api_id,
              'date_created' => date('Y-m-d H:i:s'),
              'is_on' => 1,
            ));          
            //find the review info
            $this->importYelp($lrs, $loc, $foundagency);
          }
        } else {
          //else we need to delete the yelp configuration
          if (isset($yelp) && isset($yelp->location_review_site_id) && $yelp->location_review_site_id > 0) $yelp->delete();
        }
        
        //check for google
        $google_place_id = $this->request->getPost('google_place_id', 'striptags');
        $google_api_id = $this->request->getPost('google_api_id', 'striptags');
        $googleScan = new GoogleScanning();
        if ($google_place_id != '') {
          if (isset($google) && isset($google->location_review_site_id) && $google->location_review_site_id > 0) {
            $google->external_id = $google_place_id;
            $google->api_id = $google_api_id;
            $google->lrd = $googleScan->getLRD($google_place_id);
            $google->save();
            //find the review info
            $this->importGoogle($google, $loc, $foundagency);
          } else {            
            //$google_reviews = $google->getLRD('15803962018122969779');

            $lrs = new LocationReviewSite();
            $lrs->assign(array(
              'location_id' => $loc->location_id,
              'review_site_id' => 3, // google = 3
              'external_id' => $google_place_id,
              'api_id' => $google_api_id,
              'date_created' => date('Y-m-d H:i:s'),
              'is_on' => 1,
              'lrd' => $googleScan->getLRD($google_place_id),
            ));          
            //find the review info
            $this->importGoogle($lrs, $loc, $foundagency);
          }
        } else {
          //else we need to delete the google configuration
          if (isset($google) && isset($google->location_review_site_id) && $google->location_review_site_id > 0) $google->delete();
        }
        
        //check for facebook
        $facebook_page_id = $this->request->getPost('facebook_page_id', 'striptags');
        if ($facebook_page_id != '') {
          if (isset($facebook) && isset($facebook->location_review_site_id) && $facebook->location_review_site_id > 0) {
            $facebook->external_id = $facebook_page_id;
            $facebook->save();
            //find the review info
            $this->importFacebook($facebook, $loc, $foundagency);
          } else {            
            $lrs = new LocationReviewSite();
            $lrs->assign(array(
              'location_id' => $loc->location_id,
              'review_site_id' => 1, // facebook = 1
              'external_id' => $facebook_page_id,
              'date_created' => date('Y-m-d H:i:s'),
              'is_on' => 1,
            ));
            //find the review info
            $this->importFacebook($lrs, $loc, $foundagency);
          }
        } else {
          //else we need to delete the facebook configuration
          if (isset($facebook) && isset($facebook->location_review_site_id) && $facebook->location_review_site_id > 0) $facebook->delete();
        }
        

        $this->auth->setLocationList();
        $this->flash->success("The location was updated successfully");
        Tag::resetInput();
      }
    }
        
    // Find all regions for this agency
    $conditions = "agency_id = :agency_id:";
    $parameters = array("agency_id" => $userObj->agency_id);
    $this->view->regions = Region::find(array($conditions, "bind" => $parameters));
    // Find looking for regions

    $this->view->location = $loc;
    $this->view->facebook_access_token = $this->facebook_access_token;

    //look for a yelp review configuration
    $conditions = "location_id = :location_id: AND review_site_id =  2";
    $parameters = array("location_id" => $loc->location_id);
    $this->view->yelp = LocationReviewSite::findFirst(array($conditions, "bind" => $parameters));
          
    //look for a facebook review configuration
    $conditions = "location_id = :location_id: AND review_site_id =  1";
    $parameters = array("location_id" => $loc->location_id);
    $this->view->facebook = LocationReviewSite::findFirst(array($conditions, "bind" => $parameters));
          
    //look for a google review configuration
    $conditions = "location_id = :location_id: AND review_site_id =  3";
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
  public function deleteAction($location_id)
  {
    $conditions = "location_id = :location_id:";
    $parameters = array("location_id" => $location_id);
    $loc = Location::findFirst(array($conditions, "bind" => $parameters));
    if (!$loc) {
      $this->flash->error("The location was not found");
      return $this->dispatcher->forward(array(
        'action' => 'index'
      ));
    }

    //verify that the user is supposed to be here, by checking to make sure that
    //their agency_id matches the agency_id of the location they are trying to edit
    $agency_id_to_check = $loc->agency_id;
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
      //echo '<pre>$userObj:'.print_r($userObj->agency_id,true).'</pre>';

      //if the agency id numbers do not match, log them out
//echo '<pre>$agency_id_to_check:'.$agency_id_to_check.':$userObj->agency_id:'.$userObj->agency_id.'</pre>';
      if ($agency_id_to_check != $userObj->agency_id) {
        $userObj->suspended = 'Y';
        $userObj->save();
        $this->auth->remove();
        return $this->response->redirect('index');               
      }
    }
    //end making sure the user should be here

    //first delete the location review sites
    $conditions = "location_id = :location_id:";
    $parameters = array("location_id" => $loc->location_id);
    $lrs = LocationReviewSite::find(array($conditions, "bind" => $parameters));
    $lrs->delete();

    if (!$loc->delete()) {
      $this->flash->error($user->getMessages());
    } else {
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
  public function regionAction()
  {
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
  public function regiondeleteAction($id)
  {
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
  public function send_emailAction()
  {
    // Only process POST reqeusts.
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      // Get the form fields and remove whitespace.
      $subject = strip_tags(trim($_POST["subject"]));
			$subject = str_replace(array("\r","\n"),array(" "," "),$subject);
      $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
      $message = trim($_POST["message"]);

      // Check that data was sent to the mailer.
      if ( empty($subject) OR empty($message) OR !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Set a 400 (bad request) response code and exit.
        http_response_code(400);
        echo "Oops! There was a problem with your submission. Please complete the form and try again.";
        exit;
      }

      try {
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



  /**
    * Sends a review invite to the selected location
    */
  public function send_review_inviteAction()
  {
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
        $message = $_POST['SMS_message'];
        //replace out the variables
        $message = str_replace("{location-name}", $this->session->get('auth-identity')['location_name'], $message);
        $message = str_replace("{name}", $name, $message);
        $guid = $this->GUID();
        $message = str_replace("{link}", $this->googleShortenURL('http://'.$_SERVER['HTTP_HOST'].'/review/?a='.$guid), $message);
          
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
          'date_sent' => date('Y-m-d H:i:s'),
          'date_last_sent' => date('Y-m-d H:i:s'),
          'sent_by_user_id' => $identity['id']
        ));

        if (!$invite->save()) {
          //$this->flash->error($invite->getMessages());
          //throw new Exception($invite->getMessages(), 123);
          $this->view->disable();
          echo $invite->getMessages();
          return;   
        } else {            
          //The message is saved, so send the SMS message now
          if ($this->SendSMS($this->formatTwilioPhone($phone), $message, $agency->twilio_api_key, $agency->twilio_auth_token, $agency->twilio_auth_messaging_sid, $agency->twilio_from_phone, $agency)) {
            //$this->flash->success("The SMS was sent successfully");
            //Tag::resetInput();
          }

        }
      }
    }
    //$this->getTotalSMSSent($agency);
    $this->view->disable();
    echo 'true';
    return;   
  }


  
  protected $fb;
  public function getAccessTokenAction()
  {
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

    /*$this->fb = new \Services\Facebook\Facebook(array(
      'app_id' => '628574057293652',
      'app_secret' => '95e89ebac7173ba0980c36d8aa5777e4'
    ));*/

    $this->fb = new \Services\Facebook\Facebook(array(
      'app_id' => '1650142038588223',
      'app_secret' => 'b1c2cb9c1cbb774ea35eb68de725ee45'
    ));

    //check for a code
    if (isset($_GET['code']) && $_GET['code'] != '') {
      //we have a code, so proccess it now
      try {
        $accessToken = $this->fb->getOAuth2Client()->getAccessTokenFromCode(
            $_GET['code'],
            $this->getRedirectUrl()
          );
        $accessTokenLong = $this->fb->getOAuth2Client()->getLongLivedAccessToken($accessToken);
        
        $accessToken = $accessTokenLong->getValue();
        //save the access token in the database

        //look for a facebook review configuration
        $conditions = "location_id = :location_id: AND review_site_id =  1";
        $parameters = array("location_id" => $this->session->get('auth-identity')['location_id']);
        $Obj = LocationReviewSite::findFirst(array($conditions, "bind" => $parameters));
        $Obj->access_token = $accessToken;
        $Obj->save();
        $this->flash->success("The Facebook code was saved");
        
        //look for a facebook review configuration
        $conditions = "location_id = :location_id:";
        $parameters = array("location_id" => $this->session->get('auth-identity')['location_id']);
        $location = Location::findFirst(array($conditions, "bind" => $parameters));

        $foundagency = array();
        $this->importFacebook($Obj, $location, $foundagency);

        //
        $this->response->redirect('/settings/location/');
      } catch (\Services\Facebook\Exceptions\FacebookSDKException $e) {
        $this->flash->error($e->getMessage());
      }
    } else {
      //else we have no code, so redirect the user to get one
      $helper = $this->fb->getRedirectLoginHelper();
    
      $url = $helper->getLoginUrl($this->getRedirectUrl(),  array('manage_pages')).'&auth_type=reauthenticate';
      echo '<p>'.$url.'</p>';

      $this->response->redirect($url);
      $this->view->disable();
      return;   
    }
    
    
    //exit;
  }
  
  protected function getRedirectUrl()
  {
    // TODO:  What is with the hardcoding of URLS?!?!  Fix this
    //return 'http://velocity.dev/location/getAccessToken';
    return 'http://reviewvelocity.co/location/getAccessToken';
  }

    

  /**
    * This function runs every night to add new reviews to the database for every location
    */
  public function cronAction()
  {
    echo '<div style="background-color: White;">';
          
    $foundagency = array();

    //now loop through every location in the database
    $allLocations = Location::find();

    $conditions = "location_id = :location_id:";
    // TODO:  Remove location restriction.  Doing this for testing purposes (it was checked in with a limitation too, so remove entirely).
    $parameters = array("location_id" => 61);
    $allLocations = Location::find(array($conditions, "bind" => $parameters));

    foreach ($allLocations as $location) {
        $rev_monthly = new ReviewsMonthly();
        $rev_monthly->location_id = $location->location_id;

        echo '<p><b>Location: '.$location->name.'</b></p>';

        //look for a yelp review configuration
        $conditions = "location_id = :location_id: AND review_site_id = 2";
        $parameters = array("location_id" => $location->location_id);
        $Obj = LocationReviewSite::findFirst(array($conditions, "bind" => $parameters));

        // start with Yelp reviews, if configured
        if (isset($Obj) && isset($Obj->api_id) && $Obj->api_id) {
          // import reviews
          $Obj = $this->importYelp($Obj, $location, $foundagency);
          
          $rev_monthly->yelp_rating = $Obj->rating;
          $rev_monthly->yelp_review_count = $Obj->review_count - $Obj->original_review_count;
        } else {
          echo '<p>Yelp api_id NOT CONFIGURED!</p>';
        }
        
        //look for a facebook review configuration
        $conditions = "location_id = :location_id: AND review_site_id =  1";
        $parameters = array("location_id" => $location->location_id);
        $Obj = LocationReviewSite::findFirst(array($conditions, "bind" => $parameters));

        //Next lets import the Facebook reviews, if configured
        if (isset($Obj) && isset($Obj->external_id) && $Obj->external_id) {
          $this->importFacebook($Obj, $location, $foundagency);
          
          $rev_monthly->facebook_rating = $Obj->rating;
          $rev_monthly->facebook_review_count = $Obj->review_count - $Obj->original_review_count;
        } else {
          echo '<p>facebook page_id NOT CONFIGURED!</p>';
        }
          
        //look for a facebook review configuration
        $conditions = "location_id = :location_id: AND review_site_id =  3";
        $parameters = array("location_id" => $location->location_id);
        $Obj = LocationReviewSite::findFirst(array($conditions, "bind" => $parameters));
          
        //Finaly lets import the Google reviews, if configured
        if (isset($Obj) && isset($Obj->api_id) && $Obj->api_id) {
          //import reviews
          $Obj = $this->importGoogle($Obj, $location, $foundagency);

          $rev_monthly->google_rating = $Obj->rating;
          $rev_monthly->google_review_count = $Obj->review_count - $Obj->original_review_count;
        } else {
          echo '<p>google api_id NOT CONFIGURED!</p>';
        }
          
        $location->date_reviews_checked = date('Y-m-d H:i:s');
        $location->save();

        //find if we should insert or update our monthly review record
        
        $conditions = "location_id = :location_id: AND month = ".date('m')." AND year = ".date('Y');
        $parameters = array("location_id" => $location->location_id);
        $rm = ReviewsMonthly::findFirst(array($conditions, "bind" => $parameters));
        //if we found a match, save the id
        if (isset($rm->reviews_monthly_id) && $rm->reviews_monthly_id > 0) $rev_monthly->reviews_monthly_id = $rm->reviews_monthly_id;
        //save our monthly data now
        $rev_monthly->month = date('m');
        $rev_monthly->year = date('Y');
        $rev_monthly->save();

        //loop through our found array and send notifications
        //echo '<pre>$foundagency:'.print_r($foundagency,true).'</pre>';
        $keys = array_keys($foundagency);
        foreach($keys as $key){
          $agencyobj = new Agency();
          $agency = $agencyobj::findFirst($key);
          //send the notification about the new review
          $message = 'Notification: a new review has been posted for '.$foundagency[$key].': http://'.(isset($agency->custom_domain) && $agency->custom_domain != ''?$agency->custom_domain.'.':'').'reviewvelocity.co/reviews/';
          //echo $message;
          parent::sendFeedback($agency, $message, $location->location_id, 'Notification: New Review', false);
        }
        $foundagency = array();

    }  // go to the next location

       

    //Check if there are any invites that need to be resent
    $invitelist = ReviewInvite::getInvitesPending();
    //loop through each invite and resend
    foreach ($invitelist as $invite) {
      $invite->date_last_sent = date('Y-m-d H:i:s');
      $invite->times_sent = $invite->times_sent+1;
      $invite->save();

      //find the location 
      $conditions = "location_id = :location_id:";
      $parameters = array("location_id" => $invite->location_id);
      $location = Location::findFirst(array($conditions, "bind" => $parameters));

      //find the agency 
      $conditions = "agency_id = :agency_id:";
      $parameters = array("agency_id" => $location->agency_id);
      $agency = Agency::findFirst(array($conditions, "bind" => $parameters));

      $this->SendSMS($this->formatTwilioPhone($invite->phone), $invite->sms_message, $agency->twilio_api_key, $agency->twilio_auth_token, $agency->twilio_auth_messaging_sid, $agency->twilio_from_phone, $agency);
    }
    //END checking for invites that need to be sent




    //START: checking if subscriptions are valid
    echo '<p></p><p><b>START: checking if subscriptions are valid</b></p>';
    define("AUTHORIZENET_LOG_FILE", "phplog");
    date_default_timezone_set('America/Los_Angeles');
    // Common Set Up for API Credentials
    $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
    $merchantAuthentication->setName("8BB6rbA9e"); 
    $merchantAuthentication->setTransactionKey("66svuY48XbuZ76Sc");
    
    //Find all subscrions that need to be checked
    $conditions = "cancel_code IS NULL";
    $parameters = array();
    $subs = UsersSubscription::find(array($conditions, "bind" => $parameters));

    //loop through the subscriptions
    foreach ($subs as $sub) {
      echo '<p><b>$sub->user_id:'.$sub->user_id.'</b></p>';

      //lets check to see if this user has three referrers yet
      $conditions = "agency_id = :agency_id:";
      $parameters = array("agency_id" => $location->agency_id);
      $agency = Agency::findFirst(array($conditions, "bind" => $parameters));

      //find the user object to update
      $user = Users::findFirstById($sub->user_id);
      if ($user) {        
        //ask authorize.net if the subscription is valid
        $request = new AnetAPI\ARBGetSubscriptionStatusRequest();
        $request->setMerchantAuthentication($merchantAuthentication);
        $refId = 'ref' . time();
        $request->setRefId($refId);
        $request->setSubscriptionId($sub->auth_subscription_id);
        echo '<p><b>auth_subscription_id:'.$sub->auth_subscription_id.'</b></p>';

        $controller = new AnetController\ARBGetSubscriptionStatusController($request);

        $response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::SANDBOX);

        if (isset($response) && ($response != null) && ($response->getMessages()->getResultCode() == "Ok"))
        {
          echo "<p>SUCCESS: Subscription Status : " . $response->getStatus() . "</p>";
          if ($response->getStatus() == 'active') {
            $agency->subscription_valid = 'Y';
          } else {
            $agency->subscription_valid = 'N';
          }
        }
        else
        {
          echo "<p>ERROR :  Invalid response</p>";
          echo "<p>Response : " . $response->getMessages()->getMessage()[0]->getCode() . "  " .$response->getMessages()->getMessage()[0]->getText() . "</p>";
          $agency->subscription_valid = 'N';
        }
    echo '<p><b>$agency->agency_id: '.$agency->agency_id.'</b></p>';
        $agency->save();
      } //end checking the user

    } // go to the next subscription
    echo '<p><b>END: checking if subscriptions are valid</b></p>';
    //END: checking if subscriptions are valid




    //START: checking if STRIPE subscriptions are valid
    echo '<p></p><p><b>START: checking if STRIPE subscriptions are valid</b></p>';
    
    //Find all subscrions that need to be checked
    $conditions = "stripe_subscription_id IS NOT NULL";
    $parameters = array();
    $agencies = Agency::find(array($conditions, "bind" => $parameters));

    //loop through the subscriptions
    foreach ($agencies as $agency) {
      echo '<p><b>$agency->stripe_subscription_id:'.$agency->stripe_subscription_id.'</b></p>';

      //lets find the parent agency 
      $conditions = "agency_id = :agency_id:";
      $parameters = array("agency_id" => $agency->parent_agency_id);
      $parent_agency = Agency::findFirst(array($conditions, "bind" => $parameters));

      \Stripe\Stripe::setApiKey($parent_agency->stripe_account_secret);

      //check subscription
      $isvalid = false;
      try {
        $customer = \Stripe\Customer::retrieve($agency->stripe_customer_id);
        $subscription = $customer->subscriptions->retrieve($agency->stripe_subscription_id);

        if ($subscription->status == 'active') $isvalid = true;
      } catch(Stripe_CardError $e) {
        echo '<p>Stripe_CardError: '.$e->getMessage().'</p>';
      } catch (Stripe_InvalidRequestError $e) {
        // Invalid parameters were supplied to Stripe's API
        echo '<p>Stripe_InvalidRequestError: '.$e->getMessage().'</p>';
      } catch (Stripe_AuthenticationError $e) {
        // Authentication with Stripe's API failed
        // (maybe you changed API keys recently)
        echo '<p>Stripe_AuthenticationError: '.$e->getMessage().'</p>';
      } catch (Stripe_ApiConnectionError $e) {
        // Network communication with Stripe failed
        echo '<p>Stripe_ApiConnectionError: '.$e->getMessage().'</p>';
      } catch (Stripe_Error $e) {
        // Display a very generic error to the user, and maybe send
        // yourself an email
        echo '<p>Stripe_Error: '.$e->getMessage().'</p>';
      } catch (Exception $e) {
        // Something else happened, completely unrelated to Stripe
        echo '<p>Exception: '.$e->getMessage().'</p>';
      } catch (\Stripe\Error\Base $e) {
        // Code to do something with the $e exception object when an error occurs
        echo '<p>\Stripe\Error\Base: '.$e->getMessage().'</p>';
      }


echo '<pre>$isvalid:'.($isvalid?'true':'false').'</pre>';
      $agency->subscription_valid = ($isvalid?'Y':'N');
      $agency->save();
//echo '<pre>$subscription:'.print_r($subscription,true).'</pre>';

    } // go to the next subscription
    echo '<p><b>END: checking if subscriptions are valid</b></p>';
    //END: checking if STRIPE subscriptions are valid
        
    echo '</div>';
  } // end cronAction




} // end LocationController class
