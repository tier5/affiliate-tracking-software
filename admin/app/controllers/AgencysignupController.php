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
            'AgencyStripeSecretKey',
            'AgencyStripePublishableKey',

            /* Step 5 / Upgrade Step */
            'Upgrade',

            /* Order form Fields */
            'FirstName',
            'LastName',
            'OwnerEmail',
            'URL',
            'Password',
        ];

        protected $tAgencyFieldTranslation = [
            /* Step 1 Fields */
            'BusinessName'          => 'name',
            'Address'               => 'address',
            'Address2'              => 'address2',
            'City'                  => 'city',
            'Country'               => 'country',
            'State'                 => 'state_province',
            'Zip'                   => 'postal_code',
            'Phone'                 => 'phone',
            'Email'                 => '',
            'Website'               => 'website',
            'EmailFromName'         => 'email_from_name',
            'EmailFromAddress'      => 'email_from_address',

            /* Step 2 Fields */
            'LogoFilename'          => 'logo_path',
            'PrimaryColor'          => 'main_color',
            'SecondaryColor'        => 'secondary_color',

            /* Step 3 Fields */
            'TwilioSID'             => 'twilio_auth_messaging_sid',
            'TwilioToken'           => 'twilio_auth_token',
            'TwilioFromNumber'      => 'twilio_from_phone',
            // GARY_TODO Remove twilio_api_key from database?

            /* Step 4 Fields */

            // GARY_TODO:  Where is stripe_account_id in form?
            'AgencyStripeSecretKey'       => 'stripe_account_secret',
            'AgencyStripePublishableKey'  => 'stripe_publishable_keys',

            /* Order form Fields */
            'FirstName'             => 'name',
            'LastName'              => 'last_name',
            'OwnerEmail'            => 'email',
            'URL'                   => 'custom_domain',
        ];

        protected $tUserFieldTranslation = [
            /* Order form Fields */
            'FirstName'             => 'name',
            'LastName'              => 'last_name',
            'OwnerEmail'            => 'email',
            'Phone'                 => 'phone',
            'URL'                   => 'custom_domain',
            'Password'              => 'password',
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
                'URL',
                'Password',
            ],
        ];

        protected $tAcceptedCardTypes = [
            'Visa',
            'Master Card',
            'American Express',
            'Discover',
        ];

        protected $tCountries = [
            "US" => "United States",
            "CA" => "Canada",
            "GB" => "United Kingdom",
            "AU" => "Australia",
            "AF" => "Afghanistan",
            "AX" => "Åland Islands",
            "AL" => "Albania",
            "DZ" => "Algeria",
            "AS" => "American Samoa",
            "AD" => "Andorra",
            "AO" => "Angola",
            "AI" => "Anguilla",
            "AQ" => "Antarctica",
            "AG" => "Antigua and Barbuda",
            "AR" => "Argentina",
            "AM" => "Armenia",
            "AW" => "Aruba",
            "AT" => "Austria",
            "AZ" => "Azerbaijan",
            "BS" => "Bahamas",
            "BH" => "Bahrain",
            "BD" => "Bangladesh",
            "BB" => "Barbados",
            "BY" => "Belarus",
            "BE" => "Belgium",
            "BZ" => "Belize",
            "BJ" => "Benin",
            "BM" => "Bermuda",
            "BT" => "Bhutan",
            "BO" => "Bolivia, Plurinational State of",
            "BQ" => "Bonaire, Sint Eustatius and Saba",
            "BA" => "Bosnia and Herzegovina",
            "BW" => "Botswana",
            "BV" => "Bouvet Island",
            "BR" => "Brazil",
            "IO" => "British Indian Ocean Territory",
            "BN" => "Brunei Darussalam",
            "BG" => "Bulgaria",
            "BF" => "Burkina Faso",
            "BI" => "Burundi",
            "KH" => "Cambodia",
            "CM" => "Cameroon",
            "CV" => "Cape Verde",
            "KY" => "Cayman Islands",
            "CF" => "Central African Republic",
            "TD" => "Chad",
            "CL" => "Chile",
            "CN" => "China",
            "CX" => "Christmas Island",
            "CC" => "Cocos (Keeling) Islands",
            "CO" => "Colombia",
            "KM" => "Comoros",
            "CG" => "Congo",
            "CD" => "Congo, the Democratic Republic of the",
            "CK" => "Cook Islands",
            "CR" => "Costa Rica",
            "CI" => "Côte d'Ivoire",
            "HR" => "Croatia",
            "CU" => "Cuba",
            "CW" => "Curaçao",
            "CY" => "Cyprus",
            "CZ" => "Czech Republic",
            "DK" => "Denmark",
            "DJ" => "Djibouti",
            "DM" => "Dominica",
            "DO" => "Dominican Republic",
            "EC" => "Ecuador",
            "EG" => "Egypt",
            "SV" => "El Salvador",
            "GQ" => "Equatorial Guinea",
            "ER" => "Eritrea",
            "EE" => "Estonia",
            "ET" => "Ethiopia",
            "FK" => "Falkland Islands (Malvinas)",
            "FO" => "Faroe Islands",
            "FJ" => "Fiji",
            "FI" => "Finland",
            "FR" => "France",
            "GF" => "French Guiana",
            "PF" => "French Polynesia",
            "TF" => "French Southern Territories",
            "GA" => "Gabon",
            "GM" => "Gambia",
            "GE" => "Georgia",
            "DE" => "Germany",
            "GH" => "Ghana",
            "GI" => "Gibraltar",
            "GR" => "Greece",
            "GL" => "Greenland",
            "GD" => "Grenada",
            "GP" => "Guadeloupe",
            "GU" => "Guam",
            "GT" => "Guatemala",
            "GG" => "Guernsey",
            "GN" => "Guinea",
            "GW" => "Guinea-Bissau",
            "GY" => "Guyana",
            "HT" => "Haiti",
            "HM" => "Heard Island and McDonald Islands",
            "VA" => "Holy See (Vatican City State)",
            "HN" => "Honduras",
            "HK" => "Hong Kong",
            "HU" => "Hungary",
            "IS" => "Iceland",
            "IN" => "India",
            "ID" => "Indonesia",
            "IR" => "Iran, Islamic Republic of",
            "IQ" => "Iraq",
            "IE" => "Ireland",
            "IM" => "Isle of Man",
            "IL" => "Israel",
            "IT" => "Italy",
            "JM" => "Jamaica",
            "JP" => "Japan",
            "JE" => "Jersey",
            "JO" => "Jordan",
            "KZ" => "Kazakhstan",
            "KE" => "Kenya",
            "KI" => "Kiribati",
            "KP" => "Korea, Democratic People's Republic of",
            "KR" => "Korea, Republic of",
            "KW" => "Kuwait",
            "KG" => "Kyrgyzstan",
            "LA" => "Lao People's Democratic Republic",
            "LV" => "Latvia",
            "LB" => "Lebanon",
            "LS" => "Lesotho",
            "LR" => "Liberia",
            "LY" => "Libya",
            "LI" => "Liechtenstein",
            "LT" => "Lithuania",
            "LU" => "Luxembourg",
            "MO" => "Macao",
            "MK" => "Macedonia, the former Yugoslav Republic of",
            "MG" => "Madagascar",
            "MW" => "Malawi",
            "MY" => "Malaysia",
            "MV" => "Maldives",
            "ML" => "Mali",
            "MT" => "Malta",
            "MH" => "Marshall Islands",
            "MQ" => "Martinique",
            "MR" => "Mauritania",
            "MU" => "Mauritius",
            "YT" => "Mayotte",
            "MX" => "Mexico",
            "FM" => "Micronesia, Federated States of",
            "MD" => "Moldova, Republic of",
            "MC" => "Monaco",
            "MN" => "Mongolia",
            "ME" => "Montenegro",
            "MS" => "Montserrat",
            "MA" => "Morocco",
            "MZ" => "Mozambique",
            "MM" => "Myanmar",
            "NA" => "Namibia",
            "NR" => "Nauru",
            "NP" => "Nepal",
            "NL" => "Netherlands",
            "NC" => "New Caledonia",
            "NZ" => "New Zealand",
            "NI" => "Nicaragua",
            "NE" => "Niger",
            "NG" => "Nigeria",
            "NU" => "Niue",
            "NF" => "Norfolk Island",
            "MP" => "Northern Mariana Islands",
            "NO" => "Norway",
            "OM" => "Oman",
            "PK" => "Pakistan",
            "PW" => "Palau",
            "PS" => "Palestinian Territory, Occupied",
            "PA" => "Panama",
            "PG" => "Papua New Guinea",
            "PY" => "Paraguay",
            "PE" => "Peru",
            "PH" => "Philippines",
            "PN" => "Pitcairn",
            "PL" => "Poland",
            "PT" => "Portugal",
            "PR" => "Puerto Rico",
            "QA" => "Qatar",
            "RE" => "Réunion",
            "RO" => "Romania",
            "RU" => "Russian Federation",
            "RW" => "Rwanda",
            "BL" => "Saint Barthélemy",
            "SH" => "Saint Helena, Ascension and Tristan da Cunha",
            "KN" => "Saint Kitts and Nevis",
            "LC" => "Saint Lucia",
            "MF" => "Saint Martin (French part)",
            "PM" => "Saint Pierre and Miquelon",
            "VC" => "Saint Vincent and the Grenadines",
            "WS" => "Samoa",
            "SM" => "San Marino",
            "ST" => "Sao Tome and Principe",
            "SA" => "Saudi Arabia",
            "SN" => "Senegal",
            "RS" => "Serbia",
            "SC" => "Seychelles",
            "SL" => "Sierra Leone",
            "SG" => "Singapore",
            "SX" => "Sint Maarten (Dutch part)",
            "SK" => "Slovakia",
            "SI" => "Slovenia",
            "SB" => "Solomon Islands",
            "SO" => "Somalia",
            "ZA" => "South Africa",
            "GS" => "South Georgia and the South Sandwich Islands",
            "SS" => "South Sudan",
            "ES" => "Spain",
            "LK" => "Sri Lanka",
            "SD" => "Sudan",
            "SR" => "Suriname",
            "SJ" => "Svalbard and Jan Mayen",
            "SZ" => "Swaziland",
            "SE" => "Sweden",
            "CH" => "Switzerland",
            "SY" => "Syrian Arab Republic",
            "TW" => "Taiwan, Province of China",
            "TJ" => "Tajikistan",
            "TZ" => "Tanzania, United Republic of",
            "TH" => "Thailand",
            "TL" => "Timor-Leste",
            "TG" => "Togo",
            "TK" => "Tokelau",
            "TO" => "Tonga",
            "TT" => "Trinidad and Tobago",
            "TN" => "Tunisia",
            "TR" => "Turkey",
            "TM" => "Turkmenistan",
            "TC" => "Turks and Caicos Islands",
            "TV" => "Tuvalu",
            "UG" => "Uganda",
            "UA" => "Ukraine",
            "AE" => "United Arab Emirates",
            "UM" => "United States Minor Outlying Islands",
            "UY" => "Uruguay",
            "UZ" => "Uzbekistan",
            "VU" => "Vanuatu",
            "VE" => "Venezuela, Bolivarian Republic of",
            "VN" => "Viet Nam",
            "VG" => "Virgin Islands, British",
            "VI" => "Virgin Islands, U.S.",
            "WF" => "Wallis and Futuna",
            "EH" => "Western Sahara",
            "YE" => "Yemen",
            "ZM" => "Zambia",
            "ZW" => "Zimbabwe",
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
                $this->view->current_step = $tMatches[1];
            } else {
                $this->view->current_step = "";
            }

            $this->view->PrimaryColor = isset($this->session->AgencySignup['PrimaryColor']) ? $this->session->AgencySignup['PrimaryColor'] : '#2a3644';
            $this->view->SecondaryColor = isset($this->session->AgencySignup['SecondaryColor']) ? $this->session->AgencySignup['SecondaryColor'] : '#2eb82e';
            $this->view->StripePublishableKey = $this->config->stripe->publishable_key;
        }

        /**
         * Validates required fields.  Returns true on success, or redirects user to appropriate page with the invalid field.
         */
        protected function ValidateFields($Page) {
            foreach($this->tRequiredFields[$Page] as $ReqField) {
                if(!isset($this->session->AgencySignup[$ReqField]) || !$this->session->AgencySignup[$ReqField]) {
                    $this->flashSession->error($ReqField . " cannot be empty.");
                    $this->response->redirect('/agencysignup/step' . strtolower($this->view->current_step - 1));
                }
            }

            return true;
        }

        protected function UpdateAgency($AgencyID) {
            $objAgency = Agency::findFirst("agency_id = {$AgencyID}");
            foreach ($this->tAgencyFieldTranslation as $FormField => $dbField) {
                if($dbField) {
                    $objAgency->$dbField = isset($this->session->AgencySignup[$FormField]) ? $this->session->AgencySignup[$FormField] : '';
                }
            }
            unset($dbField);

            $objAgency->save();
        }

        protected function CreateAgency($tData) {
            try {
                $objAgency = new Agency();
                foreach ($this->tAgencyFieldTranslation as $FormField => $dbField) {
                    if($dbField) {
                        $objAgency->$dbField = isset($tData[$FormField]) ? $tData[$FormField] : '';
                    }
                }
                unset($dbField);

                $objAgency->agency_type_id = 1; // REFACTOR:  Drop this column
                $objAgency->subscription_id = '';
                $objAgency->parent_id = \Vokuro\Models\Agency::AGENCY;

                if (!$objAgency->create()) {
                    $this->flashSession->error($objAgency->get_val_errors());
                    return false;
                }

                $objUser = new Users();
                $objUser->agency_id = $objAgency->agency_id;
                foreach ($this->tUserFieldTranslation as $FormField => $dbField) {
                    if($dbField) {
                        if ($FormField == 'Password')
                            $objUser->password = $this->security->hash($tData[$FormField]);
                        else
                            $objUser->$dbField = isset($tData[$FormField]) ? $tData[$FormField] : '';
                    }
                }
                unset($dbField);

                $objUser->mustChangePassword = 'N';
                $objUser->create_time = date("Y-m-d H:i:s");
                $objUser->is_employee = 0;
                $objUser->is_all_locations = 0;
                $objUser->send_confirmation = true;
                $objUser->profilesId = 1; // Agency Admin
                $objUser->role = "Super Admin";

                if(!$objUser->create()) {
                    $this->flashSession->error($objUser->getMessages());
                    return false;
                }

                return $objUser->id;

            } catch (Exception $e) {
                return false;
            }
        }

        protected function CreateProfile($tData) {
            try {
                if (!$this->request->isPost())
                    throw new \Exception();

                $objPaymentService = $this->di->get('paymentService');

                $tData['MonthExpiration'] = strlen($tData['MonthExpiration']) == 1 ? '0'.$tData['MonthExpiration'] : $tData['MonthExpiration'];

                $this->db->begin();
                if(!$UserID = $this->CreateAgency($this->session->AgencySignup)) {
                    $this->response->redirect('/agencysignup/order');
                    $this->db->rollback();
                    return false;
                }

                // I hate that this is in here.  Quick fix.  I dont want this function using the session at all.  This is due to wiping Authorize.net out completely and just getting this out.
                $this->session->AgencySignup = array_merge($this->session->AgencySignup, ['UserID' => $UserID]);

                $tParameters = [
                    'userName'          => $tData['FirstName'],
                    'lastName'          => $tData['LastName'],
                    'userEmail'         => $tData['OwnerEmail'],
                    'provider'          => ServicesConsts::$PAYMENT_PROVIDER_STRIPE,
                    'tokenID'           => $tData['StripeToken'],
                    'type'              => 'Agency',
                    'userId'            => $UserID,
                ];

                if(!$Profile = $objPaymentService->createPaymentProfile($tParameters)) {
                    $this->db->rollback();
                    $this->flashSession->error('Could not create payment profile.');
                    return false;
                }
                $this->db->commit();

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

                /*$objAuthDotNet = new AuthorizeDotNetModel();
                $objAuthDotNet->setUserId($UserID);
                $objAuthDotNet->setCustomerProfileId($tData['AuthProfile']['customerProfileId']);

                if (!$objAuthDotNet->create()) {
                    $this->flashSession->error($objAuthDotNet->getMessages());
                }*/

                $objPaymentService = $this->di->get('paymentService');

                $tParameters = [
                    'userId'                    => $tData['UserID'],
                    'provider'                  => ServicesConsts::$PAYMENT_PROVIDER_STRIPE,
                    'amount'                    => $tData['Price'] * 100,
                    'type'                      => 'Agency',
                ];

                if (!$objPaymentService->changeSubscription($tParameters)) {
                    $this->flashSession->error('Could not create subscription.  Contact customer support.');
                    return false;
                }

            } catch (Exception $e) {
                $this->response->redirect('/agencysignup/step5');
                return false;
            }
            return true;
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
            //$this->view->tCardTypes = $this->tAcceptedCardTypes;
            $this->view->setLayout('agencysignup');
        }

        protected function GetSubscriptionPrice($Name) {
            $objPricingPlan = AgencyPricingPlan::findFirst("name='{$Name}'");
            if(!$objPricingPlan)
                $this->flashSession->error("Could not find subscription plan.  Contact customer support.");

            return $objPricingPlan->number_of_businesses * $objPricingPlan->price_per_business;
        }

        protected function IsUniqueEmail($tData) {
            return count(Users::find('email = "' . $tData['OwnerEmail'] . '"')) == 0;
        }

        protected function IsUniqueDomain($tData) {
            return count(Agency::find('custom_domain = "' . $tData['URL'] . '"')) == 0;
        }

        public function submitorderAction() {

            if(!$this->IsUniqueEmail($this->session->AgencySignup)) {
                $this->flashSession->error("This email address is already in use.  Please use another one.");
                $this->response->redirect('/agencysignup/order');
                return false;
            }

            if(!$this->IsUniqueDomain($this->session->AgencySignup)) {
                $this->flashSession->error("This domain is already in use.  Please use another one.");
                $this->response->redirect('/agencysignup/order');
                return false;
            }

            $Token = $this->request->getPost('stripeToken', 'striptags');
            if($Token) {
                $this->session->AgencySignup = array_merge($this->session->AgencySignup, ['StripeToken' => $Token]);
            }
            else {
                $this->flashSession->error("Credit card declined.  If you feel this is an error, please contact our customer support.");
                $this->response->redirect('/agencysignup/order');
                return false;
            }

            try {
                if ($this->request->isPost() && $this->ValidateFields('Order')) {

                    if (!$Profile = $this->CreateProfile($this->session->AgencySignup)) {
                        $this->flashSession->error('Invalid credit card information');
                        return $this->response->redirect('/agencysignup/order');
                    }

                    $this->session->AgencySignup = array_merge($this->session->AgencySignup, ['AuthProfile' => $Profile]);

                }
            } catch(Exception $e) {
                $this->response->redirect('/agencysignup/order');
                return false;
            }

            $SubscriptionPlan = 'Ten for ten';
            $this->view->TodayYear = date("Y");

            try {
                if ($this->request->isPost() && $this->ValidateFields('Order')) {
                    $this->db->begin();

                    $Price = $this->GetSubscriptionPrice($SubscriptionPlan);
                    $this->session->AgencySignup = array_merge($this->session->AgencySignup, ['Price' => $Price]);

                    if (!$UserID = $this->CreateSubscription($this->session->AgencySignup)) {
                        $this->flashSession->error('Could not create subscription.  Contact customer support.');
                        $this->response->redirect('/agencysignup/order');
                    }

                    $this->db->commit();
                }
            } catch (Exception $e) {
                $this->db->rollback();
                $this->response->redirect('/agencysignup/order');
                return false;
            }

            $this->response->redirect('/agencysignup/step1');
            return true;
        }

        protected function GetAgencyUrl() {
            return "http://" . $this->session->AgencySignup['custom_domain'] . ".getmobilereviews.com";
        }

        public function thankyouAction() {
            if($this->session->AgencySignup['Upgrade']) {
                $SubscriptionPlan = $this->session->AgencySignup['Upgrade'] ? 'Twenty for eight' : 'Ten for ten';
                $this->view->TodayYear = date("Y");

                try {
                    if ($this->request->isPost() && $this->ValidateFields('Order')) {
                        $this->db->begin();

                        $Price = $this->GetSubscriptionPrice($SubscriptionPlan);
                        $this->session->AgencySignup = array_merge($this->session->AgencySignup, ['Price' => $Price]);

                        if (!$UserID = $this->CreateSubscription($this->session->AgencySignup)) {
                            $this->flashSession->error('Could not create subscription.  Contact customer support.');
                            $this->response->redirect('/agencysignup/step5');
                        }

                        $this->db->commit();

                        $objUser = Users::findFirst("id = " . $this->session->AgencySignup['UserID']);
                        $this->UpdateAgency($objUser->agency_id);
                    }
                } catch (Exception $e) {
                    $this->db->rollback();
                    return false;
                }
            }

            $this->view->setLayout('agencysignup');
        }

        public function step1Action() {
            $this->view->tCountries = $this->tCountries;
        }

        public function step2Action() {
            $this->ValidateFields('Step1');
            $this->StoreLogo();
            $this->view->Subdomain = $this->session->AgencySignup['URL'];
            $this->view->Name = $this->session->AgencySignup['BusinessName'];
            $this->view->Phone = $this->session->AgencySignup['Phone'];
            $this->view->PrimaryColorNohash = str_replace('#', '', $this->session->AgencySignup['PrimaryColor']);
            $this->view->SecondaryColorNohash = str_replace('#', '', $this->session->AgencySignup['SecondaryColor']);
            $this->view->LogoPath = !empty($this->session->AgencySignup['LogoFilename']) ? $this->session->AgencySignup['LogoFilename'] : '';
        }

        protected function StoreLogo() {
            if($this->request->hasFiles()) {
                foreach ($this->request->getUploadedFiles() as $file) {
                    // This is for handling page reloads.
                    if($file->getTempName()) {
                        if(isset($this->session->AgencySignup['LogoFilename']) && $this->session->AgencySignup['LogoFilename']) {
                            unlink(__DIR__ . "/../../public/img/agency_logos/" . $this->session->AgencySignup['LogoFilename']);
                            $this->session->AgencySignup = array_merge($this->session->AgencySignup, ['LogoFilename' => '']);
                        }

                        $FileName = uniqid('logo') . '.' . $file->getExtension();
                        file_put_contents(__DIR__ . "/../../public/img/agency_logos/{$FileName}", file_get_contents($file->getTempName()));
                        $this->session->AgencySignup = array_merge($this->session->AgencySignup, ['LogoFilename' => $FileName]);
                        break;
                    }
                }
            }
        }

//        public function salesAction () { // Moved to indexController indexAction; shown in getmobilereviews subdomains
//            $this->view->LogoSource = (isset($this->session->AgencySignup['LogoFilename']) && $this->session->AgencySignup['LogoFilename']) ? '/img/agency_logos/' . $this->session->AgencySignup['LogoFilename'] : '/img/logo-white.gif';
//            $this->view->setLayout('agencysignup');
//        }

        public function step3Action() {
            $this->StoreLogo();
        }

        public function step4Action() {
        }

        public function step5Action() {
        }
    }
