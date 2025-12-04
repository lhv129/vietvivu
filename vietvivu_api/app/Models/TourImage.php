<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TourImage extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'tour_id',
        'image',
        'fileImage',
        'sort_order',
    ];

    // QUAN HỆ
    public function tour()
    {
        return $this->belongsTo(Tour::class);
    }
}
