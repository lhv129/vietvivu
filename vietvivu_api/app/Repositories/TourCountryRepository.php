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
    public function syncCountries($tourId, array $countryIds)
    {
        $this->model::where('tour_id', $tourId)->delete();
        $this->storeCountries($tourId, $countryIds);
    }
}
