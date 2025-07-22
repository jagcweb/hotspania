<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZoneUser extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'zone_id'];
    protected $table = 'zone_users';

    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}