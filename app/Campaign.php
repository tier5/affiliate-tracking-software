<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    public function affiliate()
    {
        return $this->hasMany('App\Affiliate','campaign_id');
    }
    public function user()
    {
        return $this->belongsTo('App\User','user_id');
    }
    public function products()
    {
        return $this->hasMany('App\Product','campaign_id');
    }
}
