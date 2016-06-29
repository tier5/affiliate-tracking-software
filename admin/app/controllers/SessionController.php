<?php

namespace Vokuro\Controllers;

use Phalcon\Tag;
use Vokuro\Forms\LoginForm;
use Vokuro\Forms\SignUpForm;
use Vokuro\Forms\CreditCardForm;
use Vokuro\Forms\ForgotPasswordForm;
use Vokuro\Auth\Exception as AuthException;
use Vokuro\Models\Agency;
use Vokuro\Models\FacebookScanning;
use Vokuro\Models\GoogleScanning;
use Vokuro\Models\Location;
use Vokuro\Models\LocationReviewSite;
use Vokuro\Models\ResetPasswords;
use Vokuro\Models\Users;
use Vokuro\Models\UsersSubscription;

/**
 * Controller used handle non-authenticated session actions like login/logout, user signup, and forgotten passwords
 */
class SessionController extends ControllerBase {

    public $facebook_access_token;

    /**
     * Default action. Set the public layout (layouts/private.volt)
     */
    public function initialize() {
        if (!$this->facebook_access_token) {
            $face = new FacebookScanning();
            $this->facebook_access_token = $face->getAccessToken();
        }
        parent::initialize();
    }

    public function indexAction() {
        $this->view->setTemplateBefore('login');
        $this->tag->setTitle('Review Velocity | Subscription');
    }

    public function isMaxLimitReached() {
        $maxreached = false;

        //check to make sure that we have not already reached the max allowed for signup today
        //echo '<p>perday:'.$this->config->maxSignup->perday.'</p>';
        if ($this->config->maxSignup->perday > 0) { //zero equals infinite
            //find out how many signed up today
            $report = Users::getDailySignupCount();
            //echo '<p>count:'.$report->count().'</p>';
            if ($report->count() >= $this->config->maxSignup->perday) {
                //we reached our max limit, so don't allow any additional signup
                $maxreached = true;
            }
        } //end checking for a max signup
        //echo '<p>$maxreached:'.($maxreached?'true':'false').'</p>';
        return $maxreached;
    }

    

    public function noSubDomains($page, $subscription_id) {
        $sub = array_shift((explode(".", $_SERVER['HTTP_HOST'])));

        if ($sub && $sub != '' && $sub != 'my' && $sub != 'www' && $sub != 'reviewvelocity' && $sub != '104' && $sub != 'dev' && $sub != 'stage' && $sub != 'dev2' && $sub != 'localhost') {
            //there is a subdomain.  That is not allowed, so redirect them out of here
            $found = false;
            $querystring = '';
            if (isset($_GET['code'])) {
                $code = $_GET['code'];
                $querystring = '?code=' . $code;
                $found = true;
            }
            if ($subscription_id > 0) {
                $querystring = $subscription_id . '/' . $querystring;
                $found = true;
            }
            //return $this->response->redirect('/session/signup' . ($page > 1 ? $page : '') . '/' . $querystring);
        }
    }
    
    /**
     * Collect credit card info
     */
    public function ccAction() {
        
    }

