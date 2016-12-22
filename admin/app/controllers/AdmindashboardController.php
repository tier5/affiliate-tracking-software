<?php

namespace Vokuro\Controllers;

use Vokuro\Forms\AgencyForm;
use Vokuro\Models\Agency;
use Vokuro\Models\EmailConfirmations;
use Vokuro\Models\Location;
use Vokuro\Models\LocationReviewSite;
use Vokuro\Models\ResetPasswords;
use Vokuro\Models\Review;
use Vokuro\Models\ReviewInvite;
use Vokuro\Models\ReviewsMonthly;
use Vokuro\Models\SharingCode;
use Vokuro\Models\Subscription;
use Vokuro\Models\Users;
use Vokuro\Services\UserManager;

/**
 * Display the default index page.
 */
class AdmindashboardController extends ControllerBusinessBase {

    public function initialize() {

        $this->assets->addCss('/css/subscription.css');

        $logged_in = is_array($this->auth->getIdentity());

        if ($logged_in && (isset($this->session->get('auth-identity')['is_admin']))) {
            $this->view->setVar('logged_in', $logged_in);
            $this->view->setTemplateBefore('private');
        } else {
            $this->response->redirect('/session/login');
            $this->view->disable();
            return;
        }
        parent::initialize();
    }


    /**
     * Default action. Set the public layout (layouts/private.volt)
     */
    public function indexAction() {
        $this->tag->setTitle('Review Velocity | Dashboard');


        //start of the month date
        $now = new \DateTime('now');
        $start_time = $now->format('Y') . '-' . $now->format('m') . '-01 00:00:00';

        //Total Active Businesses
        $this->view->total_businesses = Agency::count(array("column" => "agency_id",
                    "conditions" => "agency_type_id = 2 AND subscription_valid = 'Y' AND status = 1 AND deleted = 0"));
        //New Businesses This Month
        $this->view->new_businesses = Agency::count(array("column" => "agency_id",
                    "conditions" => "agency_type_id = 2 AND date_created > '" . $start_time . "'"));
        //Lost Businesses This Month
        $this->view->lost_businesses = Agency::count(array("column" => "agency_id",
                    "conditions" => "agency_type_id = 2 AND date_left > '" . $start_time . "' AND (subscription_valid = 'N' AND status = 0 AND deleted = 1)"));
        //Monthly Churn Rate
        /*
          eg - Calculating Churn for say Oct

          Oct1st you have 100 customers
          During Oct you gain 20 customers
          During Oct you lose 5 customers.
          => end of Oct you have 100 + 20 - 5 = 115 customers

          Churn Rate = 5 / 115 = 4.34%

         */
        $this->view->churn_rate = $this->view->lost_businesses / $this->view->total_businesses;


        //Total Active Agencies
        $this->view->total_agencies = Agency::count(array("column" => "agency_id",
                    "conditions" => "agency_type_id = 1 AND subscription_valid = 'Y' AND status = 1 AND deleted = 0"));
        //New Agencies This Month
        $this->view->new_agencies = Agency::count(array("column" => "agency_id",
                    "conditions" => "agency_type_id = 1 AND date_created > '" . $start_time . "'"));
        //Lost Agencies This Month
        $this->view->lost_agencies = Agency::count(array("column" => "agency_id",
                    "conditions" => "agency_type_id = 1 AND date_left > '" . $start_time . "' AND (subscription_valid = 'N' AND status = 0 AND deleted = 1)"));
        //Monthly Churn Rate
        $this->view->churn_rate_agencies = $this->view->lost_agencies / $this->view->total_agencies;


        //Analytics
        //Total SMS Sent (overall, last month, this month, monthly growth)
        //Total Click Through Rate  (overall, last month, this month, monthly growth)
        //Total Conversion Rate (overall, last month, this month, monthly growth)
        //This is customers that left a feedback rating
        //Total!
        $this->view->sms_sent_total = ReviewInvite::count(
                        array(
                            "column" => "review_invite_id",
                            "conditions" => "review_invite_id = review_invite_id AND sms_broadcast_id IS NULL",
                        )
        );
        $this->view->click_through_total = ReviewInvite::count(
                        array(
                            "column" => "review_invite_id",
                            "conditions" => "date_viewed IS NOT NULL  AND sms_broadcast_id IS NULL ",
                        )
        );
        $this->view->conversion_total = ReviewInvite::count(
                        array(
                            "column" => "review_invite_id",
                            "conditions" => "date_viewed IS NOT NULL AND (recommend IS NOT NULL OR (rating IS NOT NULL AND rating != '')) AND sms_broadcast_id IS NULL ",
                        )
        );

        //Last month!
        $start_time = date("Y-m-d", strtotime("first day of previous month"));
        $end_time = date("Y-m-d 23:59:59", strtotime("last day of previous month"));
        $sms_sent_last_month = ReviewInvite::count(
                        array(
                            "column" => "review_invite_id",
                            "conditions" => "date_sent >= '" . $start_time . "' AND date_sent <= '" . $end_time . "' ",
                        )
        );
        $this->view->sms_sent_last_month = $sms_sent_last_month;
        $click_through_last_month = ReviewInvite::count(
                        array(
                            "column" => "review_invite_id",
                            "conditions" => "date_sent >= '" . $start_time . "' AND date_sent <= '" . $end_time . "' AND date_viewed IS NOT NULL ",
                        )
        );
        $this->view->click_through_last_month = $click_through_last_month;
        $conversion_last_month = ReviewInvite::count(
                        array(
                            "column" => "review_invite_id",
                            "conditions" => "date_sent >= '" . $start_time . "' AND date_sent <= '" . $end_time . "' AND date_viewed IS NOT NULL AND (recommend IS NOT NULL OR (rating IS NOT NULL AND rating != ''))  AND sms_broadcast_id IS NULL ",
                        )
        );
        $this->view->conversion_last_month = $conversion_last_month;

        //This month!
        $start_time = date("Y-m-d", strtotime("first day of this month"));
        $end_time = date("Y-m-d 23:59:59", strtotime("last day of this month"));
        $sms_sent_this_month = ReviewInvite::count(
                        array(
                            "column" => "review_invite_id",
                            "conditions" => "date_sent >= '" . $start_time . "' AND date_sent <= '" . $end_time . "' ",
                        )
        );
        $this->view->sms_sent_this_month = $sms_sent_this_month;
        $click_through_this_month = ReviewInvite::count(
                        array(
                            "column" => "review_invite_id",
                            "conditions" => "date_sent >= '" . $start_time . "' AND date_sent <= '" . $end_time . "' AND date_viewed IS NOT NULL ",
                        )
        );
        $this->view->click_through_this_month = $click_through_this_month;
        $conversion_this_month = ReviewInvite::count(
                        array(
                            "column" => "review_invite_id",
                            "conditions" => "date_sent >= '" . $start_time . "' AND date_sent <= '" . $end_time . "' AND date_viewed IS NOT NULL AND (recommend IS NOT NULL OR (rating IS NOT NULL AND rating != '')) ",
                        )
        );
        $this->view->conversion_this_month = $conversion_this_month;


        //Reviews
        //Total New Reviews (overall, last month, this month, monthly growth)
        $this->view->total_prev_reviews = LocationReviewSite::sum(
                        array(
                            "column" => "COALESCE(original_review_count, 0)"
                        )
        );
        $this->view->total_reviews = LocationReviewSite::sum(
                        array(
                            "column" => "COALESCE(review_count, 0)"
                        )
        );

        //Last month!
        $this->view->num_reviews_last_month = ReviewsMonthly::sum(
                        array(
                            "column" => "COALESCE(facebook_review_count, 0) + COALESCE(google_review_count, 0) + COALESCE(yelp_review_count, 0)",
                            "conditions" => "month = " . date("m", strtotime("first day of previous month")) . " AND year = '" . date("Y", strtotime("first day of previous month")) . "' ",
                        )
        );
        $this->view->num_reviews_two_months_ago = ReviewsMonthly::sum(
                        array(
                            "column" => "COALESCE(facebook_review_count, 0) + COALESCE(google_review_count, 0) + COALESCE(yelp_review_count, 0)",
                            "conditions" => "month = " . date("m", strtotime("-2 months", time())) . " AND year = '" . date("Y", strtotime("-2 months", time())) . "' ",
                        )
        );
        $this->view->total_reviews_last_month = $this->view->num_reviews_last_month - $this->view->num_reviews_two_months_ago;

        //This month!
        $this->view->num_reviews_this_month = ReviewsMonthly::sum(
                        array(
                            "column" => "COALESCE(facebook_review_count, 0) + COALESCE(google_review_count, 0) + COALESCE(yelp_review_count, 0)",
                            "conditions" => "month = " . date("m", strtotime("first day of this month")) . " AND year = '" . date("Y", strtotime("first day of this month")) . "' ",
                        )
        );
        //echo '<p>num_reviews_this_month:'.$this->view->num_reviews_this_month.':total_reviews_last_month:'.$this->view->total_reviews_last_month.'</p>';
        $this->view->total_reviews_this_month = $this->view->num_reviews_this_month - $this->view->total_reviews_last_month;


        //echo '<pre>total_reviews_last_month:'.$this->view->total_reviews_last_month.':$this->view->num_reviews_this_month:'.$this->view->num_reviews_this_month.':$this->view->num_reviews_last_month:'.$this->view->num_reviews_last_month.'</pre>';

        $this->view->total_reviews = $this->view->total_reviews - $this->view->total_prev_reviews;
        //Total Review Conversions (this is calculated based on the total SMS sent based off the total new reviews that have come in from Google, Yelp & Facebook)
        //$this->view->sms_sent_total
        //$this->view->total_reviews
        
    }

