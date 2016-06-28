<?php
    namespace Vokuro\Controllers;
    use Phalcon\Mvc\View;
    use Vokuro\Services\ServicesConsts;
    use Vokuro\Models\Agency;
    use Vokuro\Models\Users;
    use Vokuro\Models\AuthorizeDotNet as AuthorizeDotNetModel;
    use Vokuro\Models\AgencyPricingPlan;


    class AgencysignupController extends ControllerBase {
        /**
         * @var array All fields from the sign up process.  Keys are the form variable names.  Values are the DB names (if they exist.
         */
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
            'StripePublishableKey',

            /* Step 5 / Upgrade Step */
            'Upgrade',

            /* Order form Fields */
            'FirstName',
            'LastName',
            'OwnerEmail',
            'OwnerPhone',
            'URL',
            'CardNumber',
            'CardType',
            'MonthExpiration',
            'YearExpiration',
            'CVV',
        ];

        protected $tAgencyFieldTranslation = [
            /* Step 1 Fields */
            'BusinessName'          => 'name',
            'Address'               => 'address',
            'Address2'              => '', // TODO:  Remove here or add this to the db.
            'City'                  => '', // TODO:  Remove here or add this to the db.
            'State'                 => 'state_province',
            'Zip'                   => 'postal_code',
            'Phone'                 => '', // TODO:  This should be populated by the order.  This may be a different email?
            'Email'                 => '', // TODO:  This should be populated by the order.  This may be a different email?
            'Website'               => '', // TODO:  Remove here or add this to the db.
            'EmailFromName'         => '', // TODO:  Remove here or add this to the db.
            'EmailFromAddress'      => '', // TODO:  Remove here or add this to the db.

            /* Step 2 Fields */
            'LogoFilename'          => 'logo_path',
            'PrimaryColor'          => 'main_color',
            'SecondaryColor'        => 'secondary_color',

            /* Step 3 Fields */
            'TwilioSID'             => 'twilio_auth_messaging_sid',
            'TwilioToken'           => 'twilio_auth_token',
            'TwilioFromNumber'      => 'twilio_from_phone',
            // TODO Remove twilio_api_key from database?

            /* Step 4 Fields */

            // TODO:  Where is stripe_account_id in form?
            'StripeSecretKey'       => 'stripe_account_secret',
            'StripePublishableKey'  => 'stripe_publishable_keys',

            /* Order form Fields */
            'FirstName'             => '',
            'LastName'              => '', // TODO:  Add last name to db or explode the name
            'OwnerEmail'            => 'email',
            'OwnerPhone'            => 'phone',
            'URL'                   => 'custom_domain',
        ];

        protected $tUserFieldTranslaction = [
            /* Order form Fields */
            'FirstName'             => 'name',
            'LastName'              => '', // TODO:  Add last name to db or explode the name
            'OwnerEmail'            => 'email',
            'OwnerPhone'            => 'phone',
            'URL'                   => 'custom_domain',
            'Password'              => '', // MUST TODO: Add this somewhere in registration process.
        ];

        protected $tRequiredFields = [
            'Step1' => [
                'BusinessName',
                'Address',
                'City',
                'State',
                'Zip',
                'Phone',
                'Email',
                'Website',
                'EmailFromName',
                'EmailFromAddress',
            ],
            'Order' => [
                'FirstName',
                'LastName',
                'OwnerEmail',
                'OwnerPhone',
                'URL',
                'CardNumber',
                'CardType',
                'MonthExpiration',
                'YearExpiration',
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
                        $tData[$Field] = $this->request->getPost($Field, 'striptags');;
                }
            }
            $this->session->AgencySignup = array_merge($this->session->AgencySignup, $tData);

            // Populate the view
            foreach($this->tAllFormFields as $Field) {
                if(isset($this->session->AgencySignup[$Field]))
                    $this->view->$Field = $this->session->AgencySignup[$Field];
                else
                    $this->view->$Field = '';
            }

            // Determine step from URI
            preg_match("#agencysignup\/step(\d)+#", $_SERVER['REQUEST_URI'], $tMatches);
            if($tMatches) {
                // In the step process.  Verify credit card information is in place, otherwise redirect.
                if(!$this->session->AgencySignup['CardNumber'] || !$this->session->AgencySignup['CardType']) {
                    $this->flash->error("Please fill out the order information first!");
                    $this->response->redirect('/agencysignup/order');
                }

                $this->view->current_step = $tMatches[1];
            }
        }

        /**
         * Validates required fields.  Returns true on success, or redirects user to appropriate page with the invalid field.
         */
        protected function ValidateFields($Page) {
            foreach($this->tRequiredFields[$Page] as $ReqField) {
                if(!isset($this->session->AgencySignup[$ReqField]) || !$this->session->AgencySignup[$ReqField]) {
                    $this->flash->error($ReqField . " cannot be empty.");
                    //$this->response->redirect('/agencysignup/' . strtolower($Step));
                }
            }

            return true;
        }

        protected function CreateAgency($tData) {
            try {
                $objAgency = new Agency();
                foreach ($this->tAgencyFieldTranslation as $FormField => $dbField) {
                    if($dbField) {
                        if($FormField == 'FirstName')
                            $objAgency->name = $tData['FirstName'] . ' ' . $tData['LastName'];
                        else
                            $objAgency->$dbField = isset($tData[$FormField]) ? $tData[$FormField] : '';
                    }
                }
                unset($dbField);

                $objAgency->agency_type_id = 1; // REFACTOR:  Drop this column
                $objAgency->subscription_id = '';
                $objAgency->parent_id = -1;

                if (!$objAgency->create())
                    throw new \Exception('Agency could not be created.');

                $objUser = new Users();
                $objUser->agency_id = $objAgency->agency_id;
                foreach ($this->tUserFieldTranslaction as $FormField => $dbField) {
                    if($dbField) {
                        if ($FormField == 'FirstName')
                            $objUser->name = $tData['FirstName'] . ' ' . $tData['LastName'];
                        else
                            $objUser->$dbField = isset($tData[$FormField]) ? $tData[$FormField] : '';
                    }
                }
                unset($dbField);

                $objUser->mustChangePassword = 'Y';
                $objUser->active = 1;
                $objUser->create_time = date("Y-m-d H:i:s");
                $objUser->is_employee = 0;
                $objUser->is_all_locations = 0;
                $objUser->profilesId = 1; // Agency Admin
                if(!$objUser->create())
                    throw new \Exception('Agency could not be created.');
                return $objUser->id;

            } catch (Exception $e) {
                // TODO:  Figure out logging / error reporting.
                return false;
            }


            /* TODO:  Update defaults in the database for fields
                review_invite_type_id
                SMS_message
                message_frequency
                date_created

            */
            /*
                Unknown Fields:
                notifications
                viral_sharing_code
                locality
                date_left

                parent_agency_id - Verify this isn't used

                user->is_all_locations ???
            */


            return true;
        }

        protected function CreateAuthProfile($tData) {
            try {
                if (!$this->request->isPost())
                    throw new \Exception();

                $objPaymentService = $this->di->get('paymentService');

                if(count($tData['MonthExpiration']) == 1)
                    $tData['MonthExpiration'] = '0'.$tData['MonthExpiration'];


                $tParameters = [
                    'cardNumber'        => $tData['CardNumber'],
                    'cardName'          => $tData['CardType'],
                    'expirationDate'    => $tData['YearExpiration'] . '-' . $tData['MonthExpiration'],
                    'csv'               => $tData['CVV'],
                    'userName'          => $tData['FirstName'],
                    'lastName'          => $tData['LastName'],
                    'userEmail'         => $tData['OwnerEmail'],
                    'provider'          => ServicesConsts::$PAYMENT_PROVIDER_AUTHORIZE_DOT_NET
                ];


                if(!$Profile = $objPaymentService->createPaymentProfile($tParameters)) {
                    throw new \Exception('Could not create payment profile.');
                }


            } catch(Exception $e) {
                return false;
            }

            return $Profile;
        }

        /**
         * Agency Subscription Detail - Day 1
         *
         * 2 plans (Both Monthly)
         * 1) 100 for 10 business accounts at 10 per month for each additional business account
         * 2) The one time offer, 160 for 20 accounts, plus lifetime 8 per additional business account
         *
         *
         */
        /**
         * @param $tData
         * @throws \Exception
         */
        protected function CreateSubscription($tData) {
            try {
                if (!$this->request->isPost())
                    throw new \Exception();


                if(!$UserID = $this->CreateAgency($this->session->AgencySignup)) {
                    throw new \Exception('DB Error:  Could not create agency.');
                }

                $objAuthDotNet = new AuthorizeDotNetModel();
                $objAuthDotNet->setUserId($UserID);
                $objAuthDotNet->setCustomerProfileId($tData['AuthProfile']['customerProfileId']);
                if (!$objAuthDotNet->create()) {
                    throw new \Exception('Could not insert auth profile into db.');
                }

                /*$objPaymentService = $this->di->get('paymentService');

                $tParameters = [
                    'userId'                    => $UserID,
                    'provider'                  => ServicesConsts::$PAYMENT_PROVIDER_AUTHORIZE_DOT_NET,
                    'price'                     => $tData['Price'],
                    'customerProfileId'         => $tData['AuthProfile']['customerProfileId'],
                    'customerPaymentProfileId'  => $tData['AuthProfile']['customerPaymentProfileId'],
                    'shippingAddressId'         => $tData['AuthProfile']['shippingAddressId'],
                ];

                if (!$objPaymentService->changeSubscription($tParameters)) {
                    throw new \Exception('Could not add subscription.');
                }*/


            } catch (Exception $e) {
                return false;
            }
            return $UserID;
        }


        public function orderAction() {
            // Generate months
            $tMonths = [];
            for($i = 1 ; $i <= 12 ; $i++) {
                $tMonths[$i] = date('F', mktime(0, 0, 0, $i+1, 0, 0));
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

        protected function GetSubscriptionPrice($Name) {
            $objPricingPlan = AgencyPricingPlan::findFirst("name='{$Name}'");
            if(!$objPricingPlan)
                throw Exception("Could not find subscription plan.");

            return $objPricingPlan->number_of_businesses * $objPricingPlan->price_per_business;
        }

        public function submitorderAction() {
            $this->response->redirect('/agencysignup/step1');
            // REMOVE JUST FOR TESTING
            $this->session->AgencySignup = array_merge($this->session->AgencySignup, ['AuthProfile' => ['customerProfileId' => 123]]);
            return true;
            try {
                // MUST TODO:  Verify email is not in use before processing payments.
                if ($this->request->isPost() && $this->ValidateFields('Order')) {
                    $this->db->begin();

                    if (!$Profile = $this->CreateAuthProfile($this->session->AgencySignup))
                        throw new \Exception('Could not create Auth Profile');

                    $this->session->AgencySignup = array_merge($this->session->AgencySignup, ['AuthProfile' => $Profile]);

                    $this->db->commit();

                }
            } catch(Exception $e) {
                $this->db->rollback();
                return false;
            }

            $this->response->redirect('/agencysignup/step1');
            return true;
        }

        public function thankyouAction() {
            $SubscriptionPlan = $this->session->AgencySignup['Upgrade'] ? 'Twenty for eight' : 'Ten for ten';
            $this->view->TodayYear = date("Y");

            try {
                // MUST TODO:  Verify email is not in use before processing payments.
                if ($this->request->isPost() && $this->ValidateFields('Order')) {
                    $this->db->begin();

                    // TODO:  Determine which plan
                    $Price = $this->GetSubscriptionPrice($SubscriptionPlan);
                    $this->session->AgencySignup = array_merge($this->session->AgencySignup, ['Price' => $Price]);

                    if (!$UserID = $this->CreateSubscription($this->session->AgencySignup))
                        throw new \Exception('Could not add subscription.');

                    $this->db->commit();

                }
            } catch(Exception $e) {
                $this->db->rollback();
                return false;
            }

            $this->view->setLayout('agencyorder');
        }

        public function salesAction () {
            $this->view->setLayout('agencyorder');
        }
        public function step1Action() {
        }

        public function step2Action() {
            $this->ValidateFields('Step1');

            $this->view->PrimaryColor = $this->session->AgencySignup['PrimaryColor'] ?: '#2a3644';
            $this->view->SecondaryColor = $this->session->AgencySignup['SecondaryColor'] ?: '#2eb82e';

        }

        public function step3Action() {
            if($this->request->hasFiles()) {
                foreach ($this->request->getUploadedFiles() as $file) {
                    $FileName = uniqid('logo') . '.' .  $file->getExtension();
                    file_put_contents(__DIR__ . "/../../public/img/agency_logos/{$FileName}", file_get_contents($file->getTempName()));
                    $this->session->AgencySignup = array_merge($this->session->AgencySignup, ['LogoFilename' => $FileName]);
                }
            }
        }

        public function step4Action() {
        }

        public function step5Action() {

        }
    }
