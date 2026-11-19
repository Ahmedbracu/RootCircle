<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Propagation extends Model
{
    protected $fillable = [
        'plant_id',
        'method',
        'start_date',
        'root_development',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
        ];
    }

    public function plant()
    {
        return $this->belongsTo(Plant::class);
    }
}
