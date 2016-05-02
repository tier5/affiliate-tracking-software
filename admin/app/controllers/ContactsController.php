<?php
namespace Vokuro\Controllers;

use Vokuro\Models\Agency;
use Vokuro\Models\Location;
use Vokuro\Models\LocationReviewSite;
use Vokuro\Models\ReviewInvite;
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
          $this->auth->setLocation($_POST['locationselect']);
        }

        $this->view->setVar('logged_in', $logged_in);
        $this->view->setTemplateBefore('private');
      } else {        
        $this->response->redirect('/admin/session/login?return=/admin/contacts/');
        $this->view->disable();
        return;
      }

      //get the location and calculate the review total and avg.
      if (isset($this->session->get('auth-identity')['location_id'])) {
        //get a list of all review invites for this location
        $invitelist = ReviewInvite::getReviewInvitesByLocation($this->session->get('auth-identity')['location_id']);
        $this->view->invitelist = $invitelist;


        $this->getSMSReport();
      }
    }

}
