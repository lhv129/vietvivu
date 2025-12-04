<?php

namespace App\Api\Controllers;

use App\Api\Requests\StoreStartLocationRequest;
use App\Api\Requests\UpdateStartLocationRequest;
use App\Services\StartLocationService;

class StartLocationController extends BaseController
{
    protected $service;

    public function __construct(StartLocationService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $startLocations = $this->service->getAll();
        return $this->responseCommon(true, 'Lấy danh sách địa điểm xuất phát thành công.', $startLocations, 200);
    }

    public function show($id)
    {
        $startLocation = $this->service->findOneById($id);
        return $this->responseCommon(true, 'Tìm địa điểm xuất phát thành công.', $startLocation, 200);
    }

    public function store(StoreStartLocationRequest $request)
    {
        $data = $request->validated();
        $startLocation = $this->service->create($data);
        return $this->responseCommon(true, 'Thêm mới địa điểm xuất phát thành công.', $startLocation, 201);
    }

    public function update(UpdateStartLocationRequest $request, $id)
    {
        $data = $request->validated();
        $startLocation = $this->service->update($id, $data);
        return $this->responseCommon(true, 'Cập nhật địa điểm xuất phát thành công.', $startLocation, 200);
    }

    public function destroy($id)
    {
        $startLocation = $this->service->delete($id);
        return $this->responseCommon(true, 'Xóa địa điểm xuất phát thành công.', [], 200);
    }
}
