<?php
namespace Vokuro\Controllers;

use Vokuro\Models\EmailConfirmations;
use Vokuro\Models\ResetPasswords;
use Vokuro\Models\Users;
use Vokuro\Services\UserManager;

/**
 * UserControlController
 * Provides help to users to confirm their passwords or reset them
 */
class UserControlController extends ControllerBase
{

    public function initialize()
    {
        if ($this->session->has('auth-identity')) {
            $this->view->setTemplateBefore('private');
        } else {
          $this->view->setTemplateBefore('login');
        }
      parent::initialize();
    }

    public function indexAction()
    {

    }

    /**
     * Confirms an e-mail, if the user must change thier password then changes it
     */
    public function confirmEmailAction()
    {
        $this->tag->setTitle('Review Velocity | Confirm email');
        $code = $this->dispatcher->getParam('code');
        $email = $this->dispatcher->getParam('email');
        $email = str_replace(' ', '+', $email);
        $confirmation = EmailConfirmations::findFirstByCode($code);
        if($confirmation && $confirmation->confirmed == 'N'){
            $confirmation->confirmed = 'Y';
            $confirmation->update();
        }

        $user_id = $confirmation->usersId;
        if ($user_id) $user = Users::findFirst('id = ' . $user_id);
        if ($user) {
            $user->active = 'Y';
            $user->last_name = ' ';
            $user->save();

        }
        //if($user) $error_messages = $user->getMessages();
        //dd($user->active); //it is a 'Y';

        //it is a 'N'


        //set user to active... need to check this out...
        if($confirmation) {
            $confirmation->confirmed = 'Y';
            $confirmation->user->active = 'Y';
            //$confirmation->user->save();
            $confirmation->save();
        }
        if (!$confirmation) {
            return $this->dispatcher->forward(array(
                'controller' => 'index',
                'action' => 'index'
            ));
        }
        if ($confirmation->confirmed == 'Y') {
            return $this->dispatcher->forward(array(
                'controller' => 'session',
                'action' => 'login',
                'params'=>['email'=>$email]
            ));
        }

        $confirmation->confirmed = 'Y';

        $confirmation->user->active = 'Y';

        /**
         * Change the confirmation to 'confirmed' and update the user to 'active'
         */
        $confirmation->save();
        if (!$confirmation->save()) {

            foreach ($confirmation->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                'controller' => 'index',
                'action' => 'index'
            ));
        }

        /**
         * Identify the user in the application
         */
        $this->auth->authUserById($user_id);

        /**
         * Check if the user must change his/her password
         */
        if ($confirmation->user->mustChangePassword == 'Y') {

            $this->flash->success('The email was successfully confirmed. Now you must change your password');

            return $this->dispatcher->forward(array(
                'controller' => 'users',
                'action' => 'changePassword'
            ));
        }

        //$this->flash->success('The email was successfully confirmed');

        return $this->dispatcher->forward(array(
            'controller' => 'session',
            'action' => 'login'
            ));
    }

    public function resetPasswordAction()
    {
        $this->tag->setTitle('Review Velocity | Reset password');
        $code = $this->dispatcher->getParam('code');

        $resetPassword = ResetPasswords::findFirstByCode($code);

        if (!$resetPassword) {
            return $this->dispatcher->forward(array(
                'controller' => 'index',
                'action' => 'index'
            ));
        }

        if ($resetPassword->reset != 'N') {
            return $this->dispatcher->forward(array(
                'controller' => 'session',
                'action' => 'login'
            ));
        }

        $resetPassword->reset = 'Y';

        /**
         * Change the confirmation to 'reset'
         */
        if (!$resetPassword->save()) {

            foreach ($resetPassword->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                'controller' => 'index',
                'action' => 'index'
            ));
        }

        /**
         * Identify the user in the application
         */
        $this->auth->authUserById($resetPassword->usersId);

        $this->flash->success('Please reset your password');

        return $this->dispatcher->forward(array(
            'controller' => 'users',
            'action' => 'changePassword'
        ));
    }
}
