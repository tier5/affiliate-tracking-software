<?php
namespace Vokuro\Controllers;

use Vokuro\Models\Agency;
use Vokuro\Models\Location;
use Vokuro\Models\LocationReviewSite;
use Vokuro\Models\Review;
use Vokuro\Models\ReviewInvite;
use Vokuro\Models\ReviewsMonthly;
use Vokuro\Models\SharingCode;
use Vokuro\Models\Subscription;
use Vokuro\Models\Users;

/**
 * Display the default index page.
 */
class IndexController extends ControllerBase
{
    public function initialize()
    {
        $this->tag->setTitle('Review Velocity | Dashboard');
        parent::initialize();
    }

    /**
     * Default action. Set the public layout (layouts/private.volt)
     */
    public function indexAction()
    {
      $logged_in = is_array($this->auth->getIdentity());
      if ($logged_in) {
        if (isset($_POST['locationselect'])) {
          $this->auth->setLocation($_POST['locationselect']);
        }

        if (isset($this->session->get('auth-identity')['is_admin']) && $this->session->get('auth-identity')['is_admin'] > 0) {
          $this->response->redirect('/admin/admindashboard/');
        } else
        if (isset($this->session->get('auth-identity')['agencytype']) && $this->session->get('auth-identity')['agencytype'] == 'agency') {
          $this->response->redirect('/admin/agency/');
        }

        $this->view->setVar('logged_in', $logged_in);
        $this->view->setTemplateBefore('private');
      } else {        
        $this->response->redirect('/admin/session/login');
        $this->view->disable();
        return;
      }

      //get the location and calculate the review total and avg.
      if (isset($this->session->get('auth-identity')['location_id']) && $this->session->get('auth-identity')['location_id'] > 0) {
        $conditions = "location_id = :location_id:";
        $parameters = array("location_id" => $this->session->get('auth-identity')['location_id']);
        $loc = Location::findFirst(array($conditions, "bind" => $parameters));
        $this->view->location = $loc;

        

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
                    "column"     => "review_invite_id",
                    "conditions" => "location_id = ".$this->session->get('auth-identity')['location_id']." AND recommend = 'N'",
                    //"group"  => "location_id",
                )
            );
//echo '<pre>$negative_total:'.print_r($negative_total,true).'</pre>';
        $this->view->negative_total = $negative_total;

        $positive_total = ReviewInvite::count(
                array(
                    "column"     => "review_invite_id",
                    "conditions" => "location_id = ".$this->session->get('auth-identity')['location_id']." AND recommend = 'Y'",
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
           if ($_GET['crt'] == 2) $conversion_report_type = 'last_month';
           if ($_GET['crt'] == 3) $conversion_report_type = 'all_time';
        }
        $this->view->conversion_report_type = $conversion_report_type;

        //default this month
        $now = new \DateTime('now');
        $start_time = $now->format('Y').'-'.$now->format('m').'-01';
        $end_time = date("Y-m-d 23:59:59", strtotime("last day of this month"));

        //get the employee conversion reports
        $this->view->employee_conversion_report = Users::getEmployeeConversionReport($loc->agency_id, $start_time, $end_time, $this->session->get('auth-identity')['location_id'], 'DESC');
        
        //we need to find the most recent reviews
        $start_time = date("Y-m-d", strtotime("first day of previous month"));
        $end_time = date("Y-m-d 23:59:59", strtotime("last day of previous month"));
        $review_report = Review::find(
            array(
              "conditions" => "location_id = ".$this->session->get('auth-identity')['location_id'],
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

        
        //Reviews
        //Total New Reviews (overall, last month, this month, monthly growth)
        $this->view->total_prev_reviews = $original_total_reviews;
        $this->view->total_reviews_location = $total_reviews;
        $this->view->total_reviews_location = $this->view->total_reviews_location - $this->view->total_prev_reviews;
        
        
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
    }


}
