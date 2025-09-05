<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Report extends Model
{
    use HasFactory;

    protected $table = 'reports';

    protected $fillable = [
        'user_id',
        'session_id',
        'reported_user_id',
        'reason',
        'details',
    ];

    /**
     * El usuario que envía el reporte (puede ser null si no logueado).
     */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    /**
     * El usuario reportado.
     */
    public function reportedUser()
    {
        return $this->belongsTo(\App\Models\User::class, 'reported_user_id');
    }
}