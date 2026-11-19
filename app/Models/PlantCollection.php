<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlantCollection extends Model
{
    protected $fillable = ['user_id', 'name', 'description'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plants()
    {
        return $this->hasMany(Plant::class, 'collection_id');
    }
}
