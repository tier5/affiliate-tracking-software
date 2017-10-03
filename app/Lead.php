<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'link_id', 'user_id', 'plan_id', 'stage', 'commission',
    ];

}
