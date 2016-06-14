<?php
    namespace Vokuro\Controllers;

    use Vokuro\Forms\AgencyForm;
    use Vokuro\Models\Subscription;
    use Vokuro\Models\Agency;

class ControllerBusinessBase extends ControllerBase {
    /**
     * BEGIN BUSINESS COMMON FUNCTIONS
     */


    /**
     * Creates business / agencies
     */
    public function createAction($agency_type_id, $agency_id = 0, $parent_id = 0) {
        $this->view->agency_type_id = $agency_type_id;
        $this->view->agency_id = $agency_id;

        $form = new AgencyForm(null);
        if ($agency_id > 0) {
            $age = Agency::findFirst("agency_id = {$agency_id}");
            if (!$age) {
                $this->flash->error("The " . ($agency_type_id == 1 ? 'agency' : 'business') . " was not found");
            }
            $form = new AgencyForm($age);
        }
        else
            $age = new Agency();

        if ($this->request->isPost()) {
            $IsEmailUnique = true;
            $IsEmailValid = true;
            $IsNameValid = true;
            if ($agency_id == 0) {
                $user = new Users();
                $user->assign(array(
                    'name' => $this->request->getPost('admin_name', 'striptags'),
                    'email' => $this->request->getPost('admin_email'),
                    'profilesId' => 1, //All new users will be "Agency Admin"
                ));
                $IsEmailUnique = $user->validation();
                $IsEmailValid = ($this->request->getPost('admin_email') != '');
                $IsNameValid = ($this->request->getPost('admin_name') != '');
            }



            if ($form->isValid($this->request->getPost()) != false && $IsEmailUnique && $IsEmailValid && $IsNameValid) {
                if (!$age->createOrUpdateBusiness([
                    'name'                      => $this->request->getPost('name', 'striptags'),
                    'agency_type_id'            => $agency_type_id,
                    'email'                     => $this->request->getPost('email', 'striptags'),
                    'address'                   => $this->request->getPost('address', 'striptags'),
                    'locality'                  => $this->request->getPost('locality', 'striptags'),
                    'state_province'            => $this->request->getPost('state_province', 'striptags'),
                    'postal_code'               => $this->request->getPost('postal_code', 'striptags'),
                    'country'                   => $this->request->getPost('country', 'striptags'),
                    'phone'                     => $this->request->getPost('phone', 'striptags'),
                    'date_created'              => (isset($age->date_created) ? $age->date_created : date('Y-m-d H:i:s')),
                    'subscription_id'           => $this->request->getPost('subscription_id', 'striptags'),
                    'deleted'                   => (isset($age->deleted) ? $age->deleted : 0),
                    'status'                    => (isset($age->status) ? $age->status : 1),
                    'subscription_valid'        => (isset($age->subscription_valid) ? $age->subscription_valid : 'Y'),
                    'parent_id'                 => $parent_id,
                ])) {
                    $messages = array();
                    foreach ($age->getMessages() as $message) {
                        $messages[] = $message->getMessage();
                    }

                    $this->flash->error($messages);
                } else {
                    if ($agency_id > 0) {
                    } else {
                        //lets create an admin for this new agency
                        $user = new Users();

                        $user->assign(array(
                            'name' => $this->request->getPost('admin_name', 'striptags'),
                            'email' => $this->request->getPost('admin_email'),
                            'agency_id' => $age->agency_id,
                            'profilesId' => $agency_type_id, // 1 = Agency User, 2 = Business User
                        ));
                        if ($user->save()) {
                            $this->flash->success('A confirmation email has been sent to ' . $this->request->getPost('admin_email'));
                        } else {
                            $messages = array();
                            foreach ($user->getMessages() as $message) {
                                $messages[] = $message->getMessage();
                            }

                            $this->flash->error($messages);
                        }
                    }

                    $this->flash->success("The " . ($agency_type_id == 1 ? 'agency' : 'business') . " was " . ($agency_id > 0 ? 'edited' : 'created') . " successfully");
                }

            } else {
                $messages = array();
                foreach ($form->getMessages() as $message) {
                    $messages[] = $message->getMessage();
                }

                $this->flash->error($messages);

                if (!$IsEmailUnique) {
                    $this->flash->error('The admin email is already used.  Please enter a different email address.');
                }
                if (!$IsEmailValid) {
                    $this->flash->error('Please enter an Admin Email.');
                }
                if (!$IsNameValid) {
                    $this->flash->error('Please enter an Admin Full Name.');
                }
            }
        }

        // find all subscriptions for the form
        $this->view->subscriptions = Subscription::find();
        //end finding subscriptions

        $this->view->agency = new Agency();
        $this->view->form = $form;

        if ($agency_id > 0) {
            $conditions = "agency_id = :agency_id:";
            $parameters = array("agency_id" => $agency_id);
            $age2 = Agency::findFirst(array($conditions, "bind" => $parameters));
            $form = new AgencyForm($age2);
            $this->view->agency = $age2;
        }
    }

    /**
     * The view of a agency/business
     */
    public function viewAction($agency_type_id, $agency_id = 0) {
        $this->view->agency_type_id = $agency_type_id;
        $this->view->agency_id = $agency_id;

        //set agency details
        $conditions = "agency_id = :agency_id:";
        $parameters = array("agency_id" => $agency_id);
        $age = Agency::findFirst(array($conditions, "bind" => $parameters));
        $this->view->agency = $age;

        //find all users associated with the agency
        $conditions = "agency_id = :agency_id:";
        $parameters = array("agency_id" => $agency_id);
        $users = Users::find(array($conditions, "bind" => $parameters));
        $this->view->users = $users;

        if (isset($_GET['s']) && $_GET['s'] == 1)
            $this->flash->success('Success! Have the employee check their email for a reset password message');

        if (isset($_GET['s']) && $_GET['s'] == 2)
            $this->flash->success('Success! Have the employee check their email for a confirmation message');

    }

    /**
     * END BUSINESS COMMON FUNCTIONS
     */

}