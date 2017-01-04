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
    use Vokuro\Services\Email;

    /**
     * Vokuro\Controllers\UsersController
     * CRUD to manage users
     */
    class UsersController extends ControllerBase
    {
        
        public function initialize()
        {
            if ($this->session->has('auth-identity')) {
                $this->tag->setTitle('Get Mobile Reviews | Users');
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
            $identity = $this->auth->getIdentity();
            $this->usersFunctionality(3);
            $this->getSMSReport();

        
        }


        /**
         * Searches for users
         */
        public function adminAction()
        {
            $identity = $this->auth->getIdentity();
            //dd($identity);
            //echo $identity['profilesId'];exit;
            $this->usersFunctionality($identity['profilesId']);
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
            $identity = $this->auth->getIdentity();
            $this->view->is_employee = isset($_GET['create_employee']) && $_GET['create_employee'] == 1 ? 1 : 0;
            $this->createFunction($identity['profilesId']);
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
         * @param int $subscription_id
         */
        public function createemployeeAction($subscription_id = 0)
        {
            $reviewgoal = $this->request->getPost('reviewgoal');
            $lifetimevalue = $this->request->getPost('lifetimevalue');
            $querystring = '?review_goal='.$reviewgoal.'&lifetime_value_customer='.$lifetimevalue;
            $url = '/session/signup4/'.($subscription_id > 0?$subscription_id:'').$querystring;

            $identity = $this->auth->getIdentity();
            if (!is_array($identity)) {
                $this->response->redirect('/session/login?return=/session/signup4/'.($subscription_id > 0?$subscription_id:''));
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
            $reviewgoal = $this->request->getPost('reviewgoal');
            $lifetimevalue = $this->request->getPost('lifetimevalue');
            $querystring = '?review_goal='.$reviewgoal.'&lifetime_value_customer='.$lifetimevalue;
            $url = '/location/create3/'.($location_id > 0?$location_id:'').$querystring;

            $identity = $this->auth->getIdentity();
            if (!is_array($identity)) {
                $this->response->redirect('/session/login?return=/location/create3/'.($location_id > 0?$location_id:''));
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
        public function createFunction($profilesId, $location_id = 0,$data = [])
        {

            $this->view->profilesId = $profilesId;
            $identity = $this->auth->getIdentity();

            // If there is no identity available the user is redirected to index/index
            if (!is_array($identity) && $profilesId == 3) {
                $this->response->redirect('/session/login?return=/users/'.($profilesId==3?'':'admin'));
                $this->view->disable();
                return;
            }
            // Query binding parameters with string placeholders
            $conditions = "id = :id:";
            $parameters = array("id" => $identity['id']);
            $userObj = Users::findFirst(array($conditions, "bind" => $parameters));
            $businessname=$userObj->name;

            if (defined('RV_TESTING') || $this->request->isPost()) {
                if(defined('RV_TESTING')) $_POST = $data;

                $user = new Users();

                $agency_id = defined('RV_TESTING') ? $data['agency_id'] : $userObj->agency_id;

                $user->assign(array(
                    'name' => $this->request->getPost('name', 'striptags'),
                    'profilesId' => $profilesId,
                    'email' => $this->request->getPost('email', 'email'),
                    'phone' => $this->request->getPost('phone'),
                    'agency_id' => $agency_id,
                    'create_time' => date('Y-m-d H:i:s'),
                ));

                $user->is_employee = (isset($_POST['is_employee']) && $_POST['is_employee'] == 'Yes') || $_POST['userType'] == 'User' ? 1 : 0;
		        $user->profilesId = ($_POST['userType'] == "User") ? 3 : 2;
                $user->role = $_POST['userType'];

                $isall = false;
                if(!empty($_POST['locations'])) {
                    foreach($_POST['locations'] as $check) {
                        if ($check == 'all') {
                            $isall = true;
                        }
                    }
                }
                //echo $isall;exit;
                $user->is_all_locations=($isall) ? 1 : 0;

                if (!$user->save()) {
                    $messages = array();
                    foreach ($user->getMessages() as $message) {
                        $messages[] = str_replace("profilesId", "role", $message->getMessage());//'The field ' . $message->getField() . ' is required';
                    }

                    $this->flash->error($messages);
                } else {

                     //print_r($_POST['locations']);exit;
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

                    if($user->is_employee){
                        $mail = new Email();
                        $mail->sendActivationEmailToEmployee($user,'',$businessname);
                    }

                    $this->flash->success("The user was created successfully");

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
            $identity = $this->auth->getIdentity();
            $this->editFunction($id, $identity['profilesId']);
            $this->view->render('users', 'edit');
            $this->view->disable();
            return;
        }


        public function linkAction($uid)
        {
                
            $id=base64_decode($uid);
            
            $conditions_user = "id = :id:";
            $parameters_user = array("id" => $id);
            $userinfo = Users::findFirst(array($conditions_user, "bind" => $parameters_user));
            if(empty($userinfo))
            {
                echo 'sorry this page does not exists';
                exit;
            }
            $conditions = "user_id = :user_id:";
            $parameters = array("user_id" => $id);
            $userObj = UsersLocation::find(array($conditions, "bind" => $parameters));
           /*if($userObj->location_id!='')
           {
            $conditions1 = "location_id = :location_id:";
            $parameters1 = array("location_id" => $userObj->location_id);
            $userObj1 = Location::findFirst(array($conditions1, "bind" => $parameters1));

            echo $userObj1->name;exit;
           }
            */
           $make_location_array=array();

           if(!empty($userObj))
           {
                foreach($userObj as $obj)
                {
                $conditions1 = "location_id = :location_id:";
                $parameters1 = array("location_id" => $obj->location_id);
                $userObj1 = Location::findFirst(array($conditions1, "bind" => $parameters1)); 
                    $make_location_array[$obj->location_id]=$userObj1->name;
                }

                //print_r($make_location_array);exit;
           }

               $this->view->userlocations = $make_location_array;
               $this->view->render('users', 'sendreviewlink');
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
                $this->response->redirect('/session/login?return=/users/'.($profilesId==3?'':'admin'));
                $this->view->disable();
                return;
            }

            $user = Users::findFirstById($id);
            if (!$user) {
                $this->flash->error("The ".($profilesId==3?'user':'admin user')." was not found");
                return $this->dispatcher->forward(array(
                    'action' => 'index'
                ));
            }



            // Query binding parameters with string placeholders
            $conditions = "id = :id:";
            $parameters = array("id" => $identity['id']);
            $userObj = Users::findFirst(array($conditions, "bind" => $parameters));
            //echo '<pre>$userObj:'.print_r($userObj->agency_id,true).'</pre>';
            //exit;

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
                $name = $this->request->getPost('name','striptags');
                if(strpos($name,' ') > -1){
                    //explode on space
                    $exploded = explode(' ',$name);
                    $first_name = $exploded[0];
                    $last_name = $exploded[1];
                }
                if(!$last_name) $last_name = "";
                $user->assign(array(
                    'name' => $name,
                    //'profilesId' => $profilesId,
                    'last_name'=>$last_name,
                    'email' => $this->request->getPost('email', 'email'),
                    'phone' => $this->request->getPost('phone'),
                    //'banned' => $this->request->getPost('banned'),
                    //'suspended' => $this->request->getPost('suspended'),
                    //'active' => $this->request->getPost('active')
                ));

                if (isset($_POST['is_employee']) && $_POST['is_employee']=='Yes' || $_POST['userType'] == 'User') {
                    $user->is_employee=1;
                } else {
                    $user->is_employee=0;
                }





                $user->profilesId = $_POST['type'] == 'User' ? 3 : 2;
                $user->role = $user->role == 'Super Admin' ? 'Super Admin' : $_POST['userType'];

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

                //dd($_POST['locations']);

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

                //dd($isall);

                $user->is_all_locations=($isall?1:0);

                if (!$user->save()) {
                    $this->flash->error($user->getMessages());
                } else {
                    $this->flash->success("The ".($user->profilesId==3?'user':'admin user')." was updated successfully");

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
                $this->response->redirect('/session/login?return=/users/'.($profilesId==3?'':'admin'));
                $this->view->disable();
                return;
            }

            $user = Users::findFirstById($id);
            if (!$user) {
                $this->flash->error("The ".($profilesId==3?'user':'admin user')." was not found");
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
                $this->flash->success("The ".($profilesId==3?'user':'admin user')." was deleted");
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
            $this->tag->setTitle('Get Mobile Reviews | Change password');
            $this->view->setTemplateBefore('login');
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
                    $_SESSION['password_save1']=$this->request->getPost('password');
                    $passwordChange = new PasswordChanges();
                    $passwordChange->user = $user;
                    $passwordChange->ipAddress = $this->request->getClientAddress();
                    $passwordChange->userAgent = $this->request->getUserAgent();

                    if (!$passwordChange->save()) {
                        $this->flash->error($passwordChange->getMessages());
                    } else {



                        /**** login credentials *****/
                        if($_SESSION['toemail_log']){
                    $feed_back_subj='Login Credentials';
                    $feed_back_body='Hi '.$_SESSION['name_log'].',';
                   

                        /*** login information ****/
                          if($_SESSION['password_save1'])
                        {   
                             $feed_back_body=$feed_back_body.'Login Details:</br>';
                             $feed_back_body=$feed_back_body.'<p>Please view the Login Credentials Below: </p>';
                             $feed_back_body=$feed_back_body.'Login URL:';
                             $feed_back_body=$feed_back_body."<br>Login Email: ".$feed_back_email."<br>";
                             $feed_back_body=$feed_back_body."Login Password: ". $_SESSION['password_save1']."<br>";
                        }

                        
                        /*** login information ****/

                        $feed_back_body=$feed_back_body."<br>".$_SESSION['AgencyUser_log']."<br>".$_SESSION['Agencyname_log'];
                       /* $Mail = $this->getDI()->getMail();
                        $Mail->setFrom($_SESSION['EmailFrom_log'],$_SESSION['EmailFromName_log']);
                        $Mail->send($_SESSION['toemail_log'], $feed_back_subj, '', '', $feed_back_body);*/


                         $mail = new Email();
                         $mail->sendLoginDetailsEmployee($_SESSION['confirm_user_id'],$_SESSION['password_save1']);

                        $_SESSION['password_save1']='';
                        $_SESSION['Agencyname_log']='';
                        $_SESSION['AgencyUser_log']='';
                        $_SESSION['EmailFrom_log']='';
                        $_SESSION['EmailFromName_log']='';
                        $_SESSION['toemail_log']='';
                        $_SESSION['name_log']='';
                    }
                        /**** login credentials *****/

                        $this->flash->success('Your password was successfully changed');

                        //if ($this->session->has('auth-identity')) {
                        //  Tag::resetInput();
                        //} else {
                        $this->response->redirect('/session/login');
                        $this->view->disable();
                        return;
                        //}
                    }
                }
            }

            $this->view->form = $form;
        }
    }
