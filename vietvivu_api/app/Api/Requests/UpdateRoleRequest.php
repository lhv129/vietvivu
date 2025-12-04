<?php

namespace App\Api\Requests;

use App\Api\Requests\BaseRequest;

class UpdateRoleRequest extends BaseRequest
{
    public function rules()
    {
        $id = $this->route('id'); // lấy ID từ route

        return [
            'name' => 'required|string|max:255|unique:roles,name,' . $id,
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Vui lòng nhập tên chức vụ.',
            'name.string'   => 'Tên chức vụ phải là dạng chữ.',
            'name.max'      => 'Tên chức vụ quá dài.',
            'name.unique'   => 'Tên chức vụ đã tồn tại.',
        ];
    }
}
