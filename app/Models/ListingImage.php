<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ListingImage extends Model
{
    // No timestamps in schema
    public $timestamps = false;

    protected $fillable = ['listing_id', 'image_url'];

    public function listing()
    {
        return $this->belongsTo(Listing::class);
    }
}
