<?php
namespace Vokuro\Controllers;

use Vokuro\Models\Agency;
use Vokuro\Models\Location;
use Vokuro\Models\LocationReviewSite;
use Vokuro\Models\ReviewInvite;
use Vokuro\Models\ReviewInviteNote;
use Vokuro\Models\ReviewSite;
use Vokuro\Models\Users;

/**
 * Display the contacts page.
 */
class ContactsController extends ControllerBase
{
  public function initialize()
  {
      $this->tag->setTitle('Review Velocity | Contacts');
      parent::initialize();
  }

  /**
    * Default action.
    */
  public function indexAction()
  {
    $logged_in = is_array($this->auth->getIdentity());
    if ($logged_in) {
      if (isset($_POST['locationselect'])) {
        $user = $this->getUserObject();
        if(!$this->getPermissions()->canUserSetLocationId($user,$_POST['locationselect'])) throw new \Exception("
        User cannot set the location of {$_POST['locationselect']} for user id of: {$user->getId()}
        ");
       if(is_numeric($_POST['locationselect'])) $this->auth->setLocation($_POST['locationselect']);
      }

      $this->view->setVar('logged_in', $logged_in);
      $this->view->setTemplateBefore('private');
    } else {
      $this->response->redirect('/session/login?return=/contacts/');
      $this->view->disable();
      return;
    }

    //get the location and calculate the review total and avg.
    if (isset($this->session->get('auth-identity')['location_id'])) {
      $conditions = "location_id = :location_id:";
      $parameters = array("location_id" => $this->session->get('auth-identity')['location_id']);
      $loc = Location::findFirst(array($conditions, "bind" => $parameters));
      $this->view->location = $loc;

      //get a list of all review invites for this location
      $invitelist = ReviewInvite::getReviewInvitesByLocation($this->session->get('auth-identity')['location_id'], false);
      $this->view->invitelist = $invitelist;


      $this->getSMSReport();
    }
  }



  /**
    * View action.
    */
  public function viewAction($review_invite_id)
  {
    if(!is_numeric($review_invite_id)) throw new \Exception('Invalid $review_invite_id, expected integer');


    $logged_in = is_array($this->auth->getIdentity());
    if ($logged_in) {
      $this->view->setVar('logged_in', $logged_in);
      $this->view->setTemplateBefore('private');
    } else {
      $this->response->redirect('/session/login?return=/contacts/');
      $this->view->disable();
      return;
    }

    //find review invite data
    $conditions = "location_id = :location_id: AND review_invite_id = :review_invite_id: AND sms_broadcast_id IS NULL ";
    $parameters = array("location_id" => $this->session->get('auth-identity')['location_id'], "review_invite_id" => $review_invite_id);
    $review_invite = ReviewInvite::findFirst(array($conditions, "bind" => $parameters));
    if (!$review_invite) {
      $this->flash->error("Review Invite was not found");
      return $this->dispatcher->forward(array(
        'action' => 'index'
      ));
    }
    $this->view->review_invite = $review_invite;

    //find review invite list by phone number
    $review_invite_list = ReviewInvite::getReviewInvitesByPhone($this->session->get('auth-identity')['location_id'], $review_invite->phone);
    $this->view->invitelist = $review_invite_list;

    $conditions = "location_id = :location_id:";
    $parameters = array("location_id" => $this->session->get('auth-identity')['location_id']);
    $loc = Location::findFirst(array($conditions, "bind" => $parameters));
    $this->view->location = $loc;


    if ($this->request->isPost()) {
      $rin = new ReviewInviteNote();
      $rin->assign(array(
        'phone' => $review_invite->phone,
        'location_id' => $this->session->get('auth-identity')['location_id'],
        'user_id' => $this->session->get('auth-identity')['id'],
        'note' => $this->request->getPost('note', 'striptags'),
        'date_created' => date('Y-m-d H:i:s'),
      ));

      if (!$rin->save()) {
        $this->flash->error($rin->getMessages());
      } else {
        $this->flash->success("The note was created successfully");
      }
    }

    //find note list by phone number
    $conditions = "location_id = :location_id: AND phone = :phone:";
    $parameters = array("location_id" => $this->session->get('auth-identity')['location_id'], "phone" => $review_invite->phone);
    $note_list = ReviewInviteNote::find(array($conditions, "bind" => $parameters));
    $this->view->note_list = $note_list;

    $this->getSMSReport();
  }

}
