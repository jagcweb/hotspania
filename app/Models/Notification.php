<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'subject',
        'text',
        'urls',
        'type',
        'type_id',
        'readed',
    ];

    protected $casts = [
        'readed' => 'boolean',
        'urls' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeUnread($query)
    {
        return $query->where('readed', false);
    }

    public function scopeRead($query)
    {
        return $query->where('readed', true);
    }

    public function markAsRead()
    {
        $this->update(['readed' => true]);
    }
}