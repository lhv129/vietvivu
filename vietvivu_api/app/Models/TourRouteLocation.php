<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TourRouteLocation extends Model
{
    use SoftDeletes;
    protected $primaryKey = 'id';
    protected $fillable = ['tour_id', 'location_id', 'sort_order'];
}
