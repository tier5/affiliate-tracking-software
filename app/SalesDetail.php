<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SalesDetail extends Model
{
    //
    public function order(){
        return $this->belongsTo('App\OrderProduct', 'sales_id');
    }
    
    public function product(){
        return $this->belongsTo('App\Product', 'product_id');
    }
}
