<?php
    namespace Vokuro\Controllers;
    use Phalcon\Session\Bag as SessionBag;

    class AgencysignupController extends ControllerBase {
        protected $tAllFormFields = [
            /* Step 1 Fields */
            'BusinessName',
            'Address',
            'Address2',
            'City',
            'State',
            'Zip',
            'Phone',
            'Email',
            'Website',
            'EmailFromName',
            'EmailFromAddress',

            /* Step 2 Fields */
            'LogoFilename',
            'PrimaryColor',
            'SecondaryColor',
            
            /* Step 3 Fields */
            'TwilioSID',
            'TwilioToken',
            'TwilioFromNumber',
            
            /* Step 4 Fields */
            'StripeSecretKey',
            'StripePublishableKey'
        ];

        protected $tRequiredFields = [
            'Step1' => [
                'BusinessName',
                'Address',
                'Address2',
                'City',
                'State',
                'Zip',
                'Phone',
                'Email',
                'Website',
                'EmailFromName',
                'EmailFromAddress',
            ],
        ];

        protected $tAcceptedCardTypes = [
            'Visa',
            'Master Card',
            'American Express',
            'Discover',
        ];


        /**
         * Auto populate the session with form data, set their appropriate view variables and determine current step.
         */
        public function initialize() {
            if(!$this->session->AgencySignup)
                $this->session->AgencySignup = [];

            // Update Session Data
            $tData = [];
            if($this->request->isPost()) {
                $Post = $this->request->getPost();

                foreach ($this->tAllFormFields as $Field) {
                    if(isset($Post[$Field]))
                        $tData[$Field] = $Post[$Field];
                }
            }
            $this->session->AgencySignup = array_merge($this->session->AgencySignup, $tData);

            // Populate the view
            foreach($this->tAllFormFields as $Field) {
                if(isset($this->session->AgencySignup[$Field]))
                    $this->view->$Field = $this->session->AgencySignup[$Field];
            }

            // Determine step from URI
            preg_match("#agencysignup\/step(\d)+#", $_SERVER['REQUEST_URI'], $tMatches);
            if($tMatches)
                $this->view->current_step = $tMatches[1];
        }

        /**
         * Validates required fields.  Returns true on success, or redirects user to appropriate page with the invalid field.
         */
        protected function ValidateFields() {
            foreach($this->tRequiredFields as $Step => $tFields) {
                foreach($tFields as $ReqField) {
                    if(!isset($this->session->AgencySignup[$ReqField]) || !$this->session->AgencySignup[$ReqField]) {
                        $this->flash->error($ReqField . " cannot be empty.");
                        //$this->response->redirect('/agencysignup/' . strtolower($Step));
                    }
                }
            }

            return true;
        }

        public function orderAction() {
            // Generate months
            $tMonths = [];
            for($i = 1 ; $i <= 12 ; $i++) {
                $tMonths[$i] = date('F', mktime(0, 0, 0,$i+1, 0, 0));
            }
            // Years.  Up to 15 years from this year
            for($i = 0 ; $i <= 15 ; $i++) {
                $tYears[] = date('Y', strtotime("today +{$i} years"));
            }

            $this->view->tMonths = $tMonths;
            $this->view->tYears = $tYears;
            $this->view->tCardTypes = $this->tAcceptedCardTypes;
            $this->view->setLayout('agencyorder');
        }

        public function salesAction () {
            $this->view->setLayout('agencyorder');
        }
        public function step1Action() {
        }

        public function step2Action() {
            $this->ValidateFields();
        }

        public function step3Action() {
        }

        public function step4Action() {
        }

        public function step5Action() {
        }
    }
