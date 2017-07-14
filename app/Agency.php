<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Agency extends Model
{
    //
    protected $table = 'agency';
    protected $primaryKey = 'agency_id';

    public static function disable()
    {
    	// disable businesses and pause subscriptions

    	// disable agency and pause subscription
    }

    public static function enable()
    {
    	// enable agency and unpause subscription
    	
    	// enable businesses and unpause subscriptions
    }
}