    /**
     * Sign up form, Step 1 (Account)
     */
    public function signupAction($subscription_id = 0) {
        $this->noSubDomains(1, $subscription_id);

        $this->view->setTemplateBefore('login');
        $this->tag->setTitle('Review Velocity | Sign up');
        $form = new SignUpForm();
        $ccform = new CreditCardForm();

        $user_id = 0;
        if (isset($this->session->get('auth-identity')['id']) && $this->session->get('auth-identity')['id'] > 0) {
            $user_id = $this->session->get('auth-identity')['id'];
            $this->view->setTemplateBefore('private');
        }
        $this->view->user_id = $user_id;
        if ($user_id > 0) {
            $this->view->maxlimitreached = false;
        } else {
            $this->view->maxlimitreached = $this->isMaxLimitReached();
        }

        //echo '<p>$subscriptionobj test:'.(isset($subscriptionobj->subscription_id) == false).'</p>';

        if ($this->request->isPost()) {
            $ccformvalid = true;
            if (isset($subscriptionobj->subscription_id) == true && $subscriptionobj->subscription_id > 0)
                $ccformvalid = $ccform->isValid($this->request->getPost());
            //echo '<p>test:'.$ccformvalid.'</p>';

            $uservalid = true;
            $isemailunuique = true;
            if ($user_id == 0) {
                //check user email unuique            
                $user = new Users();
                $user->assign(array(
                    'name' => $this->request->getPost('name', 'striptags'),
                    'email' => $this->request->getPost('email'),
                    'password' => $this->security->hash($this->request->getPost('password')),
                    'profilesId' => 1, //All new users will be "Agency Admin"
                ));
                $isemailunuique = $user->validation();
                $uservalid = ($form->isValid($this->request->getPost()) != false);
            }

            //$this->flash->error('Posted...');
            if ($uservalid && $ccformvalid && $isemailunuique) {

                if (isset($subscriptionobj->subscription_id) == false || (($response != null) && ($response->getMessages()->getResultCode() == "Ok") )) {
                    //echo "SUCCESS: Subscription ID : " . $response->getSubscriptionId() . "\n";
                    //$this->flash->error('Valid...');
                    if ($user_id == 0) {
                        //first create an agency
                        $agency_name = $this->request->getPost('agency_name', 'striptags');
                        $agency = new Agency();
                        $agency->assign(array(
                            'name' => $agency_name,
                            'referrer_code' => $this->request->getPost('sharecode'),
                            'date_created' => date('Y-m-d H:i:s'),
                            'signup_page' => 2, //go to the next page,
                            'agency_type_id' => 2,
                        ));
                        if (!$agency->save()) {
                            $this->flash->error($agency->getMessages());
                        }
                        //$this->flash->error('Agency created.  Id:'.$agency->agency_id);

                        $user->agency_id = $agency->agency_id;

                        if ($user->save()) {
                
                            //$this->flash->error('A confirmation email has been sent to ' . $this->request->getPost('email'));
                            //redirect
                            //return $this->response->redirect('/session/login?n=1');
                            $_SESSION['name'] = $this->request->getPost('name', 'striptags');
                            $_SESSION['email'] = $this->request->getPost('email');

                            /*
                             * REFACTOR: We don't have a choice due to the fragmented registration system :(  Will remove 
                             * this later.  MT, 2016 
                             * 
                             */
                            // $this->createDefaultSubscriptionPlan($user->id);

                            return $this->response->redirect('/session/thankyou');
                            //'signup_page' => 2, //go to the next page
                            //return $this->dispatcher->forward(array(
                            //  'controller' => 'index',
                            //  'action' => 'index'
                            //));
                        }
                    } else {
                        //else, we already have a user, so redirect home
                        $conditions = "id = :id:";
                        $parameters = array("id" => $user_id);
                        $user = Users::findFirst(array($conditions, "bind" => $parameters));

                        //save the credit card info            
                        $us = new UsersSubscription();
                        $us->assign(array(
                            'user_id' => $user->id,
                            'agency_id' => $user->agency_id,
                            'subscription_id' => $subscription_id,
                            'date_created' => date('Y-m-d H:i:s'),
                            'cardnumber' => preg_replace('/\s+/', '', $this->request->getPost('card-number')),
                            'expirymonth' => $this->request->getPost('expiry-month'),
                            'expiryyear' => $this->request->getPost('expiry-year'),
                            'cvc' => $this->request->getPost('cvc'),
                            'auth_subscription_id' => $response->getSubscriptionId(),
                        ));

                        //save the UsersSubscription now
                        if (!$us->save()) {
                            $this->flash->error($us->getMessages());
                        }
                        return $this->response->redirect('/?n=1');
                    }
                    $this->flash->error($user->getMessages());
                } else {
                    $this->flash->error('The credit card is invalid.  Please check the informaiton and try again.' . "<!--Response : " . $response->getMessages()->getMessage()[0]->getCode() . "  " . $response->getMessages()->getMessage()[0]->getText() . "-->");
                    //echo "ERROR: Credit Card Proccessing Error\n";
                    //echo "Response : " . $response->getMessages()->getMessage()[0]->getCode() . "  " .$response->getMessages()->getMessage()[0]->getText() . "\n";
                }
            } else {
                if (!$isemailunuique)
                    $this->flash->error('That email address is already taken.');

                foreach ($form->getMessages() as $message) {
                    $this->flash->error($message->getMessage());
                }
                foreach ($ccform->getMessages() as $message) {
                    $this->flash->error($message->getMessage());
                }
            }
        }

        $this->view->form = $form;
        $this->view->ccform = $ccform;
        $this->view->current_step = 1;
    }

    private function createDefaultSubscriptionPlan($userId) {
        $subscriptionManager = $this->di->get('subscriptionManager');

        $newSubscriptionParameters = [];

        $pricingPlan = $subscriptionManager->getPricingPlanByName('Review Velocity - Default');
        if ($pricingPlan) {
            $newSubscriptionParameters['userAccountId'] = $userId;
            $newSubscriptionParameters['freeLocations'] = 0;
            $newSubscriptionParameters['freeSmsMessagesPerLocation'] = 0;
            $newSubscriptionParameters['pricingPlanId'] = $pricingPlan['id'];
        } else {
            $newSubscriptionParameters['userAccountId'] = $userId;
            $newSubscriptionParameters['freeLocations'] = 1;
            $newSubscriptionParameters['freeSmsMessagesPerLocation'] = 100;
            $newSubscriptionParameters['pricingPlanId'] = "Unpaid";
        }

        $created = $subscriptionManager->createSubscriptionPlan($newSubscriptionParameters);
        if (!$created) {
            $this->flash->error('Failed to create default subscription plan');
        }
    }

