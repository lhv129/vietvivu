<?php

namespace App\Api\Requests;

use App\Api\Requests\BaseRequest;

class StoreClientMenuRequest extends BaseRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title'      => 'required|string|max:255',
            'slug'       => 'nullable|string|max:255|unique:client_menus,slug',
            'identifier'  => 'required|string|alpha_dash|max:100|unique:client_menus,identifier',
            'url'        => 'nullable|string|max:255',
            'parent_id'  => 'nullable|integer|exists:client_menus,id',
            'sort_order' => 'nullable|integer|min:0',
            'is_active'  => 'required|in:0,1',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'Tiêu đề menu không được để trống.',
            'title.max'      => 'Tiêu đề menu không được vượt quá 255 ký tự.',

            'slug.max'       => 'Slug không được vượt quá 255 ký tự.',
            'slug.unique'    => 'Slug này đã tồn tại, vui lòng chọn slug khác.',

            'identifier.required' => 'Identifier là bắt buộc.',
            'identifier.alpha_dash' => 'Identifier chỉ được bao gồm chữ, số, dấu gạch ngang và gạch dưới.',
            'identifier.unique'   => 'Identifier đã tồn tại.',
            'identifier.max'      => 'Identifier không được vượt quá 100 ký tự.',

            'url.max'        => 'URL không được vượt quá 255 ký tự.',

            'parent_id.integer' => 'Parent ID phải là số.',
            'parent_id.exists'  => 'Menu cha không tồn tại.',

            'sort_order.integer' => 'Thứ tự sắp xếp phải là số.',
            'sort_order.min'     => 'Thứ tự sắp xếp không được âm.',

            'is_active.required' => 'Trạng thái không được để trống.',
            'is_active.in'       => 'Trạng thái không hợp lệ.',
        ];
    }
}
