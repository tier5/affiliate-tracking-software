<?php

namespace Vokuro\Models;

use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Validator\InclusionIn;
use Phalcon\Mvc\Model\Validator\Numericality;
use Phalcon\Mvc\Model\Behavior\SoftDelete;

/**
 * SubscriptionPricingPlan
 * 
 * @package Vokuro\Models
 * @autogenerated by Phalcon Developer Tools
 * @date 2016-05-23, 13:16:05
 */
class SubscriptionPricingPlan extends Model
{

    /**
     *
     * @var integer
     */
    protected $id;

    /**
     *
     * @var string
     */
    protected $name;
    
    /**
     *
     * @var integer
     */
    protected $enable_free_account;

    /**
     *
     * @var integer
     */
    protected $enable_discount_on_upgrade;

    /**
     *
     * @var double
     */
    protected $base_price;

    /**
     *
     * @var double
     */
    protected $cost_per_sms;

    /**
     *
     * @var integer
     */
    protected $trial_period;

    /**
     *
     * @var integer
     */
    protected $max_sms_during_trial_period;

    /**
     *
     * @var integer
     */
    protected $max_messages_on_free_account;
    
    /**
     *
     * @var integer
     */
    protected $max_location_on_free_account;

    /**
     *
     * @var double
     */
    protected $updgrade_discount;

    /**
     *
     * @var double
     */
    protected $charge_per_sms;

    /**
     *
     * @var integer
     */
    protected $max_sms_messages;

    /**
     *
     * @var integer
     */
    protected $trial_number_of_days;

    /**
     *
     * @var integer
     */
    protected $collect_credit_card_on_sign_up;

    /**
     *
     * @var string
     */
    protected $pricing_details;

    /**
     *
     * @var integer
     */
    protected $agency_id;
    
    /**
     *
     * @var integer
     */
    protected $created_at;
    
    /**
     *
     * @var integer
     */
    protected $update_at;
    
    /**
     *
     * @var integer
     */
    protected $deleted_at;

    
    public function initialize()
    {        
        $this->addBehavior(
            new SoftDelete(
                array(
                    'field' => 'deleted_at',
                    'value' => time()
                )
            )
        );
        
        $this->hasMany("id", "Vokuro\Models\SubscriptionPricingPlanHasParameterList", "subscription_pricing_plan_id", ['alias' => 'SubscriptionPricingPlanHasParameterList']);
    }
        
    public function validation()
    {
        $this->validate(new Inclusionin(["field"  => "enable_free_account", "domain" => [true, false]]));
        $this->validate(new Inclusionin([ "field"  => "enable_discount_on_upgrade", "domain" => [true, false] ]));
        $this->validate(new Numericality(["field" => 'base_price' ]));
        $this->validate(new Numericality(["field" => 'cost_per_sms']));
        $this->validate(new Numericality(["field" => 'max_sms_during_trial_period']));
        $this->validate(new Numericality(["field" => 'max_sms_messages']));
        $this->validate(new Numericality(["field" => 'updgrade_discount']));
        $this->validate(new Numericality(["field" => 'charge_per_sms']));
        $this->validate(new Numericality(["field" => 'max_sms_messages']));
        $this->validate(new Numericality(["field" => 'trial_number_of_days']));
        $this->validate(new Inclusionin(["field" => 'collect_credit_card_on_sign_up', "domain" => [true, false] ]));    
        // // $this->validate(new Numericality(["field" => 'created_at']));
        // // $this->validate(new Numericality(["field" => 'updated_at']));
        // // $this->validate(new Numericality(["field" => 'delete_at']));
        // 
        $pass = $this->base_price > 0 &&
            $this->cost_per_sms > 0 &&
            $this->max_sms_during_trial_period > 0 &&
            $this->max_sms_messages > 0 &&
            $this->updgrade_discount > 0 &&
            $this->charge_per_sms > 0 &&
            $this->max_sms_messages > 0 &&
            $this->trial_number_of_days > 0 &&
            $this->collect_credit_card_on_sign_up > 0;    
            
        /* TODO: Implement timestamp validator 
            $this->created_at > 0 &&
            $this->updated_at > 0 &&
            $this->delete_at > 0;
        */
       
        return true;/*$pass && $this->validationHasFailed() != true;*/
    }
    
    /**
     * Method to set the value of field id
     *
     * @param integer $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }
    
    /**
     * Method to set the value of field name
     *
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }
    
    /**
     * Method to set the value of field enable_free_account
     *
     * @param integer $enable_free_account
     * @return $this
     */
    public function setEnableFreeAccount($enable_free_account)
    {
        $this->enable_free_account = $enable_free_account;

        return $this;
    }

