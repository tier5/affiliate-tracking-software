<?php namespace Vokuro\Services;
use Vokuro\Models\Agency;
use Vokuro\Models\EmailConfirmations;
use Vokuro\Models\Users;
use Vokuro\Models\UsersLocation;
use Vokuro\Models\Location;

/**
 * Class Email
 * @package Vokuro\Services
 */
class Email{

    protected $from = 'no-reply@no-domain.com';
    protected $from_name = '';

    /**
     * @var \Phalcon\DiInterface
     */
    protected $di;

    public function __construct() {
        $this->di = $this->getDI();
        $this->config = $this->di->get('config');
    }

    /**
     * @param string $from
     */
    public function setFrom($from,$from_name)
    {
        $this->from = $from;
        $this->from_name = $from_name;
        return $this;
    }

    public function getDI() {
        if($this->di) return $this->di;
        $di = new \Phalcon\Di();
        $this->di = $di->getDefault();
        return $this->di;
    }

    /**
     * @param $user_id
     */
    public function sendActivationEmailByUserId($user_id){
        $users = new Users();
        $record = $users->getById($user_id);
        if($record) return $this->sendActivationEmailToUser($record);
    }

    /**
     * @param Users $user
     */
    public function sendActivationEmailToUser(Users $user) {
        $confirmationModel = new EmailConfirmations();
        $record = $confirmationModel->getByUserId($user->getId());
        
        

        $template='confirmation';
        if(!$record) throw new \Exception("Could not find an Email Confirmation for user with email of:".$user->email);

        $Domain = $this->config->application->domain;

        $objAgency = \Vokuro\Models\Agency::findFirst("agency_id = {$user->agency_id}");
        if($objAgency->parent_id == \Vokuro\Models\Agency::BUSINESS_UNDER_RV) {
            $AgencyName = "Review Velocity";
            $AgencyUser = "Zach Anderson";
            $EmailFrom = "zacha@reviewvelocity.co";
            $EmailFromName="Zach Anderson";
            
        }
        elseif($objAgency->parent_id == \Vokuro\Models\Agency::AGENCY) { // Thinking about this... I don't think this case ever happens.  A user is created for a business, so I don't know when it would be an agency.
            $objAgencyUser = \Vokuro\Models\Users::findFirst("agency_id = {$objAgency->agency_id} AND role='Super Admin'");
            $AgencyUser =$objAgencyUser->name." ".$objAgencyUser->last_name;
            $AgencyName = $objAgency->name;
            $EmailFrom =  $objAgency->email;
            $EmailFromName='';

        }
        elseif($objAgency->parent_id > 0) {
            $objParentAgency = \Vokuro\Models\Agency::findFirst("agency_id = {$objAgency->parent_id}");
            $objAgencyUser = \Vokuro\Models\Users::findFirst("agency_id = {$objParentAgency->agency_id} AND role='Super Admin'");
            $AgencyName = $objParentAgency->name;
            $AgencyUser = $objAgencyUser->name." ".$objAgencyUser->last_name;
            if(!$objParentAgency->email_from_address && !$objParentAgency->custom_domain)
                throw new \Exception("Your email from address or your custom domain needs to be set to send email");
            $EmailFrom =$objParentAgency->email_from_address ?: "no_reply@{$objParentAgency->custom_domain}.{$Domain}";
            $EmailFromName=$objParentAgency->email_from_name ?: "";

           
            //$EmailFrom =$objParentAgency->email_from_address ?: "no_reply@{$objParentAgency->custom_domain}.{$Domain}";
        }
         if($AgencyName =='') {
            
            $AgencyName = "Review Velocity";
            $AgencyUser = "Zach Anderson";
            $EmailFrom = "zacha@reviewvelocity.co";
            $EmailFromName='';
            $template="agencyconfirmation";
         }

        $params = [
            'confirmUrl'=> '/confirm/' . $record->code . '/' . $user->email,
            'firstName' =>  $user->name,
            'AgencyName' => $AgencyName,
            'AgencyUser' => $AgencyUser,
           
        ];

        try {
            $mail = $this->getDI()->getMail();
            $mail->setFrom($EmailFrom,$EmailFromName);
            $mail->send($user->email, "You’re in :) | PLUS, a quick question...", $template, $params);
        } catch (Exception $e) {
            print $e;
            throw new \Exception('Not able to send email in:'.__CLASS__.'::'.__FUNCTION__);
        }
        return true;
    }

