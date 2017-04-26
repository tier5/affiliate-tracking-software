<?php

namespace Vokuro\Auth;

use Phalcon\Mvc\User\Component;
use Vokuro\Models\Agency;
use Vokuro\Models\FailedLogins;
use Vokuro\Models\Location;
use Vokuro\Models\RememberTokens;
use Vokuro\Models\SuccessLogins;
use Vokuro\Models\Users;

/**
 * Vokuro\Auth\Auth
 * Manages Authentication/Identity Management in Vokuro
 */
class Auth extends Component 
{

    /**
     * Checks the user credentials
     *
     * @param array $credentials
     * @return boolan
     */
    public function check($credentials) 
    {
        if ($this->session->has("subscription_not_valid")) {
            $this->session->remove("subscription_not_valid");
        }

        // Check if the user exist
        $user = Users::findFirstByEmail($credentials['email']);

        if ($user == false) {
            $this->registerUserThrottling(0);
            throw new Exception('Wrong email/password combination');
        }

        // Check the password
        if (!$this->security->checkHash($credentials['password'], $user->password)) {
            $this->registerUserThrottling($user->id);
            throw new Exception('Wrong email/password combination');
        }

        // Check if the user was flagged

        $this->checkUserFlags($user);

        // Register the successful login
        $this->saveSuccessLogin($user);

        // Check if the remember me was selected
        if ($credentials['remember']) {
            $this->createRememberEnviroment($user);
        }

        $this->login($user);
    }

    public function login($user)
    {
        $locs = $this->getLocationList($user); //set the location list in the identity
        $location_id = ($locs && isset($locs[0]) ? $locs[0]->location_id : '');
        $location_name = ($locs && isset($locs[0]) ? $locs[0]->name : '');

        $this->session->set('auth-identity', array(
            'id' => $user->id,
            'name' => $user->name,
            'profile' => $user->profile->name,
            'profilesId' => $user->profilesId,
            'locations' => $locs, //set the location list in the identity,
            'location_id' => $location_id,
            'location_name' => $location_name,
            'is_admin' => $user->is_admin,
            'is_employee' => $user->is_employee,
        	'role' => $user->role,
            'agencytype' => $this->getAgencyType($user->agency_id),
            'parent_id' => $this->getAgencyParentId($user->agency_id),
            'signup_page' => $this->getCurrentSignupPage($user->agency_id)
        ));
    }

    public function getAgencyParentId($agency_id)
    {
        $conditions = "agency_id = :agency_id:";
        $parameters = array("agency_id" => $agency_id);
        $agency = Agency::findFirst(array($conditions, "bind" => $parameters));

        if (!$agency) {
            return 0;
        }
        
        return $agency->parent_id;
    }

    public function getAgencyType($agency_id)
    {
        // find agency type
        $agencytype = 'agency';
        $conditions = "agency_id = :agency_id:";
        $parameters = array("agency_id" => $agency_id);
        $agency = Agency::findFirst(array($conditions, "bind" => $parameters));
        if ($agency->agency_type_id == 2) {
            $agencytype = 'business';
        }
        return $agencytype;
    }

    public function getCurrentSignupPage($agency_id)
    {
        $conditions = "agency_id = :agency_id:";
        $parameters = array("agency_id" => $agency_id);
        $agency = Agency::findFirst(array($conditions, "bind" => $parameters));
        return $agency->signup_page;
    }

    /**
     * This function gets a list of agency locations
     *
     * @param Vokuro\Models\Users $user
     */
    public function getLocationList($user)
    {
        $conditions = "agency_id = :agency_id:";
        $parameters = array("agency_id" => $user->agency_id);
        $locs = Location::find(array($conditions, "bind" => $parameters));

        if ($locs) {
            return $locs;
        }

        return [];
    }

    /**
     * This function sets a list of agency locations
     *
     */
    public function setLocationList()
    {
        $this->session->set('auth-identity', array(
            'id' => $this->session->get('auth-identity')['id'],
            'name' => $this->session->get('auth-identity')['name'],
            'profile' => $this->session->get('auth-identity')['profile'],
            'profilesId' => $this->session->get('auth-identity')['profilesId'],
            'locations' => $this->getLocationList($this->getUser()), //set the location list in the identity,
            'location_id' => $this->session->get('auth-identity')['location_id'],
            'location_name' => $this->session->get('auth-identity')['location_name'],
            'is_admin' => $this->session->get('auth-identity')['is_admin'],
            'is_employee' => $this->session->get('auth-identity')['is_employee'],
        	'role' => $this->session->get('auth-identity')['role'],
            'agencytype' => $this->session->get('auth-identity')['agencytype']
        ));
    }