    /**
     * Method to set the value of field enable_discount_on_upgrade
     *
     * @param integer $enable_discount_on_upgrade
     * @return $this
     */
    public function setEnableDiscountOnUpgrade($enable_discount_on_upgrade)
    {
        $this->enable_discount_on_upgrade = $enable_discount_on_upgrade;

        return $this;
    }

    /**
     * Method to set the value of field base_price
     *
     * @param double $base_price
     * @return $this
     */
    public function setBasePrice($base_price)
    {
        $this->base_price = $base_price;

        return $this;
    }

    /**
     * Method to set the value of field cost_per_sms
     *
     * @param double $cost_per_sms
     * @return $this
     */
    public function setCostPerSms($cost_per_sms)
    {
        $this->cost_per_sms = $cost_per_sms;

        return $this;
    }

    /**
     * Method to set the value of field trial_period
     *
     * @param integer $trial_period
     * @return $this
     */
    public function setTrialPeriod($trial_period)
    {
        $this->trial_period = $trial_period;

        return $this;
    }

    /**
     * Method to set the value of field max_sms_during_trial_period
     *
     * @param integer $max_sms_during_trial_period
     * @return $this
     */
    public function setMaxSmsDuringTrialPeriod($max_sms_during_trial_period)
    {
        $this->max_sms_during_trial_period = $max_sms_during_trial_period;

        return $this;
    }

    /**
     * Method to set the value of field max_messages_on_free_account
     *
     * @param integer $max_messages_on_free_account
     * @return $this
     */
    public function setMaxMessagesOnFreeAccount($max_messages_on_free_account)
    {
        $this->max_messages_on_free_account = $max_messages_on_free_account;

        return $this;
    }
    
    /**
     * Method to set the value of field max_locations_on_free_account
     *
     * @param integer $max_locations_on_free_account
     * @return $this
     */
    public function setMaxLocationsOnFreeAccount($max_locations_on_free_account)
    {
        $this->max_locations_on_free_account = $max_locations_on_free_account;

        return $this;
    }

    /**
     * Method to set the value of field updgrade_discount
     *
     * @param double $updgrade_discount
     * @return $this
     */
    public function setUpdgradeDiscount($updgrade_discount)
    {
        $this->updgrade_discount = $updgrade_discount;

        return $this;
    }

    /**
     * Method to set the value of field charge_per_sms
     *
     * @param double $charge_per_sms
     * @return $this
     */
    public function setChargePerSms($charge_per_sms)
    {
        $this->charge_per_sms = $charge_per_sms;

        return $this;
    }
    
    /**
     * Method to set the value of field annual_plan_discount
     *
     * @param double $annual_plan_discount
     * @return $this
     */
    public function setAnnualPlanDiscount($annual_plan_discount)
    {
        $this->annual_plan_discount = $annual_plan_discount;

        return $this;
    }

    /**
     * Method to set the value of field max_sms_messages
     *
     * @param integer $max_sms_messages
     * @return $this
     */
    public function setMaxSmsMessages($max_sms_messages)
    {
        $this->max_sms_messages = $max_sms_messages;

        return $this;
    }

    /**
     * Method to set the value of field trial_number_of_days
     *
     * @param integer $trial_number_of_days
     * @return $this
     */
    public function setTrialNumberOfDays($trial_number_of_days)
    {
        $this->trial_number_of_days = $trial_number_of_days;

        return $this;
    }

    /**
     * Method to set the value of field collect_credit_card_on_sign_up
     *
     * @param integer $collect_credit_card_on_sign_up
     * @return $this
     */
    public function setCollectCreditCardOnSignUp($collect_credit_card_on_sign_up)
    {
        $this->collect_credit_card_on_sign_up = $collect_credit_card_on_sign_up;

        return $this;
    }

    /**
     * Method to set the value of field pricing_details
     *
     * @param string $pricing_details
     * @return $this
     */
    public function setPricingDetails($pricing_details)
    {
        $this->pricing_details = $pricing_details;

        return $this;
    }

    /**
     * Method to set the value of field agency_id
     *
     * @param integer $agency_id
     * @return $this
     */
    public function setAgencyId($agency_id)
    {
        $this->agency_id = $agency_id;

        return $this;
    }
    
    /**
     * Method to set the value of field created_at
     *
     * @param integer $created_at
     * @return $this
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;

        return $this;
    }
    
    /**
     * Method to set the value of field updated_at
     *
     * @param integer $updated_at
     * @return $this
     */
    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = $updated_at;

