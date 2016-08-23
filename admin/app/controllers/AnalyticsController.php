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
      if (!$logged_in) {
        $this->response->redirect('/session/login?return=/analytics/');
        $this->view->disable();
        return;
      }
      
      $this->view->setVar('logged_in', $logged_in);
      $this->view->setTemplateBefore('private');

      //get the location and calculate the review total and avg.
      if (isset($this->session->get('auth-identity')['location_id'])) {
        //get a list of all review invites for this location
        $invitelist = ReviewInvite::getReviewInvitesByLocation($this->session->get('auth-identity')['location_id'], true);
        $this->view->invitelist = $invitelist;
        
        //get a list of all review invites for this location
        $clickreport = ReviewInvite::getReviewInviteClickReport($this->session->get('auth-identity')['location_id'], true);
        $this->view->clickreport = $clickreport;
        //find the total clicks to calculate percent
        $clicktotal = 0;
        $clicklargest = 0;
        foreach ($clickreport as $click) {
          $clicktotal += $click->num_clicks;
          if ($click->num_clicks > $clicklargest) $clicklargest = $click->num_clicks;
        } 
        $this->view->clicktotal = $clicktotal;
        $this->view->clicklargest = $clicklargest;

        $this->view->sms_sent_all_time = ReviewInvite::count(
                array(
                  "column" => "review_invite_id",
                  "conditions" => "location_id = ".$this->session->get('auth-identity')['location_id']." AND sms_broadcast_id IS NULL ",
                )
              );

        $this->view->review_count_all_time = LocationReviewSite::sum(
                array(
                  "column" => "review_count",
                  "conditions" => "location_id = ".$this->session->get('auth-identity')['location_id'],
                )
              ) - LocationReviewSite::sum(
                array(
                  "column" => "original_review_count",
                  "conditions" => "location_id = ".$this->session->get('auth-identity')['location_id'],
                )
              );

        //Last month!
        $start_time = date("Y-m-d", strtotime("first day of previous month"));
        $end_time = date("Y-m-d 23:59:59", strtotime("last day of previous month"));
        $this->view->sms_converted_last_month = ReviewInvite::count(
              array(
                "column" => "review_invite_id",
                "conditions" => "date_sent >= '".$start_time."' AND date_sent <= '".$end_time."' AND location_id = ".$this->session->get('auth-identity')['location_id']." AND date_viewed IS NOT NULL AND (recommend IS NOT NULL OR (rating IS NOT NULL AND rating != '')) AND sms_broadcast_id IS NULL",
              )
            ); 

        //This month!
        $start_time = date("Y-m-d", strtotime("first day of this month"));
        $end_time = date("Y-m-d 23:59:59", strtotime("last day of this month"));
        $this->view->sms_converted_this_month = ReviewInvite::count(
              array(
                "column" => "review_invite_id",
                "conditions" => "date_sent >= '".$start_time."' AND date_sent <= '".$end_time."' AND location_id = ".$this->session->get('auth-identity')['location_id']." AND date_viewed IS NOT NULL AND (recommend IS NOT NULL OR (rating IS NOT NULL AND rating != '')) AND sms_broadcast_id IS NULL ",
              )
            );
        $this->view->sms_converted_all_time = ReviewInvite::count(
                array(
                  "column" => "review_invite_id",
                  "conditions" => "location_id = ".$this->session->get('auth-identity')['location_id']." AND date_viewed IS NOT NULL AND (recommend IS NOT NULL OR (rating IS NOT NULL AND rating != '')) AND sms_broadcast_id IS NULL ",
                )
              );
              
        //Last month!
        $start_time = date("Y-m-d", strtotime("first day of previous month"));
        $end_time = date("Y-m-d 23:59:59", strtotime("last day of previous month"));
        $this->view->sms_click_last_month = ReviewInvite::count(
              array(
                "column" => "review_invite_id",
                "conditions" => "date_sent >= '".$start_time."' AND date_sent <= '".$end_time."' AND location_id = ".$this->session->get('auth-identity')['location_id']." AND date_viewed IS NOT NULL  AND sms_broadcast_id IS NULL ",
              )
            ); 

        //This month!
        $start_time = date("Y-m-d", strtotime("first day of this month"));
        $end_time = date("Y-m-d 23:59:59", strtotime("last day of this month"));
        $this->view->sms_click_this_month = ReviewInvite::count(
              array(
                "column" => "review_invite_id",
                "conditions" => "date_sent >= '".$start_time."' AND date_sent <= '".$end_time."' AND location_id = ".$this->session->get('auth-identity')['location_id']." AND date_viewed IS NOT NULL AND sms_broadcast_id IS NULL ",
              )
            );
        $this->view->sms_click_all_time = ReviewInvite::count(
                array(
                  "column" => "review_invite_id",
                  "conditions" => "location_id = ".$this->session->get('auth-identity')['location_id']." AND date_viewed IS NOT NULL AND sms_broadcast_id IS NULL ",
                )
              );

      }

      $this->view->sms_click_this_month = 10;
      $this->view->sms_click_all_time = 100;
      $this->view->sms_click_last_month = 9;
      $this->view->sms_converted_all_time = 60;
      $this->view->sms_converted_this_month = 6;
      $this->view->sms_converted_last_month = 5;
      $this->view->review_count_all_time = 20;
      $this->view->clicktotal = 22;
      $this->view->clicklargest = 30;
      $this->view->sms_sent_all_time = 300;
      
      $this->getSMSReport();

    }

}
