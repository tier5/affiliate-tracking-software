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

    /**
     * Display the default index page.
     */
    class AdmindashboardController extends ControllerBusinessBase {
        public function initialize() {

            $logged_in = is_array($this->auth->getIdentity());
            if ($logged_in && isset($this->session->get('auth-identity')['is_admin']) && $this->session->get('auth-identity')['is_admin'] > 0) {
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


        /**
         * This find the agencies for the agencies and businesses actions
         */
        public function findAgencies($agency_type_id) {
            //get the user id
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
            //echo '<pre>$userObj:'.print_r($userObj->agency_id,true).'</pre>';

            // Query binding parameters with string placeholders
            $conditions = "agency_type_id = " . $agency_type_id; //parent_agency_id = :parent_agency_id: AND
            $parameters = null;
            array("parent_agency_id" => $userObj->agency_id);
            $agencies = Agency::find(array($conditions, "bind" => $parameters));

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
                    //
                    $age2->status = $status;
                    $age2->save();
                    $this->flash->error("The " . ($agency_type_id == 1 ? 'agency' : 'business') . " status was updated.");
                }
            }
            $this->response->redirect('/admindashboard/list/' . $agency_type_id);
            $this->view->disable();
            return;
        }


        public function createAction($agency_type_id, $agency_id = 0, $parent_id = 0) {


            $Identity = $this->auth->getIdentity();
            $UserID = $Identity['id'];
            $objLoggedInUser = Users::findFirst("id = {$UserID}");

            // Businesses under Review Velocity have a parent_id of -1
            $Ret =  parent::createAction(2, $agency_id, -1);
            $this->view->pick("admindashboard/create");
            return $Ret;
        }


        /**
         * agencies action.
         */
        public function listAction($agency_type_id) {
            $this->tag->setTitle('Review Velocity | See All ' . ($agency_type_id == 1 ? 'Agencies' : 'Businesses'));

            $this->findAgencies($agency_type_id);
            $this->view->agency_type_id = $agency_type_id;
        }


        /**
         * Deletes an agency
         *
         * @param int $id
         */
        public function deleteAction($agency_type_id, $agency_id) {
            $conditions = "agency_id = :agency_id:";
            $parameters = array("agency_id" => $agency_id);
            $age = Agency::findFirst(array($conditions, "bind" => $parameters));
            if (!$age) {
                $this->flash->error("The " . ($agency_type_id == 1 ? 'agency' : 'business') . " was not found");

                $this->response->redirect('/admindashboard/list/' . $agency_type_id);
                $this->view->disable();
                return;
            }

            if (!$age->delete()) {
                $this->flash->error($age->getMessages());
            } else {
                $this->flash->success("The " . ($agency_type_id == 1 ? 'agency' : 'business') . " was deleted");
            }

            $this->response->redirect('/admindashboard/list/' . $agency_type_id);
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
        public function loginAction($agency_type_id, $agency_id, $user_id) {
            $conditions = "id = :id:";
            $parameters = array("id" => $user_id);
            $user = Users::findFirst(array($conditions, "bind" => $parameters));
            $this->auth->login($user);

            $this->response->redirect('/');
            $this->view->disable();
            return;
        }


        /**
         * Shows the forgot password form
         */
        public function forgotPasswordAction($agency_type_id, $agency_id, $user_id) {
            $resetPassword = new ResetPasswords();
            $resetPassword->usersId = $user_id;
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
            $this->tag->setTitle('Review Velocity | Payments');

        }


        /**
         * settings action.
         */
        public function settingsAction() {
            $this->tag->setTitle('Review Velocity | Settings');

        }
        private function buildSubsriptionPricingPlanMarkUp() {
        $subscriptionPricingPlans = $this->di->get('subscriptionManager')->getSubscriptionPricingPlans();

        $markup = "<select id=\"subscription_pricing_plan_id\" name=\"subscription_pricing_plan_id\">";
        $markup .= "    <option value=\"Unpaid\">Unpaid</option>";  // This is default plan
        foreach($subscriptionPricingPlans as $subscriptionPricingPlan) {
            $markup .= "<option value=\"";
            $markup .= $subscriptionPricingPlan->id;
            $markup .= '">';
            $markup .= $subscriptionPricingPlan->name;
            $markup .= "</option>";
        }
        $markup .= "</select>";
        return $markup;
    }

    private function createSubscriptionPlan($userAccountId, $request) {
        $newSubscriptionParameters = [
            'userAccountId' => $userAccountId,
            'freeLocations' => $request->getPost('free_locations', 'striptags'),
            'freeSmsMessagesPerLocation' => $request->getPost('sms_messages', 'striptags'),
            'pricingPlanId' => $request->getPost('subscription_pricing_plan_id', 'striptags')
        ];
        return $this->di->get('subscriptionManager')->createSubscriptionPlan($newSubscriptionParameters);
    }
    //end finding subscriptions
}
