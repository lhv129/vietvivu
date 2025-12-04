<?php

namespace App\Repositories;

use App\Models\TourDay;
use App\Models\TourDayLocation;

class TourDayRepository extends BaseRepository
{

    protected $locationRepository;

    public function __construct(TourDayLocationRepository $locationRepository)
    {
        parent::__construct();
        $this->locationRepository = $locationRepository;
    }

    public function model()
    {
        return TourDay::class;
    }

    /**
     * Thêm mới thông tin của ngày trong tour
     */
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
                    $this->locationRepository->create([
                        'tour_day_id' => $tourDay->id,
                        'location_id' => $loc['location_id'],
                        'description' => $loc['description'] ?? null,
                        'order_in_day'  => $loc['order_in_day'] ?? ($locIndex + 1),
                    ]);
                }
            }
        }
    }


    public function syncDays($tourId, array $days)
    {
        $dayIds = [];

        foreach ($days as $day) {

            if (!isset($day['day_number'])) {
                throw new \Exception("Thiếu day_number: " . json_encode($day));
            }

            $tourDay = $this->model->updateOrCreate(
                [
                    'tour_id'    => $tourId,
                    'day_number' => $day['day_number'],
                ],
                [
                    'title'       => $day['title'] ?? null,
                    'description' => $day['description'] ?? null,
                    'sort_order'  => $day['day_number'],
                ]
            );

            $dayIds[] = $tourDay->id;

            // — GỌI REPO MỚI —
            if (!empty($day['locations'])) {
                $this->locationRepository->syncLocations($tourDay->id, $day['locations']);
            }
        }

        // xóa những ngày không còn
        $this->model
            ->where('tour_id', $tourId)
            ->whereNotIn('id', $dayIds)
            ->delete();
    }
}
