<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tour extends Model
{

    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'start_location_id',
        'title',
        'code',
        'slug',
        'description',
        'is_status',
        'is_featured',
        'sort_order'
    ];
}
