<?php
    namespace Vokuro\Models;

    use Phalcon\Mvc\Model;
    use Phalcon\Mvc\Model\Resultset\Simple as Resultset;
    use Phalcon\Mvc\Model\Validator\Uniqueness;

    /**
     * Vokuro\Models\AgencySubscriptionPlan
     * The Pricing Plans
     */
    class AgencySubscriptionPlan extends Model
    {
        public $id, $pricing_plan_id, $agency_id, $created_at, $updated_at, $deleted_at;

        public function initialize()
        {
            $this->setSource('agency_subscription_plan');

            // Not sure if this is correct, so commenting out until needed.
            //$this->belongsTo("pricing_plan_id", "AgencyPricingPlan", "id");
        }

    }