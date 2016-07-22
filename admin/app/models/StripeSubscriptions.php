<?php

namespace Vokuro\Models;

use Phalcon\Mvc\Model;

/**
 * AuthorizeDotNet
 */
class StripeSubscriptions extends Model
{
    public $id;
    public $stripe_customer_id;
    public $user_id;
    public $stripe_subscription_id;

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
        return 'stripe_subscriptions';
    }



}
