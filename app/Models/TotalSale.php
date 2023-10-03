<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TotalSale extends Model
{
    protected $table = 'total_sales';
    protected $fillable = ['quantity', 'product_id'];

    public function product(){
    	return $this->hasOne("App\Models\Product", "id", "product_id");
    }


}
