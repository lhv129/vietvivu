<?php

namespace App\Api\Requests;

use App\Api\Requests\BaseRequest;

class StoreStartLocationRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'name'       => 'required|string|max:255|unique:start_locations,name',

            'description' => 'nullable|string|max:1000',

            'is_status'  => 'nullable|in:active,inactive',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Vui lòng nhập tên địa điểm xuất phát.',
            'name.string'   => 'Tên địa điểm xuất phát phải là dạng chữ.',
            'name.max'      => 'Tên địa điểm xuất phát quá dài.',
            'name.unique'   => 'Tên địa điểm xuất phát đã tồn tại.',

            'description.string' => 'Mô tả phải là dạng chữ.',
            'description.max'    => 'Mô tả không được vượt quá 1000 ký tự.',

            'is_status.in' => 'Trạng thái chỉ được phép là active hoặc inactive.',
        ];
    }
}
