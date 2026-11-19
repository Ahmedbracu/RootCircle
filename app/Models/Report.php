<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = [
        'reporter_id',
        'reportable_type',
        'reportable_id',
        'reason',
        'status',
        'resolved_by',
        'resolved_at',
        'admin_note',
    ];

    protected function casts(): array
    {
        return [
            'resolved_at' => 'datetime',
        ];
    }

    // ── Relationships ─────────────────────────────────────────

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    public function resolver()
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }

    /**
     * Polymorphic: the reported item can be a Post, User, Listing, etc.
     * Usage: $report->reportable
     */
    public function reportable()
    {
        return $this->morphTo();
    }
}
