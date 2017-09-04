<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model
{
    public function product()
    {
        return $this->belongsTo('App\Product','product_id');
    }
    public function log()
    {
        return $this->belongsTo('App\AgentUrlDetails','log_id');
    }
}