    /**
     * Creates the remember me environment settings the related cookies and generating tokens
     *
     * @param Vokuro\Models\Users $user
     */
    public function saveSuccessLogin($user)
    {
        $successLogin = new SuccessLogins();
        $successLogin->usersId = $user->id;
        $successLogin->ipAddress = $this->request->getClientAddress();
        $successLogin->userAgent = $this->request->getUserAgent();

        if (!$successLogin->save()) {
            $messages = $successLogin->getMessages();

            throw new Exception($messages[0]);
        }
    }

    /**
     * Implements login throttling
     * Reduces the efectiveness of brute force attacks
     *
     * @param int $userId
     */
    public function registerUserThrottling($userId)
    {
        $failedLogin = new FailedLogins();
        $failedLogin->usersId = $userId;
        $failedLogin->ipAddress = $this->request->getClientAddress();
        $failedLogin->attempted = time();
        $failedLogin->save();

        $attempts = FailedLogins::count(array(
                    'ipAddress = ?0 AND attempted >= ?1',
                    'bind' => array(
                        $this->request->getClientAddress(),
                        time() - 3600 * 6
                    )
        ));

        switch ($attempts) {
            case 1:
            case 2:
                // no delay
                break;
            case 3:
            case 4:
                sleep(2);
                break;
            default:
                sleep(4);
                break;
        }
    }

    /**
     * Creates the remember me environment settings the related cookies and generating tokens
     *
     * @param Vokuro\Models\Users $user
     */
    public function createRememberEnviroment(Users $user)
    {
        $userAgent = $this->request->getUserAgent();
        $token = md5($user->email . $user->password . $userAgent);

        $remember = new RememberTokens();
        $remember->usersId = $user->id;
        $remember->token = $token;
        $remember->userAgent = $userAgent;

        if ($remember->save() != false) {
            $expire = time() + 86400 * 8;
            $this->cookies->set('RMU', $user->id, $expire);
            $this->cookies->set('RMT', $token, $expire);
        }
    }

    /**
     * Check if the session has a remember me cookie
     *
     * @return boolean
     */
    public function hasRememberMe()
    {
        return $this->cookies->has('RMU');
    }

    /**
     * Logs on using the information in the coookies
     *
     * @return Phalcon\Http\Response
     */
    public function loginWithRememberMe()
    {
        $userId = $this->cookies->get('RMU')->getValue();
        $cookieToken = $this->cookies->get('RMT')->getValue();

        $user = Users::findFirstById($userId);

        if ($user) {
            $userAgent = $this->request->getUserAgent();
            $token = md5($user->email . $user->password . $userAgent);

            if ($cookieToken == $token) {
                $remember = RememberTokens::findFirst(array(
                            'usersId = ?0 AND token = ?1',
                            'bind' => array(
                                $user->id,
                                $token
                            )
                ));

                if ($remember) {

                    // Check if the cookie has not expired
                    if ((time() - (86400 * 8)) < $remember->createdAt) {

                        // Check if the user was flagged
                        $this->checkUserFlags($user);

                        $locs = $this->getLocationList($user); //set the location list in the identity
                        $location_id = ($locs && $locs[0]) ? $locs[0]->location_id : '';
                        $location_name = ($locs && $locs[0]) ? $locs[0]->name : '';
                        $this->session->set('auth-identity', array(
                            'id' => $user->id,
                            'name' => $user->name,
                            'profile' => $user->profile->name,
                            'profilesId' => $user->profilesId,
                            'locations' => $locs, //set the location list in the identity,
                            'location_id' => $location_id,
                            'location_name' => $location_name,
                            'is_admin' => $user->is_admin,
                            'is_employee' => $user->is_employee,
                        	'role' => $user->role,
                            'agencytype' => $this->getAgencyType($user->agency_id)
                        ));

                        // Register the successful login
                        $this->saveSuccessLogin($user);

                        return $this->response->redirect('/users');
                    }
                }
            }
        }

        $this->cookies->get('RMU')->delete();
        $this->cookies->get('RMT')->delete();

        return $this->response->redirect('/session/login');
    }

    /**
     * Sets the location
     *
     * @return array
     */
    public function setLocation($locationid)
    {
        $conditions = "location_id = :location_id:";
        $parameters = array("location_id" => $locationid);
        $loc = Location::findFirst(array($conditions, "bind" => $parameters));

        $iden = $this->session->get('auth-identity');
        $this->session->set('auth-identity', array(
            'id' => $iden['id'],
            'name' => $iden['name'],
            'profile' => $iden['profile'],
            'profilesId' => $iden['profilesId'],
            'locations' => $iden['locations'], //set the location list in the identity,
            'location_id' => $locationid,
            'location_name' => $loc->name,
            'is_admin' => $iden['is_admin'],
            'is_employee' => $iden['is_employee'],
        	'role' => $iden['role'],
            'agencytype' => $iden['agencytype']
        ));
    }

