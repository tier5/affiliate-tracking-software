<?php

namespace Vokuro\Services;

class UserManager extends BaseService {

    function __construct($config) {
        parent::__construct($config);
    }

    public function getIdentity($session){
        return $session->get('auth-identity');
    }

    public function isSuperAdmin($session) {
        $identity = $this->getIdentity($session);
        return $identity && $identity['is_admin'];
    }

    public function isAgency($session) {
        $identity = $this->getIdentity($session);
        return $identity && $identity['agencytype'] === 'Agency';
    }

    public function isBusiness($session) {
        $identity = $this->getIdentity($session);
        return $identity && $identity['agencytype'] === 'business';
    }

    public function isWhiteLabeledBusiness($session) {
        $identity = $this->getIdentity($session);
        return $identity && ($identity['agencytype'] === 'business') && (intval($identity['parent_id']) !== -1);
    }

    public function isEmployee($session) {
        $identity = $this->getIdentity($session);
        return $identity && $identity['profile'] === 'Employee';
    }

    public function hasLocation($session) {
        $identity = $session->getIdentity($session);
        return $identity && $identity['location_id'];
    }

    public function getUserId($session) {
        $identity = $this->getIdentity($session);
        if ($identity) {
            return $identity['id'];
        }
        return false;
    }

    public function currentSignupPage($session) {
        $identity = $this->getIdentity($session);
        if ($identity) {
            return $identity['signup_page'];
        }
        return 0;
    }

    public function getLocationId($session) {
        $identity = $this->getIdentity($session);
        if ($identity) {
            return $identity['location_id'];
        }
        return false;
    }

    public function isMaxLimitReached() {
        $max_reached = false;

        //check to make sure that we have not already reached the max allowed for signup today
        //echo '<p>perday:'.$this->config->maxSignup->perday.'</p>';
        if ($this->config->maxSignup->perday > 0) { //zero equals infinite
        //
            //find out how many signed up today
            $report = Users::getDailySignupCount();
            //echo '<p>count:'.$report->count().'</p>';
            if ($report->count() >= $this->config->maxSignup->perday) {
                //we reached our max limit, so don't allow any additional signup
                $max_reached = true;
            }

        } //end checking for a max signup
        return $max_reached;
    }

}
