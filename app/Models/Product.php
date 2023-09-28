<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';
    protected $fillable = ['user_id', 'category_id', 'subcategory_id', 'name', 'description', 'price', 'units', 'discontinued', 'images'];

    public function user(){
    	return $this->hasOne("App\Models\User", "id", "user_id");
    }

    public function category(){
    	return $this->belongsTo("App\Models\Category", "category_id", "id");
    }

    public function subcategory(){
    	return $this->belongsTo("App\Models\Subcategory", "subcategory_id", "id");
    }

}
