<?php
namespace Vokuro\Controllers;

use Vokuro\Models\Agency;
use Vokuro\Models\Location;
use Vokuro\Models\LocationReviewSite;
use Vokuro\Models\ReviewInvite;
use Vokuro\Models\Users;

/**
 * Display the default index page.
 */
class AnalyticsController extends ControllerBase
{
    public function initialize()
    {
        $this->tag->setTitle('Review Velocity | Analytics');
        parent::initialize();
    }

    /**
     * Default action. 
     */
    public function indexAction()
    {
      $logged_in = is_array($this->auth->getIdentity());
      if ($logged_in) {
        if (isset($_POST['locationselect'])) {
          $this->auth->setLocation($_POST['locationselect']);
        }

        $this->view->setVar('logged_in', $logged_in);
        $this->view->setTemplateBefore('private');
      } else {        
        $this->response->redirect('/admin/session/login?return=/admin/analytics/');
        $this->view->disable();
        return;
      }

      //get the location and calculate the review total and avg.
      if (isset($this->session->get('auth-identity')['location_id'])) {
        $conditions = "location_id = :location_id:";
        $parameters = array("location_id" => $this->session->get('auth-identity')['location_id']);
        $loc = Location::findFirst(array($conditions, "bind" => $parameters));




        //###  START: find review site config info ###
        $facebook_review_count = 0;
        $google_review_count = 0;
        $yelp_review_count = 0;        
        $facebook_rating = 0;
        $google_rating = 0;
        $yelp_rating = 0;        
                        
        //look for a yelp review configuration
        $conditions = "location_id = :location_id: AND review_site_id =  2";
        $parameters = array("location_id" => $this->session->get('auth-identity')['location_id']);
        $Obj = LocationReviewSite::findFirst(array($conditions, "bind" => $parameters));
        //start with Yelp reviews, if configured
        if (isset($Obj) && isset($Obj->external_id) && $Obj->external_id) {
          $this->view->yelp_id = $Obj->external_id;
          $yelp_review_count = $Obj->review_count;
          $yelp_rating = $Obj->rating;
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
        } else {
          $this->view->facebook_page_id = '';
        }
        //###  END: find review site config info ###




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
                    "column"     => "review_invite_id",
                    "conditions" => "location_id = ".$this->session->get('auth-identity')['location_id']." AND recommend = 'N'",
                    //"group"  => "location_id",
                )
            );
//echo '<pre>$negative_total:'.print_r($negative_total,true).'</pre>';
        $this->view->negative_total = $negative_total;

        //calculate Revenue Retained
        //look in settings for the "Lifetime Value of the Customer"
        $conditions = "agency_id = :agency_id:";
        $parameters = array("agency_id" => $loc->agency_id);
        $agency = Agency::findFirst(array($conditions, "bind" => $parameters));
        if ($agency) {
          $this->view->revenue_retained = ($negative_total * $loc->lifetime_value_customer);
        }
        

        $month_review = ReviewInvite::count(
              array(
                "column"     => "review_invite_id",
                "conditions" => "location_id = ".$this->session->get('auth-identity')['location_id']." AND MONTH(date_sent) = MONTH(NOW()) AND YEAR(date_sent) = YEAR(NOW())",
              )
            );
        $this->view->month_review = $month_review;
        $percent_done = false;
        if ($month_review >= $loc->review_goal) {
          $percent_done = 100;
        } else {
          $percent_done = ($month_review / $loc->review_goal) * 100;
        }
        $this->view->review_goal = $loc->review_goal;
        $this->view->percent_done = $percent_done;
      }
    }

}
