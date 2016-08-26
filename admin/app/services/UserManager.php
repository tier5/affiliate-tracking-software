<?php

namespace Vokuro\Services;

use Phalcon\Crypt;
use Vokuro\Auth\Auth;
use Vokuro\Models\Users;

class UserManager extends BaseService {

    function __construct($config = null) {
        $di = \Phalcon\Di::getDefault();
        $config = $di->getConfig();
        parent::__construct($config);
    }

    public function isSuperAdmin($session) {
        $identity = $session->get('auth-identity');
        return $identity && $identity['is_admin'];
    }

    public function isAgency($session) {
        $identity = $session->get('auth-identity');
        return $identity && ($identity['agencytype'] === 'Agency');
    }

    public function isBusiness($session) {
        $identity = $session->get('auth-identity');
        return $identity && ($identity['agencytype'] === 'business');
    }

    public function isWhiteLabeledBusiness($session) {
        $identity = $session->get('auth-identity');
        // GARY_TODO:  Refactor this.  I dont want to make a db call every page reload

        $objUser = \Vokuro\Models\Users::findFirst('id = ' . $identity['id']);
        $objAgency = \Vokuro\Models\Agency::findFirst('agency_id = ' . $objUser->agency_id);
        return $identity && ($identity['agencytype'] === 'business') && (intval($objAgency->parent_id) !== -1);
    }

    public function isEmployee($session) {
        $identity = $session->get('auth-identity');
        return $identity && $identity['is_employee'];
    }

    public function hasLocation($session) {
        $identity = $session->get('auth-identity');
        return $identity && $identity['location_id'];
    }

    public function getUserId($session) {
        $identity = $session->get('auth-identity');
        if ($identity) {
            return $identity['id'];
        }
        return false;
    }

    public function currentSignupPage($session) {
        $identity = $session->get('auth-identity');
        if ($identity) {
            return $identity['signup_page'];
        }
        return 0;
    }

    public function getLocationId($session) {
        $identity = $session->get('auth-identity');
        if ($identity) {
            return $identity['location_id'];
        }
        return false;
    }

    public function isMaxLimitReached() {
        $maxreached = false;

        //check to make sure that we have not already reached the max allowed for signup today
        //echo '<p>perday:'.$this->config->maxSignup->perday.'</p>';
        if ($this->config->maxSignup->perday > 0) { //zero equals infinite
        //
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


    /**
     * This function allows you to login as a user from a service, instead of doing everything from a controller
     * @param $userId
     * @param bool $authInactive
     * @return static
     * @throws \Exception
     */
    public function sudoAsUserId($userId, $authInactive = false){
        $users = new Users();
        $record = $users->findFirst($userId);
        if(!$record) throw new \Exception('Invalid User specified for id of:'.$userId);
        $active = $record->active;
        if($active == 'N' && !$authInactive){
            throw new \Exception('You cannot login as an inactive user, provided id of: ' .$userId);
        }
        $auth = new Auth();
        $auth->login($record);
        return $record;
    }

}
