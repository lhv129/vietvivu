<?php

namespace App\Repositories;

use App\Models\TourCountry;

class TourCountryRepository extends BaseRepository
{
    public function model()
    {
        return TourCountry::class;
    }

    /**
     * Lưu danh sách quốc gia cho tour
     *
     * @param int $tourId
     * @param array $countryIds
     * @return void
     */
    public function storeCountries($tourId, array $countryIds)
    {
        foreach ($countryIds as $index => $countryId) {
            $this->model::create([
                'tour_id'     => $tourId,
                'country_id'  => $countryId,
                'sort_order'  => $index + 1,
            ]);
        }
    }

    /**
     * Xóa và thêm lại quốc gia khi update tour
     */
    public function syncCountriesUpsert($tourId, array $countryIds)
    {
        // Lấy bản ghi hiện có trong DB theo đúng sort_order
        $current = $this->model
            ->where('tour_id', $tourId)
            ->orderBy('sort_order')
            ->get()
            ->values(); // reset key 0,1,2,...

        $currentCount = $current->count();
        $newCount = count($countryIds);

        // 1. Update các bản ghi có sẵn & trùng index
        for ($i = 0; $i < min($currentCount, $newCount); $i++) {

            $current[$i]->update([
                'country_id' => (int) $countryIds[$i],
                'sort_order' => $i + 1
            ]);
        }

        // 2. Nếu gửi lên nhiều hơn DB → insert thêm
        for ($i = $currentCount; $i < $newCount; $i++) {
            $this->model->create([
                'tour_id'    => $tourId,
                'country_id' => (int) $countryIds[$i],
                'sort_order' => $i + 1
            ]);
        }

        // 3. Nếu DB có nhiều hơn gửi lên → delete phần dư
        if ($currentCount > $newCount) {
            for ($i = $newCount; $i < $currentCount; $i++) {
                $current[$i]->delete();
            }
        }
    }
}