    public function createAction($agency_type_id, $agency_id = 0, $parent_id = 0) {
	    // Businesses under Review Velocity have a parent_id of -1.  agency_type_id == 1 means Agency.  I do want to get rid of this field.
	    $Ret =  parent::createAction($agency_type_id, $agency_id, $agency_type_id == 1 ? \Vokuro\Models\Agency::AGENCY : \Vokuro\Models\Agency::BUSINESS_UNDER_RV);
	    return $this->view->pick("admindashboard/create");
	}

    /**
     * This find the agencies for the agencies and businesses actions
     */
    public function findAgencies($agency_type_id) {
        $identity = $this->auth->getIdentity();
        // If there is no identity available the user is redirected
        if (!is_array($identity)) {
            $this->response->redirect('/session/login?return=/admindashboard/list/' . $agency_type_id);
            $this->view->disable();
            return;
        }
        // Query binding parameters with string placeholders
        $conditions = "id = :id:";
        $parameters = array("id" => $identity['id']);
        $userObj = Users::findFirst(array($conditions, "bind" => $parameters));

        // Query binding parameters with string placeholders
        $conditions = "agency_type_id = " . $agency_type_id;

        if($agency_type_id == 1) {
            // Display agencies
            if($userObj->is_admin)
                $agencies = \Vokuro\Models\Agency::find("parent_id = " . \Vokuro\Models\Agency::AGENCY);
            else
                $agencies = \Vokuro\Models\Agency::find("agency_id = {$userObj->agency_id}");   // Case should never happen, but just in case it did somehow
        } else {
            // Display businesses
            if($userObj->is_admin)
                $agencies = \Vokuro\Models\Agency::find("parent_id = " . \Vokuro\Models\Agency::BUSINESS_UNDER_RV . " OR parent_id > 0");
            else {
                $objAgency = \Vokuro\Models\Agency::findFirst("agency_id = {$userObj->agency_id}");
                if($objAgency->parent_id > 0) {
                    $this->response->setStatusCode(404, "Not Found");
                    echo "<h1>404 Page Not Found</h1>";
                    $this->view->disable();
                    return;
                }
                $agencies = \Vokuro\Models\Agency::find("parent_id = {$userObj->agency_id}");
            }
        }

        $this->view->agencies = $agencies;
    }

