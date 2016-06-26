<?php
    namespace Vokuro\Models;

    use Phalcon\Mvc\Model;

    /**
     * FailedLogins
     * This model registers unsuccessfull logins registered and non-registered users have made
     */
    class FailedLogins extends Model
    {


        public $id;
        public $usersId;
        public $ipAddress;
        public $attempted;


        public function initialize()
        {
            $this->belongsTo('usersId', __NAMESPACE__ . '\Users', 'id', array(
                'alias' => 'user'
            ));
        }
    }
