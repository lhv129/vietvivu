<?php

namespace App\Api\Controllers;

use App\Api\Requests\StoreLocationRequest;
use App\Api\Requests\UpdateLocationRequest;
use App\Services\LocationService;

class LocationController extends BaseController
{
    protected $service;

    public function __construct(LocationService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $locations = $this->service->getAll();
        return $this->responseCommon(true, 'Lấy danh sách địa điểm thành công.', $locations, 200);
    }

    public function show($id)
    {
        $location = $this->service->findOneById($id);
        return $this->responseCommon(true, 'Tìm địa điểm thành công.', $location, 200);
    }

    public function store(StoreLocationRequest $request)
    {
        $data = $request->validated();
        $location = $this->service->create($data);
        return $this->responseCommon(true, 'Thêm mới địa điểm thành công.', $location, 201);
    }

    public function update(UpdateLocationRequest $request, $id)
    {
        $data = $request->validated();
        $location = $this->service->update($id, $data);
        return $this->responseCommon(true, 'Cập nhật địa điểm thành công.', $location, 200);
    }

    public function destroy($id)
    {
        $location = $this->service->delete($id);
        return $this->responseCommon(true, 'Xóa địa điểm thành công.', [], 200);
    }
}
