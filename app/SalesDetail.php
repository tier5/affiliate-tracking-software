<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SalesDetail extends Model
{
    //
    public function order(){
        return $this->belongsTo('App\OrderProduct', 'sales_id');
    }
}
