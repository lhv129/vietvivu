<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TourDeparture extends Model
{
    use SoftDeletes;
    protected $primaryKey = 'id';
    protected $fillable = [
        'tour_id',
        'departure_date',
        'available_seats',
        'booked_seats',
        'price_adult',
        'price_child',
        'discount_percent',
        'discount_amount',
        'sort_order',
        'is_status'
    ];
}
