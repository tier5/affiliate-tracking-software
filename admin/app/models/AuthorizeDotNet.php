<?php

namespace Vokuro\Models;

use Phalcon\Mvc\Model;

/**
 * AuthorizeDotNet
 */
class AuthorizeDotNet extends Model
{
    public $id;
    public $user_id;
    public $customer_profile_id;
    public $subscription_id;
    public $credit_card_type;
    public $created_at;
    public $update_at;
    public $deleted_at;

    /**
     * Validation method for model.
     */
    public function validation()
    {
        return true;
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'authorize_dot_net';
    }

    /**
     * Independent Column Mapping.
     * Keys are the real names in the table and the values their names in the application
     *
     * @return array
     */
    public function columnMap()
    {
        return array(
            'id' => 'id',
            'user_id' => 'user_id',
            'customer_profile_id' => 'customer_profile_id',
            'subscription_id' => 'subscription_id',
            'credit_card_type' => 'credit_card_type',
            'created_at' => 'created_at',
            'updated_at' => 'updated_at',
            'deleted_at' => 'deleted_at' 
        );
    }


}
