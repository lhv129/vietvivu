<?php

namespace App\Api\Controllers;

use App\Api\Requests\StoreTourRequest;
use App\Services\TourService;

class TourController extends BaseController
{
    protected $service;

    public function __construct(TourService $service)
    {
        $this->service = $service;
    }

    public function index(){
        $tours = $this->service->getAll();
        return $this->responseCommon(true, 'Lấy danh sách tours thành công.', $tours, 200);
    }

    public function show($id){
        $tour = $this->service->findOneById($id);
        return $this->responseCommon(true, 'Lấy danh sách tours thành công.', $tour, 200);
    }

    public function store(StoreTourRequest $request){
        $data = $request->validated();
        $tour = $this->service->createTour($data);
        return $this->responseCommon(true, 'Thêm mới tour thành công.', $tour, 200);
    }
}
