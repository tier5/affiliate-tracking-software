<?php

/**
 * File Doc Comment
 *
 * @category Controller
 */

namespace Vokuro\Controllers;

use Vokuro\ArrayException;
use Vokuro\Forms\AgencyForm;
use Vokuro\Models\Agency;
use Vokuro\Models\Users;
use Vokuro\Models\Location;
use Vokuro\Models\Notification;
use Vokuro\Models\SubscriptionPricingPlan;

class ControllerBusinessBase extends ControllerBase
{
    /**
     * BEGIN BUSINESS COMMON FUNCTIONS
     */

    /**
     * Edit agencies/businesses
     * @param $agency_id
     * @return mixed
     **/
    public function editAction($agency_id = 0)
    {
        $this->view->agency_id = $agency_id;

        $objSubscriptionManager = new \Vokuro\Services\SubscriptionManager();
        $this->view->UnpaidPlan = $objSubscriptionManager->GetBusinessSubscriptionLevel($agency_id) == 'FR';

        $form = new AgencyForm(null);
        if ($agency_id) {
            $age = Agency::findFirst("agency_id = {$agency_id}");
            if (!$age) {
                $this->flash->error("The Entity with id:>" . $agency_id . "<:was not found");
            }
            $form = new AgencyForm($age);
        } else {
            $this->flash->error("The Entity with id:>" . $agency_id . "<:is not valid");
        }

        /*
        I don't know of a situation where this shouldn't ever exist. 
        I'm putting the check in just to make sure I don't break something else.
        */
        if ($agency_id) {
            $objSuperUser = \Vokuro\Models\Users::findFirst("agency_id = {$agency_id} AND role='Super Admin'");
            if($objSuperUser) {
                $objBusinessSubscription = \Vokuro\Models\BusinessSubscriptionPlan::findFirst(
                    "user_id = {$objSuperUser->id}"
                );
                $this->view->sms_messages = $objBusinessSubscription->sms_messages_per_location;
                $this->view->free_locations = $objBusinessSubscription->locations;
            }
        }


        if ($this->request->isPost()) {
            $errors = [];
            $messages = [];
            $IsEmailUnique = true;
            $IsEmailValid = true;
            $IsNameValid = true;
            // If is agency...
            if ($agency_id == 0) {
                $user = new Users();

                $user->assign(
                    array(
                    'name' => $this->request->getPost('admin_name', 'striptags'),
                    'email' => $this->request->getPost('admin_email'),
                    'profilesId' => 1, //All new users will be "Agency Admin"
                    )
                );

                $IsEmailUnique = $user->validation();

                $IsEmailValid = ($this->request->getPost('admin_email') != '');
                $IsNameValid = ($this->request->getPost('admin_name') != '');
            }
            /* Form valid? (Refactored from a maze of "nested ifs".  This is the best I could do on short notice) */
            $messages = [];

            if (!$form->isValid($this->request->getPost())) {
                $messages[] = $form->getMessages();
            }

            if (count($messages) > 0) {
                $error = true;
            }


            $db = $this->di->get('db');
            $db->begin();

            /* Attempt to create our new business */
            $params = [
                'agency_id'          => $agency_id,
                'name'               => $this->request->getPost('name', 'striptags'),
                'email'              => $this->request->getPost('email', 'striptags'),
                'address'            => $this->request->getPost('address', 'striptags'),
                'locality'           => $this->request->getPost('locality', 'striptags'),
                'state_province'     => $this->request->getPost('state_province', 'striptags'),
                'postal_code'        => $this->request->getPost('postal_code', 'striptags'),
                'country'            => ($this->request->getPost('country', 'striptags')) ? $this->request->getPost('country', 'striptags'): 'n/a',
                'phone'              => $this->request->getPost('phone', 'striptags'),
                'date_created'       => (isset($age->date_created) ? $age->date_created : date('Y-m-d H:i:s')),
                'subscription_id'    => $this->request->getPost('subscription_pricing_plan_id', 'striptags'),
                'deleted'            => (isset($age->deleted) ? $age->deleted : 0),
                'status'             => (isset($age->status) ? $age->status : 1),
                'subscription_valid' => (isset($age->subscription_valid) ? $age->subscription_valid : 'Y'),
            ];

            if (!$age->createOrUpdateBusiness($params)) {
                $error = true;

                foreach ($age->getMessages() as $error_message) {
                    $errors[] = $error_message;
                }
            }

            $CreateType = $age->parent_id == \Vokuro\Models\Agency::AGENCY ? 'agency' : 'business';

            if ($CreateType === 'business') {
                // Update subscription
                $objBusinessSubscription = \Vokuro\Models\BusinessSubscriptionPlan::findFirst(
                    "user_id = {$objSuperUser->id}"
                );
                if (!$objBusinessSubscription) {
                    $this->createSubscriptionPlan($objSuperUser, $this->request);
                } else {
                    $this->updateSubscriptionPlan($objSuperUser, $this->request);
                }
            }

            $this->flash->success(
                "The {$CreateType} was " . ($agency_id > 0 ? 'edited' : 'created') . " successfully"
            );

            $this->flash->success(
                'A confirmation email has been sent to ' . $this->request->getPost('admin_email')
            );

            if (!$errors) {
                $db->commit();
            }

            if ($errors) {
                $db->rollback();
            }
        }

        $identity = $this->getIdentity();

        $tUserIDs = [];

        // Get all user IDs for agency
        if ($agency_type_id != 1) {
            // Creating a business
            // Are we a super user?
            if ($identity['is_admin']) {
                $dbUsers = \Vokuro\Models\Users::find("is_admin = 1");
                foreach ($dbUsers as $objUser) {
                    $tUserIDs[] = $objUser->id;
                }
                unset($objUser);
            } else {
                $objLoggedInUser = \Vokuro\Models\Users::findFirst("id = " . $identity['id']);
                $dbUsers = \Vokuro\Models\Users::find("agency_id = " . $objLoggedInUser->agency_id);
                foreach ($dbUsers as $objUser) {
                    $tUserIDs[] = $objUser->id;
                }
                unset($objUser);
            }
        }

        $sub_selected = ($age && isset($age->subscription_id)) ? $age->subscription_id : null;
        
        if (!$sub_selected) {
            $sub_selected = 0;
        }
        
        $markup = $this->buildSubsriptionPricingPlanMarkUp($sub_selected, $tUserIDs);
        $this->view->setVar("subscriptionPricingPlans", $markup);

        $this->view->agency = new Agency();
        $this->view->form = $form;

        if ($agency_id > 0) {
            $conditions = "agency_id = :agency_id:";
            $parameters = array("agency_id" => $agency_id);
            $age2 = Agency::findFirst(array($conditions, "bind" => $parameters));
            $this->view->agency = $age2;
            $location = Location::findFirst('agency_id = '.$agency_id);
            $this->view->setVar("location", $location);
        }

        if ($errors) {
            dd($errors);
        }

        if ($this->request->isPost() && $this->view->agency) {
            $this->flash->success('Entity Saved');
            return $this->response->redirect('/?saved=1');
        }
    }

