<?php
    namespace Vokuro\Models;

    use Phalcon\Mvc\Model;
    use Phalcon\Mvc\Model\Resultset\Simple as Resultset;
    use Phalcon\Mvc\Model\Validator\Uniqueness;

    /**
     * Vokuro\Models\AgencyPricingPlan
     * The Pricing Plans
     */
    class AgencyPricingPlan extends Model
    {
        public $id, $name, $price_per_business, $number_of_businesses, $created_at, $updated_at, $deleted_at;

        public function initialize()
        {
            $this->setSource('agency_pricing_plan');


            // Not sure if this is correct, so commenting out until needed.
            //$this->hasMany("id", "AgencySubscriptionPlan", "pricing_plan_id");
        }

    }