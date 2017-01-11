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
    class TwilioController extends ControllerBusinessBase {
        public function initialize() {
            $identity = $this->session->get('auth-identity');
            if ($identity && $identity['profile'] != 'User') {
                $this->tag->setTitle('Get Mobile Reviews | Subscription');
                $this->view->setTemplateBefore('private');
            } else {
                $this->response->redirect('/session/login?return=/');
                $this->view->disable();
                return;
            }
            parent::initialize();

            //add needed css
            $this->assets
                ->addCss('/assets/global/plugins/bootstrap-summernote/summernote.css')
                ->addCss('/css/subscription.css')
                ->addCss('/css/slider-extended.css')
                ->addCss('/assets/global/plugins/card-js/card-js.min.css');

            //add needed js
            $this->assets
                ->addJs('/assets/global/plugins/bootstrap-summernote/summernote.min.js')
                ->addJs('/assets/global/plugins/card-js/card-js.min.js');
        }
        public function indexAction(){
            $twilio_api_key = "";
            $twilio_auth_token = "";
            $twilio_auth_messaging_sid = "";
            $twilio_from_phone = "";
            $identity = $this->auth->getIdentity();
            if (!is_array($identity)) {
                $this->response->redirect('/session/login?return=/reviews/sms_broadcast');
                $this->view->disable();
                return;
            }
            $idxcx=$identity['id'];
            $db = $this->di->get('db');
            $db->begin();
            $result=$this->db->query(" SELECT * FROM `twilio_number_to_business` WHERE `buisness_id`='".$idxcx."'");
            $xcd=$result->numRows();
            if($xcd!=0){
                $this->view->twilio_details=$result->fetchAll();
                $this->view->pick("twilio/twilioNumberDetails");
            }
            $Twillioset=$this->getTwilioDetails();
            $twilio_api_key=$Twillioset['twilio_api_key'];
            if(($Twillioset['twilio_api_key']==""|| $twilio_api_key==NULL)||($Twillioset['twilio_auth_token']==""|| $Twillioset['twilio_auth_token']==NULL)){
                $this->view->pick("twilio/twilioNumberError");
                
            }else{
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
            
        }
        public function getTypeNumberAction(){
            $country_code=$_REQUEST['country_select'];
            $Twillioset=$this->getTwilioDetails();
            $client = new Services_Twilio($Twillioset['twilio_api_key'], $Twillioset['twilio_auth_token']);
            $uri = '/'. $client->getVersion() . '/Accounts/' . $Twillioset['twilio_api_key'] . '/AvailablePhoneNumbers.json';
            $all_numbers = $client->retrieveData($uri);
            $type_of_number=array();
            foreach ($all_numbers as $key => $full) {
                foreach ($full as $key => $typ) { 
                    if($typ->country_code==$country_code){
                      foreach ($typ->subresource_uris as $key => $value) {
                            if($key=="local"){
                                $type_of_number['Local']="Local";
                            }
                            if($key=="mobile"){
                                $type_of_number['Mobile']="Mobile";
                            }
                        }  
                    } 
                }
            }
            $result='<select name="number_type" id="number_type_select" class="form-control" style="width: 100%;" ><option value="">SELECT</option>';
            if(!empty($type_of_number)){
                foreach ($type_of_number as $key => $value) {
                   $result=$result.'<option value="'.$key.'">'.$value.'</option>';
                }
            }
            $result=$result.'</select>';
            return $result;
        }
        public function getAvailableNumberAction(){
            $this->view->setTemplateBefore('ajax');
            $number['local']=array();
            $number['Mobile']=array();
            $number['purchased']=array();
            $country_select=$_REQUEST['country_select'];
            $number_type_select=$_REQUEST['number_type_select'];
            $area_code=$_REQUEST['area_code'];
            $Contains=$_REQUEST['Contains'];
            $params_type['AreaCode']=$_REQUEST['area_code'];
            $params_type['Contains']=$_REQUEST['Contains'];
            $Twillioset=$this->getTwilioDetails();
            if($number_type_select==""){
               
                $client = new Services_Twilio($Twillioset['twilio_api_key'], $Twillioset['twilio_auth_token']); 
                $uri = '/'. $client->getVersion() . '/Accounts/' . $Twillioset['twilio_api_key'] . '/AvailablePhoneNumbers.json';
                $all_numbers = $client->retrieveData($uri);
                $type_of_number=array();
                //print_r($all_numbers);
                foreach ($all_numbers as $key => $full) {
                    foreach ($full as $key => $typ) { 
                        if($typ->country_code==$country_select){
                          foreach ($typ->subresource_uris as $keyxz => $value) {
                                
                                if($keyxz=="local"){
                                    $type_of_number['Local']="Local";
                                }
                                if($keyxz=="mobile"){
                                    $type_of_number['Mobile']="Mobile";
                                }
                            }  
                        } 
                    }
                }
                
                $client_get = new Services_Twilio($Twillioset['twilio_api_key'], $Twillioset['twilio_auth_token']); 
                foreach ($type_of_number as $key => $type_of) {
                    # code...
                    if($type_of=="Local"){
                      $number['Local']= $client_get->account->available_phone_numbers->getList(
                                        $country_select, 'Local', $params_type); 
                        $number['Local']=$number['Local']->available_phone_numbers;
                    }
                    if($type_of=="Mobile"){
                      $number['Mobile']= $client_get->account->available_phone_numbers->getList(
                                        $country_select, 'Mobile', $params_type); 
                        $number['Mobile']=$number['Mobile']->available_phone_numbers;
                    }
                }
                
            }else{
                $client_get = new Services_Twilio($Twillioset['twilio_api_key'], $Twillioset['twilio_auth_token']); 
                
              if($number_type_select=="Local"){
                        $number['Local']= $client_get->account->available_phone_numbers->getList(
                                        $country_select, 'Local', $params_type); 
                        $number['Local']=$number['Local']->available_phone_numbers;
              } 
              if($number_type_select=="Mobile"){
                        $number['Mobile']= $client_get->account->available_phone_numbers->getList(
                                        $country_select, 'Mobile', $params_type); 
                        $number['Mobile']=$number['Mobile']->available_phone_numbers;
              } 
            }
            
            $this->view->mobile=$number['Mobile'];
            $this->view->local=$number['Local'];
            $this->view->purchased=$number['purchased'];
            $this->view->pick("twilio/getlist");
        }
        public function available_numberAction(){
            $twilio_api_key = "";
            $twilio_auth_token = "";
            $twilio_auth_messaging_sid = "";
            $twilio_from_phone = "";
            $identity = $this->auth->getIdentity();
            if (!is_array($identity)) {
                $this->response->redirect('/session/login?return=/reviews/sms_broadcast');
                $this->view->disable();
                return;
            }
            $conditions = "id = :id:";
            $parameters = array("id" => $identity['id']);
            $userObj = Users::findFirst(array($conditions, "bind" => $parameters));
            $conditions = "agency_id = :agency_id:";
            $parameters = array("agency_id" => $userObj->agency_id);
            $agency = Agency::findFirst(array($conditions, "bind" => $parameters));
            if ($agency) {
                $this->view->agency = $agency;
                if (isset($agency->twilio_api_key) && $agency->twilio_api_key != "" && isset($agency->twilio_auth_token) && $agency->twilio_auth_token != ""  && isset($agency->twilio_from_phone) && $agency->twilio_from_phone != "") {
                        $twilio_api_key = $agency->twilio_api_key;
                        $twilio_auth_token = $agency->twilio_auth_token;
                        $twilio_from_phone = $agency->twilio_from_phone;
                } 
                if ($twilio_api_key  == "" && $twilio_auth_token == ""  && $twilio_from_phone == "") {
                    $parameters1 = array("agency_id" => $agency->parent_id);
                    $agency1 = Agency::findFirst(array($conditions, "bind" => $parameters1));
                    $twilio_api_key = $agency1->twilio_api_key;
                    $twilio_auth_token = $agency1->twilio_auth_token;
                    $twilio_from_phone = $agency1->twilio_from_phone;   
                }
            }
            echo $twilio_api_key;
            echo "<br>";
            echo $twilio_auth_token;
            echo "<br>";
            echo $twilio_from_phone;
            $client = new Services_Twilio($twilio_api_key, $twilio_auth_token);
            foreach ($client->account->incoming_phone_numbers as $number) {
                echo $number->phone_number;
            }
            foreach ($client->account->available_phone_numbers->getList('US') as $key => $value) {
                echo "<pre>";
                print_r($value);
            }
            // $clientx = new Pricing_Services_Twilio($twilio_api_key, $twilio_auth_token);
            // $phoneNumberCountries = $clientx->pricing->phoneNumbers->countries->read();

            // foreach ($phoneNumberCountries as $c) {
            // echo $c->isoCountry;
            // }
        }
        public function getPreviousNumberAction(){
            $this->view->setTemplateBefore('ajax');
            $number['local']=array();
            $number['Mobile']=array();
            $number['Purchased']=array();
            $Twillioset=$this->getTwilioDetails();
            $client = new Services_Twilio($Twillioset['twilio_api_key'], $Twillioset['twilio_auth_token']);
            $i=0;
            $db = $this->di->get('db');
            $db->begin();
            foreach ($client->account->incoming_phone_numbers as $numberx) {
                        $result=$this->db->query(" SELECT * FROM `twilio_number_to_business` WHERE `phone_number`='".$numberx->phone_number."'");
                           $x=$result->numRows();
                           if($x==0) {
                            $number['Purchased'][$i]['friendly_name']=$numberx->friendly_name;
                            $number['Purchased'][$i]['phone_number']=$numberx->phone_number;
                            $number['Purchased'][$i]['capabilities']['voice']=$numberx->capabilities->voice;
                            $number['Purchased'][$i]['capabilities']['sms']=$numberx->capabilities->sms;
                            $number['Purchased'][$i]['capabilities']['mms']=$numberx->capabilities->mms;
                            $i++;
                           }
                             
                        }            
            $this->view->mobile=$number['Mobile'];
            $this->view->local=$number['Local'];
            $this->view->purchased=$number['Purchased'];
            $this->view->pick("twilio/getlist");
        }
        public function reworkAction(){
            $this->user_object = $this->getUserObject();
            $identity = $this->auth->getIdentity();
            if (is_array($identity)) {
                $userObj = $this->getUserObject();
                echo $parameters=$userObj->agency_id;
            }
            
            $twilio_api_key = "";
            $twilio_auth_token = "";
            $twilio_auth_messaging_sid = "";
            $twilio_from_phone = "";
            $identity = $this->auth->getIdentity();
            if (!is_array($identity)) {
                $this->response->redirect('/session/login?return=/reviews/sms_broadcast');
                $this->view->disable();
                return;
            }
            $conditions = "id = :id:";
            $parameters = array("id" => $identity['id']);
            $userObj = Users::findFirst(array($conditions, "bind" => $parameters));
            $conditions = "agency_id = :agency_id:";
            $parameters = array("agency_id" => $userObj->agency_id);
            $agency = Agency::findFirst(array($conditions, "bind" => $parameters));
            if ($agency) {
                $this->view->agency = $agency;
                if (isset($agency->twilio_api_key) && $agency->twilio_api_key != "" && isset($agency->twilio_auth_token) && $agency->twilio_auth_token != ""  && isset($agency->twilio_from_phone) && $agency->twilio_from_phone != "") {
                        $twilio_api_key = $agency->twilio_api_key;
                        $twilio_auth_token = $agency->twilio_auth_token;
                        $twilio_from_phone = $agency->twilio_from_phone;
                } 
                if ($twilio_api_key  == "" && $twilio_auth_token == ""  && $twilio_from_phone == "") {
                    $parameters1 = array("agency_id" => $agency->parent_id);
                    $agency1 = Agency::findFirst(array($conditions, "bind" => $parameters1));
                    $twilio_api_key = $agency1->twilio_api_key;
                    $twilio_auth_token = $agency1->twilio_auth_token;
                    $twilio_from_phone = $agency1->twilio_from_phone;   
                }
            }
        }
        public function bookpurchasedAction($numbers){
            //echo base64_encode(
            //echo $numbers;
            $pieces = explode("||", $numbers);
            
            $identity = $this->auth->getIdentity();
            $id=$identity['id'];
            $number=base64_decode($pieces[0]);
            $friendly_number=base64_decode($pieces[1]);
            $db = $this->di->get('db');
            $db->begin();
            $createdxx=date('Y-m-d H:i:s');
            $Twillioset=$this->getTwilioDetails();
            $twilio_api_key=$Twillioset['twilio_api_key'];
            $twilio_auth_token=$Twillioset['twilio_auth_token'];
            $twilio_user_id=$Twillioset['twilio_user_id'];
            $result=$this->db->query(" INSERT INTO twilio_number_to_business ( `friendly_name`, `phone_number`, `buisness_id`, `created`,`updated`,`parent_twilio_api_key`,`parent_twilio_auth_token`,`parent_user_id`,`purchased`,`twilio_purchase_token`) VALUES ( '".$friendly_number."', '".$number."', '".$id."','".$createdxx."','".$createdxx."','".$twilio_api_key."','".$twilio_auth_token."','".$twilio_user_id."','0','')");
            $this->response->redirect('/twilio');
                $this->view->disable();
                return;
        }
        public function getTwilioDetails(){
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
        public function booknumberAction($numbers){
            $pieces = explode("||", $numbers);
            $number=base64_decode($pieces[0]);
            $friendly_number=base64_decode($pieces[1]);
            $Twillioset=$this->getTwilioDetails();
            $client = new Services_Twilio($Twillioset['twilio_api_key'], $Twillioset['twilio_auth_token']);
            // Purchase the first number on the list.
            $twilioNumber = $client->account->incoming_phone_numbers->create(
                                array(
                                "PhoneNumber" => $number
                                )
                            );
            $twilioNumber->sid;
            if($twilioNumber->sid){
                $twilio_api_key=$Twillioset['twilio_api_key'];
                $twilio_auth_token=$Twillioset['twilio_auth_token'];
                $twilio_user_id=$Twillioset['twilio_user_id'];
                $db = $this->di->get('db');
                $db->begin();
                $createdxx=date('Y-m-d H:i:s');
                $identity = $this->auth->getIdentity();
                $id=$identity['id'];
                $result=$this->db->query(" INSERT INTO twilio_number_to_business ( `friendly_name`, `phone_number`, `buisness_id`, `created`,`updated`,`parent_twilio_api_key`,`parent_twilio_auth_token`,`parent_user_id`,`purchased`,`twilio_purchase_token`) VALUES ( '".$friendly_number."', '".$number."', '".$id."','".$createdxx."','".$createdxx."','".$twilio_api_key."','".$twilio_auth_token."','".$twilio_user_id."','1','".$twilioNumber->sid."')");
                $this->response->redirect('/twilio');
                $this->view->disable();
                return;
            }

        }

    }