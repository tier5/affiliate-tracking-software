<?php
    namespace Vokuro\Controllers;
    use Phalcon\Mvc\View;
    use Vokuro\Services\ServicesConsts;
    use Vokuro\Models\Agency;
    use Vokuro\Models\Users;
    use Vokuro\Models\AuthorizeDotNet as AuthorizeDotNetModel;
    use Vokuro\Models\AgencyPricingPlan;


    class AgencysignupController extends ControllerBase {
        protected $EncryptionKey = "0bf14113f8d657cbb4aad17753592fd4d278672f0cd6f8d4722bd907965786bf";
        protected $DefaultSubscription = "97 Ten for ten";
        protected $DefaultUpgradeSubscription = "97 Twenty for eight";
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
        	'Country',
        	'Phone',
            'Email',
            'Website',
            'EmailFromName',
            'EmailFromAddress',
            'sbyp',

            /* Step 2 Fields */
            'LogoFilename',
            'PrimaryColor',
            'SecondaryColor',
            'sbyp',
            
            /* Step 3 Fields */
            'TwilioSID',
            'TwilioToken',
            'TwilioFromNumber',
            'sbyp',
            
            /* Step 4 Fields */
            'AgencyStripeSecretKey',
            'AgencyStripePublishableKey',
            'sbyp',

            /* Step 5 / Upgrade Step */
            'Upgrade',
            'sbyp',

            /* Order form Fields */
            'FirstName',
            'LastName',
            'OwnerEmail',
            'URL',
            'Password',
            'sbyp',
        ];

        protected $tAgencyFieldTranslation = [
            /* Step 1 Fields */
            'BusinessName'          => 'name',
            'Address'               => 'address',
            'Address2'              => 'address2',
            'City'                  => 'locality',
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
            'TwilioAPIKey'          => 'twilio_api_key',
            'TwilioToken'           => 'twilio_auth_token',
            'TwilioSID'             => 'twilio_auth_messaging_sid',
            'TwilioFromNumber'      => 'twilio_from_phone',
            // GARY_TODO Remove twilio_api_key from database?

            /* Step 4 Fields */

            // GARY_TODO:  Where is stripe_account_id in form?
            'AgencyStripeSecretKey'       => 'stripe_account_secret',
            'AgencyStripePublishableKey'  => 'stripe_publishable_keys',

            /* Order form Fields */
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
            	'Country',
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
         * Current subscriptons plan work as this.  The hash to the right represents the subscription label to the left.  The "Upgrade" button refers to the subscription immediately following the previous subscription so sbyp should never be a subscription "X Twenty for eight"
            0 Ten for ten -         P7NhtxYfDIxoJl%2FxF20M%2Bw%3D%3D
            0 Twenty for eight -    dnQyMo1njuKgu8ZDzyen5A%3D%3D
            97 Ten for ten -        JIJTX0QscOKPJcnueOPehQ%3D%3D
            97 Twenty for eight -   lIqhi1DFsZLPIu8vf7uJbA%3D%3D
            197 Ten for ten -       17MmTfedKoKXdfRkQbRvKQ%3D%3D
            197 Twenty for eight -  8Sv1GFdmxcpM6YnIwaD3sg%3D%3D

            Sample URL - http://getmobilereviews.com/agencysignup/order?sbyp=17MmTfedKoKXdfRkQbRvKQ%3D%3D
         */

        /**
         * Auto populate the session with form data, set their appropriate view variables and determine current step.
         */
        public function initialize() {
            if($_GET['sbyp'] || $_POST['sbyp']) {
                $sbyp = $_GET['sbyp'] ? $_GET['sbyp'] : $_POST['sbyp'];
                // For current measures, the id should always be odd due to the way the signup process works.  Otherwise use the defaults
                $id = openssl_decrypt($sbyp, 'aes-256-cbc', hex2bin($this->EncryptionKey));
                if($id % 2 == 0) {
                    $this->CurrentSubscription = $this->DefaultSubscription;
                    $this->CurrentUpgradeSubscription = $this->DefaultUpgradeSubscription;
                } else {
                    $this->CurrentSubscription = $this->GetAgencyPricingPlanByHash($sbyp);
                    $id++;
                    $objUpgradeSubscription = \Vokuro\Models\AgencyPricingPlan::findFirst("id = {$id}");
                    if($objUpgradeSubscription) {
                        $this->CurrentUpgradeSubscription = $objUpgradeSubscription->name;
                    } else {
                        // Again going to fallback on defaults
                        $this->CurrentSubscription = $this->DefaultSubscription;
                        $this->CurrentUpgradeSubscription = $this->DefaultUpgradeSubscription;
                    }
                }
            } else {
                $this->CurrentSubscription = $this->DefaultSubscription;
                $this->CurrentUpgradeSubscription = $this->DefaultUpgradeSubscription;
            }

            if(!$this->session->AgencySignup)
                $this->session->AgencySignup = [];


            // Update Session Data
            $tData = [];
            if($this->request->isPost()) {
                $Post = $this->request->getPost();

                foreach ($this->tAllFormFields as $Field) {
                    if(isset($Post[$Field]))
                        $tData[$Field] = $this->request->getPost($Field, 'striptags');
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
                $objAgency->date_created = date("Y-m-d H:i:s", strtotime('now'));

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

                $objSubscriptionManager = new \Vokuro\Services\SubscriptionManager();
                $objSubscriptionManager->CreateDefaultSubscriptionPlan($objAgency->agency_id);

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
        protected function CreateSubscription($tData, $SkipInitial = false) {
            try {
                if (!$this->request->isPost())
                    throw new \Exception();

                $objPaymentService = $this->di->get('paymentService');

                $tParameters = [
                    'userId'                    => $tData['UserID'],
                    'provider'                  => ServicesConsts::$PAYMENT_PROVIDER_STRIPE,
                    'amount'                    => $tData['PricingPlan']['RecurringPayment'] * 100,
                    'initial_amount'            => $SkipInitial ? 0 : $tData['PricingPlan']['InitialFee'] * 100,
                    'type'                      => 'Agency',
                ];

                // GARY_TODO:  Refactor:  No reason to have to query the DB here.
                $objUser = \Vokuro\Models\Users::findFirst("id = " . $tData['UserID']);
                $objAgency = \Vokuro\Models\Agency::findFirst("agency_id = {$objUser->agency_id}");

                // This method is potentially called twice (To upgrade in the thank you action)
                $objAgencySubscriptionPlan = \Vokuro\Models\AgencySubscriptionPlan::findFirst("agency_id = {$objAgency->agency_id}");
                if(!$objAgencySubscriptionPlan)
                    $objAgencySubscriptionPlan = new \Vokuro\Models\AgencySubscriptionPlan();

                $objAgencySubscriptionPlan->agency_id = $objAgency->agency_id;
                $objAgencySubscriptionPlan->pricing_plan_id = $tData['PricingPlan']['PlanID'];
                $objAgencySubscriptionPlan->save();

                if (!$objPaymentService->changeSubscription($tParameters)) {
                    $this->flashSession->error('Could not create subscription.  Contact customer support.');
                    return false;
                }

            } catch (Exception $e) {
                $this->flashSession->error($e->getMessage());
                return false;
            }
            return true;
        }


        /**
         * @param $Hash
         * @return string
         */
        protected function GetAgencyPricingPlanByHash($Hash) {
            $id = openssl_decrypt($Hash, 'aes-256-cbc', hex2bin($this->EncryptionKey));
            $objPricingPlan = null;
            if($id)
                $objPricingPlan = \Vokuro\Models\AgencyPricingPlan::findFirst("id = {$id}");
            return $objPricingPlan ? $objPricingPlan->name : $this->DefaultSubscription;
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
            $this->view->setLayout('agencysignup');
        }

        protected function GetSubscriptionPricingPlan($Name) {
            $objPricingPlan = AgencyPricingPlan::findFirst("name='{$Name}'");

            if(!$objPricingPlan)
                $this->flashSession->error("Could not find subscription plan.  Contact customer support.");

            return [
                'InitialFee' => $objPricingPlan->initial_fee,
                'RecurringPayment' => $objPricingPlan->number_of_businesses * $objPricingPlan->price_per_business,
                'PlanID' => $objPricingPlan->id,
            ];
        }

        protected function IsUniqueEmail() {
        	$this->view->setLayout('');
        	if (isset($this->session->AgencySignup['OwnerEmail'])) {
            	return 4;
        	} else {
        		return -1;
        	}
        }

        protected function IsUniqueDomain() {
        	if (isset($this->session->AgencySignup['custom_domain'])) {
            	return count(Agency::find('custom_domain = "' . $this->session->AgencySignup['custom_domain'] . '"')) == 0;
        	} else {
        		return -1;
        	}
        }

        public function submitorderAction() {
/*
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
*/
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

            $SubscriptionPlanId = '';
            $this->view->TodayYear = date("Y");

            try {
                if ($this->request->isPost() && $this->ValidateFields('Order')) {
                    $this->db->begin();

                    $PricingPlan = $this->GetSubscriptionPricingPlan($this->CurrentSubscription);
                    $this->session->AgencySignup = array_merge($this->session->AgencySignup, ['PricingPlan' => $PricingPlan]);

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
                $SubscriptionPlan = $this->session->AgencySignup['Upgrade'] ? $this->CurrentUpgradeSubscription : $this->DefaultSubscription;
                $this->view->TodayYear = date("Y");

                try {
                    if ($this->request->isPost() && $this->ValidateFields('Order')) {
                        $this->db->begin();

                        $PricingPlan = $this->GetSubscriptionPricingPlan($SubscriptionPlan);
                        $this->session->AgencySignup = array_merge($this->session->AgencySignup, ['PricingPlan' => $PricingPlan]);

                        if (!$UserID = $this->CreateSubscription($this->session->AgencySignup, true)) {
                            $this->flashSession->error('Could not create subscription.  Contact customer support.');
                            $this->response->redirect('/agencysignup/step5');
                        }

                        $this->db->commit();
                    }
                } catch (Exception $e) {
                    $this->db->rollback();
                    return false;
                }
            }

             $objUser = Users::findFirst("id = " . $this->session->AgencySignup['UserID']);
            $this->UpdateAgency($objUser->agency_id);

            $this->view->setLayout('agencysignup');
        }

        public function step1Action() {
            $this->view->tCountries = $this->tCountries;
        }

        public function step2Action() {
            $this->ValidateFields('Step1');
            $this->StoreLogo();
            $this->view->Subdomain = $this->session->AgencySignup['URL'];
            $this->view->BusinessName = $this->session->AgencySignup['BusinessName'];
            $this->view->Phone = $this->session->AgencySignup['Phone'];
            $this->view->PrimaryColorNohash = str_replace('#', '', $this->session->AgencySignup['PrimaryColor']);
            $this->view->SecondaryColorNohash = str_replace('#', '', $this->session->AgencySignup['SecondaryColor']);
            $this->view->logo_path = !empty($this->session->AgencySignup['LogoFilename']) ? "/img/agency_logos/".$this->session->AgencySignup['LogoFilename'] : '';
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

        public function step3Action() {
            $this->StoreLogo();
        }

        public function step4Action() {
        }

        public function step5Action() {
        }
    }
