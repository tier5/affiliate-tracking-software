<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public function campaign(){

        return $this->belongsTo(Campaign::class);
    }
}
