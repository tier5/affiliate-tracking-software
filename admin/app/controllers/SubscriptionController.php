<?php

namespace Vokuro\Controllers;

use Phalcon\Tag;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Vokuro\Forms\ChangePasswordForm;
use Vokuro\Models\Location;
use Vokuro\Forms\SubscriptionForm;
use Vokuro\Models\Subscription;
use Vokuro\Models\SubscriptionInterval;

/**
 * Vokuro\Controllers\SubscriptionController
 * CRUD to manage users
 */
class SubscriptionController extends ControllerBase {

    public function initialize() {
        
        // TODO: Original code
        // if (isset($this->session->get('auth-identity')['is_admin']) && $this->session->get('auth-identity')['is_admin'] > 0) {
        //     $this->tag->setTitle('Review Velocity | Subscription');
        //     $this->view->setTemplateBefore('private');
        // } else {
        //     $this->response->redirect('/admin/session/login?return=/admin/');
        //     $this->view->disable();
        //     return;
        // }
        $identity = $this->session->get('auth-identity');
        if ($identity['profile'] != 'Employee') {
            $this->tag->setTitle('Review Velocity | Subscription');
            $this->view->setTemplateBefore('private');
        } else {    
            $this->response->redirect('/admin/session/login?return=/admin/');
            $this->view->disable();
            return;
        }
        parent::initialize();
    }

    /**
     * Searches for subscriptions
     */
    public function indexAction() {
        // TODO: Original code
        // $this->view->subscriptions = Subscription::find();
        $this->getSMSReport();
    }

    /**
     * Creates a subscriptions
     */
    public function createAction() {
        if ($this->request->isPost()) {

            $sub = new Subscription();

            $sub->assign(array(
                'name' => $this->request->getPost('name', 'striptags'),
                'subscription_interval_id' => $this->request->getPost('subscription_interval_id', 'int'),
                'duration' => $this->request->getPost('duration', 'int'),
                'amount' => $this->request->getPost('amount'),
                'trial_amount' => $this->request->getPost('trial_amount'),
                'trial_length' => $this->request->getPost('trial_length', 'int'),
            ));

            if (!$sub->save()) {
                $messages = array();
                foreach ($sub->getMessages() as $message) {
                    $messages[] = str_replace("subscription_interval_id", "Interval", $message->getMessage());
                }

                $this->flash->error($messages);
            } else {
                $this->flash->success("The subscription was created successfully");

                Tag::resetInput();
            }
        }

        // find all subscription intervals for the form
        $this->view->subscription_intervals = SubscriptionInterval::find();
        //end finding subscription intervals        

        $this->view->subscription = new Subscription();
        $this->view->form = new SubscriptionForm(null);
    }

}
