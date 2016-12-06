<?php
    namespace Vokuro\Controllers;

    use Vokuro\Models\Agency;
    use Vokuro\Models\Location;
    use Vokuro\Models\Review;
    use Vokuro\Models\ReviewInvite;
    use Vokuro\Models\SharingCode;
    use Vokuro\Models\Users;
    use Vokuro\Models\UsersSubscription;

    /**
     * Display the default index page.
     */
    class AgencyController extends ControllerBusinessBase {
        public function initialize() {
            $tUser = $this->auth->getIdentity();
            $logged_in = is_array($tUser);
            if ($logged_in && $tUser['profile'] == 'Agency Admin') {
                if (isset($_POST['locationselect'])) {
                    $this->auth->setLocation($_POST['locationselect']);
                }

                $this->view->setVar('logged_in', $logged_in);
                $this->view->setTemplateBefore('private');
            } else {
                $this->response->redirect('/session/login');
                $this->view->disable();
                return;
            }
            parent::initialize();
        }

        public function dismissUpgradeAction() {
            $this->view->disable();
            $responseParameters['status'] = false;

            $identity = $this->auth->getIdentity();
            if ($identity) {
                $objUser = \Vokuro\Models\Users::findFirst('id = ' . $identity['id']);
                $objAgency = \Vokuro\Models\Agency::findFirst("agency_id = {$objUser->agency_id}");
                $objAgency->upgraded_status++;
                $objAgency->save();
                $responseParameters['status'] = true;
            } else {
                $responseParameters['error'] = "Could not determine identification.";
            }

            $this->response->setContentType('application/json', 'UTF-8');
            $this->response->setContent(json_encode($responseParameters));
            return $this->response;
        }

        public function upgradePlanAction() {
            $DefaultUpgradeSubscription = "97 Twenty for eight";
            $this->view->disable();
            $responseParameters['status'] = false;
            try {
                if (!$this->request->isPost())
                    throw new \Exception("Request must be POST");

                $identity = $this->auth->getIdentity();
                if ($identity) {
                    $objUser = \Vokuro\Models\Users::findFirst('id = ' . $identity['id']);
                    $objAgency = \Vokuro\Models\Agency::findFirst("agency_id = {$objUser->agency_id}");
                    $objAgency->upgraded_status++;
                    $objAgency->save();

                    $SubscriptionManager = new \Vokuro\Services\SubscriptionManager();
                    $tPricingInfo = $SubscriptionManager->GetAgencySubscriptionPricingPlan($DefaultUpgradeSubscription);

                    if($SubscriptionManager->createAgencySubscription($objUser->id, $tPricingInfo['PlanID'], $tPricingInfo['RecurringPayment'])) {
                        $responseParameters['status'] = true;
                    } else {
                        $responseParameters['error'] = "Could not upgrade subscription";
                    }

                } else {
                    $responseParameters['error'] = "Could not determine identification.";
                }
            } catch (Exception $e) {
                $responseParameters['status'] = false;
                $responseParameters['error'] = $e->getMessage();
            }

            $this->response->setContentType('application/json', 'UTF-8');
            $this->response->setContent(json_encode($responseParameters));
            return $this->response;
        }

        public function createAction($agency_type_id = null, $agency_id = 0, $parent_id = 0 ) {
            //$parent_id is never used...
            if(!$agency_type_id) $agency_type_id = 2;
            $Identity = $this->auth->getIdentity();
            $UserID = $Identity['id'];
            $objLoggedInUser = Users::findFirst("id = {$UserID}");

            $Ret = parent::createAction($agency_type_id, $agency_id, $objLoggedInUser->agency_id);
            $this->view->pick("admindashboard/create");

            return $Ret;
        }

        public function viewAction($agency_type_id, $agency_id = 0) {
            if (!$agency_type_id) $agency_type_id = 2;
            $this->view->pick("admindashboard/view");
            $Ret = parent::viewAction($agency_type_id, $agency_id);

            return $Ret;
        }

        /**
         * END OVERWRITE OF BUSINESS COMMON FUNCTIONS
         */

        /**
         * REFACTOR:  This is duplicated mostly (function is called findAgencies()) in AdmindashboardController.  This really should be in the Agency Model class and not modifying the view.
         * This find the agencies for the agencies and businesses actions.
         * Agency Type 1 = Agency, 2 = Business
         */
        public function findBusinesses() {
            $Identity = $this->auth->getIdentity();

            if (!is_array($Identity)) {
                $this->response->redirect("/session/login?return=/agency");
                $this->view->disable();
                return;
            }

            $UserID = $Identity['id'];
            $objUser = Users::findFirst("id = {$UserID}");

            $tAgencies = Agency::find("agency_type_id = 2 AND parent_id = {$objUser->agency_id}");
            return $tAgencies;
        }

        /**
         * Default action. Set the public layout (layouts/private.volt)
         */
        public function indexAction() {
            $UpgradeSubscriptionPlanID = 4;
            $identity = $this->auth->getIdentity();

            $objUser = \Vokuro\Models\Users::findFirst('id = ' . $identity['id']);
            $objAgency = \Vokuro\Models\Agency::findFirst("agency_id = {$objUser->agency_id}");
            $objAgencyPricingPlan = \Vokuro\Models\AgencySubscriptionPlan::findFirst("agency_id = {$objAgency->agency_id}");

            $this->view->showUpgrade = ($objAgency->upgraded_status > 0 || $objAgencyPricingPlan->pricing_plan_id == $UpgradeSubscriptionPlanID) ? false : true;
            
            $this->tag->setTitle('Manage Businesses');
            $this->view->tBusinesses = $this->findBusinesses();
        }
    }
