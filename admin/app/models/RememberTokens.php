<?php
    namespace Vokuro\Models;

    use Phalcon\Mvc\Model;

    /**
     * RememberTokens
     * Stores the remember me tokens
     */
    class RememberTokens extends Model
    {


        public $id;
        public $usersId;
        public $token;
        public $userAgent;
        public $createdAt;

        /**
         * Before create the user assign a password
         */
        public function beforeValidationOnCreate()
        {
            // Timestamp the confirmaton
            $this->createdAt = time();
        }

        public function initialize()
        {
            $this->belongsTo('usersId', __NAMESPACE__ . '\Users', 'id', array(
                'alias' => 'user'
            ));
        }
    }
