<?php
namespace Vokuro\Controllers;

use Vokuro\Models\Agency;
use Vokuro\Models\Location;
use Vokuro\Models\Review;
use Vokuro\Models\ReviewInvite;
use Vokuro\Models\SharingCode;
use Vokuro\Models\Users;
use Vokuro\Models\UsersSubscription;

/**
 * Display the default index page.
 */
class AgencyController extends ControllerBase
{
    public function initialize()
    {
  
      $logged_in = is_array($this->auth->getIdentity());
      if ($logged_in) {
        if (isset($_POST['locationselect'])) {
          $this->auth->setLocation($_POST['locationselect']);
        }

        $this->view->setVar('logged_in', $logged_in);
        $this->view->setTemplateBefore('private');
      } else {        
        $this->response->redirect('/session/login');
        $this->view->disable();
        return;
      }
      parent::initialize();
    }

    /**
     * Default action. Set the public layout (layouts/private.volt)
     */
    public function indexAction()
    {
      $this->tag->setTitle('Review Velocity | Manage Businesses');

    }


    



}