    /**
     * Sign up form, Step 2 (Add Location) 
     */
    public function signup2Action($subscription_id = 0) {
        $this->noSubDomains(2, $subscription_id);

        $this->view->setTemplateBefore('signup');
        $this->tag->setTitle('Review Velocity | Sign up | Step 2 | Add Location');

        //get the user id, to find the settings
        $identity = $this->auth->getIdentity();
        //echo '<pre>$identity:'.print_r($identity,true).'</pre>';
        // If there is no identity available the user is redirected to index/index
        if (!is_array($identity)) {
            $this->response->redirect('/session/login?return=/session/signup2/' . ($subscription_id > 0 ? $subscription_id : ''));
            $this->view->disable();
            return;
        }
        // Query binding parameters with string placeholders
        $conditions = "id = :id:";
        $parameters = array("id" => $identity['id']);
        $userObj = Users::findFirst(array($conditions, "bind" => $parameters));
        //echo '<pre>$userObj:'.print_r($userObj->agency_id,true).'</pre>';
        //find the agency 
        $conditions = "agency_id = :agency_id:";
        $parameters = array("agency_id" => $userObj->agency_id);
        $agency = Agency::findFirst(array($conditions, "bind" => $parameters));


        if ($this->request->isPost()) {
            $loc = new Location();
            $loc->assign(array(
                'name' => $this->request->getPost('name', 'striptags'),
                'agency_id' => $agency->agency_id,
                'phone' => $this->request->getPost('phone', 'striptags'),
                'address' => $this->request->getPost('address', 'striptags'),
                'locality' => $this->request->getPost('locality', 'striptags'),
                'state_province' => $this->request->getPost('state_province', 'striptags'),
                'postal_code' => $this->request->getPost('postal_code', 'striptags'),
                'country' => $this->request->getPost('country', 'striptags'),
                'latitude' => $this->request->getPost('latitude', 'striptags'),
                'longitude' => $this->request->getPost('longitude', 'striptags'),
                'region_id' => $this->request->getPost('region_id', 'striptags'),
                'date_created' => date('Y-m-d H:i:s'),
            ));


            if (!$loc->save()) {
                $this->flash->error($loc->getMessages());
            } else {
                $foundagency = array();

                //check for yelp
                $yelp_api_id = $this->request->getPost('yelp_id', 'striptags');
                $yelp_id = $this->yelpId($yelp_api_id);
                if ($yelp_api_id != '' && !(strpos($yelp_api_id, '>') !== false)) {
                    $lrs = new LocationReviewSite();
                    $lrs->assign(array(
                        'location_id' => $loc->location_id,
                        'review_site_id' => 2, // yelp = 2
                        'external_id' => $yelp_id,
                        'api_id' => $yelp_api_id,
                        'date_created' => date('Y-m-d H:i:s'),
                        'is_on' => 1,
                    ));

                    //find the review info
                    $this->importYelp($lrs, $loc, $foundagency);
                }

                //check for google
                $google_place_id = $this->request->getPost('google_place_id', 'striptags');
                $google_api_id = $this->request->getPost('google_api_id', 'striptags');
                if ($google_place_id != '') {
                    $googleScan = new GoogleScanning();
                    $lrs = new LocationReviewSite();
                    $lrs->assign(array(
                        'location_id' => $loc->location_id,
                        'review_site_id' => 3, // google = 3
                        'external_id' => $google_place_id,
                        'api_id' => $google_api_id,
                        'date_created' => date('Y-m-d H:i:s'),
                        'is_on' => 1,
                        'lrd' => $googleScan->getLRD($google_place_id),
                    ));

                    //find the review info
                    $this->importGoogle($lrs, $loc, $foundagency);
                }

                //check for facebook
                $facebook_page_id = $this->request->getPost('facebook_page_id', 'striptags');
                if ($facebook_page_id != '') {
                    $lrs = new LocationReviewSite();
                    $lrs->assign(array(
                        'location_id' => $loc->location_id,
                        'review_site_id' => 1, // facebook = 1
                        'external_id' => $facebook_page_id,
                        'date_created' => date('Y-m-d H:i:s'),
                        'is_on' => 1,
                    ));

                    //find the review info
                    $this->importFacebook($lrs);
                }


                $this->flash->success("The location was created successfully");
                $agency->assign(array(
                    'signup_page' => 3, //go to the next page
                ));
                if (!$agency->save()) {
                    //$this->flash->error($agency->getMessages());
                }

                $this->auth->setLocation($loc->location_id);

                return $this->response->redirect('/session/signup3/' . ($subscription_id > 0 ? $subscription_id : ''));
            }
        }

        $this->view->facebook_access_token = $this->facebook_access_token;
        $this->view->current_step = 2;
    }

