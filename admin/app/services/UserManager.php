<?php

namespace Vokuro\Services;

class UserManager extends BaseService {
    
    function __construct($config) {
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
        return $identity && $identity['profile'] === 'Employee';
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
    
}
