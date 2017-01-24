<?php
    namespace Vokuro\Controllers;

    use Vokuro\Models\Agency;
    use Vokuro\Models\Location;
    use Vokuro\Models\Review;
    use Vokuro\Models\ReviewInvite;
    use Vokuro\Models\SharingCode;
    use Vokuro\Models\Users;
    use Vokuro\Models\UsersSubscription;
    use Services_Twilio;
    use Services_Twilio_RestException;
    use Pricing_Services_Twilio;
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

        public function findBusinessescustom() {
            $Identity = $this->auth->getIdentity();

            if (!is_array($Identity)) {
                $this->response->redirect("/session/login?return=/agency");
                $this->view->disable();
                return;
            }

            $UserID = $Identity['id'];
            $objUser = Users::findFirst("id = {$UserID}");
            $objUser->agency_id;
            $tAgencies = Agency::find("agency_type_id = 2 AND parent_id = {$objUser->agency_id}");
            $businessArray=array();
            foreach($tAgencies as $agency)
            {
                //echo $agency->agency_id;
                $result=$this->db->query(" SELECT * FROM `users` WHERE `agency_id`='". $agency->agency_id."'");
                           $x=$result->fetch();
                           array_push($businessArray, $x);
                           
            }
            /*echo '<pre>';print_r($businessArray);
            //exit;
            exit;*/
            return $businessArray;
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
            $bagencies=$this->findBusinesses();
             $generate_array=array();
             foreach($bagencies as $agent)
                    {
                        $usersinfo = \Vokuro\Models\users::find("agency_id = " .$agent->agency_id );

                         if($agent->subscription_id >0)
                        {
                            $subcription_details=\Vokuro\Models\SubscriptionPricingPlan::findFirst("id = " .$agent->subscription_id ); 
                            $plan_name[$agent->agency_id]=$subcription_details->name;
                            /*echo $agent->name."-".$agent->agency_id."-".$subcription_details->name;
                            echo "<br>";*/
                            

                        }
                        foreach($usersinfo as $use)
                        {
                            //echo $use->id;//echo '<br>';
                            $subscription = \Vokuro\Models\BusinessSubscriptionPlan::findFirst("user_id = " .$use->id);
                                /*echo $use->id;
                                echo "-";
                                echo $subscription->payment_plan;
                                echo"-";
                                echo $agent->agency_id;
                                echo "<br>";*/
                                if($subscription->payment_plan!='')
                                {
                                    $generate_array[$agent->agency_id]=$subscription->payment_plan;
                                }
                               
                        }
                    }
                    $this->view->generate_array =$generate_array;
                    $this->view->plan_name =$plan_name;
                    //exit;
        }

        public function assignnumberAction($id)
        {
               $user_id=base64_decode($id);
               $this->view->user_id=$user_id; 
               $this->view->twilio_details=0;
                $Twillioset=$this->agencygetTwilioDetails();
                //dd($Twillioset);
                $twilio_api_key=$Twillioset['twilio_api_key'];
                if(($Twillioset['twilio_api_key']==""|| $twilio_api_key==NULL)||($Twillioset['twilio_auth_token']==""|| $Twillioset['twilio_auth_token']==NULL)){

                }
                $client = new Services_Twilio($Twillioset['twilio_api_key'], $Twillioset['twilio_auth_token']);
            $uri = '/'. $client->getVersion() . '/Accounts/' . $twilio_api_key . '/AvailablePhoneNumbers.json';
            $numbers = $client->retrieveData($uri);
            
            $country=array();
            foreach ($numbers as $key => $value) {
                foreach ($value as $key => $nox) {
                
                $country[$nox->country_code]=$nox->country;
                
                }
                
            }
            asort($country);
            $this->view->countries=$country;
        }


             public function agencygetTwilioDetails(){
            $twilio_api_key = "";
            $twilio_auth_token = "";
            $twilio_auth_messaging_sid = "";
            $twilio_from_phone = "";
            $identity = $this->auth->getIdentity();
            
            $conditions = "id = :id:";
            $parameters = array("id" => $identity['id']);
            $userObj = Users::findFirst(array($conditions, "bind" => $parameters));
            $conditions = "agency_id = :agency_id:";
            $parameters = array("agency_id" => $userObj->agency_id);
            $agency = Agency::findFirst(array($conditions, "bind" => $parameters));
            if ($agency) {
                $this->view->agency = $agency;
                if (isset($agency->twilio_api_key) && $agency->twilio_api_key != "" && isset($agency->twilio_auth_token) && $agency->twilio_auth_token != ""  && isset($agency->twilio_from_phone) && $agency->twilio_from_phone != "") {
                        $conditionsUser = "agency_id = :agency_id:";
                        $userParam=$parameters = array("agency_id" => $agency->agency_id);
                        $userObjNew = Users::findFirst(array($conditionsUser, "bind" => $userParam));
                        $twilio_user_id=$userObjNew->id;
                        $twilio_api_key = $agency->twilio_api_key;
                        $twilio_auth_token = $agency->twilio_auth_token;
                        $twilio_from_phone = $agency->twilio_from_phone;
                } 
                if ($twilio_api_key  == "" && $twilio_auth_token == ""  && $twilio_from_phone == "") {
                    $parameters1 = array("agency_id" => $agency->parent_id);
                    $agency1 = Agency::findFirst(array($conditions, "bind" => $parameters1));
                    $conditionsUser = "agency_id = :agency_id:";
                    $userParam=$parameters = array("agency_id" => $agency1->agency_id);
                    $userObjNew = Users::findFirst(array($conditionsUser, "bind" => $userParam));
                    $twilio_user_id=$userObjNew->id;
                    $twilio_api_key = $agency1->twilio_api_key;
                    $twilio_auth_token = $agency1->twilio_auth_token;
                    $twilio_from_phone = $agency1->twilio_from_phone;   
                }
            }
            $Twiio=array();
            $Twiio['twilio_user_id']=$twilio_user_id;
            $Twiio['twilio_api_key']=$twilio_api_key;
            $Twiio['twilio_auth_token']=$twilio_auth_token;
            $Twiio['twilio_from_phone']=$twilio_from_phone;
            return($Twiio);
        }




        public function customnumberAction()
        {
            $UpgradeSubscriptionPlanID = 4;
            $identity = $this->auth->getIdentity();

            $objUser = \Vokuro\Models\Users::findFirst('id = ' . $identity['id']);
            $objAgency = \Vokuro\Models\Agency::findFirst("agency_id = {$objUser->agency_id}");
          $this->tag->setTitle('Custom SMS number');
          $this->view->tBusinesses = $this->findBusinesses();
          $business=$this->findBusinesses();

          /*** twilio information ***/
            $twilio_api_key = "";
            $twilio_auth_token = "";
            $twilio_auth_messaging_sid = "";
            $twilio_from_phone = "";
            $identity = $this->auth->getIdentity();
            
            $conditions = "id = :id:";
            $parameters = array("id" => $identity['id']);
            $userObj = Users::findFirst(array($conditions, "bind" => $parameters));
            $conditions = "agency_id = :agency_id:";
            $parameters = array("agency_id" => $userObj->agency_id);
            $agency = Agency::findFirst(array($conditions, "bind" => $parameters));
            if ($agency) {
                $this->view->agency = $agency;
                if (isset($agency->twilio_api_key) && $agency->twilio_api_key != "" && isset($agency->twilio_auth_token) && $agency->twilio_auth_token != ""  && isset($agency->twilio_from_phone) && $agency->twilio_from_phone != "") {
                        $conditionsUser = "agency_id = :agency_id:";
                        $userParam=$parameters = array("agency_id" => $agency->agency_id);
                        $userObjNew = Users::findFirst(array($conditionsUser, "bind" => $userParam));
                        $twilio_user_id=$userObjNew->id;
                        $twilio_api_key = $agency->twilio_api_key;
                        $twilio_auth_token = $agency->twilio_auth_token;
                        $twilio_from_phone = $agency->twilio_from_phone;
                } 
                if ($twilio_api_key  == "" && $twilio_auth_token == ""  && $twilio_from_phone == "") {
                    $parameters1 = array("agency_id" => $agency->parent_id);
                    $agency1 = Agency::findFirst(array($conditions, "bind" => $parameters1));
                    $conditionsUser = "agency_id = :agency_id:";
                    $userParam=$parameters = array("agency_id" => $agency1->agency_id);
                    $userObjNew = Users::findFirst(array($conditionsUser, "bind" => $userParam));
                    $twilio_user_id=$userObjNew->id;
                    $twilio_api_key = $agency1->twilio_api_key;
                    $twilio_auth_token = $agency1->twilio_auth_token;
                    $twilio_from_phone = $agency1->twilio_from_phone;   
                }
            }
           // echo $twilio_from_phone;exit;
          /*** twilio information ***/

          $generate_array=array();
                 foreach($business as $agent)
                    {
                       
                      $usersinfo = \Vokuro\Models\users::findfirst("agency_id = " .$agent->id );
                      $result=$this->db->query("SELECT * FROM `twilio_number_to_business` WHERE `buisness_id`='".$usersinfo->id."'");
                           $x=$result->fetch();
                           $status=($x['phone_number']!='')?'Custom':'Default';
                           $phone=($x['phone_number']!='')?$x['friendly_name']:$twilio_from_phone;
                             $friendly_phone=($x['phone_number']!='')?$x['phone_number']:'none';
                           $action=($x['phone_number']!='')? '1':'2';
                      $generate_array[$agent->id]=$usersinfo->id."?".$status."?".$phone."?".$friendly_phone."?".$action;
                    }
                    //exit;
                   $this->view->generate_array=$generate_array;
           //$this->view->tBusinesses = $this->findBusinessescustom();
            //echo '<pre>';print_r($this->view->tBusinesses);exit;
        }
        
        public function emailisexistAction(){
            $email = trim( $this->request->getPost('email') );
            if(!$email) { return 'blank email'; }
            $conditions = "email like :email:";
            $parameters = array("email" => $email);
            $userObj = \Vokuro\Models\Users::findFirst("email like '".$email."'");
            $agencyObj = \Vokuro\Models\Agency::findFirst("email like '".$email."'");
            if($userObj || $agencyObj){
                echo 'exist';
                exit();
            }
            
             echo 'not exist';
             exit();
        }

    }
