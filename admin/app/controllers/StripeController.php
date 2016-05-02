<?php
namespace Vokuro\Controllers;

use Phalcon\Tag;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Vokuro\Forms\ChangePasswordForm;
use Vokuro\Forms\StripeForm;
use Vokuro\Models\Location;
use Vokuro\Models\SubscriptionStripe;
use Vokuro\Models\Users;

/**
 * Vokuro\Controllers\StripeController
 * CRUD to manage stripe subscriptions
 */
class StripeController extends ControllerBase
{
  public function initialize()
  {
    if ($this->session->get('auth-identity')['profile'] == 'Agency Admin') {
      $this->tag->setTitle('Review Velocity | Subscriptions');
      $this->view->setTemplateBefore('private');
    } else {
      $this->response->redirect('/admin/session/login?return=/admin/stripe/');
      $this->view->disable();
      return;      
    }
    parent::initialize();
  }


  /**
    * Searches for stripe subscriptions
    */
  public function indexAction() {
    $identity = $this->auth->getIdentity();
    $conditions = "id = :id:";
    $parameters = array("id" => $identity['id']);
    $userObj = Users::findFirst(array($conditions, "bind" => $parameters));

    $conditions = "agency_id = :agency_id:";
    $parameters = array("agency_id" => $userObj->agency_id);
    $this->view->subscriptions = SubscriptionStripe::find(array($conditions, "bind" => $parameters));
  }


  /**
    * Creates a subscriptions
    */
  public function createAction()
  {
    $identity = $this->auth->getIdentity();
    $conditions = "id = :id:";
    $parameters = array("id" => $identity['id']);
    $userObj = Users::findFirst(array($conditions, "bind" => $parameters));

    if ($this->request->isPost()) {

      $sub = new SubscriptionStripe();
          
      $sub->assign(array(
        'agency_id' => $userObj->agency_id,
        'plan' => $this->request->getPost('plan', 'striptags'),
        'amount' => $this->request->getPost('amount'),
        'description' => $this->request->getPost('description'),
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
        
    $this->view->subscription = new SubscriptionStripe();
    $this->view->form = new StripeForm(null);
  }
}
