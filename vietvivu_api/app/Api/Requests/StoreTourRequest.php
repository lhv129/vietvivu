<?php

namespace App\Api\Requests;

use App\Api\Requests\BaseRequest;

class StoreTourRequest extends BaseRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [

            // ========== TOUR ==========
            'title' => 'required|string|max:255|unique:tours,title',
            'description' => 'nullable|string',
            'slug' => 'nullable|string',
            'code' => 'nullable|string|max:100',
            'start_location_id' => 'required|exists:locations,id',

            // ========== QUỐC GIA ==========
            'tour_country_ids' => 'required|array|min:1',
            'tour_country_ids.*' => 'required|integer|exists:countries,id',

            // ========== ẢNH TỔNG QUAN ==========
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:4096',

            // ========== TOUR ĐI QUA ĐỊA ĐIỂM NÀO (tour_locations) ==========
            'tour_route_locations' => 'required|array|min:1',
            'tour_route_locations.*' => 'required|integer|exists:locations,id',

            // ========== NGÀY TRONG TOUR (tour_days) ==========
            'days' => 'required|array|min:1',
            'days.*.day_number' => 'required|integer|min:1',
            'days.*.title' => 'required|string|max:255',
            'days.*.description' => 'nullable|string',

            // ========== ĐỊA ĐIỂM TRONG MỖI NGÀY (tour_day_locations) ==========
            'days.*.locations' => 'required|array|min:1',
            'days.*.locations.*.location_id' => 'required|integer|exists:locations,id',
            'days.*.locations.*.time' => 'nullable|string',
            'days.*.locations.*.description' => 'nullable|string',
            'days.*.locations.*.sort_order' => 'nullable|integer|min:0',

            // ========== LỊCH KHỞI HÀNH (tour_departures) ==========
            'departures' => 'required|array|min:1',
            'departures.*.start_date' => 'required|date',
            'departures.*.end_date' => 'required|date|after_or_equal:departures.*.start_date',
            'departures.*.available_seats' => 'required|integer|min:1',
            'departures.*.booked_seats' => 'nullable|integer|min:0',

            'departures.*.price_adult' => 'required|numeric|min:0',
            'departures.*.price_child' => 'nullable|numeric|min:0',

            'departures.*.discount_amount' => 'nullable|numeric|min:0',
            'departures.*.discount_percent' => 'nullable|integer|min:0|max:100',
        ];
    }

    public function messages()
    {
        return [

            // ========== TOUR ==========
            'title.required' => 'Tiêu đề tour không được để trống.',
            'title.unique' => 'Tiêu đề tour đã tồn tại.',
            'start_location_id.required' => 'Bạn phải chọn địa điểm khởi hành.',
            'start_location_id.exists' => 'Địa điểm khởi hành không hợp lệ.',

            // ========== QUỐC GIA ==========
            'tour_country_ids.required' => 'Tour phải thuộc ít nhất một quốc gia.',
            'tour_country_ids.*.exists' => 'Quốc gia không hợp lệ.',

            // ========== ẢNH ==========
            'images.*.image' => 'File tải lên phải là hình ảnh.',
            'images.*.mimes' => 'Ảnh phải có định dạng jpg, jpeg, png hoặc webp.',
            'images.*.max' => 'Kích thước ảnh tối đa 4MB.',

            // ========== ĐỊA ĐIỂM CHÍNH CỦA TOUR ==========
            'tour_route_locations.required' => 'Bạn phải chọn ít nhất một địa điểm mà tour đi qua.',
            'tour_route_locations.*.exists' => 'Địa điểm bạn chọn không tồn tại.',

            // ========== NGÀY ==========
            'days.required' => 'Tour phải có ít nhất một ngày lịch trình.',
            'days.*.day_number.required' => 'Thiếu số thứ tự ngày.',
            'days.*.title.required' => 'Tiêu đề của ngày không được để trống.',

            // ========== ĐỊA ĐIỂM MỖI NGÀY ==========
            'days.*.locations.required' => 'Mỗi ngày phải có ít nhất một địa điểm.',
            'days.*.locations.*.location_id.required' => 'Thiếu location_id trong ngày.',
            'days.*.locations.*.location_id.exists' => 'Location trong ngày không hợp lệ.',

            // ========== DEPARTURES ==========
            'departures.required' => 'Bạn phải nhập ít nhất một lịch khởi hành.',
            'departures.*.start_date.required' => 'Ngày bắt đầu là bắt buộc.',
            'departures.*.end_date.after_or_equal' => 'Ngày kết thúc phải sau hoặc bằng ngày bắt đầu.',
            'departures.*.available_seats.required' => 'Số ghế trống là bắt buộc.',

            'departures.*.price_adult.required' => 'Giá người lớn là bắt buộc.',
            'departures.*.discount_percent.max' => 'Phần trăm giảm giá tối đa là 100%.',
        ];
    }
}