    /**
     * Sign up form, Step 3 (Customize Survey)
     */
    public function signup3Action($subscription_id = 0) {
        $this->noSubDomains(3, $subscription_id);

        $this->view->setTemplateBefore('signup');
        $this->tag->setTitle('Review Velocity | Sign up | Step 3 | Customize Survey');

        //get the user id, to find the settings
        $identity = $this->auth->getIdentity();
        // If there is no identity available the user is redirected to index/index
        if (!is_array($identity)) {
            $this->response->redirect('/session/login?return=/session/signup3/' . ($subscription_id > 0 ? $subscription_id : ''));
            $this->view->disable();
            return;
        }
        // Query binding parameters with string placeholders
        $conditions = "id = :id:";
        $parameters = array("id" => $identity['id']);
        $userObj = Users::findFirst(array($conditions, "bind" => $parameters));
        //echo '<pre>$userObj:'.print_r($userObj->agency_id,true).'</pre>';
        //find the agency 
        $conditions = "agency_id = :agency_id:";
        $parameters = array("agency_id" => $userObj->agency_id);
        $agency = Agency::findFirst(array($conditions, "bind" => $parameters));

        //find the location
        $conditions = "location_id = :location_id:";
        $parameters = array("location_id" => $this->session->get('auth-identity')['location_id']);
        $location = Location::findFirst(array($conditions, "bind" => $parameters));

        if ($this->request->isPost()) {

            $location->assign(array(
                'name' => $this->request->getPost('agency_name', 'striptags'),
                'sms_button_color' => $this->request->getPost('sms_button_color', 'striptags'),
                'sms_top_bar' => $this->request->getPost('sms_top_bar', 'striptags'),
                'sms_text_message_default' => $this->request->getPost('sms_text_message_default', 'striptags'),
            ));
            $file_location = $this->uploadAction($agency->agency_id);
            if ($file_location != '')
                $location->sms_message_logo_path = $file_location;
            if (!$location->save()) {
                $this->flash->error($location->getMessages());
            } else {
                //increment signup page value
                $agency->signup_page = 4; //go to the next page
                $agency->save();

                return $this->response->redirect('/session/signup4/' . ($subscription_id > 0 ? $subscription_id : ''));
            }
        }


        $this->view->agency = $agency;
        $this->view->location = $location;
        $this->view->current_step = 3;
        $this->view->id = $agency->agency_id;
    }

    /**
     * Sign up form, Step 4 (Add Employee)
     */
    public function signup4Action($subscription_id = 0) {
        $this->noSubDomains(4, $subscription_id);

        $this->view->setTemplateBefore('signup');
        $this->tag->setTitle('Review Velocity | Sign up | Step 4 | Add Employee');

        //get the user id, to find the settings
        $identity = $this->auth->getIdentity();
        // If there is no identity available the user is redirected to index/index
        if (!is_array($identity)) {
            $this->response->redirect('/session/login?return=/session/signup4/' . ($subscription_id > 0 ? $subscription_id : ''));
            $this->view->disable();
            return;
        }
        // Query binding parameters with string placeholders
        $conditions = "id = :id:";
        $parameters = array("id" => $identity['id']);
        $userObj = Users::findFirst(array($conditions, "bind" => $parameters));
        //echo '<pre>$userObj:'.print_r($userObj->agency_id,true).'</pre>';
        //find the agency 
        $conditions = "agency_id = :agency_id:";
        $parameters = array("agency_id" => $userObj->agency_id);
        $agency = Agency::findFirst(array($conditions, "bind" => $parameters));


        //find the location
        $conditions = "location_id = :location_id:";
        $parameters = array("location_id" => $this->session->get('auth-identity')['location_id']);
        $location = Location::findFirst(array($conditions, "bind" => $parameters));


        if ($this->request->isPost()) {
            $agency->assign(array(
                'signup_page' => 5, //go to the next page
            ));
            $location->assign(array(
                'lifetime_value_customer' => $this->request->getPost('lifetime_value_customer', 'striptags'),
                'review_goal' => $this->request->getPost('review_goal', 'striptags'),
            ));

            if (!$location->save()) {
                $this->flash->error($location->getMessages());
            } else {
                $agency->save();
                return $this->response->redirect('/session/signup5/' . ($subscription_id > 0 ? $subscription_id : ''));
                $this->view->disable();
                return;
            }
        }


        $this->usersFunctionality(3, $this->session->get('auth-identity')['location_id']);
        $this->view->location = $location;
        $this->view->current_step = 4;
    }

