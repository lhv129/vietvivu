<?php

namespace App\Api\Controllers;

use App\Api\Requests\StoreClientMenuRequest;
use App\Api\Requests\UpdateClientMenuRequest;
use App\Services\ClientMenuService;

class ClientMenuController extends BaseController
{
    protected $service;

    public function __construct(ClientMenuService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $menus = $this->service->getAll(['id', 'title', 'url', 'parent_id', 'route_type', 'identifier']);
        return $this->responseCommon(true, 'Lấy danh sách menus thành công', $menus, 200);
    }

    public function show($id)
    {
        $menu = $this->service->findOneById($id);
        return $this->responseCommon(true, 'Tìm menu thành công', $menu, 200);
    }

    public function store(StoreClientMenuRequest $request)
    {
        $data = $request->validated();
        $menu = $this->service->create($data);
        return $this->responseCommon(true, 'Thêm menu thành công', $menu, 201);
    }

    public function update(UpdateClientMenuRequest $request, $id)
    {
        $data = $request->validated();
        $menu = $this->service->update($id, $data);
        return $this->responseCommon(true, 'Cập nhật menu thành công', $menu, 200);
    }

    public function destroy($id)
    {
        $menu = $this->service->delete($id);
        return $this->responseCommon(true, 'Xóa menu thành công', [], 200);
    }
}
