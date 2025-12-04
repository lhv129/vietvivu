<?php

namespace App\Repositories;

use App\Models\Tour;

class TourRepository extends BaseRepository
{
    public function model()
    {
        return Tour::class;
    }

    public function findFullById($id)
    {
        return $this->model
            ->with([

                // START LOCATION
                'startLocation:id,name',

                // -------- COUNTRIES --------
                'countries:id,tour_id,country_id',
                'countries.country:id,name',

                // ROUTE LOCATIONS
                'routeLocations:id,tour_id,location_id',
                'routeLocations.location:id,name,country_id',

                // IMAGES
                'images:id,tour_id,image,fileImage',

                // DEPARTURES
                'departures:id,tour_id,start_date,end_date,available_seats,booked_seats,price_adult,price_child,discount_percent,discount_amount',

                // DAYS
                'days:id,tour_id,day_number,title,description',

                // DAY LOCATIONS
                'days.locations:id,tour_day_id,location_id,order_in_day',
                'days.locations.location:id,name,country_id',

            ])
            ->find($id);
    }
}
