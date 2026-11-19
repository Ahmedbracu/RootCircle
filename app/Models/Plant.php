<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plant extends Model
{
    protected $fillable = [
        'collection_id',
        'user_id',
        'name',
        'species',
        'photo_url',
        'description',
        'condition',
        'acquisition_date',
    ];

    protected function casts(): array
    {
        return [
            'acquisition_date' => 'date',
        ];
    }

    public function collection()
    {
        return $this->belongsTo(PlantCollection::class, 'collection_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function growthLogs()
    {
        return $this->hasMany(PlantGrowthLog::class);
    }

    public function careSchedules()
    {
        return $this->hasMany(CareSchedule::class);
    }

    public function propagations()
    {
        return $this->hasMany(Propagation::class);
    }

    public function healthCases()
    {
        return $this->hasMany(HealthCase::class);
    }
}
