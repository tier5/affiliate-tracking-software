<?php

namespace Vokuro\Controllers;

use Vokuro\ArrayException;
use Vokuro\Forms\AgencyForm;
use Vokuro\Models\Agency;
use Vokuro\Models\Users;

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
        if ($agency_id) {
            $age = Agency::findFirst("agency_id = {$agency_id}");
            if (!$age) {
                $this->flash->error("The " . ($agency_type_id == 1 ? 'agency' : 'business') . " was not found");
            }
            $form = new AgencyForm($age);
        } else {
            $age = new Agency();
        }

        if ($this->request->isPost()) {

            try {

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
                    throw new ArrayException("", 0, null, $messages);
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
                    'country'            => $this->request->getPost('country', 'striptags'),
                    'phone'              => $this->request->getPost('phone', 'striptags'),
                    'date_created'       => (isset($age->date_created) ? $age->date_created : date('Y-m-d H:i:s')),
                    'subscription_id'    => $this->request->getPost('subscription_pricing_plan_id', 'striptags'),
                    'deleted'            => (isset($age->deleted) ? $age->deleted : 0),
                    'status'             => (isset($age->status) ? $age->status : 1),
                    'subscription_valid' => (isset($age->subscription_valid) ? $age->subscription_valid : 'Y'),
                    'parent_id'          => $parent_id,
                ];

                if (!$age->createOrUpdateBusiness($params)) {
                    throw new ArrayException("", 0, null, $age->getMessages());
                }

                /* Create an admin for this new agency */
                $user = new Users();
                $user->send_confirmation = $this->request->getPost('send_registration_email', 'striptags') === "on " ? true : false;
                $user->assign(array(
                    'name' => $this->request->getPost('admin_name', 'striptags'),
                    'email' => $this->request->getPost('admin_email'),
                    'agency_id' => $age->agency_id,
                    'profilesId' => $agency_type_id, // 1 = Agency User, 2 = Business User
                ));
                if (!$user->save()) {
                    throw new ArrayException("", 0, null, $user->getMessages());
                }

                $result = $this->createSubscriptionPlan($user, $this->request);
                if($result !== true) {
                    $this->flash->error($messages);
                }

                $this->flash->success("The " . ($agency_type_id == 1 ? 'agency' : 'business') . " was " . ($agency_id > 0 ? 'edited' : 'created') . " successfully");
                $this->flash->success('A confirmation email has been sent to ' . $this->request->getPost('admin_email'));

                $db->commit();

            } catch(ArrayException $e) {

                if(isset($db)) { $db->rollback(); }
                $this->flash->error($e->getOptions());

            }
        }
        $sub_selected = ($age && isset($age->subscription_id)) ? $age->subscription_id : null;
        if(!$sub_selected) $sub_selected = 0;
            $markup = $this->buildSubsriptionPricingPlanMarkUp($sub_selected);
        $this->view->setVar("subscriptionPricingPlans", $markup);

        $this->view->agency = new Agency();
        $this->view->form = $form;

        if ($agency_id > 0) {
            $conditions = "agency_id = :agency_id:";
            $parameters = array("agency_id" => $agency_id);
            $age2 = Agency::findFirst(array($conditions, "bind" => $parameters));
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
        $agency = Agency::findFirst(array($conditions, "bind" => $parameters));
        $this->view->agency = $agency;

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

    private function buildSubsriptionPricingPlanMarkUp($selected_subscription_id = null) {
        $subscriptionPricingPlans = $this->di->get('subscriptionManager')->getSubscriptionPricingPlans();
        $selected_subscription_id = (int)$selected_subscription_id;

        $markup = "<select id=\"subscription_pricing_plan_id\" name=\"subscription_pricing_plan_id\">";
        $markup .= "    <option value=\"0\">Unpaid</option>";  // This is default plan
        foreach($subscriptionPricingPlans as $subscriptionPricingPlan) {
            $markup .= "<option value=\"";
            $markup .= $subscriptionPricingPlan->id.'"';
            if ($subscriptionPricingPlan->id == $selected_subscription_id) $markup .= ' selected="selected" ';
            $markup .= '">';
            $markup .= $subscriptionPricingPlan->name;
            $markup .= '</option>';
        }
        $markup .= "</select>";
        return $markup;
    }

    private function createSubscriptionPlan($user, $request) {
        $newSubscriptionParameters = [
            'userAccountId' => $user->id,
            'userEmail' => $user->email,
            'freeLocations' => $request->getPost('free_locations', 'striptags'),
            'freeSmsMessagesPerLocation' => $request->getPost('sms_messages', 'striptags'),
            'pricingPlanId' => $request->getPost('subscription_pricing_plan_id', 'striptags')
        ];
        return $this->di->get('subscriptionManager')->createSubscriptionPlan($newSubscriptionParameters);
    }
}
