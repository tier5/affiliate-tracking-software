<?php

namespace Vokuro\Controllers;

use Vokuro\Models\Agency;
use Vokuro\Models\Location;
use Vokuro\Models\LocationReviewSite;
use Vokuro\Models\Review;
use Vokuro\Models\ReviewInvite;
use Vokuro\Models\ReviewsMonthly;
use Vokuro\Models\Users;

/**
 * Display the default index page.
 */
class IndexController extends ControllerBase {

    public function initialize() {

        $this->tag->setTitle('Get Mobile Reviews | Dashboard');
        parent::initialize();

        //add needed css
        $this->assets
            ->addCss('/css/subscription.css')
            ->addCss('/assets/global/plugins/card-js/card-js.min.css');

        //add needed js
        $this->assets
            ->addJs('/assets/global/plugins/card-js/card-js.min.js');
    }

    /**
     * Default action. Set the public layout (layouts/private.volt)
     */
    public function indexAction() {
        $this->view->TLDomain = $this->config->application->domain;
        $tUser = $this->auth->getIdentity();
        $logged_in = is_array($tUser);
       
        if ($logged_in) {

            /*
             * TODO: The setup process is currently "baked" into the free signup sequence.
             * There's no time to rewrite it so I will add a check here to see if the user has
             * any locations yet.  If not, we know that this action is being called from
             * the create business sequence.
             */
            $userManager = $this->di->get('userManager');

            $isBusiness = $userManager->isBusiness($this->session);
            $signupPage = $userManager->currentSignupPage($this->session);

            if($isBusiness && $signupPage) {
                $this->response->redirect('/session/signup' . $signupPage);
                return;
            }

            if (isset($_POST['locationselect'])) {
                $this->auth->setLocation($_POST['locationselect']);
            }

            $identity = $this->session->get('auth-identity');
            $this->view->setVar('logged_in', $logged_in);
            $this->view->setTemplateBefore('private');



            if($tUser['is_admin']) {
                $this->view->pick('admindashboard/index');
            } else {
                if(!$isBusiness) {
                   // echo $_SERVER['SERVER_NAME'];exit;
                    //$this->response->redirect('/agency');
                    //return;
            /** agency redirect 23.11.2016**/

            $this->view->setTemplateBefore('public');

                    
            $parts = explode(".", $_SERVER['SERVER_NAME']);
            if(count($parts) >= 2 && $parts[0] != 'www') { // Index loaded from getmobilereviews subdomain
                $subdomain = $parts[0];

                $agency = Agency::findFirst([
                        "custom_domain = :custom_domain:",
                        "bind" => ["custom_domain" => $subdomain]
                    ]);

                // Subdomain must exist
                if(!$agency) {
                    $this->response->setStatusCode(404, "Not Found");
                    echo "<h1>404 Page Not Found</h1>";
                    $this->view->disable();
                    return;
                }

                $this->view->SubDomain = $subdomain;

                if(!empty($_GET['name']) && !empty($_GET['phone'])){ // Loaded from GET for preview page from agency signup process
                    $this->view->Name = $_GET['name'];
                    $this->view->Phone = $_GET['phone'];
                    $this->view->PrimaryColor = '#'.$_GET['primary_color'];
                    $this->view->SecondaryColor = '#'.$_GET['secondary_color'];
                    $this->view->logo_path = !empty($_GET['logo_path']) ? '/img/agency_logos/'.$_GET['logo_path'] : '';
                    $this->view->CleanUrl = true;
                } else { // Loaded from DB for subdomain
                    $this->view->Name = !empty($agency->name) ? $agency->name : (!empty($_SESSION['demo_name']) ? $_SESSION['demo_name'] : 'Agency');
                    $this->view->Phone = !empty($agency->phone) ? $agency->phone : '(888) 555-1212';
                    $this->view->PrimaryColor = !empty($agency->main_color) ? $agency->main_color : '#2a3644';
                    $this->view->SecondaryColor = !empty($agency->secondary_color) ? $agency->secondary_color : '#65CE4D';
                    $this->view->logo_path = !empty($agency->logo_path) ? '/img/agency_logos/'.$agency->logo_path : '';
                }

                $this->view->setTemplateBefore('agencysignup');
                $this->view->pick('agencysignup/sales');
                return;

            }

                else
                {
                    $this->response->redirect('/agency');
                    return;

                }
                    //$this->response->redirect('/agency');
                   // return;
                     /** agency redirect 23.11.2016**/
                }
            }

            $objUser = \Vokuro\Models\Users::findFirst("id = " . $identity['id']);
            $objBusiness = \Vokuro\Models\Agency::findFirst("agency_id = {$objUser->agency_id}");
            $objSubscriptionManager = new \Vokuro\Services\SubscriptionManager();
            $this->view->SubscriptionPlan = $objSubscriptionManager->GetBusinessSubscriptionLevel($objBusiness->agency_id);

        } else {
            // Check for use of whitelabel domain
            // Moved to here from AgencySignupController::salesAction (agencysignup/sales)
            // The best way to fully test this is to use your local hosts file to treat your local reviewvelocity server as getmobilereiews.com


            $parts = explode(".", $_SERVER['SERVER_NAME']);
            if(count($parts) >= 2 && $parts[1] == 'getmobilereviews' && $parts[0] != 'www') { // Index loaded from getmobilereviews subdomain
                $subdomain = $parts[0];

                $agency = Agency::findFirst([
                        "custom_domain = :custom_domain:",
                        "bind" => ["custom_domain" => $subdomain]
                    ]);

                // Subdomain must exist
                if(!$agency) {
                    $this->response->setStatusCode(404, "Not Found");
                    echo "<h1>404 Page Not Found</h1>";
                    $this->view->disable();
                    return;
                }

                $this->view->SubDomain = $subdomain;

                if(!empty($_GET['name']) && !empty($_GET['phone'])){ // Loaded from GET for preview page from agency signup process
                    $this->view->Name = $_GET['name'];
                    $this->view->Phone = $_GET['phone'];
                    $this->view->PrimaryColor = '#'.$_GET['primary_color'];
                    $this->view->SecondaryColor = '#'.$_GET['secondary_color'];
                    $this->view->logo_path = !empty($_GET['logo_path']) ? '/img/agency_logos/'.$_GET['logo_path'] : '';
                    $this->view->CleanUrl = true;
                } else { // Loaded from DB for subdomain
                    $this->view->Name = !empty($agency->name) ? $agency->name : (!empty($_SESSION['demo_name']) ? $_SESSION['demo_name'] : 'Agency');
                    $this->view->Phone = !empty($agency->phone) ? $agency->phone : '(888) 555-1212';
                    $this->view->PrimaryColor = !empty($agency->main_color) ? $agency->main_color : '#2a3644';
                    $this->view->SecondaryColor = !empty($agency->secondary_color) ? $agency->secondary_color : '#65CE4D';
                    $this->view->logo_path = !empty($agency->logo_path) ? '/img/agency_logos/'.$agency->logo_path : '';
                }

                $this->view->pick('agencysignup/sales');
                return;

            } else { // Normal index page, not loading from subdomain
                $this->response->redirect('/session/login');
                $this->view->disable();
                return;
            }
        }

        if ($identity['location_id'] > 0) {
            //echo $identity['location_id'];exit;
            $conditions = "location_id = :location_id:";

            $parameters = array(
                "location_id" => $identity['location_id']
            );

            $loc = Location::findFirst(
                            array(
                                $conditions,
                                "bind" => $parameters)
            );

            $this->view->location = $loc;
            $this->view->location_id = $LocationID = $identity['location_id'];
            $objBusiness = \Vokuro\Models\Agency::findFirst("agency_id = {$loc->agency_id}");

            $dbYelpReviews = \Vokuro\Models\Review::find("location_id = {$LocationID} and rating_type_id = " . \Vokuro\Models\Location::TYPE_YELP);
            $dbFacebookReviews = \Vokuro\Models\Review::find("location_id = {$LocationID} and rating_type_id = " . \Vokuro\Models\Location::TYPE_FACEBOOK);
            $dbGoogleReviews = \Vokuro\Models\Review::find("location_id = {$LocationID} and rating_type_id = " . \Vokuro\Models\Location::TYPE_GOOGLE);

            $YelpSinceCreate = 0;
            $FacebookSinceCreate = 0;
            $GoogleSinceCreate = 0;
            $TotalYelpRating = 0;
            $TotalFacebookRating = 0;
            $TotalGoogleRating = 0;

            $this->view->new_reviews = ReviewsMonthly::newReviewReport($this->session->get('auth-identity')['location_id']);


            foreach($dbYelpReviews as $objYelpReview) {
                if(strtotime($objBusiness->date_created) < strtotime($objYelpReview->time_created))
                    $YelpSinceCreate++;
                $TotalYelpRating += $objYelpReview->rating;
            }

            // Yelp stats work differently since we calculate based on the #s yelp gives us, rather than we import since we can only import 1 yelp review at a time.  Leaving code in for when we solve this problem.
            $objYelpReviewSite = \Vokuro\Models\LocationReviewSite::findFirst("location_id = {$LocationID} and review_site_id = " . \Vokuro\Models\Location::TYPE_YELP);
            $TotalYelpRating = $objYelpReviewSite ? $objYelpReviewSite->rating * $objYelpReviewSite->review_count : 0;


            foreach($dbFacebookReviews as $objFacebookReview) {
                if(strtotime($objBusiness->date_created) < strtotime($objFacebookReview->time_created))
                    $FacebookSinceCreate++;

                $TotalFacebookRating += $objFacebookReview->rating;
            }

            foreach($dbGoogleReviews as $objGoogleReview) {
                if(strtotime($objBusiness->date_created) < strtotime($objGoogleReview->time_created))
                    $GoogleSinceCreate++;
                $TotalGoogleRating += $objGoogleReview->rating;
            }

            $this->view->yelp_review_count = $YelpReviewCount = $objYelpReviewSite ? $objYelpReviewSite->review_count : 0; //count($dbYelpReviews);
            $this->view->facebook_review_count = $FacebookReviewCount = count($dbFacebookReviews);
            $this->view->google_review_count = $GoogleReviewCount = count($dbGoogleReviews);
            //$this->view->total_reviews = $TotalReviews = $FacebookReviewCount + $GoogleReviewCount + $YelpReviewCount;

           $TotalReviews = $FacebookReviewCount + $GoogleReviewCount + $YelpReviewCount;

           /*** 5/12/2016 ***/

             $objFbReviewSite = \Vokuro\Models\LocationReviewSite::findFirst("location_id = {$LocationID} and review_site_id = " . \Vokuro\Models\Location::TYPE_FACEBOOK);

              $objGlReviewSite = \Vokuro\Models\LocationReviewSite::findFirst("location_id = {$LocationID} and review_site_id = " . \Vokuro\Models\Location::TYPE_GOOGLE);
            $this->view->total_reviews  = $objYelpReviewSite->review_count+$objFbReviewSite->review_count+$objGlReviewSite->review_count;


             /*** 5/12/2016 ***/



            $this->view->yelp_rating = $YelpReviewCount > 0 ? $TotalYelpRating / $YelpReviewCount : 0;
            $this->view->facebook_rating = $FacebookReviewCount > 0 ? $TotalFacebookRating / $FacebookReviewCount : 0;
            $this->view->google_rating = $GoogleReviewCount > 0 ? $TotalGoogleRating / $GoogleReviewCount : 0;
            $this->view->average_rating = $AverageRating = $TotalReviews > 0 ? ($TotalYelpRating + $TotalFacebookRating + $TotalGoogleRating ) / $TotalReviews : 0;

            // New Reviews Since Joining Get Mobile Reviews
            $this->view->total_reviews_location = $YelpSinceCreate + $FacebookSinceCreate + $GoogleSinceCreate;

            // New Reviews By Month Graph
            //$this->view->new_reviews = ReviewsMonthly::newReviewReport($this->session->get('auth-identity')['location_id']);

            $negative_total = ReviewInvite::count(
                    array(
                        "column" => "review_invite_id",
                        "conditions" => "location_id = " . $this->session->get('auth-identity')['location_id'] . " AND recommend = 'N' AND sms_broadcast_id IS NULL ",
                    )
            );

            $this->view->negative_total = $negative_total;
            $positive_total = ReviewInvite::count(
                array(
                    "column" => "review_invite_id",
                    "conditions" => "location_id = " . $this->session->get('auth-identity')['location_id'] . " AND recommend = 'Y' AND sms_broadcast_id IS NULL ",
                )
            );
            $this->view->positive_total = $positive_total;

            // Calculate Revenue Retained
            // Look in settings for the "Lifetime Value of the Customer"
            $conditions = "agency_id = :agency_id:";
            $parameters = array("agency_id" => $loc->agency_id);
            $agency = Agency::findFirst(array($conditions, "bind" => $parameters));
            if ($agency) {
                $this->view->revenue_retained = ($positive_total * $loc->lifetime_value_customer);
                $this->view->agency = $agency;
            }

            $this->getSMSReport();

            // Find the employee conversion report type
            $conversion_report_type = 'this_month'; //default this month
            if (isset($_GET['crt'])) {
                if ($_GET['crt'] == 2)
                    $conversion_report_type = 'last_month';
                if ($_GET['crt'] == 3)
                    $conversion_report_type = 'all_time';
            }
            $this->view->conversion_report_type = $conversion_report_type;

            // Default this month
            $now = new \DateTime('now');
            $start_time = $now->format('Y') . '-' . $now->format('m') . '-01';
            $end_time = date("Y-m-d 23:59:59", strtotime("last day of this month"));

            // Get the employee conversion reports
            $this->view->employee_conversion_report = Users::getEmployeeConversionReport($loc->agency_id, $start_time, $end_time, $this->session->get('auth-identity')['location_id'], 'DESC');

            // We need to find the most recent reviews
            $start_time = date("Y-m-d", strtotime("first day of previous month"));
            $end_time = date("Y-m-d 23:59:59", strtotime("last day of previous month"));
            $review_report = Review::find(
                array(
                    "conditions" => "location_id = " . $this->session->get('auth-identity')['location_id'],
                    "limit" => 3,
                    "order" => "time_created DESC"
                )
            );
            $this->view->review_report = $review_report;

            // Last month!
            $start_time = date("Y-m-d", strtotime("first day of previous month"));
            $end_time = date("Y-m-d 23:59:59", strtotime("last day of previous month"));
            $sms_sent_last_month = ReviewInvite::count(
                            array(
                                "column" => "review_invite_id",
                                "conditions" => "date_sent >= '" . $start_time . "' AND date_sent <= '" . $end_time . "' AND location_id = " . $this->session->get('auth-identity')['location_id'] . " AND sms_broadcast_id IS NULL AND sms_broadcast_id IS NULL ",
                            )
            );
            $this->view->sms_sent_last_month = $sms_sent_last_month;

            // This month!
            $start_time = date("Y-m-d", strtotime("first day of this month"));
            $end_time = date("Y-m-d 23:59:59", strtotime("last day of this month"));
            $sms_sent_this_month = ReviewInvite::count(
                            array(
                                "column" => "review_invite_id",
                                "conditions" => "date_sent >= '" . $start_time . "' AND date_sent <= '" . $end_time . "' AND location_id = " . $this->session->get('auth-identity')['location_id'] . " AND sms_broadcast_id IS NULL ",
                            )
            );
            $this->view->sms_sent_this_month = $sms_sent_this_month;

            $this->view->total_reviews_this_month = \Vokuro\Models\Review::count("time_created BETWEEN '{$start_time}' AND '{$end_time}' AND location_id = {$LocationID}");

            $this->view->review_goal = $loc->review_goal;

            $percent_needed = 10;
            $this->view->percent_needed = $percent_needed;

            $this->view->total_sms_needed = round($loc->review_goal / ($percent_needed / 100));


            $this->getShareInfo($agency);

            $conditions = "location_id = :location_id: AND review_site_id = " . \Vokuro\Models\Location::TYPE_YELP;
            $parameters = array("location_id" => $this->session->get('auth-identity')['location_id']);
            $Obj = LocationReviewSite::findFirst(array($conditions, "bind" => $parameters));
            //start with Yelp reviews, if configured
            if (isset($Obj) && isset($Obj->external_id) && $Obj->external_id) {
                $this->view->yelp_id = $Obj->external_id;
            } else {
                $this->view->yelp_id = '';
            }

            //look for a google review configuration
            $conditions = "location_id = :location_id: AND review_site_id = " . \Vokuro\Models\Location::TYPE_GOOGLE;
            $parameters = array("location_id" => $this->session->get('auth-identity')['location_id']);
            $Obj = LocationReviewSite::findFirst(array($conditions, "bind" => $parameters));
            //start with google reviews, if configured
            if (isset($Obj) && isset($Obj->external_id) && $Obj->external_id) {
                $this->view->google_place_id = $Obj->external_id;
            } else {
                $this->view->google_place_id = '';
            }

            //look for a facebook review configuration
            $conditions = "location_id = :location_id: AND review_site_id = " . \Vokuro\Models\Location::TYPE_FACEBOOK;
            $parameters = array("location_id" => $this->session->get('auth-identity')['location_id']);
            $Obj = LocationReviewSite::findFirst(array($conditions, "bind" => $parameters));
            //start with Facebook reviews, if configured
            //echo  $Obj->external_id;exit;
            if (isset($Obj) && isset($Obj->external_id) && $Obj->external_id) {
                $this->view->facebook_page_id = $Obj->external_id;
            } else {
                $this->view->facebook_page_id = '';
            }
            //###  END: find review site config info ###
        }
    }

}
