<?php

namespace App\Api\Requests;

use App\Api\Requests\BaseRequest;

class UpdateClientMenuRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('id'); // lấy ID đang cập nhật

        return [
            'title'       => 'required|string|max:255',
            'slug'        => 'required|string|max:255|unique:client_menus,slug,' . $id,
            'identifier'  => 'required|string|alpha_dash|max:100|unique:client_menus,identifier,' . $id,
            'url'         => 'nullable|url|max:255',

            'parent_id'   => 'nullable|exists:client_menus,id',

            'route_type'  => 'nullable|string|max:50',
            'route_id'    => 'nullable|integer',

            'sort_order'  => 'nullable|integer|min:0',
            'is_active'   => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required'      => 'Tiêu đề menu không được để trống.',
            'title.max'           => 'Tiêu đề menu không được vượt quá 255 ký tự.',

            'slug.required'       => 'Slug không được để trống.',
            'slug.unique'         => 'Slug đã tồn tại.',
            'slug.max'            => 'Slug không được vượt quá 255 ký tự.',

            'identifier.required' => 'Identifier là bắt buộc.',
            'identifier.alpha_dash' => 'Identifier chỉ được bao gồm chữ, số, dấu gạch ngang và gạch dưới.',
            'identifier.unique'   => 'Identifier đã tồn tại.',
            'identifier.max'      => 'Identifier không được vượt quá 100 ký tự.',

            'url.url'             => 'URL không hợp lệ.',
            'url.max'             => 'URL không được vượt quá 255 ký tự.',

            'parent_id.exists'    => 'Menu cha không hợp lệ.',

            'route_type.required' => 'Route type là bắt buộc.',
            'route_type.max'      => 'Route type không được vượt quá 50 ký tự.',

            'route_id.integer'    => 'Route ID phải là số.',

            'sort_order.integer'  => 'Thứ tự phải là số.',
            'sort_order.min'      => 'Thứ tự không được âm.',

            'is_active.required'  => 'Trạng thái kích hoạt là bắt buộc.',
            'is_active.boolean'   => 'Trạng thái kích hoạt không hợp lệ.',
        ];
    }
}
