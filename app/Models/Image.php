<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'route',
        'route_gif',
        'size',
        'type',
        'status',
        'visible',
        'frontimage',
        'watermarked'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}