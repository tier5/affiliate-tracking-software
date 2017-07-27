<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Affiliate extends Model
{
    public function user()
    {
        return $this->hasOne('App\User','user_id');
    }
}
