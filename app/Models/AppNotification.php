<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Custom app notification model (stored in our own `notifications` table).
 * Named AppNotification to avoid colliding with Laravel's built-in
 * \Illuminate\Notifications\DatabaseNotification.
 */
class AppNotification extends Model
{
    protected $table = 'notifications';

    protected $fillable = [
        'user_id',
        'type',
        'data',
        'read_at',
    ];

    protected function casts(): array
    {
        return [
            'data'    => 'array',
            'read_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isRead(): bool
    {
        return $this->read_at !== null;
    }

    public function markAsRead(): void
    {
        if (! $this->read_at) {
            $this->update(['read_at' => now()]);
        }
    }
}
