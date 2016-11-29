<?php
    namespace Vokuro\Controllers;

    use Vokuro\Models\Agency;
    use Vokuro\Models\Location;
    use Vokuro\Models\LocationReviewSite;
    use Vokuro\Models\Review;
    use Vokuro\Models\ReviewInvite;
    use Vokuro\Models\ReviewsMonthly;
    use Vokuro\Models\SMSBroadcast;
    use Vokuro\Models\Users;


    /**
     * Display the default index page.
     */
    class ReviewsController extends ControllerBase {
        public function initialize() {
            $logged_in = is_array($this->auth->getIdentity());
            if ($logged_in) {
                if (isset($_POST['locationselect'])) {
                    $this->auth->setLocation($_POST['locationselect']);
                }
                $this->view->setVar('logged_in', $logged_in);
                $this->view->setTemplateBefore('private');
            } else {
                $this->response->redirect('/session/login?return=/reviews/');
                $this->view->disable();
                return;
            }

            parent::initialize();
        }

        /**
         * Default action.
         */
        public function indexAction() {

            $location_id = $this->session->get('auth-identity')['location_id'];

            $this->tag->setTitle('Get Mobile Reviews | Reviews');
            //get the location and calculate the review total and avg.
            if (isset($this->session->get('auth-identity')['location_id'])) {
                $conditions = "location_id = :location_id:";
                $parameters = array("location_id" => $location_id);
                $loc = Location::findFirst(array($conditions, "bind" => $parameters));
                $this->view->location = $loc;

                //###  START: find review site config info ###
                $facebook_review_count = 0;
                $google_review_count = 0;
                $yelp_review_count = 0;
                $facebook_rating = 0;
                $google_rating = 0;
                $yelp_rating = 0;

                //look for a yelp review configuration
                $conditions = "location_id = :location_id: AND review_site_id = " . \Vokuro\Models\Location::TYPE_YELP;
                $parameters = array("location_id" => $location_id);


                $Obj = LocationReviewSite::findFirst(array($conditions, "bind" => $parameters));
                //start with Yelp reviews, if configured
                if (isset($Obj) && isset($Obj->external_location_id) && $Obj->external_location_id) {
                    $this->view->yelp_id = $Obj->external_id;
                    $yelp_review_count = $Obj->review_count;
                    $yelp_rating = $Obj->rating;
                } else {
                    $this->view->yelp_id = '';
                }

                //look for a facebook review configuration
                $conditions = "location_id = :location_id: AND review_site_id = " . \Vokuro\Models\Location::TYPE_FACEBOOK;
                $parameters = array("location_id" => $location_id);
                $Obj = LocationReviewSite::findFirst(array($conditions, "bind" => $parameters));
                //start with Facebook reviews, if configured
                if (isset($Obj)) {
                    $facebook_review_count = $Obj->review_count;
                    $facebook_rating = $Obj->rating;
                     $this->view->facebook_page_id =$Obj->external_id;
                } else {
                    $this->view->facebook_page_id = '';
                }
                //###  END: find review site config info ###

                //look for a google review configuration
                $conditions = "location_id = :location_id: AND review_site_id = " . \Vokuro\Models\Location::TYPE_GOOGLE;
                $parameters = array("location_id" => $location_id);

                $Obj = LocationReviewSite::findFirst(array($conditions, "bind" => $parameters));
                //start with google reviews, if configured
                if (isset($Obj)) {
                    $this->view->google_place_id = $Obj->external_id;
                    $google_review_count = $Obj->review_count;
                    $google_rating = $Obj->rating;
                } else {
                    $this->view->google_place_id = '';
                }

                //calculate the total reviews
                $total_reviews = $facebook_review_count + $google_review_count + $yelp_review_count;
                $this->view->facebook_review_count = $facebook_review_count;
                $this->view->google_review_count = $google_review_count;

                $this->view->yelp_review_count = $yelp_review_count;
                $this->view->total_reviews = $total_reviews;
                //calculate the average rating
                if ($total_reviews > 0) {
                    $average_rating = (($yelp_rating * $yelp_review_count) + ($google_rating * $google_review_count) + ($facebook_rating * $facebook_review_count)) / $total_reviews;
                } else {
                    $average_rating = 0;
                }
                $this->view->yelp_rating = $yelp_rating;
                $this->view->google_rating = $google_rating;
                $this->view->facebook_rating = $facebook_rating;
                $this->view->average_rating = $average_rating;


                $negative_total = ReviewInvite::count(
                    array(
                        "column" => "review_invite_id",
                        "conditions" => "location_id = " . $this->session->get('auth-identity')['location_id'] . " AND recommend = 'N' AND sms_broadcast_id IS NULL",
                    )
                );

                $this->view->negative_total = $negative_total;

                $conditions = "agency_id = :agency_id:";
                $parameters = array("agency_id" => $loc->agency_id);
                $agency = Agency::findFirst(array($conditions, "bind" => $parameters));
                if ($loc) {
                    $this->view->review_goal = $loc->review_goal;
                }

                $month_review = ReviewInvite::count(
                    array(
                        "column" => "review_invite_id",
                        "conditions" => "location_id = " . $this->session->get('auth-identity')['location_id'] . " AND MONTH(date_sent) = MONTH(NOW()) AND YEAR(date_sent) = YEAR(NOW())",
                    )
                );
                $this->view->month_review = $month_review;
                $percent_done = false;
                if ($month_review >= $loc->review_goal) {
                    $percent_done = 100;
                } else {
                    $percent_done = ($month_review / $loc->review_goal) * 100;
                }
                $this->view->percent_done = $percent_done;

                $review_report = Review::find(
                    array(
                        "conditions" => "location_id = " . $this->session->get('auth-identity')['location_id'],
                        //"limit" => 30,
                        "order" => "time_created DESC"
                    )
                );
               // echo '<pre>';print_r($review_report);exit;
                $this->view->review_report = $review_report;
                
                //get a list of all review invites for this location
                $invitelist = ReviewInvite::getReviewInvitesByLocation($this->session->get('auth-identity')['location_id']);
                $this->view->invitelist = $invitelist;
                $this->getSMSReport();

                //lets grab the count of reviews we have in the database
                $this->view->reviews_five = Review::count(array("column" => "review_id", "conditions" => "location_id = " . $this->session->get('auth-identity')['location_id'] . " AND rating > 4 "));
                $this->view->reviews_four = Review::count(array("column" => "review_id", "conditions" => "location_id = " . $this->session->get('auth-identity')['location_id'] . " AND rating > 3 AND rating <= 4 "));
                $this->view->reviews_three = Review::count(array("column" => "review_id", "conditions" => "location_id = " . $this->session->get('auth-identity')['location_id'] . " AND rating > 2 AND rating <= 3 "));
                $this->view->reviews_two = Review::count(array("column" => "review_id", "conditions" => "location_id = " . $this->session->get('auth-identity')['location_id'] . " AND rating > 1 AND rating <= 2 "));
                $this->view->reviews_one = Review::count(array("column" => "review_id", "conditions" => "location_id = " . $this->session->get('auth-identity')['location_id'] . " AND rating <= 1 "));


            }
        }


        /**
         * This sends an SMS braodcast message
         */
        public function sms_broadcastAction() {
            

            $this->tag->setTitle('Get Mobile Reviews | Reviews | SMS Broadcast');

            $twilio_api_key = "";
            $twilio_auth_token = "";
            $twilio_auth_messaging_sid = "";
            $twilio_from_phone = "";

            //get the user id
            $identity = $this->auth->getIdentity();
            // If there is no identity available the user is redirected to index/index
            if (!is_array($identity)) {
                $this->response->redirect('/session/login?return=/reviews/sms_broadcast');
                $this->view->disable();
                return;
            }
            // Query binding parameters with string placeholders
            $conditions = "id = :id:";
            $parameters = array("id" => $identity['id']);
            $userObj = Users::findFirst(array($conditions, "bind" => $parameters));
            //print_r($userObj);

            //find the agency
            $conditions = "agency_id = :agency_id:";
            //echo $userObj->agency_id;
            //exit;

            $parameters = array("agency_id" => $userObj->agency_id);
            $agency = Agency::findFirst(array($conditions, "bind" => $parameters));
            
            if ($agency) {
                $this->view->agency = $agency;


                if(isset($agency->twilio_api_key) && $agency->twilio_api_key != "" && isset($agency->twilio_auth_token) && $agency->twilio_auth_token != "" && isset($agency->twilio_auth_messaging_sid) && $agency->twilio_auth_messaging_sid != "" && isset($agency->twilio_from_phone) && $agency->twilio_from_phone != "") {

                    $twilio_api_key = $agency->twilio_api_key;
                    $twilio_auth_token = $agency->twilio_api_key;
                    $twilio_auth_messaging_sid = $agency->twilio_auth_messaging_sid;
                    $twilio_from_phone = $agency->twilio_from_phone;


                }
                else if(isset($agency->parent_id)) {


                    $parameters1 = array("agency_id" => $agency->parent_id);
                    $agency1 = Agency::findFirst(array($conditions, "bind" => $parameters1));

                    /*echo "API Key: ".$agency1->twilio_api_key."<br>";
                    echo "Auth Token: ".$agency1->twilio_auth_token."<br>";
                    echo "Messaging SID: ".$agency1->twilio_auth_messaging_sid."<br>";
                    echo "Twilio Phone: ".$agency1->twilio_from_phone."<br>";*/

                    if(isset($agency1->twilio_api_key) && $agency1->twilio_api_key != "" && isset($agency1->twilio_auth_token) && $agency1->twilio_auth_token != "" && isset($agency1->twilio_auth_messaging_sid) && $agency1->twilio_auth_messaging_sid != "" && isset($agency1->twilio_from_phone) && $agency1->twilio_from_phone != "") {

                        $twilio_api_key = $agency1->twilio_api_key;
                        $twilio_auth_token = $agency1->twilio_api_key;
                        $twilio_auth_messaging_sid = $agency1->twilio_auth_messaging_sid;
                        $twilio_from_phone = $agency1->twilio_from_phone;

                    }
                   

                }
              

            }

            
            $conditions = "location_id = :location_id:";
            $parameters = array("location_id" => $this->session->get('auth-identity')['location_id']);
            $loc = Location::findFirst(array($conditions, "bind" => $parameters));
            $this->view->location = $loc;

            //set locations user has access to
            $this->view->locations = $this->auth->getLocationList($userObj);

            if (!empty($_POST)) {
                $this->view->invitelist = ReviewInvite::findCustomers($userObj->agency_id);
                //echo '<pre>$allReviewDetails:'.print_r($this->view->invitelist,true).'</pre>';

                if (isset($_POST['formposttype']) && $_POST['formposttype'] == 'test') {
                    //find the location
                    $conditions = "location_id = :location_id:";
                    $parameters = array("location_id" => $this->session->get('auth-identity')['location_id']);
                    $loc = Location::findFirst(array($conditions, "bind" => $parameters));

                    //else we have a phone number, so send the message
                    $message = $_POST['SMS_message'].'  Reply stop to be removed';
                    //replace out the variables
                    $message = str_replace("{location-name}", $loc->name, $message);
                    $message = str_replace("{name}", $_POST['name'], $message);
                    $message = str_replace("{link}", $this->googleShortenURL($_POST['link']), $message);

                    /*echo "API Key: ".$twilio_api_key."<br>";
                    echo "Auth Token: ".$twilio_auth_token."<br>";
                    echo "Messaging SID: ".$twilio_auth_messaging_sid."<br>";
                    echo "Twilio Phone: ".$twilio_from_phone."<br>";
                    exit;*/




                    if ($this->SendSMS($this->formatTwilioPhone($_POST['phone']), $message, $twilio_api_key, $twilio_auth_token, $twilio_auth_messaging_sid, $twilio_from_phone)) {
                        $this->flash->success("The SMS was sent successfully to: " . $_POST['phone']);
                    }
                } else if (isset($_POST['formposttype']) && $_POST['formposttype'] == 'send') {

                    //check if the user wants to send a message
                    if (!empty($_POST['review_invite_ids'])) {
                        //we have messages to send, so lets first create an SMS Broadcast record
                        $smsb = new SMSBroadcast();
                        $smsb->assign(array(
                            'api_key' => $this->GUID(),
                            'sms_message' => $_POST['SMS_message'].'   Reply stop to be removed',
                            'date_sent' => date('Y-m-d H:i:s'),
                            'link' => $_POST['link'],
                            'sent_by_user_id' => $identity['id'],
                            'agency_id' => $userObj->agency_id
                        ));
                        $smsb->save();

                        foreach ($_POST['review_invite_ids'] as $id) {
                            //get the Review Invite
                            $conditions = "review_invite_id = :review_invite_id:";
                            $parameters = array("review_invite_id" => $id);
                            $invite = ReviewInvite::findFirst(array($conditions, "bind" => $parameters));

                            //echo '<pre>$id:'.$id.':$invite->phone:'.$invite->phone.':</pre>';

                            //find the location
                            $conditions = "location_id = :location_id:";
                            $parameters = array("location_id" => $invite->location_id);
                            $loc = Location::findFirst(array($conditions, "bind" => $parameters));

                            //else we have a phone number, so send the message
                            $message = $_POST['SMS_message'].'  Reply stop to be removed';
                            //replace out the variables
                            $message = str_replace("{location-name}", $loc->name, $message);
                            $message = str_replace("{name}", $invite->name, $message);
                            $link = '';
                            if (isset($_POST['link']) && $_POST['link'] != '') {
                                $guid = $this->GUID();
                                $link = 'http://' . $_SERVER['HTTP_HOST'] . '/review/link?a=' . $guid;
                                $link = $this->googleShortenURL($link);
                            } else {
                                $guid = $invite->api_key;
                                $link = $this->googleShortenURL('http://' . $_SERVER['HTTP_HOST'] . '/review/?a=' . $guid);
                            }
                            $message = str_replace("{link}", $link, $message);


                            //save the message to the database before sending the message
                            $invite2 = new ReviewInvite();
                            $invite2->assign(array(
                                'name' => $invite->name,
                                'location_id' => $invite->location_id,
                                'phone' => $invite->phone,
                                'api_key' => $guid,
                                'sms_message' => $message,
                                'date_sent' => date('Y-m-d H:i:s'),
                                'link' => $_POST['link'],
                                'sms_broadcast_id' => $smsb->sms_broadcast_id
                            ));
                            $invite2->save();

                            /*echo "API Key: ".$twilio_api_key."<br>";
                            echo "Auth Token: ".$twilio_auth_token."<br>";
                            echo "Messaging SID: ".$twilio_auth_messaging_sid."<br>";
                            echo "Twilio Phone: ".$twilio_from_phone."<br>";
                            exit;*/


                            //The message is saved, so send the SMS message now
                            if ($this->SendSMS($this->formatTwilioPhone($invite->phone), $message, $twilio_api_key, $twilio_auth_token, $twilio_auth_messaging_sid, $twilio_from_phone)) {
                                $this->flash->success("The SMS was sent successfully to: " . $invite->phone);
                            }

                        }
                    } //end checking for formposttype
                }
            }
            $this->getSMSReport();
        }


        /**
         * Sent message report
         */
        public function sent_messageAction() {
            //get the user id
            $identity = $this->auth->getIdentity();
            // If there is no identity available the user is redirected to index/index
            if (!is_array($identity)) {
                $this->response->redirect('/session/login?return=/reviews/sent_message');
                $this->view->disable();
                return;
            }
            // Query binding parameters with string placeholders
            $conditions = "id = :id:";
            $parameters = array("id" => $identity['id']);
            $userObj = Users::findFirst(array($conditions, "bind" => $parameters));
            //echo '<pre>$userObj:'.print_r($userObj->agency_id,true).'</pre>';

            $this->view->invitelist = SMSBroadcast::getReport($userObj->agency_id);
            $this->getSMSReport();
        }


        /**
         * Sent message report
         */
        public function sent_message_viewAction($id = 0) {
            $this->checkIntegerOrThrowException($id);

            //get the user id
            $identity = $this->auth->getIdentity();
            // If there is no identity available the user is redirected to index/index
            if (!is_array($identity)) {
                $this->response->redirect('/session/login?return=/reviews/sent_message');
                $this->view->disable();
                return;
            }
            // Query binding parameters with string placeholders
            $conditions = "id = :id:";
            $parameters = array("id" => $identity['id']);
            $userObj = Users::findFirst(array($conditions, "bind" => $parameters));

            $conditions = "sms_broadcast_id = :sms_broadcast_id: AND agency_id = :agency_id:";
            $parameters = array("sms_broadcast_id" => $id, "agency_id" => $userObj->agency_id);
            $sms_broadcast = SMSBroadcast::findFirst(array($conditions, "bind" => $parameters));
            $this->view->sms_broadcast = $sms_broadcast;

            $conditions = "sms_broadcast_id = :sms_broadcast_id:";
            $parameters = array("sms_broadcast_id" => $sms_broadcast->sms_broadcast_id);
            $this->view->invitelist = ReviewInvite::find(array($conditions, "bind" => $parameters));

            $this->getSMSReport();
        }


        public function resolvedAction($id = 0) {
            $conditions = "review_invite_id = :review_invite_id:";
            $parameters = array("review_invite_id" => $id);
            $Obj = ReviewInvite::findFirst(array($conditions, "bind" => $parameters));

            $Obj->is_resolved = 0;
            $Obj->save();

            $this->view->disable();
            echo 'true';
        }

        public function unresolvedAction($id = 0) {
            $conditions = "review_invite_id = :review_invite_id:";
            $parameters = array("review_invite_id" => $id);
            $Obj = ReviewInvite::findFirst(array($conditions, "bind" => $parameters));

            $Obj->is_resolved = 1;
            $Obj->save();

            $this->view->disable();
            echo 'true';
        }


        


    }
