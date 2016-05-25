<?php
namespace Vokuro\Controllers;

use Phalcon\Tag;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Vokuro\Forms\ChangePasswordForm;
use Vokuro\Models\Location;
use Vokuro\Forms\UsersForm;
use Vokuro\Models\Users;
use Vokuro\Models\UsersLocation;
use Vokuro\Models\PasswordChanges;

/**
 * Vokuro\Controllers\UsersController
 * CRUD to manage users
 */
class UsersController extends ControllerBase
{
    public function initialize()
    {
      if ($this->session->has('auth-identity')) {
        $this->tag->setTitle('Review Velocity | Users');
        $this->view->setTemplateBefore('private');
      } else {
        $this->view->setTemplateBefore('login');        
      }
      parent::initialize();
    }


    /**
     * Searches for users
     */
    public function indexAction()
    {
      $this->usersFunctionality(3);
      $this->getSMSReport();
    }


    /**
     * Searches for users
     */
    public function adminAction()
    {
      $this->usersFunctionality(1);
      $this->getSMSReport();
      try {
        $this->view->render('users', 'index');
        $this->view->disable();
        return;
      } catch (Exception $e) {

      }
    }

    


    /**
     * Searches for users
     */
    public function createAction()
    {
      $this->createFunction(3);
    }


    /**
     * Searches for users
     */
    public function admincreateAction()
    {
      $this->createFunction(1);
      try {
        $this->view->render('users', 'create');
        $this->view->disable();
        return;
      } catch (Exception $e) {

      }
    }
    

    


    /**
     * Searches for users
     */
    public function checkemailAction()
    {
      $email = $_POST['email'];
      $validate = new Users();
      $validate->email = $email;
      $this->view->disable();
      echo $validate->validation();
    }
    

    


    /**
     * Searches for users
     */
    public function createemployeeAction($subscription_id = 0)
    {
      //$('#reviewgoal').val($('#review_goal').val());
      //$('#lifetimevalue').val($('#lifetime_value_customer').val());
      $reviewgoal = $this->request->getPost('reviewgoal');
      $lifetimevalue = $this->request->getPost('lifetimevalue');
      $querystring = '?review_goal='.$reviewgoal.'&lifetime_value_customer='.$lifetimevalue;
      $url = '/admin/session/signup4/'.($subscription_id > 0?$subscription_id:'').$querystring;
//echo '<pre>post:'.print_r($_POST,true).'</pre>';

      //get the user id, to find the settings
      $identity = $this->auth->getIdentity();
      // If there is no identity available the user is redirected to index/index
      if (!is_array($identity)) {
        $this->response->redirect('/admin/session/login?return=/admin/session/signup4/'.($subscription_id > 0?$subscription_id:''));
        $this->view->disable();
        return;
      }
      $this->createFunction(3);
      
      //echo $url;
      $this->response->redirect($url);
      $this->view->disable();
      return;
    }
    

    


    /**
     * Searches for users
     */
    public function createemployee2Action($location_id)
    {
      //$('#reviewgoal').val($('#review_goal').val());
      //$('#lifetimevalue').val($('#lifetime_value_customer').val());
      $reviewgoal = $this->request->getPost('reviewgoal');
      $lifetimevalue = $this->request->getPost('lifetimevalue');
      $querystring = '?review_goal='.$reviewgoal.'&lifetime_value_customer='.$lifetimevalue;
      $url = '/admin/location/create3/'.($location_id > 0?$location_id:'').$querystring;
//echo '<pre>post:'.print_r($_POST,true).'</pre>';

      //get the user id, to find the settings
      $identity = $this->auth->getIdentity();
      // If there is no identity available the user is redirected to index/index
      if (!is_array($identity)) {
        $this->response->redirect('/admin/session/login?return=/admin/location/create3/'.($location_id > 0?$location_id:''));
        $this->view->disable();
        return;
      }
      $this->createFunction(3, $location_id);
      
      //echo $url;
      $this->response->redirect($url);
      $this->view->disable();
      return;
    }




