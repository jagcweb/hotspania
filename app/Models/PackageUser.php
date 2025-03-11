<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageUser extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'package_id', 'start_date', 'end_date'];
    protected $table = 'package_users';
    protected $dates = ['start_date', 'end_date'];

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}