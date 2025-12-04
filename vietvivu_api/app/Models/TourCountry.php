<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TourCountry extends Model
{
    use SoftDeletes;

    protected $table = 'tour_countries';

    protected $fillable = [
        'tour_id',
        'country_id',
        'sort_order',
        'is_status',
    ];

    public function tour()
    {
        return $this->belongsTo(Tour::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}
