<?php

namespace App\Api\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

abstract class BaseRequest extends FormRequest
{
    /**
     * Nếu muốn tất cả request API luôn authorize = true
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Override lỗi validate
     */
    protected function failedValidation(Validator $validator)
    {
        $response = [
            'status'  => false,
            'message' => 'Lỗi dữ liệu gửi lên',
            'errors'  => $validator->errors(),
        ];

        throw new HttpResponseException(response()->json($response, 422));
    }
}
