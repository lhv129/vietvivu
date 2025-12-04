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
        // Lấy toàn bộ location hiện có của tour và map theo location_id
        $existing = $this->model
            ->where('tour_id', $tourId)
            ->get()
            ->keyBy('location_id');

        $rows = [];
        $ids = []; // lưu danh sách id cần giữ lại

        foreach ($locationIds as $index => $locId) {

            $rows[] = [
                'id'          => $existing[$locId]->id ?? null, // nếu có thì update, không thì insert
                'tour_id'     => $tourId,
                'location_id' => $locId,
                'sort_order'  => $index + 1,
            ];

            // Lưu lại id để không xóa
            if (isset($existing[$locId])) {
                $ids[] = $existing[$locId]->id;
            }
        }

        // Insert hoặc update theo id
        $this->model->upsert(
            $rows,
            ['id'],
            ['location_id', 'sort_order']
        );

        // Xóa những bản ghi không còn nữa
        $this->model
            ->where('tour_id', $tourId)
            ->whereNotIn('id', $ids)
            ->delete();
    }
}
