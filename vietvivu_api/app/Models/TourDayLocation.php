<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TourDayLocation extends Model
{
    use SoftDeletes;
    protected $primaryKey = 'id';
    protected $fillable = ['tour_day_id', 'location_id', 'order_in_day'];
}