    public function sendEmployeeReport($dbEmployees, $objLocation, $tSendTo) {
        try {
            $objBusiness = \Vokuro\Models\Agency::findFirst("agency_id = {$objLocation->agency_id}");
            $objFacebookReviewSite = \Vokuro\Models\LocationReviewSite::findFirst("location_id = {$objLocation->location_id} AND review_site_id = " . \Vokuro\Models\Location::TYPE_FACEBOOK);
            $FacebookURL = $objFacebookReviewSite->external_location_id ? "http://www.facebook.com/{$objFacebookReviewSite->external_location_id}" : '';
            $mail = $this->getDI()->getMail();
            $Domain = $this->config->application->domain;

            if($objBusiness->parent_id == \Vokuro\Models\Agency::BUSINESS_UNDER_RV || $objBusiness->parent_id > 0) {
                if($objBusiness->parent_id > 0) {
                    $objAgency = \Vokuro\Models\Agency::findFirst("agency_id = {$objBusiness->parent_id}");
                    $EmailFrom = $objAgency->email_from_address ?: "no_reply@{$objAgency->custom_domain}.{$Domain}";
                    $EmailFromName = $objAgency->email_from_name ?: "";
                    $mail->setFrom($EmailFrom,$EmailFromName);
                    $FullDomain = "{$objAgency->custom_domain}.{$Domain}";
                } else {
                    $mail->setFrom('zacha@reviewvelocity.co','zacha Anderson');
                    $FullDomain = "{$Domain}";
                }
            } else {
                $objAgency = \Vokuro\Models\Agency::findFirst("agency_id = {$objBusiness->parent_id}");
                if(!$objAgency->email_from_address && !$objAgency->custom_domain)
                    throw new \Exception("Your email from address or your custom domain needs to be set to send email");
                $EmailFrom = $objAgency->email_from_address ?: "no_reply@{$objAgency->custom_domain}.{$Domain}";

                $EmailFromName = $objAgency->email_from_name ?: "";
                $mail->setFrom($EmailFrom,$EmailFromName);
                $FullDomain = "{$objAgency->custom_domain}.{$Domain}";
            }
            $objEmployees = $dbEmployees;

            $Params = array(
                'dbEmployees'           => $dbEmployees,
                'objLocation'           => $objLocation,
                'objAgency'             => $objAgency ?: null,
                'FullDomain'            => $FullDomain,
                'Website'               => $objBusiness->website,
                'FacebookURL'           => $FacebookURL,

            );
            
            //echo $objRecipient->email;exit;
            foreach($tSendTo as $objRecipient) {
                echo $mail->send($objRecipient->email, "Your monthly employee report!", 'employee_report', $Params);
                sleep(1);
            }
        } catch (Exception $e) {
            // GARY_TODO: Add logging!
            print $e;
            throw new \Exception('Not able to send email in:'.__CLASS__.'::'.__FUNCTION__);
        }

        return true;
    }

    public function sendResetPasswordEmailToUser(Users $user, $code){
        $objAgency = \Vokuro\Models\Agency::findFirst("agency_id = {$user->agency_id}");
        $Domain = $this->config->application->domain;
        if($objAgency->parent_id == \Vokuro\Models\Agency::BUSINESS_UNDER_RV) {
            $AgencyName = "Review Velocity";
            $AgencyUser = "Zach Anderson";
            $EmailFrom = "zacha@reviewvelocity.co";
            $EmailFromName ="";
        }
        elseif($objAgency->parent_id == \Vokuro\Models\Agency::AGENCY) {
            $objAgencyUser = \Vokuro\Models\Users::findFirst("agency_id = {$objAgency->agency_id} AND role='Super Admin'");
            $AgencyUser = $objAgencyUser->name;
            $AgencyName = $objAgency->name;
            if(!$objAgency->email_from_address && !$objAgency->custom_domain)
                throw new \Exception("Email from address not configured correctly.  Please contact support.");

            $EmailFrom = $objAgency->email_from_address ?: "no-reply@{$objAgency->custom_domain}.{$Domain}";
            $EmailFromName = $objAgency->email_from_name ?: "";
       }
        elseif($objAgency->parent_id > 0) {
            $objParentAgency = \Vokuro\Models\Agency::findFirst("agency_id = {$objAgency->parent_id}");
            $objAgencyUser = \Vokuro\Models\Users::findFirst("agency_id = {$objParentAgency->agency_id} AND role='Super Admin'");
            $AgencyName = $objParentAgency->name;
            $AgencyUser = $objAgencyUser->name;
            if(!$objParentAgency->email_from_address && !$objParentAgency->custom_domain)
                throw new \Exception("Email from address not configured correctly.  Please contact support.");

            $EmailFrom = $objParentAgency->email_from_address ?: "no-reply@{$objParentAgency->custom_domain}.{$Domain}";
            $EmailFromName = $objParentAgency->email_from_name ?: "";

        }

        $params = [];
        $params['resetUrl'] = '/session/resetPassword/' . $code . '/' . $user->email;
        $params['AgencyUser']=$AgencyUser;
        $params['AgencyName']=$AgencyName;
        $params['firstname']=$user->name;

        $mail = $this->getDI()->getMail();
        $mail->setFrom($EmailFrom,$EmailFromName);
        $mail->send($user->email, "Your password reset request", 'reset', $params);

        
    }