    /**
     * Creates business / agencies
     * @param $agency_type_id
     * @param $agency_id
     * @param $parent_id
     * @return
     */
    public function createAction($agency_type_id, $agency_id = 0, $parent_id = 0)
    {

        $this->view->agency_type_id = $agency_type_id;
        $this->view->agency_id = $agency_id;

        $form = new AgencyForm(null);
        $age = new Agency();
        $this->view->form = $form;
        
        if ($this->request->isPost()) {
            // $db = $this->di->get('db');
            //     $db->begin();
            //     $Notification = new Notification();
            // $params = ['to' => 1,
            //         'from' => 1,
            //         'message' => 1,
            //         'read' =>1, // 1 = Agency User, 2 = Business User
                    
            //     ];
            //     $Notification->createOrUpdateBusiness($params)
            //        $Notification->save();

            // print_r($_POST);exit;

            $errors = [];
            $messages = [];
            $IsEmailUnique = true;
            $IsEmailValid = true;
            $IsNameValid = true;
            
            // If is agency...
            if ($agency_id == 0) {
                $user = new Users();

                $user->assign(
                    array(
                        'name' => $this->request->getPost('admin_name', 'striptags'),
                        'email' => $this->request->getPost('admin_email'),
                        'profilesId' => 1, //All new users will be "Agency Admin"
                    )
                );

                $IsEmailUnique = $user->validation();

                $IsEmailValid = ($this->request->getPost('admin_email') != '');
                $IsNameValid = ($this->request->getPost('admin_name') != '');
                $CreateType = 'agency';
            } else {
                $CreateType = 'business';
            }
            
            /* Form valid? (Refactored from a maze of "nested ifs".  This is the best I could do on short notice) */
            $messages = [];

            if (!$form->isValid($this->request->getPost())) {
                $messages[] = $form->getMessages();
            }
            if (!$IsEmailUnique) {
                $messages[] = 'The admin email is already used.  Please enter a different email address.';
            }
            if (!$IsEmailValid) {
                $messages[] = 'Please enter an Admin Email.';
            }
            if (!$IsNameValid) {
                $messages[] = 'Please enter an Admin Full Name.';
            }
            if (count($messages) > 0) {
                $error = true;
            }

            $db = $this->di->get('db');
            $db->begin();
            
            
            /* Attempt to create our new business */
            $params = [
                'name'               => $this->request->getPost('name', 'striptags'),
                'agency_type_id'     => $agency_type_id,
                'email'              => $this->request->getPost('email', 'striptags'),
                'address'            => $this->request->getPost('address', 'striptags'),
                'locality'           => $this->request->getPost('locality', 'striptags'),
                'state_province'     => $this->request->getPost('state_province', 'striptags'),
                'postal_code'        => $this->request->getPost('postal_code', 'striptags'),
                'country'            => ($this->request->getPost('country', 'striptags')) ? $this->request->getPost('country', 'striptags'): 'n/a',
                'phone'              => $this->request->getPost('phone', 'striptags'),
                'date_created'       => (isset($age->date_created) ? $age->date_created : date('Y-m-d H:i:s')),
                'subscription_id'    => $this->request->getPost('subscription_pricing_plan_id', 'striptags'),
                'deleted'            => (isset($age->deleted) ? $age->deleted : 0),
                'status'             => (isset($age->status) ? $age->status : 1),
                'subscription_valid' => (isset($age->subscription_valid) ? $age->subscription_valid : 'Y'),
                'parent_id'          => $parent_id,
            ];
          
          if($this->request->getPost('subscription_pricing_plan_id')) {
            $subs_plan = SubscriptionPricingPlan::findFirst('id = '.$this->request->getPost('subscription_pricing_plan_id', 'striptags'));
            if($subs_plan && isset($subs_plan->max_sms_messages)) {
              $params['review_goal'] = $subs_plan->max_sms_messages;
            }
          }

            if (!$age->createOrUpdateBusiness($params)) {
                $error = true;

                foreach ($age->getMessages() as $error_message) {
                    $errors[] = $error_message;
                }
            } else {
                $an = $this->request->getPost('name', 'striptags');

                $msgx = $this->request
                             ->getPost('name', 'striptags')
                             ." is register under You with email ID "
                             .$this->request->getPost('email', 'striptags');

                $createdxx = date('Y-m-d H:i:s');
                $result = $this->db->query(
                    "INSERT INTO notification "
                    ."( `to`, `from`, `message`, `read`,`created`,`updated`) "
                    ."VALUES "
                    ."( '".$parent_id."', '".$an."', '".$msgx."', '0','".$createdxx."','".$createdxx."')"
                );

                /*** notification mail ***/
                $objSuperAdminUser = \Vokuro\Models\Users::findFirst(
                    'agency_id = ' . $parent_id . ' AND role="Super Admin"'
                );
                
                $planName = 'Free';
                
                if ($this->request->getPost('subscription_pricing_plan_id', 'striptags') != 0) {
                    $subscriptionPricePlan = SubscriptionPricingPlan::findFirst(
                        'id = '.$this->request->getPost(
                            'subscription_pricing_plan_id',
                            'striptags'
                        )
                    );

                    $db = $this->di->get('db');
                    $db->begin();

                    /* Attempt to create our new business */
                    $params = [
                        'name'               => $this->request->getPost('name', 'striptags'),
                        'agency_type_id'     => $agency_type_id,
                        'email'              => $this->request->getPost('email', 'striptags'),
                        'signup_page'=>2,
                        'address'            => $this->request->getPost('address', 'striptags'),
                        'locality'           => $this->request->getPost('locality', 'striptags'),
                        'state_province'     => $this->request->getPost('state_province', 'striptags'),
                        'postal_code'        => $this->request->getPost('postal_code', 'striptags'),
                        'country'            => ($this->request->getPost('country', 'striptags')) ? $this->request->getPost('country', 'striptags'): 'n/a',
                        'phone'              => $this->request->getPost('phone', 'striptags'),
                        'date_created'       => (isset($age->date_created) ? $age->date_created : date('Y-m-d H:i:s')),
                        'subscription_id'    => $this->request->getPost('subscription_pricing_plan_id', 'striptags'),
                        'deleted'            => (isset($age->deleted) ? $age->deleted : 0),
                        'status'             => (isset($age->status) ? $age->status : 1),
                        'subscription_valid' => (isset($age->subscription_valid) ? $age->subscription_valid : 'Y'),
                        'parent_id'          => $parent_id,
                    ];


                    if (!$age->createOrUpdateBusiness($params)) {
                        $error = true;
                        foreach ($age->getMessages() as $error_message) $errors[] = $error_message;
                    } else {
                        $an = $this->request->getPost('name', 'striptags');
                        $msgx = $this->request->getPost('name', 'striptags');
                        $msgx .= " is registered under You with email ID ";
                        $msgx .= $this->request->getPost('email', 'striptags');
                        $createdxx = date('Y-m-d H:i:s');
                        $result = $this->db->query(
                            "INSERT INTO notification ( `to`, `from`, `message`, `read`,`created`,`updated`) "
                            . "VALUES ( '".$parent_id."', '".$an."', '".$msgx."', '0','".$createdxx."','".$createdxx."')"
                        );
                        /*** notification mail ***/  
                        $objSuperAdminUser = \Vokuro\Models\Users::findFirst(
                            'agency_id = ' . $parent_id . ' AND role="Super Admin"'
                        );
                        $planName = 'Free';

                        if($this->request->getPost('subscription_pricing_plan_id', 'striptags') != 0){
                            $subscriptionPricePlan = SubscriptionPricingPlan::findFirst(
                                'id = '.$this->request->getPost('subscription_pricing_plan_id', 'striptags')
                            );
                            $planName = $subscriptionPricePlan->name;
                        }
                        
                        $planName = $subscriptionPricePlan->name;
                    }
                
                    $EmailFrom = 'no-reply@reviewvelocity.co';
                    $EmailFromName = "Zach Anderson";
                    $subject = "New Business Registered Successfully";
                    $mail_body = 'Dear '.$objSuperAdminUser->name.',';
                    $mail_body = $mail_body;
                    $mail_body .= '<p>Congratulations a new business has registered successfully with following details:
                        </p>';
                    $mail_body .= '<p>Name: '.$an.'</p>';
                    $mail_body .= '<p>Email: '.$this->request->getPost('email', 'striptags').'</p>';
                    $mail_body .= '<p>Subscription: '.$planName.'</p>';
                    $mail_body = $mail_body."Thanks";

                    $Mail = $this->getDI()->getMail();
                    $Mail->setFrom($EmailFrom, $EmailFromName);
                    $Mail->send($objSuperAdminUser->email, $subject, '', '', $mail_body);

                    /*** notification mail ***/

                    $resultx=$this->db->query(
                        " SELECT * FROM `notification` WHERE `to` =".$parent_id." AND `read` = 0"
                    );
                    $x=$resultx->numRows();
                    $this->view->setVar('NumberOfNotification', $x);
                }

                if ($errors) {
                    $db->rollback();
                    
                    $this->flash->error(
                        "There was an error creating the {$CreateType}<BR />" . implode("<BR />", $errors)
                    );
                    dd('errrr');
                   /// return false;
                }else{

                  /* Create an admin for this new agency */
                  $user = new Users();
                  $sendRegistrationOn = $this->request->getPost('send_registration_email', 'striptags');
                  $user->send_confirmation = $sendRegistrationOn === "on" ? true : false;
  
                  $user->assign(
                      array(
                          'name' => $this->request->getPost('admin_name', 'striptags'),
                          'email' => $this->request->getPost('admin_email'),
                          'agency_id' => $age->agency_id,
                          'profilesId' => $agency_type_id, // 1 = Agency User, 2 = Business User
                          'is_employee' => 1,
                          'role' => 'Super Admin',
                      )
                  );
  
                  if (!$user->save()) {
                      $error = true;
  
                      foreach ($user->getMessages() as $error_message) {
                          $errors[] = $error_message;
                      }
                  }
                  if ($errors) {
                      $db->rollback();
                      $this->flash->error("There was an error creating the {$CreateType}<BR />" . implode("<BR />", $errors));
                      return false;
                  } else {
                      $db->commit();
                  }
  
                  $dbUsers = \Vokuro\Models\Users::find(
                      'email = "' .  $this->request->getPost('admin_email') . '"'
                  );
                  
                  foreach ($dbUsers as $objUser) {
                      $newAdmin = $objUser;
                  }
                      unset($objUser);
  
                  $result = $this->createSubscriptionPlan($newAdmin, $this->request);
                  
                  if ($result != true) {
                      $this->flash->error($messages);
                  }
  
                  if ($age->agency_type_id == 1) {
                      // Create a default subscription for the agency
                      $objSubscriptionManager = new \Vokuro\Services\SubscriptionManager();
                      $objSubscriptionManager->CreateDefaultSubscriptionPlan($age->agency_id, true);
                  }
  
                  $this->view->isSuccess = 1;
  
                  $this->flash->success(
                      "The " . ($age->agency_type_id == 1 ? 'agency' : 'business') . " was created successfully"
                  );
                  $this->flash->success('A confirmation email has been sent to ' . $this->request->getPost('admin_email'));
                  //if(!$errors) $db->commit();
                  
                  if ($errors) {
                      $db->rollback();
                      $this->flash->error("There was an error creating <BR />" . implode("<BR />", $errors));
                      //return false;
                  }
                }
            }

            
        }
        $identity = $this->getIdentity();
        
            $tUserIDs = [];
            // Get all user IDs for agency
            if ($agency_type_id != 1) {
                // Creating a business
                // Are we a super user?
                if ($identity['is_admin']) {
                    $dbUsers = \Vokuro\Models\Users::find("is_admin = 1");
                    foreach ($dbUsers as $objUser) {
                        $tUserIDs[] = $objUser->id;
                    }
                    unset($objUser);
                } else {
                    $objLoggedInUser = \Vokuro\Models\Users::findFirst(
                        "id = " . $identity['id']
                    );
                    $dbUsers = \Vokuro\Models\Users::find(
                        "agency_id = " . $objLoggedInUser->agency_id
                    );
                    
                    foreach ($dbUsers as $objUser) {
                        $tUserIDs[] = $objUser->id;
                    }
                    unset($objUser);
                }
            }

            $sub_selected = ($age && isset($age->subscription_id)) ? $age->subscription_id : null;
            if (!$sub_selected) {
                $sub_selected = 0;
            }
            $markup = $this->buildSubsriptionPricingPlanMarkUp($sub_selected, $tUserIDs);
            $this->view->setVar("subscriptionPricingPlans", $markup);

            $this->view->agency = new Agency();
            $this->view->form = $form;
            $conditions = "agency_id = :agency_id:";
            $parameters = array("agency_id" => $agency_id);
            $age2 = Agency::findFirst(array($conditions, "bind" => $parameters));
            $this->view->agency = $age2;

            if ($errors) {
                //dd($errors);
            }

            if ($this->request->isPost() && $this->view->agency) {
                $this->flash->success('User Saved');

                return $this->response->redirect('/?saved=1');
            }
    }

