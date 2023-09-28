<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';
    protected $fillable = ['user_id', 'discount', 'total', 'reference', 'status'];

    public function user(){
    	return $this->hasOne("App\Models\User", "id", "user_id");
    }

    public function products(){
    	return $this->hasMany(OrdersProduct::class);
    }

}
