<?php

namespace Vokuro\Models;

use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Behavior\SoftDelete;

class BusinessSubscriptionInvitation extends Model {

    public $id;
    public $user_id;
    public $business_subscription_plan_id;
    public $token;
    public $created_at;
    public $updated_at;
    public $deleted_at;

    public function beforeValidationOnCreate() {
        $this->created_at = time();
        $this->token = preg_replace('/[^a-zA-Z0-9]/', '', base64_encode(openssl_random_pseudo_bytes(24)));
    }

    public function beforeValidationOnUpdate() {
        $this->updated_at = time();
    }

    public function initialize() {
        $this->addBehavior(new SoftDelete([ 'field' => 'deleted_at', 'value' => time() ]));
        $this->skipAttributesOnCreate(['updated_at']);
    }

    public function validation() {
        return true;
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource() {
        return 'business_subscription_invitation';
    }

    /**
     * Independent Column Mapping.
     * Keys are the real names in the table and the values their names in the application
     *
     * @return array
     */
    public function columnMap() {
        return array(
            'id' => 'id',
            'user_id' => 'user_id',
            'business_subscription_plan_id' => 'business_subscription_plan_id',
            'token' => 'token',
            'created_at' => 'created_at',
            'updated_at' => 'updated_at',
            'deleted_at' => 'deleted_at'
        );
    }

}
