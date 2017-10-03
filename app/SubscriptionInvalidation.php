<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubscriptionInvalidation extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'agency_type', 'agency_id', 'name', 'email', 'stripe_exists_in_db', 'stripe_subscription_exists',
    ];
}
