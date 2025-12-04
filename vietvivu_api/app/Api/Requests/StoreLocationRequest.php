<?php

namespace App\Api\Requests;

use App\Api\Requests\BaseRequest;

class StoreLocationRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'country_id' => 'required|exists:countries,id',

            'name'       => 'required|string|max:255|unique:locations,name',

            'description' => 'nullable|string|max:1000',

            'is_status'  => 'nullable|in:active,inactive',
        ];
    }

    public function messages()
    {
        return [
            'country_id.required' => 'Vui lòng chọn quốc gia.',
            'country_id.exists'   => 'Quốc gia không tồn tại.',

            'name.required' => 'Vui lòng nhập tên địa điểm.',
            'name.string'   => 'Tên địa điểm phải là dạng chữ.',
            'name.max'      => 'Tên địa điểm quá dài.',
            'name.unique'   => 'Tên địa điểm đã tồn tại.',

            'description.string' => 'Mô tả phải là dạng chữ.',
            'description.max'    => 'Mô tả không được vượt quá 1000 ký tự.',

            'is_status.in' => 'Trạng thái chỉ được phép là active hoặc inactive.',
        ];
    }
}
