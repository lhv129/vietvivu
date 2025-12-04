<?php

namespace App\Api\Controllers;

use App\Api\Requests\StoreRoleRequest;
use App\Api\Requests\UpdateRoleRequest;
use App\Services\RoleService;

class RoleController extends BaseController
{
    protected $service;

    public function __construct(RoleService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $roles = $this->service->getAll();
        return $this->responseCommon(true, 'Lấy danh sách role thành công.', $roles, 200);
    }

    public function show($id)
    {
        $role = $this->service->findOneById($id);
        return $this->responseCommon(true, 'Tìm role thành công.', $role, 200);
    }

    public function store(StoreRoleRequest $request)
    {
        $data = $request->validated();
        $role = $this->service->create($data);
        return $this->responseCommon(true, 'Thêm mới role thành công.', $role, 201);
    }

    public function update(UpdateRoleRequest $request, $id)
    {
        $data = $request->validated();
        $role = $this->service->update($id, $data);
        return $this->responseCommon(true, 'Cập nhật role thành công.', $role, 200);
    }

    public function destroy($id)
    {
        $role = $this->service->delete($id);
        return $this->responseCommon(true, 'Xóa địa role thành công.', [], 200);
    }
}
