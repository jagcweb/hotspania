<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Documentation extends Model
{
    protected $table = 'documentation';
    
    protected $fillable = [
        'title',
        'content',
        'file_path',
        'category',
        'processed_at'
    ];

    protected $casts = [
        'processed_at' => 'datetime'
    ];
}