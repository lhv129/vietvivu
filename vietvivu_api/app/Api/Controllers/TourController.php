<?php

namespace App\Api\Controllers;

use App\Api\Requests\StoreTourRequest;
use App\Api\Requests\UpdateTourRequest;
use App\Services\TourService;

class TourController extends BaseController
{
    protected $service;

    public function __construct(TourService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $tours = $this->service->getAll();
        return $this->responseCommon(true, 'Lấy danh sách tours thành công.', $tours, 200);
    }

    public function show($id)
    {
        $tour = $this->service->getTourDetail($id);
        return $this->responseCommon(true, 'Lấy danh sách tours thành công.', $tour, 200);
    }

    public function store(StoreTourRequest $request)
    {
        $data = $request->validated();
        $tour = $this->service->createTour($data);
        return $this->responseCommon(true, 'Thêm mới tour thành công.', $tour, 200);
    }

    public function update(UpdateTourRequest $request, $id)
    {
        $data = $request->validated();
        $tour = $this->service->updateTour($id, $data);
        return $this->responseCommon(true, 'Cập nhật tour thành công.', $tour, 200);
    }

    public function destroy($id)
    {
        $this->service->delete($id);
        return $this->responseCommon(true, 'Xóa tour thành công.', [], 200);
    }
}
