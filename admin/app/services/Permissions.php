<?php namespace Vokuro\Services;
use Vokuro\Models\Users;

class Permissions{
    protected $di;
    public function __construct(){
        $di = \Phalcon\Di::getDefault();
        $this->di = $di;
    }

    public function canUserEditUser(Users $u, Users $targetUser){
        $adminId = $u->getId();
        $targetUserId = $targetUser->getId();
        /**
         * @TODO: create this function.. probably need to grab associated agency's and businesses then get the userIds
         * associated, then do a check for the target user based off of the $u
         */
        return true;
    }

    public function canUserAdminUser(Users $u, Users $targetUser){
        return $this->canUserEditUser($u,$targetUser);
    }

}