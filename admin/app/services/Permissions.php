<?php namespace Vokuro\Services;
use Vokuro\Models\Agency;
use Vokuro\Models\Location;
use Vokuro\Models\Users;

class Permissions{
    protected $di;
    public function __construct(){
        $di = \Phalcon\Di::getDefault();
        $this->di = $di;
    }

    /**
     * @param Users $u
     * @param Users $targetUser
     * @return bool
     */
    public function canUserEditUser(Users $u, Users $targetUser){
        $adminId = $u->getId();
        $targetUserId = $targetUser->getId();
        if($this->isUserSuperAdmin($u)) return true;
        if($targetUserId == $u->getId()) return true;
        /**
         * @TODO: create this function.. probably need to grab associated agency's and businesses then get the userIds
         * associated, then do a check for the target user based off of the $u
         */
        return true;
    }

    /**
     * @param Users $u
     * @param Users $targetUser
     * @return bool
     */
    public function canUserAdminUser(Users $u, Users $targetUser){
        return $this->canUserEditUser($u,$targetUser);
    }

    /**
     * @param Users $u
     * @param Agency $agency
     * @return bool
     */
    public function canUserEditAgency(Users $u, Agency $agency){
        if($this->isUserSuperAdmin($u)) return true;
        $agency_id = $u->agency_id;
        if($agency_id == $agency->agency_id) return true;
        //by default, return false
        return false;
    }

    /**
     * @param Users $u
     * @param Location $location
     * @return bool
     */
    public function canUserEditLocation(Users $u, Location $location){
        $agency = new Agency();
        $agency = $agency->findOneBy(['agency_id'=>$location->agency_id]);
        if($agency){
            if($agency->agency_id == $location->agency_id) return true;
        }
        if($this->isUserSuperAdmin($u)) return true;
        return false;
    }

    /**
     * @param Users $u
     * @param $location_id
     * @return bool
     */
    public function canUserEditLocationId(Users $u, $location_id){
        $m = new Location();
        $record = $m->findOneBy(['location_id'=>$location_id]);
        if($record){
            return $this->canUserEditLocation($u,$record);
        }
        return false;
    }


    /**
     * @param Users $u
     * @return bool
     */
    public function isUserSuperAdmin(Users $u){
        return (bool)$u->is_admin;
    }

    /**
     * @param Users $u
     * @param Location $location
     * @return bool
     */
    public function canUserSetLocation(Users $u, Location $location){
       return $this->canUserEditLocation($u,$location);
    }

    /**
     * @param Users $u
     * @param $location_id
     * @return bool
     */
    public function canUserSetLocationId(Users $u, $location_id){
        $model = new Location();
        $record = $model->findOneBy(['location_id'=>$location_id]);
        if($record){
            return $this->canUserSetLocation($u,$record);
        }
        return false;
    }

}