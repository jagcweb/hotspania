<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrdersProduct extends Model
{
    protected $table = 'orders_product';
    protected $fillable = ['user_id', 'order_id', 'quantity', 'discount', 'price', 'name', 'images'];

    public function user(){
    	return $this->hasOne("App\Models\User", "id", "user_id");
    }

    public function order(){
    	return $this->belongsTo("App\Models\Order", "order_id", "id");
    }

}
