<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImageLike extends Model
{
    use HasFactory;

    protected $table = 'image_likes';

    protected $fillable = [
        'image_id',
        'user_id',
        'guest_id',
    ];

    /**
     * Relación con la imagen.
     */
    public function image()
    {
        return $this->belongsTo(Image::class);
    }

    /**
     * Relación con el usuario (opcional, si el like es de un usuario registrado).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