    /**
     * Sign up form, Step 5 (Share)
     */
    public function signup5Action($subscription_id = 0) {
        $this->noSubDomains(5, $subscription_id);

        $this->view->setTemplateBefore('signup');
        $this->tag->setTitle('Review Velocity | Sign up | Step 5 | Share');
        $this->view->messages_sent = false;

        //get the user id, to find the settings
        $identity = $this->auth->getIdentity();
        // If there is no identity available the user is redirected to index/index
        if (!is_array($identity)) {
            $this->response->redirect('/session/login?return=/session/signup5/' . ($subscription_id > 0 ? $subscription_id : ''));
            $this->view->disable();
            return;
        }
        // Query binding parameters with string placeholders
        $conditions = "id = :id:";
        $parameters = array("id" => $identity['id']);
        $userObj = Users::findFirst(array($conditions, "bind" => $parameters));
        //echo '<pre>$userObj:'.print_r($userObj->agency_id,true).'</pre>';
        //find the agency 
        $conditions = "agency_id = :agency_id:";
        $parameters = array("agency_id" => $userObj->agency_id);
        $agency = Agency::findFirst(array($conditions, "bind" => $parameters));

        //Get the sharing code
        $this->getShareInfo($agency);
        //end getting the sharing code
        // Check if the user wants to send emails
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Get the form fields and remove whitespace.
            $subject = $this->view->share_subject;
            $message = $this->view->share_message;

            //loop through all the emails
            for ($i = 1; $i < 12; $i++) {
                if (isset($_POST['email_' . $i])) {
                    $email = $_POST['email_' . $i];
                    if ($email != '') {
                        try {
                            $this->getDI()
                                    ->getMail()
                                    ->send($email, $subject, '', '', $message);
                        } catch (Exception $e) {
                            // do nothing, just ignore
                        }
                    }
                }
            }
            $this->view->messages_sent = true;
            $this->flash->success('The emails have been sent.  Use the form to send some more.  Click the "FINISHED" button to leave this page.');
            Tag::resetInput();
        }

        if (isset($_GET['q']) && $_GET['q'] == 's') {
            $agency->assign(array(
                'signup_page' => '', //go to the next page
            ));

            if (!$agency->save()) {
                $this->flash->error($agency->getMessages());
            } else {
                return $this->response->redirect('/');
                $this->view->disable();
                return;
            }
        }