    /**
     * status action
     */
    public function statusAction($agency_type_id, $agency_id, $status) {
        $age2 = new Agency();
        if ($agency_id > 0) {
            $conditions = "agency_id = :agency_id:";
            $parameters = array("agency_id" => $agency_id);
            $age2 = Agency::findFirst(array($conditions, "bind" => $parameters));
            if ($age2) {
                $age2->status = $status;
                $age2->save();
                $this->flash->error("The " . ($agency_type_id == 1 ? 'agency' : 'business') . " status was updated.");
            }
        }
        $identity = $this->auth->getIdentity();

        if($identity['is_admin'])
            $this->response->redirect('/admindashboard/list/' . $agency_type_id);
        else
            if($agency_type_id == 2)
                $this->response->redirect('/agency');
            else
                $this->response->redirect('/admindashboard/list/' . $agency_type_id);
        $this->view->disable();
        return;
    }

    /**
     * agencies action.
     */
    public function listAction($agency_type_id = 1) {
        $agency_type_id = (int)$agency_type_id;
        if($agency_type_id && !is_int($agency_type_id)) throw new \Exception('Invalid agency type id specified');
        $this->tag->setTitle('Get Mobile Reviews | See All ' . ($agency_type_id == 1 ? 'Agencies' : 'Businesses'));

        $this->findAgencies($agency_type_id);
        $this->view->agency_type_id = $agency_type_id;

        $dbAllParentAgencies = \Vokuro\Models\Agency::find('parent_id = ' . \Vokuro\Models\Agency::AGENCY);
        $tAllParentAgencies = [];

        foreach($dbAllParentAgencies as $objParentAgency)
            $tAllParentAgencies[$objParentAgency->agency_id] = $objParentAgency->toArray();


        $this->view->tAllParentAgencies = $tAllParentAgencies;
    }

