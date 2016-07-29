<?php namespace Vokuro\Services;
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
        $params = [
            'confirmUrl'=> '/confirm/' . $this->code . '/' . $this->user->email,
            'firstName' => $user->getFirstName()
        ];
        try {
            $this->getDI()
                ->getMail()
                ->send($user->email, "You’re in :) | PLUS, a quick question...", 'confirmation', $params);
        } catch (Exception $e) {
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