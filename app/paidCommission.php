<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class paidCommission extends Model
{
    public function affiliate()
    {
        return $this->belongsTo('App\Affiliate','affiliate_id');
    }
    public function campaign()
    {
        return $this->belongsTo('App\Campaign','campaign_id');
    }
}
