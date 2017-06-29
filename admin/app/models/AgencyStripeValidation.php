<?php
    namespace Vokuro\Models;

    use Phalcon\Mvc\Model;
    use Phalcon\Mvc\Model\Resultset\Simple as Resultset;
    use Phalcon\Mvc\Model\Validator\Uniqueness;

    /**
     * Vokuro\Models\AgencyStripeValidation
     * Stripe Validation Table
     */
    class AgencyStripeValidation extends Model
    {
        public $id, $agency_id, $stripe_status, $action, $stripe_subscription_id, $stripe_customer_id;

        const ACTION_DISABLE                    = 1;
        const ACTION_ENABLE                     = 2;
        const CREATE_SUPER_USER                 = 4;
        const CREATE_INTERNAL_SUBSCRIPTION      = 8;
        const UPDATE_CUSTOMER_ID                = 16;
        const UPDATE_SUBSCRIPTION_ID            = 32;

        const STATUS_NOT_PROCESSEED             = 0;
        const STATUS_PROCESSED                  = 1;

        public function initialize() {
            $this->setSource('agency_stripe_validation');
        }
    }