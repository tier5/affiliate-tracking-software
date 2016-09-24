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

        $this->tag->setTitle('Review Velocity | Dashboard');
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
                    $this->response->redirect('/agency');
                    return;
                }
            }

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
                    $this->view->LogoPath = !empty($_GET['logo_path']) ? '/img/agency_logos/'.$_GET['logo_path'] : '';
                    $this->view->CleanUrl = true;
                } else { // Loaded from DB for subdomain
                    $this->view->Name = !empty($agency->name) ? $agency->name : (!empty($_SESSION['demo_name']) ? $_SESSION['demo_name'] : 'Agency');
                    $this->view->Phone = !empty($agency->phone) ? $agency->phone : '(888) 555-1212';
                    $this->view->PrimaryColor = !empty($agency->main_color) ? $agency->main_color : '#2a3644';
                    $this->view->SecondaryColor = !empty($agency->secondary_color) ? $agency->secondary_color : '#65CE4D';
                    $this->view->LogoPath = !empty($agency->logo_path) ? '/img/agency_logos/'.$agency->logo_path : '';
                }

                $this->view->pick('agencysignup/sales');
                return;

            } else { // Normal index page, not loading from subdomain
                $this->response->redirect('/session/login');
                $this->view->disable();
                return;
            }
        }
        //get the location and calculate the review total and avg.
        if ($identity['location_id'] > 0) {
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
            $this->view->location_id = $identity['location_id'];



            //###  START: find review site config info ###
            $facebook_review_count = 0;
            $google_review_count = 0;
            $yelp_review_count = 0;
            $facebook_rating = 0;
            $google_rating = 0;
            $yelp_rating = 0;

            $original_facebook_review_count = 0;
            $original_google_review_count = 0;
            $original_yelp_review_count = 0;
            $original_facebook_rating = 0;
            $original_google_rating = 0;
            $original_yelp_rating = 0;

            //look for a yelp review configuration
            $conditions = "location_id = :location_id: AND review_site_id =  2";
            $parameters = array("location_id" => $this->session->get('auth-identity')['location_id']);
            $Obj = LocationReviewSite::findFirst(array($conditions, "bind" => $parameters));
            //start with Yelp reviews, if configured
            if (isset($Obj) && isset($Obj->external_id) && $Obj->external_id) {
                $this->view->yelp_id = $Obj->external_id;
                $yelp_review_count = $Obj->review_count;
                $yelp_rating = $Obj->rating;
                $original_yelp_review_count = $Obj->original_review_count;
                $original_yelp_rating = $Obj->original_rating;
            } else {
                $this->view->yelp_id = '';
            }

            //look for a google review configuration
            $conditions = "location_id = :location_id: AND review_site_id =  3";
            $parameters = array("location_id" => $this->session->get('auth-identity')['location_id']);
            $Obj = LocationReviewSite::findFirst(array($conditions, "bind" => $parameters));
            //start with google reviews, if configured
            if (isset($Obj) && isset($Obj->external_id) && $Obj->external_id) {
                $this->view->google_place_id = $Obj->external_id;
                $google_review_count = $Obj->review_count;
                $google_rating = $Obj->rating;
                $original_google_review_count = $Obj->original_review_count;
                $original_google_rating = $Obj->original_rating;
            } else {
                $this->view->google_place_id = '';
            }

            //look for a facebook review configuration
            $conditions = "location_id = :location_id: AND review_site_id =  1";
            $parameters = array("location_id" => $this->session->get('auth-identity')['location_id']);
            $Obj = LocationReviewSite::findFirst(array($conditions, "bind" => $parameters));
            //start with Facebook reviews, if configured
            if (isset($Obj) && isset($Obj->external_id) && $Obj->external_id) {
                $this->view->facebook_page_id = $Obj->external_id;
                $facebook_review_count = $Obj->review_count;
                $facebook_rating = $Obj->rating;
                $original_facebook_review_count = $Obj->original_review_count;
                $original_facebook_rating = $Obj->original_rating;
            } else {
                $this->view->facebook_page_id = '';
            }
            //###  END: find review site config info ###
            //calculate the total reviews
            $total_reviews = $facebook_review_count + $google_review_count + $yelp_review_count;
            $original_total_reviews = $original_facebook_review_count + $original_google_review_count + $original_yelp_review_count;
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
                                "conditions" => "location_id = " . $this->session->get('auth-identity')['location_id'] . " AND recommend = 'N' AND sms_broadcast_id IS NULL ",
                            //"group"  => "location_id",
                            )
            );
