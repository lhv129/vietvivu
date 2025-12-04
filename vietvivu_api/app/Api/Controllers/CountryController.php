<?php

namespace App\Api\Controllers;

use App\Api\Requests\StoreCountryRequest;
use App\Api\Requests\UpdateCountryRequest;
use App\Services\CountryService;

class CountryController extends BaseController
{

    protected $service;

    public function __construct(CountryService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $countries = $this->service->getAll();
        return $this->responseCommon(true, 'Lấy danh sách thông tin quốc gia thành công.', $countries, 200);
    }

    public function show($id)
    {
        $country = $this->service->findOneById($id);
        return $this->responseCommon(true, 'Lấy thông tin quốc gia thành công.', $country, 200);
    }

    public function store(StoreCountryRequest $request)
    {
        $data = $request->validated();
        $country = $this->service->create($data);
        return $this->responseCommon(true, 'Thêm mới thông tin quốc gia thành công.', $country, 201);
    }

    public function update(UpdateCountryRequest $request, $id)
    {
        $data = $request->validated();
        $country = $this->service->update($id, $data);
        return $this->responseCommon(true, 'Cập nhật thông tin quốc gia thành công.', $country, 200);
    }

    public function destroy($id)
    {
        $country = $this->service->delete($id);
        return $this->responseCommon(true, 'Xóa quốc gia thành công.', [], 200);
    }
}