    protected function DeleteBusiness($BusinessID) {
      // echo $BusinessID;exit;


       $objEmploeeunderbusiness = \Vokuro\Models\Users::findFirst("agency_id = {$BusinessID} AND role='User' AND is_employee=1");
       if(empty($objEmploeeunderbusiness)){


        $dbLocations = \Vokuro\Models\Location::find("agency_id = {$BusinessID}");
        // Delete locations, review sites and review invites

        if( $dbLocations){
        foreach ($dbLocations as $objLocation) {
            $dbLocationReviewSites = \Vokuro\Models\LocationReviewSite::find("location_id = {$objLocation->location_id}");

            // Review invites
            $dbReviewInvite = \Vokuro\Models\ReviewInvite::find("location_id = {$objLocation->location_id}");
            foreach($dbReviewInvite as $objReviewInvite) {
                $dbReviewInviteReviewSite = \Vokuro\Models\ReviewInviteReviewSite::find("review_invite_id = {$objReviewInvite->review_invite_id}");
                foreach($dbReviewInviteReviewSite as $objReviewInviteReviewSite)
                    $objReviewInviteReviewSite->delete();

                $objReviewInvite->delete();
            }

            // Review Sites
            foreach($dbLocationReviewSites as $objReviewSite)
                $objReviewSite->delete();

            // Location
            $objLocation->delete();
        }
        }


        // Delete current business subscription if exists
        $objSuperUser = \Vokuro\Models\Users::findFirst("agency_id = {$BusinessID} AND role='Super Admin'");
//echo $BusinessID;exit;
        if($objSuperUser)
        {
        $objSubscription = \Vokuro\Models\BusinessSubscriptionPlan::findFirst("user_id = {$objSuperUser->id}");
        }
       
        $SubscriptionManager = new \Vokuro\Services\SubscriptionManager();

        if($objSubscription)
            $objSubscription->delete();

        // Delete agency subscription.  Cancel subscription in stripe if exists?
        //echo 'kk';exit;
        if(!$SubscriptionManager->CancelSubscription($BusinessID)) {
            return false;
        }

        $dbSharingCodes = \Vokuro\Models\SharingCode::find("business_id = {$BusinessID}");
        foreach($dbSharingCodes as $objSharingCode)
            $objSharingCode->delete();

        $dbUsers = \Vokuro\Models\Users::find("agency_id = {$BusinessID}");
        foreach($dbUsers as $objUser)
            $objUser->delete();

        $objBusiness = \Vokuro\Models\Agency::findFirst("agency_id = {$BusinessID}");
        $objBusiness->delete();

        return true;
        }
        else
        {
             $this->session->set("err_msg", 'This business cannot be deleted as some users are under this business.please delete the users to delete this business');
             
            return false;
        }
    }

