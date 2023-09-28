<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    protected $table = 'discounts';
    protected $fillable = ['user_id', 'name', 'discount', 'uses', 'expiration_date'];

    public function user(){
    	return $this->hasOne("App\Models\User", "id", "user_id");
    }

}
