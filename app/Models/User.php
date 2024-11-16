<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'full_name',
        'nickname',
        'age',
        'whatsapp',
        'phone',
        'is_smoker',
        'working_zone',
        'service_location',
        'gender',
        'services',
        'dni',
        'dni_file',
        'phone_number',
        'date_of_birth',
        'email',
        'height',
        'weight',
        'bust',
        'waist',
        'hip',
        'start_day',
        'end_day',
        'start_time',
        'end_time',
        'profile_image',
        'active',
        'frozen',
        'visible',
        'online',
        'password',
        'banned',
        'email_verified_at',
        'completed',
        'link'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_smoker' => 'boolean',
    ];

    public function images(){
        return $this->hasMany('App\Models\Image', 'user_id','id');
    }

    public function getRoleNames()
    {
        // Assuming you have a 'roles' pivot table
        return $this->roles->pluck('name')->toArray();
    }
}