<?php

namespace App\Api\Requests;

use App\Api\Requests\BaseRequest;

class LoginRequest extends BaseRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'email' => [
                'required',
                'email',
                'regex:/^[A-Za-z0-9._%+-]+@gmail\.com$/',
            ],
            'password' => 'required|string',
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'Email không được để trống.',
            'email.email' => 'Email không hợp lệ.',
            'email.unique' => 'Email đã tồn tại.',
            'email.regex'  => 'Email phải là Gmail (đuôi @gmail.com).',
            'password.required' => 'Mật khẩu không được để trống.',
        ];
    }
}