    /**
     * Checks if the user is banned/inactive/suspended
     *
     * @param Vokuro\Models\Users $user
     */
    public function checkUserFlags(Users $user)
    {
        $objAgency = \Vokuro\Models\Agency::findFirst("agency_id = {$user->agency_id}");

        $AgencyID = 0;

        if ($objAgency->parent_id == \Vokuro\Models\Agency::AGENCY) {
            $AgencyID = $objAgency->agency_id;
        } else {
            $objParentAgency = \Vokuro\Models\Agency::findFirst("agency_id = {$objAgency->parent_id}");
            $AgencyID = $objParentAgency->agency_id;
        }

        if ($user->active != 'Y') {
            throw new Exception('Your account is inactive');
        }

        if ($user->banned != 'N') {
            throw new Exception('Your account is banned');
        }

        if ($user->suspended != 'N') {
            throw new Exception('Your account is suspended');
        }

        $conditions = "agency_id = :agency_id:";
        $parameters = array("agency_id" => $user->agency_id);
        $agency = Agency::findFirst(array($conditions, "bind" => $parameters));

        if ($agency->subscription_valid != 'Y') {
            $this->session->set("subscription_not_valid", true);

            $user = Users::findFirst(array(
                'conditions' => 'agency_id = ' . $agency->agency_id . ' AND role="Super Admin"'
            ));

            $email = $user->email;

            // if agency get rv stripe keys
            if ($agency->parent_id == Agency::AGENCY) {
                $stripeKey = $this->config->stripe->publishable_key;
            } else { 
                // get agency stripe keys

                $conditions = "agency_id = :agency_id:";
                $parameters = array("agency_id" => $agency->parent_id);
                
                $parentAgency = Agency::findFirst(
                    array($conditions, "bind" => $parameters)
                );

                $stripeKey = $parentAgency->stripe_publishable_keys;
            }

            $this->session->set("stripe_publishable_key", $stripeKey);

            $this->session->set("email", $email);

            throw new Exception('Your account is suspended because your subscription is not active');
        }

        if ($agency->status != '1') {
            throw new Exception('Your account is not active');
        }
    }

    /**
     * Returns the current identity
     *
     * @return array
     */
    public function getIdentity()
    {
        return $this->session->get('auth-identity');
    }

    /**
     * Returns the current identity
     *
     * @return string
     */
    public function getName()
    {
        $identity = $this->session->get('auth-identity');
        return $identity['name'];
    }

    /**
     * Removes the user identity information from session
     */
    public function remove()
    {
        if ($this->cookies->has('RMU')) {
            $this->cookies->get('RMU')->delete();
        }

        if ($this->cookies->has('RMT')) {
            $this->cookies->get('RMT')->delete();
        }

        $this->session->remove('auth-identity');
    }

    /**
     * Auths the user by id
     *
     * @param int $id
     */
    public function authUserById($id)
    {
        $user = Users::findFirstById($id);
        if ($user == false) {
            throw new Exception('The user does not exist');
        }

        $this->checkUserFlags($user);
        $locs = null;
        $locs = $this->getLocationList($user); // set the location list in the identity

        if (isset($locs[0])) {
          $location_id = ($locs && $locs[0]) ? $locs[0]->location_id : '';
          $location_name = ($locs && $locs[0]) ? $locs[0]->name : '';
        }

        $this->session->set('auth-identity', array(
            'id' => $user->id,
            'name' => $user->name,
            'profile' => $user->profile->name,
            'profilesId' => $user->profilesId,
            'locations' => $locs, //set the location list in the identity,
            'location_id' => $location_id,
            'location_name' => $location_name,
            'is_admin' => $user->is_admin,
        	'role' => $user->role,
            'is_employee' => $user->is_employee,
            'agencytype' => $this->getAgencyType($user->agency_id),
        ));
    }

    /**
     * Get the entity related to user in the active identity
     *
     * @return \Vokuro\Models\Users
     */
    public function getUser()
    {
        $identity = $this->session->get('auth-identity');

        if ($identity['id']) {
            $user = Users::findFirstById($identity['id']);

            if ($user == false) {
                throw new Exception(
                    'The user with id of: ' . $identity['id'] . ' does not exist'
                );
            }
            return $user;
        }
        return false;
    }
}