    /**
     * Creates a User
     */
    public function createFunction($profilesId, $location_id = 0)
    {
      $this->view->profilesId = $profilesId;

      //get the user id
      $identity = $this->auth->getIdentity();
      // If there is no identity available the user is redirected to index/index
      if (!is_array($identity)) {
        $this->response->redirect('/admin/session/login?return=/admin/users/'.($profilesId==3?'':'admin'));
        $this->view->disable();
        return;
      }
      // Query binding parameters with string placeholders
      $conditions = "id = :id:";
      $parameters = array("id" => $identity['id']);
      $userObj = Users::findFirst(array($conditions, "bind" => $parameters));
      //echo '<pre>$userObj:'.print_r($userObj->agency_id,true).'</pre>';

      if ($this->request->isPost()) {

        $user = new Users();
          
        $user->assign(array(
          'name' => $this->request->getPost('name', 'striptags'),
          'profilesId' => $profilesId,
          'email' => $this->request->getPost('email', 'email'),
          'phone' => $this->request->getPost('phone'),
          'agency_id' => $userObj->agency_id,
          'create_time' => date('Y-m-d H:i:s'),
        ));
//echo '<pre>$user:'.print_r($user,true).'</pre>';
                  
        if (isset($_POST['type']) && $_POST['type']=='1') {
          $user->is_employee=1;
        } else {
          $user->is_employee=0;
        }

        $isall = false;
        if(!empty($_POST['locations'])) {
          foreach($_POST['locations'] as $check) {
            if ($check == 'all') {
              $isall = true;
            }
          }
        }
        $user->is_all_locations=($isall?1:0);

        if (!$user->save()) {
          $messages = array();
          foreach ($user->getMessages() as $message) {
            /*echo "<p>";
            echo "Message: ", $message->getMessage(), "\n";
            echo "Field: ", $message->getField(), "\n";
            echo "Type: ", $message->getType(), "\n";
            echo "</p>";*/
            $messages[] = str_replace("profilesId", "role", $message->getMessage());//'The field ' . $message->getField() . ' is required';
          }

          $this->flash->error($messages);
        } else {             
          //echo '<pre>$_POST[locations]:'.print_r($_POST['locations'],true).'</pre>';     
          //set locations on the user object after saving 
          if(!empty($_POST['locations'])) {
            foreach($_POST['locations'] as $check) {
              $locInsert = new UsersLocation();
              $locInsert->location_id = $check;
              $locInsert->user_id = $user->id;
              $locInsert->save();
            }
          } else if ($location_id > 0) {
            $locInsert = new UsersLocation();
            $locInsert->location_id = $location_id;
            $locInsert->user_id = $user->id;
            $locInsert->save();
          } else {
            $locInsert = new UsersLocation();
            $locInsert->location_id = $this->session->get('auth-identity')['location_id'];
            $locInsert->user_id = $user->id;
            $locInsert->save();
          }

          $this->flash->success("The ".($profilesId==3?'employee':'admin user')." was created successfully");

          Tag::resetInput();
        }
      }

      // find all locations for the form
      $this->view->locations = $this->auth->getLocationList($userObj);
      //end finding locations        
        
      $this->view->user = new Users();
      $this->view->form = new UsersForm(null);
    }

    


    /**
     * Saves the user from the 'edit' action
     */
    public function editAction($id)
    {
      $this->editFunction($id, 3);
    }

    


    /**
     * Saves the user from the 'edit' action
     */
    public function admineditAction($id)
    {
      $this->editFunction($id, 1);
      $this->view->render('users', 'edit');
      $this->view->disable();
      return;
    }



    /**
     * Saves the user from the 'edit' action
     */
    public function editFunction($id, $profilesId)
    {
      $this->view->profilesId = $profilesId;

      $identity = $this->auth->getIdentity();
      // If there is no identity available the user is redirected to index/index
      if (!is_array($identity)) {
        $this->response->redirect('/admin/session/login?return=/admin/users/'.($profilesId==3?'':'admin'));
        $this->view->disable();
        return;
      }

      $user = Users::findFirstById($id);
      if (!$user) {
        $this->flash->error("The ".($profilesId==3?'employee':'admin user')." was not found");
        return $this->dispatcher->forward(array(
          'action' => 'index'
        ));
      }
        
      // Query binding parameters with string placeholders
      $conditions = "id = :id:";
      $parameters = array("id" => $identity['id']);
      $userObj = Users::findFirst(array($conditions, "bind" => $parameters));
      //echo '<pre>$userObj:'.print_r($userObj->agency_id,true).'</pre>';

      //verify that the user is supposed to be here, by checking to make sure that
      //their agency_id matches the agency_id of the user they are trying to edit
      $agency_id_to_check = $user->agency_id;
      if ($agency_id_to_check > 0) {
          //if the agency id numbers do not match, log them out
//echo '<pre>$agency_id_to_check:'.$agency_id_to_check.':$userObj->agency_id:'.$userObj->agency_id.'</pre>';
          if ($agency_id_to_check != $userObj->agency_id) {
            $userObj->suspended = 'Y';
            $userObj->save();
            $this->auth->remove();
            return $this->response->redirect('index');               
          }
        }
        //end making sure the user should be here

      if ($this->request->isPost()) {
        $user->assign(array(
          'name' => $this->request->getPost('name', 'striptags'),
          //'profilesId' => $profilesId,
          'email' => $this->request->getPost('email', 'email'),
          'phone' => $this->request->getPost('phone'),
          //'banned' => $this->request->getPost('banned'),
          //'suspended' => $this->request->getPost('suspended'),
          //'active' => $this->request->getPost('active')
        ));
          
        if (isset($_POST['type']) && $_POST['type']=='1') {
          $user->is_employee=1;
        } else {
          $user->is_employee=0;
        }

        //delete all locations for this user
        //$conditions = "user_id = :user_id:";
        //$parameters = array("user_id" => $id);
        //$locationsDelete = UsersLocation::find(array($conditions, "bind" => $parameters));
        //$locationsDelete->delete();
        //only delete the locations that the logged in user has access to
        $dellocations = $this->auth->getLocationList($userObj);
        foreach($dellocations as $del) {
          $locInsert = new UsersLocation();
          $locInsert->location_id = $del->location_id;
          $locInsert->user_id = $id;
          $locInsert->delete();
        }

        $isall = false;
        if(!empty($_POST['locations'])) {
          foreach($_POST['locations'] as $check) {
            if ($check == 'all') {
              $isall = true;
            } else {
              $locInsert = new UsersLocation();
              $locInsert->location_id = $check;
              $locInsert->user_id = $id;
              $locInsert->save();
            }
          }
        }
        $user->is_all_locations=($isall?1:0);

        if (!$user->save()) {
            $this->flash->error($user->getMessages());
        } else {
          $this->flash->success("The ".($profilesId==3?'employee':'admin user')." was updated successfully");

          //Tag::resetInput();
        }
      }

      $this->view->user = $user;
        
      // find all locations for the form
      $this->view->locations = $this->auth->getLocationList($userObj);
      //end finding locations

      $conditions = "user_id = :user_id:";
      $parameters = array("user_id" => $id);
      $this->view->userlocations = UsersLocation::find(array($conditions, "bind" => $parameters));
      
      $this->view->user = $user;
      $this->view->form = new UsersForm($user, array(
          'edit' => true
      ));
    }

    
    public function deleteAction($id)
    {
      $this->deleteFunction($id, 3);
    }
    public function admindeleteAction($id)
    {
      $this->deleteFunction($id, 1);
    }