        return $this;
    }
    
    /**
     * Method to set the value of field deleted_at
     *
     * @param integer $deleted_at
     * @return $this
     */
    public function setDeletedAt($deleted_at)
    {
        $this->deleted_at = $deleted_at;

        return $this;
    }

    /**
     * Returns the value of field id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * Returns the value of field name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Returns the value of field enable_free_account
     *
     * @return integer
     */
    public function getEnableFreeAccount()
    {
        return $this->enable_free_account;
    }

    /**
     * Returns the value of field enable_discount_on_upgrade
     *
     * @return integer
     */
    public function getEnableDiscountOnUpgrade()
    {
        return $this->enable_discount_on_upgrade;
    }

    /**
     * Returns the value of field base_price
     *
     * @return double
     */
    public function getBasePrice()
    {
        return $this->base_price;
    }

    /**
     * Returns the value of field cost_per_sms
     *
     * @return double
     */
    public function getCostPerSms()
    {
        return $this->cost_per_sms;
    }
    
    /**
     * Returns the value of field annual_plan_discount
     *
     * @return double
     */
    public function getAnnualPlanDiscount()
    {
        return $this->annual_plan_discount;
    }
    
    /**
     * Returns the value of field trial_period
     *
     * @return integer
     */
    public function getTrialPeriod()
    {
        return $this->trial_period;
    }

    /**
     * Returns the value of field max_sms_during_trial_period
     *
     * @return integer
     */
    public function getMaxSmsDuringTrialPeriod()
    {
        return $this->max_sms_during_trial_period;
    }

    /**
     * Returns the value of field max_messages_on_free_account
     *
     * @return integer
     */
    public function getMaxMessagesOnFreeAccount()
    {
        return $this->max_messages_on_free_account;
    }
    
    /**
     * Returns the value of field max_locations_on_free_account
     *
     * @return integer
     */
    public function getMaxLocationsOnFreeAccount()
    {
        return $this->max_locations_on_free_account;
    }

    /**
     * Returns the value of field updgrade_discount
     *
     * @return double
     */
    public function getUpdgradeDiscount()
    {
        return $this->updgrade_discount;
    }

    /**
     * Returns the value of field charge_per_sms
     *
     * @return double
     */
    public function getChargePerSms()
    {
        return $this->charge_per_sms;
    }

    /**
     * Returns the value of field max_sms_messages
     *
     * @return integer
     */
    public function getMaxSmsMessages()
    {
        return $this->max_sms_messages;
    }

    /**
     * Returns the value of field trial_number_of_days
     *
     * @return integer
     */
    public function getTrialNumberOfDays()
    {
        return $this->trial_number_of_days;
    }

    /**
     * Returns the value of field collect_credit_card_on_sign_up
     *
     * @return integer
     */
    public function getCollectCreditCardOnSignUp()
    {
        return $this->collect_credit_card_on_sign_up;
    }

    /**
     * Returns the value of field pricing_details
     *
     * @return string
     */
    public function getPricingDetails()
    {
        return $this->pricing_details;
    }

    /**
     * Returns the value of field agency_id
     *
     * @return integer
     */
    public function getAgencyId()
    {
        return $this->agency_id;
    }
    
    /**
     * Returns the value of field created_at
     *
     * @return integer
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }
    
    /**
     * Returns the value of field updated_at
     *
     * @return integer
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }
    
    /**
     * Returns the value of field deleted_at
     *
     * @return integer
     */
    public function getDeletedAt()
    {
        return $this->deleted_at;
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'subscription_pricing_plan';
    }
    
    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return SubscriptionPricingPlan[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return SubscriptionPricingPlan
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
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
            'name' => 'name',
            'enable_free_account' => 'enable_free_account',
            'enable_discount_on_upgrade' => 'enable_discount_on_upgrade',
            'base_price' => 'base_price',
            'cost_per_sms' => 'cost_per_sms',
            'annual_plan_discount' => 'annual_plan_discount',
            'trial_period' => 'trial_period',
            'max_sms_during_trial_period' => 'max_sms_during_trial_period',
            'max_messages_on_free_account' => 'max_messages_on_free_account',
            'max_locations_on_free_account' => 'max_locations_on_free_account',
            'updgrade_discount' => 'updgrade_discount',
            'charge_per_sms' => 'charge_per_sms',
            'max_sms_messages' => 'max_sms_messages',
            'trial_number_of_days' => 'trial_number_of_days',
            'collect_credit_card_on_sign_up' => 'collect_credit_card_on_sign_up',
            'pricing_details' => 'pricing_details',
            'agency_id' => 'agency_id',
            'created_at' => 'created_at',
            'updated_at' => 'updated_at',
            'deleted_at' => 'deleted_at'  
        );
    }

}