    /**
     * The view of a agency/business
     */
    public function viewAction($agency_type_id, $agency_id = 0)
    {
        $this->view->agency_type_id = $agency_type_id;
        $this->view->agency_id = $agency_id;

        //set agency details
        $conditions = "agency_id = :agency_id:";
        $parameters = array("agency_id" => $agency_id);
        $agency = Agency::findFirst(array($conditions, "bind" => $parameters));
        $this->view->agency = $agency;

        //find all users associated with the agency
        $conditions = "agency_id = :agency_id:";
        $parameters = array("agency_id" => $agency_id);
        $users = Users::find(array($conditions, "bind" => $parameters));
        $this->view->users = $users;

        if (isset($_GET['s']) && $_GET['s'] == 1) {
            $this->flash->success('Success! Have the employee check their email for a reset password message');
        }

        if (isset($_GET['s']) && $_GET['s'] == 2) {
            $this->flash->success('Success! Have the employee check their email for a confirmation message');
        }
        if (isset($_GET['s']) && $_GET['s'] == 3) {
            $this->flash->success(
                'This user has not activated their account, please activate or resend the confirmation email'
            );
        }
    }

    private function buildSubsriptionPricingPlanMarkUp($selected_subscription_id = null, $tUserIDs)
    {
        $subscriptionPricingPlans = $this->di->get('subscriptionManager')->getSubscriptionPricingPlans($tUserIDs);
        $selected_subscription_id = (int)$selected_subscription_id;

        $markup = "<select id=\"subscription_pricing_plan_id\" name=\"subscription_pricing_plan_id\">";
        $markup .= "    <option value=\"0\">Unpaid</option>";  // This is default plan
        foreach ($subscriptionPricingPlans as $subscriptionPricingPlan) {
            $markup .= "<option value=\"";
            $markup .= $subscriptionPricingPlan->id.'"';

            if ($subscriptionPricingPlan->id == $selected_subscription_id) {
                $markup .= ' selected="selected" ';
            }
            
            $markup .= '">';
            $markup .= $subscriptionPricingPlan->name;
            $markup .= '</option>';
        }
        $markup .= "</select>";
        return $markup;
    }

