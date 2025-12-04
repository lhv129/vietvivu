<?php

namespace App\Repositories;

use App\Models\TourRouteLocation;

class TourRouteLocationRepository extends BaseRepository
{
    public function model()
    {
        return TourRouteLocation::class;
    }


    /**
     * Thêm mới địa điểm chính của tour
     */
    public function storeTourRouteLocations($tourId, array $locations)
    {
        foreach ($locations as $loc) {
            $this->model::create([
                'tour_id' => $tourId,
                'location_id' => $loc,
            ]);
        }
    }

    /**
     * Cập nhật địa điểm chính của tour
     */
    public function syncTourRouteLocations($tourId, array $locationIds)
    {
        // Lấy các location_id đang tồn tại
        $existing = $this->model
            ->where('tour_id', $tourId)
            ->pluck('location_id')
            ->toArray();

        // 1. Xóa những location không còn trong danh sách mới
        $this->model
            ->where('tour_id', $tourId)
            ->whereNotIn('location_id', $locationIds)
            ->delete();

        // 2. Insert hoặc update sort_order
        foreach ($locationIds as $index => $locId) {
            $this->model->updateOrCreate(
                [
                    'tour_id' => $tourId,
                    'location_id' => $locId,   // DÙNG UNIQUE KEY NÀY
                ],
                [
                    'sort_order' => $index + 1
                ]
            );
        }
    }
}