        $this->view->current_step = 5;
    }

    /**
     * thankyou page
     */
    public function thankyouAction() {
        $this->view->setTemplateBefore('login');
        $this->tag->setTitle('Review Velocity | Thank You');

        //test code below, uncomment to test
        //$_SESSION['name']='Test Tester';
        //$_SESSION['email']='test@tester.com';
    }

    /**
     * privacy page
     */
    public function privacyAction() {
        $this->view->setTemplateBefore('login');
        $this->tag->setTitle('Review Velocity | Privacy');
    }

    /**
     * terms page
     */
    public function termsAction() {
        $this->view->setTemplateBefore('login');
        $this->tag->setTitle('Review Velocity | Terms');
    }

    /**
     * Anti-span Policy page
     */
    public function antispanAction() {
        $this->view->setTemplateBefore('login');
        $this->tag->setTitle('Review Velocity | Anti-span Policy');
    }

    /**
     * Starts a session in the admin backend
     */
    public function loginAction() {
        $this->view->setTemplateBefore('login');
        $this->tag->setTitle('Review Velocity | Login');
        $form = new LoginForm();

        try {
            if (!$this->request->isPost()) {
                if ($this->auth->hasRememberMe()) {
                    return $this->auth->loginWithRememberMe();
                }
            } else {
                if ($form->isValid($this->request->getPost()) == false) {
                    foreach ($form->getMessages() as $message) {
                        $this->flash->error($message);
                    }
                } else {
                    $this->auth->check(array(
                        'email' => $this->request->getPost('email'),
                        'password' => $this->request->getPost('password'),
                        'remember' => $this->request->getPost('remember')
                    ));

                    $return = '/';
                    if (isset($_GET['return']) && strpos($_GET['return'], '/') !== false)
                        $return = $_GET['return'];

                    //get the user id, to find the settings
                    $identity = $this->auth->getIdentity();
                    // If there is no identity available the user is redirected to index/index
                    if (is_array($identity)) {
                        // Query binding parameters with string placeholders
                        $conditions = "id = :id:";
                        $parameters = array("id" => $identity['id']);
                        $userObj = Users::findFirst(array($conditions, "bind" => $parameters));
                        //echo '<pre>$userObj:'.print_r($userObj->agency_id,true).'</pre>';
                        //find the agency 
                        $conditions = "agency_id = :agency_id:";
                        $parameters = array("agency_id" => $userObj->agency_id);
                        $agency = Agency::findFirst(array($conditions, "bind" => $parameters));

                        if ($agency->signup_page > 0)
                            $return = '/session/signup' . $agency->signup_page . '/' . ($agency->subscription_id > 0 ? $subscription_id : '');
                    }

                    return $this->response->redirect($return);
                }
            }
        } catch (AuthException $e) {
            $this->flash->error($e->getMessage());
        }
        if (isset($_GET['n']) && $_GET['n'] == 1)
            $this->flash->success('Your account has been created and a confirmation email has been sent to your email address.');
        $this->view->form = $form;
    }

    /**
     * Shows the forgot password form
     */
    public function forgotPasswordAction() {
        $this->view->setTemplateBefore('login');
        $this->tag->setTitle('Review Velocity | Forgot password');
        $form = new ForgotPasswordForm();

        if ($this->request->isPost()) {
            if ($form->isValid($this->request->getPost()) == false) {
                foreach ($form->getMessages() as $message) {
                    $this->flash->error($message);
                }
            } else {
                $user = Users::findFirstByEmail($this->request->getPost('email'));
                if (!$user) {
                    $this->flash->success('There is no account associated with this email');
                } else {
                    $resetPassword = new ResetPasswords();
                    $resetPassword->usersId = $user->id;
                    if ($resetPassword->save()) {
                        $this->flash->success('Success! Please check your messages for an email reset password');
                    } else {
                        foreach ($resetPassword->getMessages() as $message) {
                            $this->flash->error($message);
                        }
                    }
                }
            }
        }

        $this->view->form = $form;
    }

    /**
     * Closes the session
     */
    public function logoutAction() {
        $this->auth->remove();
        return $this->response->redirect('/');
    }

    function curl_get_contents($url) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }

    /**
     * This is Search using Google place API.
     * @return Json
     */
    public function googlesearchapiAction() {

        #check form post
        $request = $this->request;

        if ($this->request->isPost()) {

            #get data from post
            $businessName = trim($request->getPost('location_name'));
            $zip = trim($request->getPost('zip'));

            try {
                #call map api
                $googleApiKey = 'AIzaSyAPisblAqZJJ7mGWcORf4FBjNMQKV20J20';
                $keyword = urlencode($businessName . ', ' . $zip);

                if (!empty($keyword)) {

                    #get place of an business with business name and zip using Google API
                    $strFindPlaceUrl = "https://maps.googleapis.com/maps/api/place/textsearch/json?query=" . $keyword . "&key=" . $googleApiKey;
                    //echo '<p>$strFindPlaceUrl:'.$strFindPlaceUrl.'</p>';
                    $resultFindPlace = $this->curl_get_contents($strFindPlaceUrl);
                    $arrResultFindPlace = json_decode($resultFindPlace, true);

                    //echo '<pre>'.print_r($arrResultFindPlace,true).'</pre>';
                    //die();

                    $admincode = '0';
                    //echo '<pre>'.print_r($_POST,true).'</pre>';         
                    if (isset($_POST['admin']) && $_POST['admin'] != '') {
                        //we need to save results
                        $admincode = '1';
                    }

                    #get place details from place ID using Google API
                    if ($arrResultFindPlace['status'] == "OK" && !empty($arrResultFindPlace['results'])) {

                        #initialize variables
                        $intCounter = 0;
                        $strHTML = "<h3>Choose the most relevant result</h3>";

                        foreach ($arrResultFindPlace['results'] as $singleResultFindPlace) {
                            #break after 10 result
                            if ($intCounter == 10)
                                break;

                            $strPlaceId = $singleResultFindPlace['place_id'];

                            $strFindPlaceDetail = "https://maps.googleapis.com/maps/api/place/details/json?placeid=" . $strPlaceId . "&key=" . $googleApiKey;
                            $resultFindPlaceDetail = $this->curl_get_contents($strFindPlaceDetail);
                            $arrResultFindPlaceDetail = json_decode($resultFindPlaceDetail, true);

                            $returnAddress = @$arrResultFindPlaceDetail['result']['formatted_address'];

                            $returnPhoneNumber = @$arrResultFindPlaceDetail['result']['formatted_phone_number'];
                            $returnBusinessName = @$arrResultFindPlaceDetail['result']['name'];

                            $street_number = $this->extractFromAdress(@$arrResultFindPlaceDetail['result']['address_components'], 'street_number');
                            $route = $this->extractFromAdress(@$arrResultFindPlaceDetail['result']['address_components'], 'route');
                            $locality = $this->extractFromAdress(@$arrResultFindPlaceDetail['result']['address_components'], 'locality');
                            $administrative_area_level_1 = $this->extractFromAdress(@$arrResultFindPlaceDetail['result']['address_components'], 'administrative_area_level_1');
                            $postal_code = $this->extractFromAdress(@$arrResultFindPlaceDetail['result']['address_components'], 'postal_code');
                            $country = $this->extractFromAdress(@$arrResultFindPlaceDetail['result']['address_components'], 'country');

                            $strURL = "";
                            $strButton = "";

                            if (isset($returnBusinessName) && $returnBusinessName != '') {

                                //check to see if this location is already in the database, by checking the place id
                                $conditions = "api_id = :api_id: AND review_site_id = 3";
                                $parameters = array("api_id" => @$arrResultFindPlaceDetail['result']['place_id']);
                                $loc = LocationReviewSite::findFirst(array($conditions, "bind" => $parameters));
                                if (!$loc) {
                                    $strURL = "onclick=\"selectLocation('" . $this->encode(@$arrResultFindPlaceDetail['result']['place_id']) . "', '" . $this->encode(@$arrResultFindPlaceDetail['result']['url']) . "', '" . $this->encode($returnBusinessName) . "', '" . $this->encode($street_number) . "', '" . $this->encode($route) . "', '" . $this->encode($locality) . "', '" . $this->encode($administrative_area_level_1) . "', '" . $this->encode($postal_code) . "', '" . $this->encode($country) . "', '" . $this->encode(@$arrResultFindPlaceDetail['result']['formatted_phone_number']) . "', '" . $this->encode(@$arrResultFindPlaceDetail['result']['geometry']['location']['lat']) . "', '" . $this->encode(@$arrResultFindPlaceDetail['result']['geometry']['location']['lng']) . "');return false;\" href=\"javascript:void(0);\"";
                                    $strButton = "<a id=\"business-name-link\" " . $strURL . " style=\"float: right; height: 40px; line-height: 24px;\" class=\"btnLink\" >Choose This Listing</a>";
                                } else {
                                    //the location was found, so tell the user that
                                    $strURL = "href=\"javascript:void(0);\"";
                                    $strButton = "<div style=\"float: right; margin-top: -10px; padding: 5px; text-align: center; width: 215px;\">Already Registered Contact Support</div>";
                                }


                                $strHTML .= "<div class=\"border-box-s\" style=\"min-height: 110px;\">
                    <p class=\"business-name\"><a id=\"business-name-link\" " . $strURL . ">" . $returnBusinessName . "</a></p>
                    " . $strButton . "
                    <ul>
                    <li>" . $returnAddress . "</li>
                    <li>" . $returnPhoneNumber . "</li>
                    </ul>
                    </div>";

                                #increment counter
                                $intCounter++;
                            }
                        }

                        #set response
                        $responseArr = array('HTML' => $strHTML);
                    } else {
                        //echo '<pre>'.print_r($arrResultFindPlace,true).'</pre>';
                        #set response
                        $responseArr = array('errorMsg' => 'Result not found.');
                    }
                } else {
                    #set response
                    $responseArr = array('errorMsg' => 'Result not found');
                }
            } catch (Exception $e) {
                #set response
                $responseArr = array('errorMsg' => 'There was an error processing your request.');
            }

            echo json_encode($responseArr);
            exit;
        }
    }

    function encode($val) {
        if ($val) {
            return str_replace("'", "%27", str_replace("\"", "%22", $val));
        } else {
            return '';
        }
    }

    function extractFromAdress($components, $type) {
        for ($i = 0; $i < count($components); ++$i) {
            for ($j = 0; $j < count($components[$i]['types']); ++$j) {
                if ($components[$i]['types'][$j] == $type)
                    return $components[$i]['short_name'];
            }
        }
        return "";
    }

    /**
     * Sends a review invite to the selected location
     */
    public function sendsmsAction() {
        $results = 'There was a problem sending the message.';

        $message = $_GET['body'];
        $name = $_GET['name'];
        $cell_phone = $_GET['cell_phone'];
        $id = $_GET['id'];
        $message = str_replace("%7D", "}", $message);
        $message = str_replace("%7B", "{", $message);
        $message = str_replace("{business-name}", $name, $message);
        $message = str_replace("{name}", 'Name', $message);
        $message = str_replace("{link}", 'Link', $message);

        //find the agency 
        $conditions = "agency_id = :agency_id:";
        $parameters = array("agency_id" => $id);
        $agency = Agency::findFirst(array($conditions, "bind" => $parameters));

        //The message is saved, so send the SMS message now
        if ($this->SendSMS($this->formatTwilioPhone($cell_phone), $message, $agency->twilio_api_key, $agency->twilio_auth_token, $agency->twilio_auth_messaging_sid, $agency->twilio_from_phone, $agency)) {
            $results = 'The message was sent.';
        }
        $this->view->disable();
        echo $results;
    }

}


