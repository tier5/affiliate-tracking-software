<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AgentUrlDetails extends Model
{
    public function affiliate()
    {
        return $this->belongsTo('App\Affiliate','affiliate_id');
    }
}
