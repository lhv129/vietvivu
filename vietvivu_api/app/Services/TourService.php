<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Repositories\TourRepository;
use App\Repositories\TourDayRepository;
use App\Repositories\TourImageRepository;
use App\Repositories\TourCountryRepository;
use App\Repositories\TourDepartureRepository;
use App\Repositories\TourRouteLocationRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\File;

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

    public function getTourDetail($id)
    {
        $tour = $this->repository->findFullById($id);

        if (!$tour) return throw new ModelNotFoundException("ID bản ghi không tồn tại, vui lòng kiểm tra lại");

        // --- CUSTOM FORMAT Quốc gia ---
        $countries = $tour->countries->map(function ($item) {
            return [
                'country_id' => $item->country_id,
                'name' => $item->country->name ?? null,
            ];
        });

        // --- CUSTOM FORMAT Địa điểm chính trong tour ---
        $routeLocations = $tour->routeLocations->map(function ($item) {
            return [
                'location_id' => $item->location_id,
                'name' => $item->location->name ?? null,
            ];
        });

        // --- CUSTOM FORMAT Lịch trình theo ngày của tour ---
        $days = $tour->days->map(function ($day) {
            return [
                'id' => $day->id,
                'day_number' => $day->day_number,
                'title' => $day->title,
                'description' => $day->description,
                'locations' => $day->locations->map(function ($loc) {
                    return [
                        'location_id' => $loc->location_id,
                        'name' => $loc->location->name ?? null,
                        'order_in_day' => $loc->order_in_day,
                    ];
                }),
            ];
        });

        // --- CUSTOM FORMAT Ảnh giới thiệu tour ---
        $images = $tour->images->map(function ($img) {
            return [
                'image' => $img->image,
                'fileImage' => $img->fileImage,
            ];
        });

        // --- CUSTOM FORMAT Địa điểm xuất phát ---
        $startLocation = $tour->startLocation ? [
            'id' => $tour->startLocation->id,
            'name' => $tour->startLocation->name,
        ] : null;

        // --- DEPARTURES giữ nguyên ---
        $departures = $tour->departures->map(function ($dep) {
            return [
                'id' => $dep->id,
                'start_date' => $dep->start_date,
                'end_date' => $dep->end_date,
                'available_seats' => $dep->available_seats,
                'booked_seats' => $dep->booked_seats,
                'price_adult' => $dep->price_adult,
                'price_child' => $dep->price_child,
                'discount_percent' => $dep->discount_percent,
                'discount_amount' => $dep->discount_amount,
            ];
        });

        // --- TRẢ VỀ FORMAT CUỐI CÙNG ---
        return [
            'id' => $tour->id,
            'title' => $tour->title,
            'description' => $tour->description,
            'code' => $tour->code,
            'slug' => $tour->slug,

            'start_location' => $startLocation,
            'countries' => $countries,
            'route_locations' => $routeLocations,
            'days' => $days,
            'images' => $images,
            'departures' => $departures,
        ];
    }


    public function updateTour($id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {

            // 1. Update tour
            $tour = parent::update($id, $data);

            // 2. Sync quốc gia
            if (isset($data['tour_country_ids'])) {
                $this->tourCountryRepository->syncCountriesUpsert($tour->id, $data['tour_country_ids']);
            }

            // 3. Sync Ảnh
            if (isset($data['images'])) {
                $this->tourImageRepository->syncImages($tour->id, $data['images']);
            }

            // 4. Sync thông tin chuyến đi
            if (isset($data['departures'])) {
                $this->tourDepartureRepository->syncDepartures($tour->id, $data['departures']);
            }

            // 5. Sync địa điểm sẽ đi trong tour
            if (isset($data['tour_route_locations'])) {
                $this->TourRouteLocationRepository->syncTourRouteLocations($tour->id, $data['tour_route_locations']);
            }

            // 6. Sync thông tin chi tiết của từng ngày
            if (isset($data['days'])) {
                $this->tourDayRepository->syncDays($tour->id, $data['days']);
            }

            return $tour;
        });
    }

    public function deleteTour($id)
    {
        return DB::transaction(function () use ($id) {
            $tour = $this->repository->findOneById($id);
            $tour->delete();
            return true;
        });
    }
}
