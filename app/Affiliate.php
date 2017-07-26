<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Affiliate extends Model
{
    public $timestamps = false;

    public function user()
    {
        return $this->hasOne('App\User','id','userid');
    }
}
