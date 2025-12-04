<?php

namespace App\Api\Requests;

use App\Api\Requests\BaseRequest;
use Illuminate\Validation\Rule;

class UpdateCountryRequest extends BaseRequest
{
    public function rules()
    {
        $id = $this->route('id');

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('countries', 'name')->ignore($id),
            ],

            'code' => 'nullable|string|max:50',

            // Không bắt buộc ảnh khi update
            'image' => 'nullable|file|image|max:2048',

            'is_status' => 'nullable|in:active,inactive',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Vui lòng nhập tên quốc gia.',
            'name.string' => 'Tên quốc gia phải là dạng chữ (text).',
            'name.max' => 'Tên quốc gia quá dài, vui lòng chọn tên khác.',
            'name.unique' => 'Tên quốc gia đã tồn tại.',

            'code.string' => 'Mã vùng quốc gia phải là dạng chữ.',
            'code.max' => 'Mã vùng quốc gia quá dài.',

            'image.file' => 'Ảnh phải là một tệp tin.',
            'image.image' => 'Ảnh phải có định dạng hợp lệ (jpg, png, webp...).',
            'image.max' => 'Ảnh không được vượt quá 2MB.',

            'is_status.in' => 'Trạng thái chỉ được phép là active hoặc inactive.',
        ];
    }
}
