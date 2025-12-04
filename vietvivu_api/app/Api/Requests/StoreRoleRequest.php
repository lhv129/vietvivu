<?php

namespace App\Api\Requests;

use App\Api\Requests\BaseRequest;

class StoreRoleRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'name'=> 'required|string|max:255|unique:roles,name',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Vui lòng nhập tên địa chức vụ.',
            'name.string'   => 'Tên địa chức vụ phải là dạng chữ.',
            'name.max'      => 'Tên địa chức vụ quá dài.',
            'name.unique'   => 'Tên địa chức vụ đã tồn tại.',
        ];
    }
}
