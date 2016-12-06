<?php
    namespace Vokuro\Models;

    use Phalcon\Mvc\Model;

    /**
     * ResetPasswords
     * Stores the reset password codes and their evolution
     */
    class ResetPasswords extends Model
    {


        public $id;
        public $usersId;
        public $code;
        public $createdAt;
        public $modifiedAt;
        public $reset;

        /**
         * Before create the user assign a password
         */
        public function beforeValidationOnCreate()
        {
            // Timestamp the confirmaton
            $this->createdAt = time();

            // Generate a random confirmation code
            $this->code = preg_replace('/[^a-zA-Z0-9]/', '', base64_encode(openssl_random_pseudo_bytes(24)));

            // Set status to non-confirmed
            $this->reset = 'N';
        }

        /**
         * Sets the timestamp before update the confirmation
         */
        public function beforeValidationOnUpdate()
        {
            // Timestamp the confirmaton
            $this->modifiedAt = time();
        }

        /**
         * Send an e-mail to users allowing him/her to reset his/her password
         */
        public function afterCreate()
        {
            //echo $this->usersId;exit;
           // echo 'yy';exit;
            /*$this->getDI()
                ->getMail()
                ->send($this->user->email, "Reset your password", 'reset', array(
                    'resetUrl' => '/session/resetPassword/' . $this->code . '/' . $this->user->email
                ));*/


        //$objAgency = \Vokuro\Models\Agency::findFirst("agency_id = {$this->user->agency_id}");
        /*if($objAgency->parent_id == \Vokuro\Models\Agency::BUSINESS_UNDER_RV) {
            $AgencyName = "Review Velocity";
            $AgencyUser = "Zach";
            //$EmailFrom = "zacha@reviewvelocity.co";
            
        }
        elseif($objAgency->parent_id == \Vokuro\Models\Agency::AGENCY) { // Thinking about this... I don't think this case ever happens.  A user is created for a business, so I don't know when it would be an agency.
            $objAgencyUser = \Vokuro\Models\Users::findFirst("agency_id = {$objAgency->agency_id} AND role='Super Admin'");
            $AgencyUser = $objAgencyUser->name;
            $AgencyName = $objAgency->name;
           // $EmailFrom = "zacha@reviewvelocity.co";

        }*/

       /* $params = [];
        $params['resetUrl'] = '/session/resetPassword/' . $this->code . '/' . $this->user->email;
        $params['AgencyUser']=$AgencyUser;
        $params['AgencyName']=$AgencyName;
        $params['firstname']=$this->user->name;

       echo '<pre>';print_r($objAgency);exit;
            $this->getDI()
            ->getMail()
            ->send($this->user->email, "Your password reset request", 'reset', $params);*/
             $email = new \Vokuro\Services\Email();
                 try {
          if($this->template == "reset"){
              $email->sendResetPasswordEmailByUserId($this->usersId, $this->code);
          }
          if($this->template == null){
              $email->sendResetPasswordEmailByUserId($this->usersId, $this->code);
          }


      } catch (Exception $e) {
          print $e;
        //do nothing
      }


        }

        public function initialize()
        {
            $this->belongsTo('usersId', __NAMESPACE__ . '\Users', 'id', array(
                'alias' => 'user'
            ));
        }
    }