    protected function createSubscriptionPlan($user, $request)
    {
        $newSubscriptionParameters = [
            'userAccountId' => $user->id,
            'userEmail' => $user->email,
            'freeLocations' => $request->getPost('free_locations', 'striptags'),
            'freeSmsMessagesPerLocation' => $request->getPost('sms_messages', 'striptags'),
            'pricingPlanId' => $request->getPost('subscription_pricing_plan_id', 'striptags')
        ];

        return $this->di->get('subscriptionManager')->createSubscriptionPlan($newSubscriptionParameters);
    }

    protected function updateSubscriptionPlan($user, $request)
    {
        $objBusinessSubscription = \Vokuro\Models\BusinessSubscriptionPlan::findFirst("user_id = {$user->id}");
        if (!$objBusinessSubscription) {
            return false;
        }

        if ($request->getPost('free_locations', 'striptags')) {
            $objBusinessSubscription->locations = $request->getPost('free_locations', 'striptags');
        }
        if ($request->getPost('sms_messages', 'striptags')) {
            $objBusinessSubscription->sms_messages_per_location = $request->getPost('sms_messages', 'striptags');
        }

        $objBusinessSubscription->updated_at = date("Y-m-d H:i:s");
        $objBusinessSubscription->save();

        return true;
    }
}
