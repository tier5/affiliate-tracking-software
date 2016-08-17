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

            $logged_in = is_array($this->auth->getIdentity());
            if ($logged_in) {
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

        /**
         * BEGIN OVERWRITE OF BUSINESS COMMON FUNCTIONS
         */

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
            $this->tag->setTitle('Manage Businesses');
            $this->view->tBusinesses = $this->findBusinesses();
        }
    }