    protected function DeleteAgency($AgencyID) {
        $SubscriptionManager = new \Vokuro\Services\SubscriptionManager();
        $objSuperUser = \Vokuro\Models\Users::findFirst("agency_id = {$AgencyID} AND role='Super Admin'");

        // Delete all business subscriptions under an agency
        $dbSubscriptionPricingPlan = \Vokuro\Models\SubscriptionPricingPlan::find("user_id = {$objSuperUser->id}");

        foreach($dbSubscriptionPricingPlan as $objSubscriptionPricingPlan) {
            $dbSubscriptionPricingPlanParameterList = \Vokuro\Models\SubscriptionPricingPlanParameterList::find("subscription_pricing_plan_id = {$objSubscriptionPricingPlan->id}");
            foreach($dbSubscriptionPricingPlanParameterList as $objParameterList)
                $objParameterList->delete();

            $objSubscriptionPricingPlan->delete();
        }

        // Delete agency subscription.  Cancel subscription in stripe if exists?
        if(!$SubscriptionManager->CancelSubscription($AgencyID)) {
            return false;
        }
        $objAgencySubscriptionPlan = \Vokuro\Models\AgencySubscriptionPlan::findFirst("agency_id = {$AgencyID}");
        if($objAgencySubscriptionPlan)
            $objAgencySubscriptionPlan->delete();

        // Delete all businesses
        $dbBusinesses = \Vokuro\Models\Agency::find("parent_id = {$AgencyID}");
        foreach($dbBusinesses as $objBusiness) {
            $this->DeleteBusiness($objBusiness->agency_id);
        }

        $dbUsers = \Vokuro\Models\Users::find("agency_id = {$AgencyID}");

        foreach($dbUsers as $objUser)
            $objUser->delete();

        $objAgency = \Vokuro\Models\Agency::findFirst("agency_id = {$AgencyID}");
        $objAgency->delete();
    }

