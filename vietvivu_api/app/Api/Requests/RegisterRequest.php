<?php

namespace App\Api\Requests;

use App\Api\Requests\BaseRequest;

class RegisterRequest extends BaseRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max=255',
            'email' => [
                'required',
                'email',
                'unique:users,email',
                'regex:/^[A-Za-z0-9._%+-]+@gmail\.com$/',
            ],
            'password' => 'required|string|min:6',
            'confirm_password' => 'required|same:password',

            // số Việt Nam: 10 số, bắt đầu bằng 03|05|07|08|09
            'phone_number' => [
                'required',
                'regex:/^(03|05|07|08|09)[0-9]{8}$/'
            ],

            'sex' => 'required|in:Nam,Nữ,Khác',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Tên không được để trống.',

            'email.required' => 'Email không được để trống.',
            'email.email' => 'Email không hợp lệ.',
            'email.unique' => 'Email đã tồn tại.',
            'email.regex'  => 'Email phải là Gmail (đuôi @gmail.com).',

            'password.required' => 'Mật khẩu không được để trống.',
            'confirm_password.required' => 'Bạn phải nhập lại mật khẩu.',
            'confirm_password.same'     => 'Mật khẩu nhập lại không khớp.',

            'phone_number.required' => 'Số điện thoại không được để trống.',
            'phone_number.regex'    => 'Số điện thoại không hợp lệ (phải là số Việt Nam).',

            'sex.required' => 'Vui lòng chọn giới tính.',
            'sex.in'       => 'Giới tính chỉ được chọn Nam, Nữ hoặc Khác.',
        ];
    }
}