    /**
     * Deletes a User
     *
     * @param int $id
     */
    public function deleteFunction($id, $profilesId)
    {
      $identity = $this->auth->getIdentity();
      // If there is no identity available the user is redirected to index/index
      if (!is_array($identity)) {
        $this->response->redirect('/admin/session/login?return=/admin/users/'.($profilesId==3?'':'admin'));
        $this->view->disable();
        return;
      }

        $user = Users::findFirstById($id);
        if (!$user) {
            $this->flash->error("The ".($profilesId==3?'employee':'admin user')." was not found");
            return $this->dispatcher->forward(array(
                'action' => 'index'
            ));
        }

        //verify that the user is supposed to be here, by checking to make sure that
        //their agency_id matches the agency_id of the user they are trying to edit
        $agency_id_to_check = $user->agency_id;
        if ($agency_id_to_check > 0) {
            // Query binding parameters with string placeholders
            $conditions = "id = :id:";
            $parameters = array("id" => $identity['id']);
            $userObj = Users::findFirst(array($conditions, "bind" => $parameters));
            //echo '<pre>$userObj:'.print_r($userObj->agency_id,true).'</pre>';

            //if the agency id numbers do not match, log them out
  //echo '<pre>$agency_id_to_check:'.$agency_id_to_check.':$userObj->agency_id:'.$userObj->agency_id.'</pre>';
            if ($agency_id_to_check != $userObj->agency_id) {
              $userObj->suspended = 'Y';
              $userObj->save();
              $this->auth->remove();
              return $this->response->redirect('index');               
            }
         }
         //end making sure the user should be here

        if (!$user->delete()) {
            $this->flash->error($user->getMessages());
        } else {
            $this->flash->success("The ".($profilesId==3?'employee':'admin user')." was deleted");
        }

        return $this->dispatcher->forward(array(
            'action' => 'index'
        ));
    }




    /**
     * Users must use this action to change its password
     */
    public function changePasswordAction()
    {
        $this->tag->setTitle('Review Velocity | Change password');
        $this->view->setTemplateBefore('private');
        $form = new ChangePasswordForm();

        if ($this->request->isPost()) {

            if (!$form->isValid($this->request->getPost())) {

                foreach ($form->getMessages() as $message) {
                    $this->flash->error($message);
                }
            } else {

                $user = $this->auth->getUser();

                $user->password = $this->security->hash($this->request->getPost('password'));
                $user->mustChangePassword = 'N';

                $passwordChange = new PasswordChanges();
                $passwordChange->user = $user;
                $passwordChange->ipAddress = $this->request->getClientAddress();
                $passwordChange->userAgent = $this->request->getUserAgent();

                if (!$passwordChange->save()) {
                    $this->flash->error($passwordChange->getMessages());
                } else {

                    $this->flash->success('Your password was successfully changed');

                    //if ($this->session->has('auth-identity')) {
                    //  Tag::resetInput();
                    //} else {    
                      $this->response->redirect('/admin/session/login');
                      $this->view->disable();
                      return;
                    //}
                }
            }
        }

        $this->view->form = $form;
    }
}
