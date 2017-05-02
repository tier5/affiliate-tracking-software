<?php

namespace Vokuro\Models;

use Vokuro\Models\BaseModel;
use Phalcon\Mvc\Model\Validator\Uniqueness;
use Vokuro\Models\Subscription;
use \Phalcon\Mvc\Model\Validator\Regex;
use \Phalcon\Mvc\Model\Validator\Email;
use Vokuro\Services\StripeService;

class Agency extends BaseModel
{

    //public $id;
    public $agency_id;
    public $deactivated_with_agency;
    public $country_code;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->agency_id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->agency_id = $id;
    }

    const AGENCY = 0;
    const BUSINESS_UNDER_RV = -1;

    /**
     * Validate that custom_domain is unique across agencies
     */
    public function validation()
    {
        /*$this->validate(new Regex([
            'field'   => 'name',
            'pattern' => '/^[a-zA-Z\.0-9 ]+$/',
            'message' => 'Name is in the wrong format (letters, period, and apostrophe)'
        ]));*/

        /*$this->validate(new Email([
            'field'   => 'email',
            'message' => 'Email is in the wrong format (xxx@xxx.xxx)'
        ]));
        $this->validate(new Uniqueness(array(
            "field"   => "email",
            "message" => "Value of field 'email' is already present in another record"
          )));
        if (isset($this->custom_domain) && $this->custom_domain != '') {
            $this->validate(new Uniqueness(array(
                "field" => "custom_domain",
                "message" => "The Custom Domain is already used"
            )));
        }*/

        return $this->validationHasFailed() != true;
    }

    public function initialize()
    {
       // $this->skipAttributes(['address2']);

        if (isset($this->parent_id) && $this->parent_id != static::AGENCY) {
            $this->skipAttributes(['website']);
            $this->skipAttributes(['email_from_name']);
            $this->skipAttributes(['email_from_address']);
        }

        $this->setSource('agency');

        $this->belongsTo(
            'subscription_id',
            __NAMESPACE__ . '\Subscription',
            'subscription_id',
            array(
                'alias' => 'subscription',
                'reusable' => true
            )
        );
        //if (!$this->_skipped) $this->skipAttributes(['address2']); //address2 should NOT be required
        parent::initialize();
    }

    public function getStripeKeys()
    {

        return [
            'public' => $this->stripe_publishable_keys,
            'secret' => $this->stripe_account_secret
        ];
    }

    /**
     * Deactivate all buinesses underneath agency
     */

    public function deactivateBusinesses($agencyId = null)
    {
        if ($agencyId == null) {
            $agencyId = $this->agency_id;
        }

        $params = array(
            'parent_id' => $agencyId,
            'status' => 1
        );

        $businesses = self::query()->where('parent_id = :parent_id:')
                                   ->andWhere('status = :status:')
                                   ->bind($params)
                                   ->execute();

        $stripe = new StripeService();

        $stripe->setAgencyStripeKeys($agencyId);

        foreach ($businesses as $business) {
            $business->deactivated_with_agency = 1;
            $business->status = 0;
            $business->save();

            $stripe->pauseSubscription($business->agency_id);
            // check if has subscription + apply 100% off coupon to plan
            // get subscription id check stripe
                // check if trial save remaining trial days
        }
    }

    /**
     * Activate all buinesses underneath agency
     */

    public function activateBusinesses($agencyId = null)
    {
        if ($agencyId == null) {
            $agencyId = $this->agency_id;
        }

        $params = array(
            'parent_id' => $agencyId,
            'deactivated_with_agency' => 1
        );

        $businesses = self::query()->where('parent_id = :parent_id:')
                                   ->addWhere('deactivated_with_agency = :deactivated_with_agency:')
                                   ->bind($params)
                                   ->execute();

        $stripe = new StripeService();

        $stripe->setAgencyStripeKeys($agencyId);

        foreach ($businesses as $business) {
            $business->deactivated_with_agency = 0;
            $business->status = 1;
            $business->save();

            $stripe->unpauseSubscription($business->agency_id);
        }
    }

    public function disable($agencyId = null)
    {
        if ($agencyId == null) {
            $agencyId = $this->agency_id;
        }

        $agency = self::findFirst('agency_id = ' . $agencyId);

        $agency->status = 0;

        $agency->save();

        // if business get agency id
        if ($agency->parent_id != 0) {
            $agencyIdForStripeKeys = $agency->parent_id;
        } else {
            $agencyIdForStripeKeys = $agencyId;
        }

        $stripe = new StripeService();

        $set = $stripe->setAgencyStripeKeys($agencyIdForStripeKeys);

        if ($set) {
            $stripe->pauseSubscription($agencyId);
        }
    }

    public function enable($agencyId = null)
    {
        if ($agencyId == null) {
            $agencyId = $this->agency_id;
        }

        $agency = self::findFirst('agency_id = ' . $agencyId);

        $agency->status = 1;

        $agency->save();

        // if business get agency id
        if ($agency->parent_id != 0) {
            $agencyIdForStripeKeys = $agency->parent_id;
        } else {
            $agencyIdForStripeKeys = $agencyId;
        }

        $stripe = new StripeService();

        $set = $stripe->setAgencyStripeKeys($agencyIdForStripeKeys);

        if ($set) {
           $stripe->unpauseSubscription($agencyId); 
        }
    }


    /**
     * Creates (or updates if exists) business.
     * @param $tData array Form fields for business
     */
    public function createOrUpdateBusiness($tData)
    {
        $this->assign($tData);
        return $this->save();
    }

}
