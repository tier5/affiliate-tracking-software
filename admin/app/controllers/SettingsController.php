<?php
namespace Vokuro\Controllers;

use Phalcon\Image\Adapter\GD;
use Phalcon\Tag;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Vokuro\Forms\AgencyForm;
use Vokuro\Forms\ChangePasswordForm;
use Vokuro\Forms\SettingsForm;
use Vokuro\Models\Agency;
use Vokuro\Models\Location;
use Vokuro\Models\LocationNotifications;
use Vokuro\Models\LocationReviewSite;
use Vokuro\Models\PasswordChanges;
use Vokuro\Models\ReviewInvite;
use Vokuro\Models\ReviewsMonthly;
use Vokuro\Models\ReviewSite;
use Vokuro\Models\SharingCode;
use Vokuro\Models\Users;

/**
 * Vokuro\Controllers\UsersController
 * CRUD to manage users
 */
class SettingsController extends ControllerBase
{

    public function initialize()
    {
      $this->tag->setTitle('Review Velocity | Settings');
      $this->view->setTemplateBefore('private');  
      parent::initialize();
    }


    /**
     * Searches for users
     */
    public function indexAction()
    {
      //get the user id, to find the settings
      $identity = $this->auth->getIdentity();
      // If there is no identity available the user is redirected to index/index
      if (!is_array($identity)) {
        $this->response->redirect('/admin/session/login?return=/admin/settings/');
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
      if (!$agency) {
          $this->flash->error("No settings were found");
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
          $agency->assign(array(
              'review_invite_type_id' => $this->request->getPost('review_invite_type_id', 'int'),
              'review_goal' => $this->request->getPost('review_goal', 'int'),
              'custom_domain' => $this->request->getPost('custom_domain'),
              'lifetime_value_customer' => str_replace("$", "", str_replace(",", "", $this->request->getPost('lifetime_value_customer'))),
              'SMS_message' => $this->request->getPost('SMS_message'),
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
              'locality' => $this->request->getPost('locality', 'striptags'),
              'state_province' => $this->request->getPost('state_province', 'striptags'),
              'postal_code' => $this->request->getPost('postal_code', 'striptags'),
              'country' => $this->request->getPost('country', 'striptags'),
              'phone' => $this->request->getPost('phone', 'striptags'),
          ));
          $file_location = $this->uploadAction($agency->agency_id);
          if ($file_location != '') $agency->logo_path = $file_location;

          //delete all notification users for this agency
          $conditions = "location_id = :location_id:";
          $parameters = array("location_id" => $this->session->get('auth-identity')['location_id']);
          $notificationdelete = LocationNotifications::find(array($conditions, "bind" => $parameters));
          $notificationdelete->delete();

          if(!empty($_POST['users'])) {
            foreach($_POST['users'] as $check) {
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

      // Query binding parameters with string placeholders
      $conditions = "agency_id = :agency_id:";
      $parameters = array("agency_id" => $userObj->agency_id);
      $users = Users::find(array($conditions, "bind" => $parameters));
      $this->view->users = $users;
      
      $conditions = "location_id = :location_id:";
      $parameters = array("location_id" => $this->session->get('auth-identity')['location_id']);
      $this->view->agencynotifications = LocationNotifications::find(array($conditions, "bind" => $parameters));

      $this->view->agency = $agency;
      $this->view->location = $agency;

      $this->view->form = new SettingsForm($agency, array(
          'edit' => true
      ));
      $this->view->agencyform = new AgencyForm($agency, array(
          'edit' => true
      ));




      
      $this->getSMSReport();
            
    }





    


    /**
     * Updates settings for locations
     */
    public function locationAction()
    {
      //get the user id, to find the settings
      $identity = $this->auth->getIdentity();
      // If there is no identity available the user is redirected to index/index
      if (!is_array($identity)) {
        $this->response->redirect('/admin/session/login?return=/admin/settings/location/');
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
      if (!$agency) {
          $this->flash->error("No settings were found");
      }
      
        
      //find the location
      $conditions = "location_id = :location_id:";
      $parameters = array("location_id" => $this->session->get('auth-identity')['location_id']);
      $location = Location::findFirst(array($conditions, "bind" => $parameters));

        
      if ($this->request->isPost()) {
        $form = new SettingsForm($location);
        $agencyform = new AgencyForm($location);
        $form->bind($_POST, $location);
        $agencyform->bind($_POST, $location);

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
          $location->assign(array(
              'review_invite_type_id' => $this->request->getPost('review_invite_type_id', 'int'),
              'review_goal' => $this->request->getPost('review_goal', 'int'),
              'lifetime_value_customer' => str_replace("$", "", str_replace(",", "", $this->request->getPost('lifetime_value_customer'))),
              'SMS_message' => $this->request->getPost('SMS_message'),
              'message_tries' => $this->request->getPost('message_tries'),
              'rating_threshold_star' => $this->request->getPost('rating_threshold_star'),
              'rating_threshold_nps' => $this->request->getPost('rating_threshold_nps'),
              'message_frequency' => $this->request->getPost('message_frequency'),
              'name' => $this->request->getPost('name', 'striptags'),
              'email' => $this->request->getPost('email', 'striptags'),
              'address' => $this->request->getPost('address', 'striptags'),
              'locality' => $this->request->getPost('locality', 'striptags'),
              'state_province' => $this->request->getPost('state_province', 'striptags'),
              'postal_code' => $this->request->getPost('postal_code', 'striptags'),
              'country' => $this->request->getPost('country', 'striptags'),
              'phone' => $this->request->getPost('phone', 'striptags'),
          ));
          $file_location = $this->uploadAction('l'.$location->location_id);
          if ($file_location != '') $location->sms_message_logo_path = $file_location;

          //delete all notification users for this agency
          $conditions = "location_id = :location_id:";
          $parameters = array("location_id" => $this->session->get('auth-identity')['location_id']);
          $notificationdelete = LocationNotifications::find(array($conditions, "bind" => $parameters));
          $notificationdelete->delete();

          if(!empty($_POST['users'])) {
            foreach($_POST['users'] as $check) {
              $agencyInsert = new LocationNotifications();
              $agencyInsert->location_id = $this->session->get('auth-identity')['location_id'];
              $agencyInsert->user_id = $check;
              $agencyInsert->save();
            }
          }

          //save the sort order of the review sites
          if(!empty($_POST['review_order'])) {
            $order = 0;
            $pieces = explode(",", $_POST['review_order']);
            foreach($pieces as $siteid) {
              $order++;
              $conditions = "location_review_site_id = :location_review_site_id:";
              $parameters = array("location_review_site_id" => $siteid);
              $Obj = LocationReviewSite::findFirst(array($conditions, "bind" => $parameters));
              $Obj->sort_order=$order;
              $Obj->save();
            }
          }

          if (!$location->save()) {
            $this->flash->error($location->getMessages());
          } else {
            $this->flash->success("The settings were updated successfully");
            Tag::resetInput();
          }
        } 
      }

      // Query binding parameters with string placeholders
      $conditions = "agency_id = :agency_id:";
      $parameters = array("agency_id" => $userObj->agency_id);
      $users = Users::find(array($conditions, "bind" => $parameters));
      $this->view->users = $users;
      
      $conditions = "location_id = :location_id:";
      $parameters = array("location_id" => $this->session->get('auth-identity')['location_id']);
      $this->view->agencynotifications = LocationNotifications::find(array($conditions, "bind" => $parameters));

      $this->view->agency = $agency;
      $this->view->location = $location;
        
      //find the location review sites
      $conditions = "location_id = :location_id:";
      $parameters = array("location_id" => $this->session->get('auth-identity')['location_id']);
      $review_site_list = LocationReviewSite::find(array($conditions, "bind" => $parameters, "order" => "sort_order ASC"));
      $this->view->review_site_list = $review_site_list;
      
      $this->view->review_sites = ReviewSite::find();

      $this->view->form = new SettingsForm($location, array(
          'edit' => true
      ));
      $this->view->agencyform = new AgencyForm($location, array(
          'edit' => true
      ));




      
      $this->getSMSReport();
      $this->view->pick("settings/index");
    }



  public function siteaddAction($location_id = 0, $review_site_id = 0)
  {
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



  public function onAction($id = 0)
  {
    $conditions = "location_review_site_id = :location_review_site_id:";
    $parameters = array("location_review_site_id" => $id);
    $Obj = LocationReviewSite::findFirst(array($conditions, "bind" => $parameters));
    
    $Obj->is_on = 0;
    $Obj->save();
      
    $this->view->disable();
    echo 'true';
  }

  public function offAction($id = 0)
  {
    $conditions = "location_review_site_id = :location_review_site_id:";
    $parameters = array("location_review_site_id" => $id);
    $Obj = LocationReviewSite::findFirst(array($conditions, "bind" => $parameters));

    $Obj->is_on = 1;
    $Obj->save();

    $this->view->disable();
    echo 'true';
  }

  

  public function notificationAction($id = 0, $fieldname, $value)
  {
    $conditions = "location_id = :location_id: AND user_id = :user_id:";
    $parameters = array("location_id" => $this->session->get('auth-identity')['location_id'], "user_id" => $id);
    $Obj = LocationNotifications::findFirst(array($conditions, "bind" => $parameters));

    if (isset($Obj) && isset($Obj->user_id) && $Obj->user_id == $id) {
      //lets edit the field and save the changes
      if ($fieldname=='ea') $Obj->email_alert = $value;
      if ($fieldname=='sa') $Obj->sms_alert = $value;
      if ($fieldname=='ar') $Obj->all_reviews = $value;
      if ($fieldname=='ir') $Obj->individual_reviews = $value;
      $Obj->save();
    } else {
      //else we need to create a record
      $loc = new LocationNotifications();
      $loc->assign(array(
        'location_id' => $this->session->get('auth-identity')['location_id'],
        'user_id' => $id,
        'email_alert' => 0,
        'sms_alert' => 0,
        'all_reviews' => 0,
        'individual_reviews' => 0,
      ));
      if ($fieldname=='ea') $loc->email_alert = $value;
      if ($fieldname=='sa') $loc->sms_alert = $value;
      if ($fieldname=='ar') $loc->all_reviews = $value;
      if ($fieldname=='ir') $loc->individual_reviews = $value;
      $loc->save();
    }

    $this->view->disable();
    echo 'true';
  }



    public function uploadAction($agencyid)
    {
      // Check if the user has uploaded files
      if ($this->request->hasFiles() == true) {
        //echo '<p>hasFiles() == true!</p>';
        $baseLocation = '/var/www/html/velocity/admin/public/img/upload/';


        // Print the real file names and sizes
        foreach ($this->request->getUploadedFiles() as $file) {
          if ($file->getName() != '') {
            //Move the file into the application
            $filepath = $baseLocation . $agencyid . '-' . $file->getName();
            $file->moveTo($filepath);

            //resize
            $image = new \Phalcon\Image\Adapter\GD($filepath);
            $image->resize(200, 30)->save($filepath);

            //echo '<p>$filepath: '.$filepath.'</p>';
            $filepath = '/admin'.str_replace("/var/www/html/velocity/admin/public", "", $filepath);
            $this->view->logo_setting = $filepath;
            return $filepath;
          }
        }
      } else {
        //echo '<p>hasFiles() == true!</p>';
      }
    }
}
