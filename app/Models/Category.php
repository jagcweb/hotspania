<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';
    protected $fillable = ['user_id', 'name', 'description', 'image'];

    public function user(){
    	return $this->hasOne("App\Models\User", "id", "user_id");
    }

    public function subcategories(){
    	return $this->hasMany("App\Models\Subcategory", "category_id", "id");
    }

    public function products(){
    	return $this->hasMany("App\Models\Product", "category_id", "id");
    }

}
