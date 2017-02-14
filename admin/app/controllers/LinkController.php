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

    public function initialize()
    {
        $this->tag->setTitle('Get Mobile Reviews | Link');

        $path_to_admin = realpath(__DIR__ . '/../../');
        include_once $path_to_admin . '/app/library/Google/mybusiness/Mybusiness.php';
        define('APPLICATION_NAME', 'User Query - Google My Business API');
        define('CLIENT_SECRET_PATH', $path_to_admin . '/app/models/client_secrets.json');

        parent::initialize();
    }



    public function createlinkAction($uid)
    {
        $getcode = base64_decode($uid);
        $getarray = explode('-', $getcode);
        $id = $getarray[0];
        
        $conditions_user = "id = :id:";
        $parameters_user = array("id" => $id);

        $userinfo = Users::findFirst(
            array($conditions_user, "bind" => $parameters_user)
        );

        if (empty($userinfo)) {
            echo 'sorry this page does not exists';
            exit;
        }

        $conditions = "user_id = :user_id:";
        $parameters = array("user_id" => $id);
        $userObj = UsersLocation::find(
            array($conditions, "bind" => $parameters)
        );
        
        $make_location_array = array();

        if (!empty($userObj)) {
            foreach ($userObj as $obj) {
                $conditions1 = "location_id = :location_id:";
                $parameters1 = array("location_id" => $obj->location_id);
                $userObj1 = Location::findFirst(array($conditions1, "bind" => $parameters1)); 
                $make_location_array[$obj->location_id] = $userObj1->name;
            }
        }

        $agency_id = $userinfo->agency_id;
        $this->view->userlocations = $make_location_array;
        $this->view->agency = $agency_id;
        $this->view->user_id = $id;
        $this->view->userID = $uid;
        $this->view->render('users', 'sendreviewlink');
        $this->view->disable();

        return;
    }

    public function send_review_invite_employeeAction($uid = null)
    {
        // If there is no identity available the user is redirected to index/index

        /*** get post value ***/

        if ($this->request->isPost()) {
            $conditions_user = "agency_id = :agency_id:";
            $parameters_user = array("agency_id" => $_POST['agency_id']);

            $agencyLocationInfo = Location::findFirst(
                array($conditions_user, "bind" => $parameters_user)
            );

            $agency_location_id = $agencyLocationInfo->location_id;
            $agency_location_name = $agencyLocationInfo->name;

            $AgencyID = $_POST['agency_id'];
            $objAgency = Agency::findFirst("agency_id = {$AgencyID}");
            
            // Are we a business?
            if ($objAgency->parent_id > 0) {
                // Return parent's keys.
                $objParentAgency = \Vokuro\Models\Agency::findFirst(
                    "agency_id = " . $objAgency->parent_id
                );

                $TwilioToken = $objParentAgency->twilio_auth_token;
                // We use the businesses' from number if it exists, otherwise use the agency's.
                $TwilioFrom = $objAgency->twilio_from_phone ?: $objParentAgency->twilio_from_phone;
                $TwilioAPI = $objParentAgency->twilio_api_key;
            } else if ($objAgency->parent_id == \Vokuro\Models\Agency::BUSINESS_UNDER_RV || $IsAdmin) {
                // Business under RV.  Return default from config.

                $TwilioToken = $this->config->twilio->twilio_auth_token;
                $TwilioFrom = $this->config->twilio->twilio_from_phone;
                $TwilioAPI = $this->config->twilio->twilio_api_key;
            }

            $twilio_api_key = $TwilioAPI;
            $twilio_auth_token = $TwilioToken;

            $twilio_from_phone = $TwilioFrom;

            // the user wants to send an SMS, so first save it in the database
            if (!$_POST['phone'] || $_POST['phone'] == '') {
                $this->view->disable();
                echo 'Please enter a Phone number.';
                return;
            } else {
                if ($_POST['location_id'] == '') {
                    $location_name = $agency_location_name;
                    $location_id = $agency_location_id;
                } else {
                    $location_name = $_POST['location_name'];
                    $location_id = $_POST['location_id'];
                }

                // else we have a phone number, so send the message

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
                $total_sms_sent=$rs->count();

                $objSubscriptionManager = new \Vokuro\Services\SubscriptionManager();

                if ($objAgency->parent_id == \Vokuro\Models\Agency::BUSINESS_UNDER_RV 
                    || $objAgency->parent_id > 0) {
                    $MaxSMS = $objSubscriptionManager->GetMaxSMS($objAgency->agency_id, $location_id);
                } else {
                    $MaxSMS = 0;
                }

                $NonViralSMS = $MaxSMS;
                $ViralSMS = $objSubscriptionManager->GetViralSMSCount($objAgency->agency_id);
                $MaxSMS += $ViralSMS;

                if ($total_sms_sent < $MaxSMS) {
                    $name = $_POST['name'];
                    $message = $_POST['SMS_message'];

                    //replace out the variables
                    $message = str_replace("{location-name}", $location_name, $message);
                    $message = str_replace("{name}", $name, $message);
                    $guid = $this->GUID();
                    $message = str_replace(
                        "{link}",
                        $this->googleShortenURL('http://' . $_SERVER['HTTP_HOST'] . '/review/?a=' . $guid),
                        $message
                    );

                    $message = $message.'  Reply stop to be removed';

                    $phone = $_POST['phone'];
                    $uid = $_POST['userID'];

                    //save the message to the database before sending the message
                    $error = "";
                    $er_msg = '';
                    $insert_id_array = array();
                    $nolengthmessage = strlen($message);
                    $no = ceil($nolengthmessage/153);

                    if ($no != 0) {
                        for ($i = 1;$i <= $no;$i++) {
                            $invite = new ReviewInvite();
                            $invite->assign(array(
                                'name' => $name,
                                'location_id' => $location_id,
                                'phone' => $phone,
                                //TODO: Added google URL shortener here
                                'api_key' => $guid,
                                'sms_message' => $message,
                                /*'date_sent' => date('Y-m-d H:i:s'),*/
                                'date_last_sent' => date('Y-m-d H:i:s'),
                                'sent_by_user_id' => $_POST['user_id']
                            ));

                            $invite->save();

                            array_push($insert_id_array,$invite->review_invite_id);
                            
                            if (!$invite->save()) {
                                $error = 1;
                                $er_msg = $invite->getMessages();
                            }
                        }
                    }

                    if ($error == 1) {
                        $this->view->disable();
                        echo $er_msg;
                        return;
                    } else {

                        $sentSMS = $this->SendSMS(
                            $phone,
                            $message,
                            $twilio_api_key,
                            $twilio_auth_token,
                            $twilio_from_phone
                        );

                        if ($sentSMS) {
                            for($i = 0;$i < count($insert_id_array);$i++) {
                                $last_insert_id = $insert_id_array[$i];
                                
                                $update_review = ReviewInvite::FindFirst(
                                    'review_invite_id =' . $last_insert_id
                                );
                                
                                $update_review->date_sent = date('Y-m-d H:i:s');
                                $update_review->update();
                            }

                            $this->flashSession->success(
                                "The SMS was sent successfully to: " . $phone.".This page will automatically refresh in 5 seconds." . $message
                            );
                            
                            $this->view->disable();
                            return $this->response->redirect('link/send_review_invite_employee/'.$uid);
                        }
                    }
                } else {
                    $this->flashSession->error(
                        "Sorry!! this message will not be sent as You have exceeded the total sms allowed for your business to sent."
                    );

                    $this->view->disable();

                    return $this->response->redirect('link/send_review_invite_employee/' . $uid);
                }//
            }
        } else { 
            /*** get post value ***/
            if ($uid) {
                $this->view->linkId = $uid;
            }

            $this->view->render('users', 'reviewmsg');
        }
    }
}