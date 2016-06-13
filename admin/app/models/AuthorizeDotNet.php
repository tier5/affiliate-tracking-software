<?php

namespace Vokuro\Models;

use Phalcon\Mvc\Model;

/**
 * AuthorizeDotNet
 */
class AuthorizeDotNet extends Model
{

    /**
     *
     * @var integer
     */
    protected $id;

    /**
     *
     * @var integer
     */
    protected $user_id;

    /**
     *
     * @var integer
     */
    protected $customer_profile_id;

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
     * Method to set the value of field user_id
     *
     * @param integer $user_id
     * @return $this
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;

        return $this;
    }
    
    /**
     * Method to set the value of customer_profile_id user_id
     *
     * @param integer $customer_profile_id user_id
     * @return $this
     */
    public function setCustomerProfileId($customer_profile_id)
    {
        $this->customer_profile_id = $customer_profile_id;

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
     * Returns the value of field user_id
     *
     * @return integer
     */
    public function getUserId()
    {
        return $this->user_id;
    }
    
    /**
     * Returns the value of field customer_profile_id
     *
     * @return integer
     */
    public function getCustomerProfileId()
    {
        return $this->customer_profile_id;
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
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return SubscriptionPlan[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return SubscriptionPlan
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
            'user_id' => 'user_id',
            'customer_profile_id' => 'customer_profile_id',
            'created_at' => 'created_at',
            'updated_at' => 'updated_at',
            'deleted_at' => 'deleted_at' 
        );
    }

}
