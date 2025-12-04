<?php

namespace App\Services;

use App\Repositories\TourCountryRepository;
use Illuminate\Support\Facades\DB;
use App\Repositories\TourRepository;
use App\Repositories\TourDayRepository;
use App\Repositories\TourImageRepository;
use App\Repositories\TourRouteLocationRepository;
use App\Repositories\TourDepartureRepository;

class TourService extends BaseService
{

    protected $tourCountryRepository;
    protected $tourImageRepository;
    protected $tourDepartureRepository;
    protected $TourRouteLocationRepository;
    protected $tourDayRepository;

    public function __construct(
        TourCountryRepository $tourCountryRepository,
        TourImageRepository $tourImageRepository,
        TourDepartureRepository $tourDepartureRepository,
        TourRouteLocationRepository $TourRouteLocationRepository,
        TourDayRepository $tourDayRepository
    ) {
        parent::__construct();
        $this->tourCountryRepository = $tourCountryRepository;
        $this->tourImageRepository = $tourImageRepository;
        $this->tourDepartureRepository = $tourDepartureRepository;
        $this->TourRouteLocationRepository = $TourRouteLocationRepository;
        $this->tourDayRepository = $tourDayRepository;
    }

    public function repository()
    {
        return TourRepository::class;
    }

    public function createTour(array $data)
    {
        return DB::transaction(function () use ($data) {

            // 1. Tạo tour
            $tour = parent::create($data);

            // 2. Lưu quốc gia
            if (!empty($data['tour_country_ids'])) {
                $this->tourCountryRepository->storeCountries($tour->id, $data['tour_country_ids']);
            }

            // 3. Lưu các ảnh chính của tour
            if (!empty($data['images'])) {
                $this->tourImageRepository->storeImages($tour->id, $data['images']);
            }

            // 4. Lưu tour_departures (nhiều ngày khởi hành)
            if (!empty($data['departures'])) {
                $this->tourDepartureRepository->storeDepartures($tour->id, $data['departures']);
            }

            // 5. Lưu danh sách địa điểm chính của tour
            if (!empty($data['tour_route_locations'])) {
                $this->TourRouteLocationRepository->storeTourRouteLocations($tour->id, $data['tour_route_locations']);
            }

            // 6. Lưu lịch trình tour_days
            if (!empty($data['days'])) {
                $this->tourDayRepository->storeDays($tour->id, $data['days']);
            }


            return $tour;
        });
    }
}
