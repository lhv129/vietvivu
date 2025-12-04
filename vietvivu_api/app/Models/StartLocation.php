<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StartLocation extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'name',
        'description',
        'is_status',
        'sort_order'
    ];

    // QUAN HỆ
    public function tours()
    {
        return $this->hasMany(Tour::class, 'start_location_id');
    }
}