//echo '<pre>$negative_total:'.print_r($negative_total,true).'</pre>';
            $this->view->negative_total = $negative_total;

            $positive_total = ReviewInvite::count(
                            array(
                                "column" => "review_invite_id",
                                "conditions" => "location_id = " . $this->session->get('auth-identity')['location_id'] . " AND recommend = 'Y' AND sms_broadcast_id IS NULL ",
                            )
            );
            $this->view->positive_total = $positive_total;

            //calculate Revenue Retained
            //look in settings for the "Lifetime Value of the Customer"
            $conditions = "agency_id = :agency_id:";
            $parameters = array("agency_id" => $loc->agency_id);
            $agency = Agency::findFirst(array($conditions, "bind" => $parameters));
            if ($agency) {
                $this->view->revenue_retained = ($positive_total * $loc->lifetime_value_customer);
                $this->view->agency = $agency;
            }


            $this->getSMSReport();



            //find the employee conversion report type
            $conversion_report_type = 'this_month'; //default this month
            if (isset($_GET['crt'])) {
                if ($_GET['crt'] == 2)
                    $conversion_report_type = 'last_month';
                if ($_GET['crt'] == 3)
                    $conversion_report_type = 'all_time';
            }
            $this->view->conversion_report_type = $conversion_report_type;

            //default this month
            $now = new \DateTime('now');
            $start_time = $now->format('Y') . '-' . $now->format('m') . '-01';
            $end_time = date("Y-m-d 23:59:59", strtotime("last day of this month"));

            //get the employee conversion reports
            $this->view->employee_conversion_report = Users::getEmployeeConversionReport($loc->agency_id, $start_time, $end_time, $this->session->get('auth-identity')['location_id'], 'DESC');

            //we need to find the most recent reviews
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



            //Last month!
            $start_time = date("Y-m-d", strtotime("first day of previous month"));
            $end_time = date("Y-m-d 23:59:59", strtotime("last day of previous month"));
            $sms_sent_last_month = ReviewInvite::count(
                            array(
                                "column" => "review_invite_id",
                                "conditions" => "date_sent >= '" . $start_time . "' AND date_sent <= '" . $end_time . "' AND location_id = " . $this->session->get('auth-identity')['location_id'] . " AND sms_broadcast_id IS NULL AND sms_broadcast_id IS NULL ",
                            )
            );
            $this->view->sms_sent_last_month = $sms_sent_last_month;

            //This month!
            $start_time = date("Y-m-d", strtotime("first day of this month"));
            $end_time = date("Y-m-d 23:59:59", strtotime("last day of this month"));
            $sms_sent_this_month = ReviewInvite::count(
                            array(
                                "column" => "review_invite_id",
                                "conditions" => "date_sent >= '" . $start_time . "' AND date_sent <= '" . $end_time . "' AND location_id = " . $this->session->get('auth-identity')['location_id'] . " AND sms_broadcast_id IS NULL ",
                            )
            );
            $this->view->sms_sent_this_month = $sms_sent_this_month;


            //Reviews
            //Total New Reviews (overall, last month, this month, monthly growth)
            $this->view->total_prev_reviews = $original_total_reviews;
            $this->view->total_reviews_location = $total_reviews;
            $this->view->total_reviews_location = $this->view->total_reviews_location - $this->view->total_prev_reviews;


            //Last month!
            $this->view->num_reviews_last_month = ReviewsMonthly::sum(
                            array(
                                "column" => "COALESCE(facebook_review_count, 0) + COALESCE(google_review_count, 0) + COALESCE(yelp_review_count, 0)",
                                "conditions" => "month = " . date("m", strtotime("first day of previous month")) . " AND year = '" . date("Y", strtotime("first day of previous month")) . "' AND location_id = " . $this->session->get('auth-identity')['location_id'],
                            )
            );
            $this->view->num_reviews_two_months_ago = ReviewsMonthly::sum(
                            array(
                                "column" => "COALESCE(facebook_review_count, 0) + COALESCE(google_review_count, 0) + COALESCE(yelp_review_count, 0)",
                                "conditions" => "month = " . date("m", strtotime("-2 months", time())) . " AND year = '" . date("Y", strtotime("-2 months", time())) . "' AND location_id = " . $this->session->get('auth-identity')['location_id'],
                            )
            );
            $this->view->total_reviews_last_month = $this->view->num_reviews_last_month - $this->view->num_reviews_two_months_ago;

            //This month!
            $this->view->num_reviews_this_month = ReviewsMonthly::sum(
                            array(
                                "column" => "COALESCE(facebook_review_count, 0) + COALESCE(google_review_count, 0) + COALESCE(yelp_review_count, 0)",
                                "conditions" => "month = " . date("m", strtotime("first day of this month")) . " AND year = '" . date("Y", strtotime("first day of this month")) . "' AND location_id = " . $this->session->get('auth-identity')['location_id'],
                            )
            );
            //echo '<p>num_reviews_this_month:'.$this->view->num_reviews_this_month.':total_reviews_last_month:'.$this->view->total_reviews_last_month.'</p>';
            $this->view->total_reviews_this_month = $this->view->num_reviews_this_month - $this->view->total_reviews_last_month;

            //set the agency SMS limit
            $this->view->review_goal = $loc->review_goal;
            //calculate how many sms messages we need to send to meet this goal.
            //$percent_needed = ($sms_sent_last_month>0?($this->view->total_reviews_last_month / $sms_sent_last_month)*100:0);
            //if ($percent_needed == 0)
            $percent_needed = 10;
            $this->view->percent_needed = $percent_needed;
            //echo '<p>$sms_sent_last_month:'.$sms_sent_last_month.':total_reviews_last_month:'.$this->view->total_reviews_last_month.'</p>';
            //echo '<p>percent_needed:'.$percent_needed.':review_goal:'.$loc->review_goal.'</p>';
            $this->view->total_sms_needed = round($loc->review_goal / ($percent_needed / 100));

            $this->view->new_reviews = ReviewsMonthly::newReviewReport($this->session->get('auth-identity')['location_id']);
