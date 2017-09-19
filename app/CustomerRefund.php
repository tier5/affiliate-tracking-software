<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerRefund extends Model
{
    public function campaign()
    {
        return $this->belongsTo('App\Campaign','campaign_id');
    }
    public function order()
    {
        return $this->belongsTo('App\OrderProduct','log_id');
    }
}
