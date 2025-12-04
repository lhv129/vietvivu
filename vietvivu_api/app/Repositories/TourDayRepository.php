<?php

namespace App\Repositories;

use App\Models\TourDay;
use App\Models\TourDayLocation;

class TourDayRepository extends BaseRepository
{
    public function model()
    {
        return TourDay::class;
    }

    public function storeDays($tourId, array $days)
    {
        foreach ($days as $dayIndex => $day) {

            // Tạo ngày
            $tourDay = $this->model->create([
                'tour_id'     => $tourId,
                'day_number'  => $day['day_number'],
                'title'       => $day['title'],
                'description' => $day['description'] ?? null,
            ]);

            // Lưu địa điểm theo ngày
            if (!empty($day['locations'])) {
                foreach ($day['locations'] as $locIndex => $loc) {
                    TourDayLocation::create([
                        'tour_day_id' => $tourDay->id,
                        'location_id' => $loc['location_id'],
                        'time'        => $loc['time'] ?? null,
                        'description' => $loc['description'] ?? null,
                        'order_in_day'  => $loc['order_in_day'] ?? ($locIndex + 1),
                    ]);
                }
            }
        }
    }
}
