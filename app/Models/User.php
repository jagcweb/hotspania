<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Filament\Models\Contracts\FilamentUser;

class User extends Authenticatable implements FilamentUser
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
        'link',
        'position',
        'available_time',
        'available_until',
        'reject_reason',
        'first_time'
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

    public function cities(){
        return $this->hasMany('App\Models\CityUser', 'user_id','id');
    }

    /*public function zones(){
        return $this->belongsToMany(Zone::class, 'zone_users');
    }*/

    public function packageUser(){
        return $this->hasOne('App\Models\PackageUser', 'user_id','id');
    }

    public function getRoleNames()
    {
        // Assuming you have a 'roles' pivot table
        return $this->roles->pluck('name')->toArray();
    }

    public function canAccessFilament(): bool
    {
        return $this->email === 'admin@admin.es';
    }

    public function getFilamentName(): string
    {
        return trim($this->full_name ?? $this->nickname ?? $this->email ?? '') ?: 'Usuario';
    }

    public function getNameAttribute(): string
    {
        return $this->full_name ?? $this->nickname ?? $this->email ?? 'Usuario';
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}