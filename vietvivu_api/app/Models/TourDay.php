<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TourDay extends Model
{
    use SoftDeletes;
    protected $primaryKey = 'id';
    protected $fillable = ['tour_id', 'day_number', 'title', 'description', 'sort_order'];

    // QUAN HỆ
    public function locations()
    {
        return $this->hasMany(TourDayLocation::class)->orderBy('order_in_day', 'ASC');
    }
}
