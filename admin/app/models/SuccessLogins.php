<?php
    namespace Vokuro\Models;

    use Phalcon\Mvc\Model;

    /**
     * SuccessLogins
     * This model registers successfull logins registered users have made
     */
    class SuccessLogins extends Model
    {


        public $id;
        public $usersId;
        public $ipAddress;
        public $userAgent;

        public function initialize()
        {
            $this->belongsTo('usersId', __NAMESPACE__ . '\Users', 'id', array(
                'alias' => 'user'
            ));
        }
    }
