<?php namespace Vokuro\Services;
use Vokuro\Models\Agency;
use Vokuro\Models\EmailConfirmations;
use Vokuro\Models\Users;

/**
 * Class Email
 * @package Vokuro\Services
 */
class Email{

    protected $from = 'zacha@reputationloop.com';

    /**
     * @var \Phalcon\DiInterface
     */
    protected $di;

    public function __construct(){
        $this->di = $this->getDI();
    }

    /**
     * @param string $from
     */
    public function setFrom($from)
    {
        $this->from = $from;
        return $this;
    }

    public function getDI(){
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
        if(!$record) throw new \Exception("Could not find an Email Confirmation for user with email of:".$user->email);

        $objAgency = \Vokuro\Models\Agency::findFirst("agency_id = {$user->agency_id}");
        if($objAgency->parent_id == \Vokuro\Models\Agency::BUSINESS_UNDER_RV) {
            $AgencyName = "Review Velocity";
            $AgencyUser = "Zach";
            $EmailFrom = "zacha@reputationloop.com";
        }
        elseif($objAgency->parent_id == \Vokuro\Models\Agency::AGENCY) { // Thinking about this... I don't think this case ever happens.  A user is created for a business, so I don't know when it would be an agency.
            $objAgencyUser = \Vokuro\Models\Users::findFirst("agency_id = {$objAgency->agency_id} AND role='Super Admin'");
            $AgencyUser = $objAgencyUser->name;
            $AgencyName = $objAgency->name;
            $EmailFrom = $objAgencyUser->email;
        }
        elseif($objAgency->parent_id > 0) {
            $objParentAgency = \Vokuro\Models\Agency::findFirst("agency_id = {$objAgency->parent_id}");
            $objAgencyUser = \Vokuro\Models\Users::findFirst("agency_id = {$objParentAgency->agency_id} AND role='Super Admin'");
            $AgencyName = $objParentAgency->name;
            $AgencyUser = $objAgencyUser->name;
            $EmailFrom = $objAgencyUser->email;
        }

        $params = [
            'confirmUrl'=> '/confirm/' . $record->code . '/' . $user->email,
            'firstName' => $user->getFirstName(),
            'AgencyName' => $AgencyName,
            'AgencyUser' => $AgencyUser,
        ];

        try {
            $mail = $this->getDI()->getMail();
            $mail->setFrom($EmailFrom);
            $mail->send($user->email, "You’re in :) | PLUS, a quick question...", 'confirmation', $params);
        } catch (Exception $e) {
            print $e;
            throw new \Exception('Not able to send email in:'.__CLASS__.'::'.__FUNCTION__);
        }
        return true;
    }

    public function sendEmployeeReport($dbEmployees, $objBusiness) {
        try {

            $mail = $this->getDI()->getMail();
            $mail->setFrom('zacha@reputationloop.com');
            $objEmployees = $dbEmployees;

            $Params = array(
              'dbEmployees'       => $dbEmployees,
              'objBusiness'       => $objBusiness
            );

            for ($i = 0; $i < count($dbEmployees); ++$i) {
                echo $mail->send($dbEmployees[$i]->email, "Your monthly employee report!", 'employee_report', $Params);
                sleep(1);

            }

        } catch (Exception $e) {
            // GARY_TODO: Add logging!
            print $e;
            throw new \Exception('Not able to send email in:'.__CLASS__.'::'.__FUNCTION__);
        }

        return true;
    }

    public function sendResetPasswordEmailToUser(Users $user){
        $this->getDI()
            ->getMail()
            ->send($user->email, "Reset your password", 'reset', array(
                'resetUrl' => '/reset-password/' . $this->code . '/' . $user->email
            ));
    }

    public function sendActivationEmailToEmployee(Users $u,$from = null){
        $confirmationModel = new EmailConfirmations();
        $record = $confirmationModel->getByUserId($u->getId());

        if (!$record){
            //we don't have a confirmation
            $confirmationModel->send_email = false;
            $confirmationModel->usersId = $u->getId();
            $confirmationModel->save();
            $record = $confirmationModel;
        }
        //get the email from address
        $code = $record->code;
        $agency = new Agency();
        $record = $agency->findFirst('agency_id = '.$u->agency_id);
        if($record) {
            $email = $record->email;
            $domain = $record->custom_domain;
            if(!$email && $domain){
                //we set the domain to no-reply
                $this->from = 'no-reply@'.$domain;
            }
            $this->from = $email;
        }


        if(!$this->from) $from = 'no-reply@reputationloop.com';
        if(!$u->is_employee){
            throw new \Exception('Cannot send an employee activation email to someone that is not an employee');
        }
        $mail = $this->getDI()->getMail();
        $mail->setFrom($from);
        $params = [];
        $params['confirmUrl'] = '/admin/confirmEmail/' . $code . '/' . $u->email;
        $mail->send($u->email, "Welcome aboard!", 'employee', $params);

    }

    public function sendActivationEmailToEmployeeById($user_id){
        $users = new Users();
        $record = $users->getById($user_id);
        if ($record) return $this->sendActivationEmailToEmployee($record);
    }

    /**
     * @param $user_id
     */
    public function sendResetPasswordEmailByUserId($user_id)
    {
        $users = new Users();
        $record = $users->getById($user_id);
        if ($record) return $this->sendResetPasswordEmailToUser($record);
    }


}
