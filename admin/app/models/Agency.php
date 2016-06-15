<?php

namespace Vokuro\Models;

use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Validator\Uniqueness;
use Vokuro\Models\Subscription;

/**
 * Vokuro\Models\Agency
 * The Locations
 */
class Agency extends Model {

    /**
     * Validate that custom_domain is unique across agencies
     */
    public function validation() {
        if (isset($this->custom_domain) && $this->custom_domain != '') {
            $this->validate(new Uniqueness(array(
                "field" => "custom_domain",
                "message" => "The Custom Domain is already used"
            )));
        }

        return $this->validationHasFailed() != true;
    }

    public function initialize() {
        $this->setSource('agency');

        $this->belongsTo('subscription_id', __NAMESPACE__ . '\Subscription', 'subscription_id', array(
            'alias' => 'subscription',
            'reusable' => true
        ));
    }

    /**
     * Creates (or updates if exists) business.
     * @param $tData array Form fields for business
     */
    public function createOrUpdateBusiness($tData) {
        $this->assign($tData);
        return $this->save();
    }

}