//echo '<pre>new_reviews:'.print_r($this->view->new_reviews,true).'</pre>';
            //Get the sharing code
            $this->getShareInfo($agency);
            //end getting the sharing code
            //###  START: find review site config info ###
            //look for a yelp review configuration
            $conditions = "location_id = :location_id: AND review_site_id =  2";
            $parameters = array("location_id" => $this->session->get('auth-identity')['location_id']);
            $Obj = LocationReviewSite::findFirst(array($conditions, "bind" => $parameters));
            //start with Yelp reviews, if configured
            if (isset($Obj) && isset($Obj->external_id) && $Obj->external_id) {
                $this->view->yelp_id = $Obj->external_id;
            } else {
                $this->view->yelp_id = '';
            }

            //look for a google review configuration
            $conditions = "location_id = :location_id: AND review_site_id =  3";
            $parameters = array("location_id" => $this->session->get('auth-identity')['location_id']);
            $Obj = LocationReviewSite::findFirst(array($conditions, "bind" => $parameters));
            //start with google reviews, if configured
            if (isset($Obj) && isset($Obj->external_id) && $Obj->external_id) {
                $this->view->google_place_id = $Obj->external_id;
            } else {
                $this->view->google_place_id = '';
            }

            //look for a facebook review configuration
            $conditions = "location_id = :location_id: AND review_site_id =  1";
            $parameters = array("location_id" => $this->session->get('auth-identity')['location_id']);
            $Obj = LocationReviewSite::findFirst(array($conditions, "bind" => $parameters));
            //start with Facebook reviews, if configured
            if (isset($Obj) && isset($Obj->external_id) && $Obj->external_id) {
                $this->view->facebook_page_id = $Obj->external_id;
            } else {
                $this->view->facebook_page_id = '';
            }
            //###  END: find review site config info ###
        }

        //$googleScan = new GoogleScanning();
        //$google_reviews = $googleScan->getLRD('15803962018122969779');
    }

}
