<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;

    // La tabla asociada al modelo
    protected $table = 'jobs'; 

    // Definir los campos que no se deben modificar manualmente
    protected $fillable = [
        'queue',
        'payload',
        'attempts',
        'reserved_at',
        'available_at',
        'created_at',
        'updated_at',
    ];

    // Los campos que deben ser tratados como fechas
    protected $casts = [
        'available_at' => 'datetime',  // Esto convertirá el valor a formato DATETIME automáticamente
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
