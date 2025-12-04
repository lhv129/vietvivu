<?php

namespace App\Repositories;

use App\Models\TourDayLocation;

class TourDayLocationRepository extends BaseRepository
{
    public function model()
    {
        return TourDayLocation::class;
    }

    /**
     * Đồng bộ địa điểm trong ngày
     */
    public function syncLocations($tourDayId, array $locations)
    {
        $newIds = [];

        foreach ($locations as $index => $loc) {

            // Normalize location_id
            $locId = $loc['location_id'] ?? null;

            if (is_string($locId) && str_starts_with($locId, '[')) {
                $decoded = json_decode($locId, true);
                $locId = $decoded[0] ?? null;
            }
            if (is_array($locId)) {
                $locId = $locId[0] ?? null;
            }

            $locId = (int) $locId;

            if (!$locId) continue;

            /** KEY CHUẨN ĐỂ UPDATE */
            $model = $this->model->updateOrCreate(
                [
                    'tour_day_id' => $tourDayId,
                    'location_id' => $locId,
                ],
                [
                    'description' => $loc['description'] ?? null,
                    'order_in_day' => $loc['order_in_day'] ?? ($index + 1),
                ]
            );

            $newIds[] = $model->id;
        }

        // ❗ chỉ xóa những bản ghi không còn location_id trong day này
        $this->model
            ->where('tour_day_id', $tourDayId)
            ->whereNotIn('id', $newIds)
            ->delete();
    }
}
