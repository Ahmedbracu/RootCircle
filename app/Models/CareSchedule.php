<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CareSchedule extends Model
{
    // No standard timestamps in schema
    public $timestamps = false;

    protected $fillable = [
        'plant_id',
        'task',
        'frequency',
        'next_due_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'next_due_at' => 'datetime',
        ];
    }

    public function plant()
    {
        return $this->belongsTo(Plant::class);
    }
}
