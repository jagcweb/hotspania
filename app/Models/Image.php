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
        'watermarked',
        'vision_data',
        'route_frontimage',
        'visits',
        'likes'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function likes()
    {
        return $this->hasMany(ImageLike::class);
    }
}