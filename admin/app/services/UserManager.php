<?php

namespace Vokuro\Services;

class UserManager extends BaseService {
    
    function __construct($config, $di) {
        parent::__construct($config, $di);
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
    
    public function getLocationId($session) {
        $identity = $session->get('auth-identity');
        if ($identity) {
            return $identity['location_id'];
        }
        return false;
    }
}
