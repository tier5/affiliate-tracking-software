<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Affiliate extends Model
{
    public function user()
    {
        return $this->belongsTo('App\User','user_id');
    }
    public function campaign()
    {
        return $this->belongsTo('App\Campaign','campaign_id');
    }
}
