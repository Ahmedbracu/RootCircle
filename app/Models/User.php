<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'bio',
        'profile_picture',
        'location',
        'gardening_interests',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at'   => 'datetime',
            'password'            => 'hashed',
            'gardening_interests' => 'array',
        ];
    }

    // ── Social ────────────────────────────────────────────────
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // ── Follows ───────────────────────────────────────────────
    public function following()
    {
        return $this->belongsToMany(User::class, 'follows', 'follower_id', 'following_id')
                    ->withTimestamps();
    }

    public function followers()
    {
        return $this->belongsToMany(User::class, 'follows', 'following_id', 'follower_id')
                    ->withTimestamps();
    }

    // ── Plants ────────────────────────────────────────────────
    public function plantCollections()
    {
        return $this->hasMany(PlantCollection::class);
    }

    public function plants()
    {
        return $this->hasMany(Plant::class);
    }

    // ── Nursery ───────────────────────────────────────────────
    public function nurseryProfile()
    {
        return $this->hasOne(NurseryProfile::class);
    }

    // ── Marketplace ───────────────────────────────────────────
    public function listings()
    {
        return $this->hasMany(Listing::class);
    }

    // ── Messaging ─────────────────────────────────────────────
    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    // ── Notifications ─────────────────────────────────────────
    public function appNotifications()
    {
        return $this->hasMany(AppNotification::class);
    }

    // ── Health ────────────────────────────────────────────────
    public function healthCases()
    {
        return $this->hasMany(HealthCase::class);
    }

    // ── Reports ───────────────────────────────────────────────
    public function reports()
    {
        return $this->hasMany(Report::class, 'reporter_id');
    }
}
