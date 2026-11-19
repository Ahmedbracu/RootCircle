<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlantGrowthLog extends Model
{
    // No standard timestamps — only logged_at
    public $timestamps = false;

    protected $fillable = ['plant_id', 'photo_url', 'note', 'logged_at'];

    protected function casts(): array
    {
        return [
            'logged_at' => 'datetime',
        ];
    }

    public function plant()
    {
        return $this->belongsTo(Plant::class);
    }
}
