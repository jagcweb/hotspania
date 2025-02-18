<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageUserHistory extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'package_id'];
    protected $table = 'package_users_history';

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}