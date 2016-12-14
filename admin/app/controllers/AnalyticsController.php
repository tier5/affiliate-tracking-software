<?php
    namespace Vokuro\Controllers;

    use Vokuro\Models\Agency;
    use Vokuro\Models\Location;
    use Vokuro\Models\LocationReviewSite;
    use Vokuro\Models\ReviewInvite;
    use Vokuro\Models\ReviewInviteReviewSite;
    use Vokuro\Models\Users;

    /**
     * Display the default index page.
     */
    class AnalyticsController extends ControllerBase {
        public function initialize() {
            $this->tag->setTitle('Get Mobile Reviews | Analytics');
            parent::initialize();
        }

        /**
         * Default action.
         */
        public function indexAction() {
            $logged_in = is_array($this->auth->getIdentity());
            if (!$logged_in) {
                $this->response->redirect('/session/login?return=/analytics/');
                $this->view->disable();
                return;
            }

            $this->view->setVar('logged_in', $logged_in);
            $this->view->setTemplateBefore('private');

            $clickreport = [];
            $invitelist = [];
            $SMSSentThisMonth = 0;
            $SMSSentLastMonth = 0;
            $SMSClickThisMonth = 0;
            $SMSClickLastMonth = 0;
            $SMSConvertedThisMonth = 0;
            $SMSConvertedLastMonth = 0;

            //$this->view->Totalclick = \Vokuro\Models\ReviewInviteReviewSite::count();//exit;
            $LocationID = $this->session->get('auth-identity')['location_id'];
            if ($LocationID) {
                $FirstDayThisMonth = date("Y-m-01 00:00:00");
                $FirstDayLastMonth = date("Y-m-01 00:00:00", strtotime('-1 month'));
                $LastDayLastMonth = date("Y-m-t 23:59:59", strtotime('-1 month'));
                $dbReviewsSinceLastMonth = \Vokuro\Models\ReviewInvite::find("location_id = {$LocationID} AND date_sent >= '{$FirstDayLastMonth}'");

                foreach($dbReviewsSinceLastMonth as $objReviewInvite) {
                    // Need to confirm w/ Zach about this.  I assume invites are sms messages sent?
                    //$invitelist[] = $objReviewInvite;
                    //if($objReviewInvite->rating && $objReviewInvite->rating > 0)
                        //$clickreport[] = $objReviewInvite;

                    if(strtotime($objReviewInvite->date_sent) > strtotime($FirstDayLastMonth) && strtotime($objReviewInvite->date_sent) < strtotime($LastDayLastMonth)) {
                        $SMSSentLastMonth++;
                        if($objReviewInvite->rating > 0)
                            $SMSConvertedLastMonth++;

                        if($objReviewInvite->sms_broadcast_id)
                            $SMSClickLastMonth++;

                    } else {
                        $SMSSentThisMonth++;
                        if($objReviewInvite->rating > 0)
                            $SMSConvertedThisMonth++;

                        if($objReviewInvite->sms_broadcast_id)
                            $SMSClickThisMonth++;
                    }
                }

                $invitelist = ReviewInvite::getReviewInvitesByLocation($LocationID, true);
                //echo '<pre>';print_r( $invitelist);exit;
                $this->view->invitelist = $invitelist;

                $clickreport = ReviewInvite::getReviewInviteClickReport($LocationID, true);
               // echo '<pre>';print_r( $clickreport);exit;
                $this->view->clickreport = $clickreport;

                $clicklargest=0;
                $tot_cal=0;
            foreach($clickreport as $click_site) {
              
              if($clicklargest<$click_site->num_clicks)
              {
              $clicklargest=$click_site->num_clicks;
              }
              $tot_cal=$tot_cal+$click_site->num_clicks;
                    }
                    $this->view->Totalclick = $tot_cal;
                    $this->view->clicklargest=$clicklargest;

                $this->view->sms_sent_this_month = $this->view->sms_sent_this_month_total = $SMSSentThisMonth;
                $this->view->sms_sent_last_month = $SMSSentLastMonth;
                $this->view->sms_sent_all_time = \Vokuro\Models\ReviewInvite::count("location_id = {$LocationID}");
                $this->view->sms_click_this_month = $SMSClickThisMonth;
                $this->view->sms_click_last_month = $SMSClickLastMonth;
                $this->view->sms_click_all_time = \Vokuro\Models\ReviewInvite::count("location_id = {$LocationID} AND rating > 0");
                $this->view->total_reviews_this_month_analytics = \Vokuro\Models\Review::count("location_id = {$LocationID} AND time_created > '{$FirstDayThisMonth}'");
                $this->view->total_reviews_last_month_analytics = \Vokuro\Models\Review::count("location_id = {$LocationID} AND time_created BETWEEN '{$FirstDayLastMonth}' AND '{$LastDayLastMonth}'");
                $this->view->review_count_all_time_analytics = \Vokuro\Models\Review::count("location_id = {$LocationID}");
                $this->view->sms_converted_this_month = $SMSConvertedThisMonth;
                $this->view->sms_converted_last_month = $SMSConvertedLastMonth;
                $this->view->sms_converted_all_time = \Vokuro\Models\ReviewInvite::count("location_id = {$LocationID} AND rating > 0");
            }

            $this->getSMSReport();
        }
    }