    public function sendActivationEmailToEmployee(Users $u,$from = null,$businessname=null){

        //echo $businessname;exit;
        $confirmationModel = new EmailConfirmations();
        $record = $confirmationModel->getByUserId($u->getId());

        if (!$record){
            //we don't have a confirmation
            $confirmationModel->send_email = false;
            $confirmationModel->usersId = $u->getId();
            $confirmationModel->save();
            $record = $confirmationModel;
        }

        /*** business Information **/
        if($u->is_all_locations==0)
        {
        $userslocation_info=\Vokuro\Models\UsersLocation::findFirst("user_id = {$u->id}");



        $Location=\Vokuro\Models\Location::findFirst("location_id = {$userslocation_info->location_id}");
        $busi_nam=$Location->name;
        }
        else
        {
            $busi_nam='All Business';
        }
/*
         echo 'mail2';
        exit;*/
        /*** business Information **/

        //get the email from address
        $code = $record->code;
        $agency = new Agency();
        $record = $agency->findFirst('agency_id = '.$u->agency_id);
        if($record->parent_id > \Vokuro\Models\Agency::AGENCY) {
            $objParentAgency = \Vokuro\Models\Agency::findFirst("agency_id = {$record->parent_id}");


            //$this->from = $from = $objParentAgency->email_from_name;
            if($objParentAgency->email_from_address)
            {
            $this->from = $from = $objParentAgency->email_from_address;
            $this->from_name = $from_name = $objParentAgency->email_from_name;
            }
            else
            {
            $this->from = $from = $objParentAgency->email;
            $this->from_name = $from_name ="";
            }
            //$this->from = $from = 'zacha@reviewvelocity.co';

            $objAgencyUser = \Vokuro\Models\Users::findFirst("agency_id = {$u->agency_id} AND role='Super Admin'");
            $oAgency = \Vokuro\Models\Users::findFirst("agency_id = {$objParentAgency->agency_id}");

            

           // $AgencyUser = $objAgencyUser->name;
            $AgencyUser = $oAgency->name." ".$oAgency->last_name;
            $AgencyName = $objParentAgency->name;
        } elseif($record->parent_id == \Vokuro\Models\Agency::BUSINESS_UNDER_RV) {
            $this->from = $from = 'no-reply@reviewvelocity.co';
            $AgencyName = "Review Velocity";
            $AgencyUser = "Zach Anderson";
            $this->from_name = $from_name = "Zach Anderson";
        }

        if(!$from) {
            $this->from = $from = "no-reply@{$objParentAgency->custom_domain}";
            $this->from_name = $from_name = "Zach Anderson";
        }

        if(!$u->is_employee){
            throw new \Exception('Cannot send an employee activation email to someone that is not an employee');
        }
            
        $mail = $this->getDI()->getMail();
        $mail->setFrom($from,$from_name);
        $params = [];
        $params['employeeName']=$u->name;
        $params['AgencyUser']=$AgencyUser;
        $params['AgencyName']=$AgencyName;
        $params['BusinessName']=$busi_nam;
        $params['confirmUrl'] = '/admin/confirmEmail/' . $code . '/' . $u->email;
       // $mail->send($u->email, "Welcome aboard!", 'employee', $params);

        $mail->send($u->email, "Activate your account!", 'employee', $params);

    }

    public function sendActivationEmailToEmployeeById($user_id) {
        $users = new Users();
        $record = $users->getById($user_id);
        if ($record) return $this->sendActivationEmailToEmployee($record);
    }

    /**
     * @param $user_id
     */
    public function sendResetPasswordEmailByUserId($user_id, $code)
    {
        // echo $user_id;exit;
        $users = new Users();
        $record = $users->getById($user_id);
        if ($record) return $this->sendResetPasswordEmailToUser($record, $code);
    }


}
