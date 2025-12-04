<?php

namespace App\Models;

use Illuminate\Support\Facades\File;
use App\Models\TourDay;
use App\Models\TourImage;
use App\Models\TourCountry;
use App\Models\StartLocation;
use App\Models\TourDeparture;
use App\Models\TourDayLocation;
use App\Models\TourRouteLocation;
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

    // QUAN HỆ
    public function startLocation()
    {
        return $this->belongsTo(StartLocation::class, 'start_location_id');
    }

    public function countries()
    {
        return $this->hasMany(TourCountry::class);
    }

    public function days()
    {
        return $this->hasMany(TourDay::class)->orderBy('day_number', 'ASC');
    }

    public function dayLocations()
    {
        return $this->hasManyThrough(
            TourDayLocation::class,
            TourDay::class,
            'tour_id',        // tour_days.tour_id
            'tour_day_id',    // tour_day_locations.tour_day_id
            'id',
            'id'
        );
    }

    public function routeLocations()
    {
        return $this->hasMany(TourRouteLocation::class)->orderBy('sort_order', 'ASC');
    }

    public function departures()
    {
        return $this->hasMany(TourDeparture::class)->orderBy('sort_order', 'ASC');
    }

    public function images()
    {
        return $this->hasMany(TourImage::class)->orderBy('sort_order', 'ASC');
    }

    // Xóa mềm tour -> Children cũng delete() mềm
    protected static function booted()
    {
        static::deleting(function ($tour) {

            // LƯU ID TRƯỚC KHI SOFT DELETE
            $tourId = $tour->id;

            // Xóa children mềm
            $tour->countries()->delete();
            $tour->departures()->delete();
            $tour->routeLocations()->delete();

            foreach ($tour->days as $day) {
                $day->locations()->delete();
            }
            $tour->days()->delete();
            $tour->images()->delete();

            // Dùng ID đã lưu
            $folder = public_path('images/tours/' . $tourId);
            if (is_dir($folder)) {
                File::deleteDirectory($folder);
            }

            // Nếu force delete → xóa cứng
            if ($tour->isForceDeleting()) {

                $tour->countries()->forceDelete();
                $tour->departures()->forceDelete();
                $tour->routeLocations()->forceDelete();

                foreach ($tour->days as $day) {
                    $day->locations()->forceDelete();
                }
                $tour->days()->forceDelete();

                $tour->images()->forceDelete();
            }
        });
    }
}