    /**
     * Deletes an agency
     *
     * @param int $id
     */
    public function deleteAction($agency_type_id, $agency_id) {
        $this->db->begin();

       

        try {

            $objBusiness = \Vokuro\Models\Agency::findFirst("agency_id = {$agency_id}");
            $DeleteType = $objBusiness->parent_id == \Vokuro\Models\Agency::AGENCY ? 'Agency' : 'Business';
            if($DeleteType == 'Agency') {
                $BackURL = '/admindashboard/list/1';
                $this->response->redirect($BackURL);
                $this->view->disable();
                return;
            }
            else {
                // Deleting an agency works as of 12/05/2016, but we deemed it too powerful to have to the super admins currently.  It will unsubscribe every business.
                $identity = $this->auth->getIdentity();
                $BackURL = $identity['is_admin'] ? '/admindashboard/list/2' : '/agency';
            }

            if($objBusiness) {
                $UserID = $this->getUserObject()->id;
                if (!$this->getPermissions()->canUserEditAgency($this->getUserObject(), $objBusiness)){
                    throw new \Exception("You do not have permissions to edit/delete this agency with the id of:
                    {$objBusiness->agency_id} with the user id of{$UserID}" );
                }
            }

            if (!$objBusiness) {
                $this->flash->error("The " . ($agency_type_id == 1 ? 'agency' : 'business') . " was not found");

                $this->response->redirect($BackURL);
                $this->view->disable();
                return;
            }

            if($DeleteType == 'Agency') {
                // Deleting an agency works as of 12/05/2016, but we deemed it too powerful to have to the super admins currently.  It will unsubscribe every business.
                //$this->DeleteAgency($agency_id);
            } else {
                //echo 'yy';exit;
                //echo $agency_id;exit;
                $this->DeleteBusiness($agency_id);


               // echo 'zz';exit;
            }
        } catch (\Exception $e) {
            $this->flash->error($e->getMessage());
            $this->flash->error("<PRE>" . $e->getTraceAsString());
            $this->db->rollback();
        }

        $this->db->commit();
        $this->response->redirect($BackURL);
        $this->view->disable();

        return;
    }

    /**
     * Sends confirmation email
     */
    public function confirmationAction($agency_type_id, $agency_id, $user_id) {
        $emailConfirmation = new EmailConfirmations();
        $emailConfirmation->usersId = $user_id;
        $emailConfirmation->save();

        $this->response->redirect('/admindashboard/view/' . $agency_type_id . '/' . $agency_id . '?s=2');
        $this->view->disable();
        return;
    }

    /**
     * Logs in as the user
     */
    public function loginAction($agency_type_id, $agency_id = null, $user_id = null) {
        if($agency_type_id && !is_numeric($agency_type_id)) throw new \Exception('invalid agency type id provided');
        if ($agency_id && !is_numeric($agency_id)) throw new \Exception('invalid agency id provided');
        $usermanager = new UserManager();
        try{
            $objUser = $usermanager->sudoAsUserId($user_id);
            $this->response->redirect('/');
        }catch(\Exception $e){
            $this->flash->success('You cannot login as an inactivated user, or there was an error sudoing as a user');
            //exit('exiting: line '.__LINE__.' of file:'.__FILE__);
            $this->response->redirect('/admindashboard/view/' . $agency_type_id . '/' . $agency_id . '?s=3');
            return;
        }

        $RedirectUrl = '/';
        $objEntity = \Vokuro\Models\Agency::findFirst("agency_id = {$objUser->agency_id}");
        $Domain = $this->config->application->domain;
        if($objUser->is_admin || $objEntity->parent_id == \Vokuro\Models\Agency::BUSINESS_UNDER_RV || $this->config->application['environment'] == 'dev')
            $RedirectUrl = '/';
        else {
            if($objEntity->parent_id == \Vokuro\Models\Agency::AGENCY) {
                // All agencies should have a custom_domain, but I don't want the site breaking in the event that they don't for some weird reason.
                $RedirectUrl = $objEntity->custom_domain ? "http://{$objEntity->custom_domain}.{$Domain}/" : '/';
            } elseif($objEntity->parent_id > 0) {
                $objAgency = \Vokuro\Models\Agency::findFirst("agency_id = {$objEntity->parent_id}");
                // All agencies should have a custom_domain, but I don't want the site breaking in the event that they don't for some weird reason.
                $RedirectUrl = $objAgency->custom_domain ? "http://{$objAgency->custom_domain}.{$Domain}/" : '/';
            }
            // GARY_TODO:  Temporarily not using custom_domain due to session problem
            $RedirectUrl = '/';
        }


        $this->response->redirect($RedirectUrl);
        $this->view->disable();
        return;
    }

    public function editAction($agency_id) {
        if (!is_numeric($agency_id)) throw new \Exception('Invalid agency id provided, expected integer');
        $Ret =  parent::editAction($agency_id);
  	    $this->view->pick("admindashboard/edit");
  	    return $Ret;
    }

    /**
     * Shows the forgot password form
     */
    public function forgotPasswordAction($agency_type_id, $agency_id, $user_id) {
        $resetPassword = new ResetPasswords();
        $resetPassword->usersId = $user_id;

       /* echo '<pre>';print_r($resetPassword);exit;*/
        if ($resetPassword->save()) {
            $this->flash->success('Success! Have the employee check their email for a reset password message');
        } else {
            foreach ($resetPassword->getMessages() as $message) {
                $this->flash->error($message);
            }
        }
        $this->response->redirect('/admindashboard/view/' . $agency_type_id . '/' . $agency_id . '?s=1');
        $this->view->disable();
        return;
    }

    /**
     * payments action.
     */
    public function paymentsAction() {
        $this->tag->setTitle('Get Mobile Reviews | Payments');
    }

    /**
     * settings action.
     */
    public function settingsAction() {
        $this->tag->setTitle('Get Mobile Reviews | Settings');
    }

    //end finding subscriptions
}