/**
     * Allow a user to signup to the system
     */
    /*
    public function subscribeAction($subscription_stripe_id = 0) {
        
          $this->view->setTemplateBefore('login');
          $this->tag->setTitle('Review Velocity | Sign up');
          $form = new SignUpForm();

          $this->view->maxlimitreached = $this->isMaxLimitReached();

          //find the agency
          $conditions = "agency_id = :agency_id:";
          $parameters = array("agency_id" => $this->view->agency_id);
          $agency = Agency::findFirst(array($conditions, "bind" => $parameters));
          if (!$agency) {
          $this->flash->error("No agency found");
          } else {
          $this->view->agency = $agency;

          if ($subscription_stripe_id > 0) {
          $conditions = "subscription_stripe_id = :subscription_stripe_id:";
          $parameters = array("subscription_stripe_id" => $subscription_stripe_id);
          $subscriptionobj = SubscriptionStripe::findFirst(array($conditions, "bind" => $parameters));
          $this->view->subscription = $subscriptionobj;

          if ($this->request->isPost()) {
          try {

          $ccformvalid = false;
          //check to make sure credit card values were filled out
          if (isset($_POST['stripeToken']) && $_POST['stripeToken'] != '' &&
          isset($_POST['stripeEmail']) && $_POST['stripeEmail'] != '') {
          //$_POST['email'] = $_POST['stripeEmail'];
          //we have values, assume valid
          $ccformvalid = true;
          }
          //check user email unuique
          $user = new Users();
          $user->assign(array(
          'name' => $this->request->getPost('name', 'striptags'),
          'email' => $this->request->getPost('email'),
          'password' => $this->security->hash($this->request->getPost('password')),
          'profilesId' => 1, //All new users will be "Agency Admin"
          ));
          $isemailunuique = $user->validation();

          if ($form->isValid($this->request->getPost()) != false && $ccformvalid && $isemailunuique) {
          //create the Stripe subscription account
          \Stripe\Stripe::setApiKey($agency->stripe_account_secret);
          $customer = \Stripe\Customer::create(array(
          'source' => $_POST['stripeToken'],
          'email' => $_POST['stripeEmail'],
          'plan' => $subscriptionobj->plan,
          ));
          //echo '<pre>$customer:'.print_r($customer,true).'</pre>';
          //first create an agency
          $agency_name = $this->request->getPost('agency_name', 'striptags');
          $agency2 = new Agency();
          $agency2->assign(array(
          'name' => $agency_name,
          'stripe_token' => $_POST['stripeToken'],
          'parent_agency_id' => $agency->agency_id,
          'stripe_customer_id' => $customer->id,
          'stripe_subscription_id' => $customer->subscriptions->data[0]->id,
          'date_created' => date('Y-m-d H:i:s'),
          ));
          if (!$agency2->save()) {
          $this->flash->error($agency2->getMessages());
          } else {
          $user->agency_id = $agency2->agency_id;

          if ($user->save()) {
          //$this->flash->error('A confirmation email has been sent to ' . $this->request->getPost('email'));
          //redirect
          return $this->response->redirect('/session/login?n=1');
          }
          }

          $this->flash->error($user->getMessages());
          } else {
          if (!$isemailunuique)
          $this->flash->error('That email address is already taken.');
          if (!$ccformvalid)
          $this->flash->error('Please enter your credit card information.');
          }
          } catch (Stripe_CardError $e) {
          $this->flash->error('There was a problem proccessing the credit card.  Please check the information and try again. <!--' . $e->getMessage() . '-->');
          } catch (Stripe_InvalidRequestError $e) {
          // Invalid parameters were supplied to Stripe's API
          $this->flash->error('There was a problem proccessing the credit card.  Please check the information and try again. <!--' . $e->getMessage() . '-->');
          } catch (Stripe_AuthenticationError $e) {
          // Authentication with Stripe's API failed
          // (maybe you changed API keys recently)
          $this->flash->error('There was a problem proccessing the credit card.  Please check the information and try again. <!--' . $e->getMessage() . '-->');
          } catch (Stripe_ApiConnectionError $e) {
          // Network communication with Stripe failed
          $this->flash->error('There was a problem proccessing the credit card.  Please check the information and try again. <!--' . $e->getMessage() . '-->');
          } catch (Stripe_Error $e) {
          // Display a very generic error to the user, and maybe send
          // yourself an email
          $this->flash->error('There was a problem proccessing the credit card.  Please check the information and try again. <!--' . $e->getMessage() . '-->');
          } catch (Exception $e) {
          // Something else happened, completely unrelated to Stripe
          $this->flash->error('There was a problem proccessing the credit card.  Please check the information and try again. <!--' . $e->getMessage() . '-->');
          } catch (\Stripe\Error\Base $e) {
          // Code to do something with the $e exception object when an error occurs
          $this->flash->error('There was a problem proccessing the credit card.  Please check the information and try again. <!--' . $e->getMessage() . '-->');
          }
          }
          }
          }
         *   
    }
     * 
     */