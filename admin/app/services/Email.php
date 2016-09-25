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
    public function sendActivationEmailToUser(Users $user){
        $confirmationModel = new EmailConfirmations();
        $record = $confirmationModel->getByUserId($user->getId());
        if(!$record) throw new \Exception("Could not find an Email Confirmation for user with email of:".$user->email);
        $params = [
            'confirmUrl'=> '/confirm/' . $record->code . '/' . $user->email,
            'firstName' => $user->getFirstName()
        ];
        try {
            $mail = $this->getDI()->getMail();
            $mail->setFrom('zacha@reputationloop.com');
            $mail->send($user->email, "You’re in :) | PLUS, a quick question...", 'confirmation', $params);
        } catch (Exception $e) {
            print $e;
            throw new \Exception('Not able to send email in:'.__CLASS__.'::'.__FUNCTION__);
        }
        return true;
    }

    public function sendEmployeeReport($dbEmployees, $objAgency, $objBusiness) {
        try {
            $mail = $this->getDI()->getMail();
            $mail->setFrom('zacha@reputationloop.com');
            $Params = [
                'dbEmployees'       => $dbEmployees,
                'objBusiness'       => $objBusiness,
                'objAgency'         => $objAgency,
            ];
            foreach($dbEmployees as $objUser) {
                $mail->send($objUser->email, "Your monthly employee report!", 'employee_report', $Params);
                die();
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
        $params['confirmUrl'] = '/confirm/' . $code . '/' . $u->email;
        $mail->send($u->email, "Employee subject", 'employee', $params);

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