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
    /**
     * Display the default index page.
     */
    class TwilloController extends ControllerBusinessBase {
        public function initialize() {
            
        }
        public function reworkAction(){
            $this->user_object = $this->getUserObject();
            $identity = $this->auth->getIdentity();
            //print_r($identity);
            if (is_array($identity)) {
                $userObj = $this->getUserObject();
                echo $parameters=$userObj->agency_id;
            }
            // $AccountSid=$_REQUEST['AccountSid'];
            // $sid = "ACe1065454a067c5aaed51d03b39f90faf"; 
            // $token = "4a1eb70972f768ad2db7bfdf54715bda"; 
            // $client = new Services_Twilio($AccountSid, $token);
            //     foreach ($client->accounts as $account) {
            //         if($account->sid!=$AccountSid){
            //             echo $account->sid;
            //             echo "<br>";
            //             echo $account->friendly_name;
            //             echo "<br>";
            //             $clientx=new Services_Twilio($account->sid,$token);
            //             foreach ($account->incoming_phone_numbers as $number) {
            //                 echo $number->phone_number;
            //                 print_r($number->capabilities);
            //             }
            //         }
            //     }
        }
        public function formatTwilioPhone($phone) {
        $phone = preg_replace('/\D+/', '', $phone);
        if (strlen($phone) == 10)
            $phone = '1' . $phone;
        return '+' . $phone;
    }

    }