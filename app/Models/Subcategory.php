<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subcategory extends Model
{
    protected $table = 'subcategories';
    protected $fillable = ['user_id', 'category_id', 'name'];

    public function user(){
    	return $this->hasOne("App\Models\User", "id", "user_id");
    }

    public function category(){
    	return $this->belongsTo("App\Models\Category", "category_id", "id");
    }

}
