<?php

namespace App\Repositories;

use App\Models\TourDeparture;
use App\Repositories\BaseRepository;

class TourDepartureRepository extends BaseRepository
{
    public function model()
    {
        return TourDeparture::class;
    }

    /**
     * Thêm mới thông tin giá, slots... của 1 tour
     */
    public function storeDepartures($tourId, array $departures)
    {
        foreach ($departures as $d) {
            $this->model::create([
                'tour_id' => $tourId,
                'start_date' => $d['start_date'],
                'available_seats' => $d['available_seats'],
                'booked_seats' => 0,
                'price_adult' => $d['price_adult'],
                'price_child' => $d['price_child'] ?? null,
                'discount_percent' => $d['discount_percent'] ?? null,
                'discount_amount' => $d['discount_amount'] ?? null,
            ]);
        }
    }



    /**
     * Cập nhật thông tin giá, slots... của 1 tour
     */
    public function syncDepartures($tourId, array $departures)
    {
        // Lấy departures hiện tại trong DB theo đúng thứ tự
        $current = $this->model
            ->where('tour_id', $tourId)
            ->orderBy('start_date') // hoặc sort_order nếu bạn thêm
            ->get()
            ->values(); // reset key về 0,1,2,...

        $currentCount = $current->count();
        $newCount = count($departures);

        // 1. Update các bản ghi có sẵn & trùng index
        for ($i = 0; $i < min($currentCount, $newCount); $i++) {

            $current[$i]->update([
                'tour_id'           => $tourId,
                'start_date'        => $departures[$i]['start_date'],
                'end_date'          => $departures[$i]['end_date'] ?? null,
                'available_seats'   => $departures[$i]['available_seats'],
                'booked_seats'      => $departures[$i]['booked_seats'] ?? 0,
                'price_adult'       => $departures[$i]['price_adult'],
                'price_child'       => $departures[$i]['price_child'] ?? null,
                'discount_percent'  => $departures[$i]['discount_percent'] ?? null,
                'discount_amount'   => $departures[$i]['discount_amount'] ?? null,
            ]);
        }

        // 2. Nếu gửi lên nhiều hơn DB → insert thêm
        for ($i = $currentCount; $i < $newCount; $i++) {
            $this->model->create([
                'tour_id'           => $tourId,
                'start_date'        => $departures[$i]['start_date'],
                'end_date'          => $departures[$i]['end_date'] ?? null,
                'available_seats'   => $departures[$i]['available_seats'],
                'booked_seats'      => $departures[$i]['booked_seats'] ?? 0,
                'price_adult'       => $departures[$i]['price_adult'],
                'price_child'       => $departures[$i]['price_child'] ?? null,
                'discount_percent'  => $departures[$i]['discount_percent'] ?? null,
                'discount_amount'   => $departures[$i]['discount_amount'] ?? null,
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
