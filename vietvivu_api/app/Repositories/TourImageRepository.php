<?php

namespace App\Repositories;

use App\Models\TourImage;
use App\Services\Upload\ImageService;

class TourImageRepository extends BaseRepository
{
    protected $model;
    protected $imageService;

    public function __construct(ImageService $imageService)
    {
        parent::__construct();
        $this->imageService = $imageService;
    }

    public function model()
    {
        return TourImage::class;
    }

    /**
     * Lưu nhiều ảnh cho 1 tour
     */
    public function storeImages($tourId, array $imageFiles)
    {
        foreach ($imageFiles as $file) {

            // Upload ảnh
            $uploaded = $this->imageService->uploadImage($file, 'tours/' . $tourId);

            // Lưu đúng 2 giá trị bạn muốn
            $this->model::create([
                'tour_id'   => $tourId,
                'image'     => $uploaded['url'], // URL tuyệt đối
                'fileImage' => $uploaded['filename'], // tên file
                'path' => $uploaded['path'], // path
                'sort_order' => 0
            ]);
        }
    }



    /**
     * Xóa 1 ảnh
     */
    public function deleteImage($imageId)
    {
        $image = $this->model::find($imageId);

        if (!$image) return false;

        // Xóa file vật lý
        $this->imageService->deleteImage($image->path);

        // Xóa DB
        return $image->delete();
    }

    /**
     * Xóa toàn bộ ảnh của 1 tour
     */
    public function deleteImagesByTour($tourId)
    {
        $images = $this->model->where('tour_id', $tourId)->get();

        foreach ($images as $img) {
            $this->imageService->deleteImage($img->path);
            $img->delete();
        }
    }

    /**
     * Cập nhật ảnh của 1 tour
     * 1) Ảnh cũ → giữ nguyên: Không gửi file mới, không xoá → giữ id.
     * 2) Ảnh cũ → thay ảnh mới: Có id + file.
     * 3) Ảnh mới → thêm: Không có id, chỉ có file.
     * 4) Ảnh cũ → xoá: Có id + _delete = true
     */
    // Ví dụ tổng quan
    // images[0][id] = 12
    // images[0][_delete] = false
    // images[0][file] = (empty) ← ảnh cũ, KHÔNG đổi file

    // images[1][id] = 15
    // images[1][_delete] = false
    // images[1][file] = (upload file) ← ảnh cũ, CÓ thay file

    // images[2][id] = 20
    // images[2][_delete] = true ← ảnh cũ, YÊU CẦU XÓA
    // images[2][file] = (empty)

    // images[3][file] = (upload file) ← ảnh MỚI

    public function syncImages($tourId, array $images)
    {
        $keepIds = []; // để biết ảnh nào giữ lại

        foreach ($images as $img) {

            // 1. Nếu có delete → xoá
            if (!empty($img['_delete']) && !empty($img['id'])) {
                $this->deleteImage($img['id']);
                continue;
            }

            // 2. Update ảnh cũ
            if (!empty($img['id'])) {
                $image = $this->model::find($img['id']);
                if (!$image) continue;

                // Nếu có file mới → thay ảnh
                if (!empty($img['file'])) {
                    // Xóa file cũ
                    $this->imageService->deleteImage($image->path);

                    // Upload file mới
                    $uploaded = $this->imageService->uploadImage($img['file'], "tours/$tourId");

                    $image->update([
                        'image'     => $uploaded['url'],
                        'fileImage' => $uploaded['filename'],
                        'path'      => $uploaded['path'],
                    ]);
                }

                $keepIds[] = $image->id;
                continue;
            }

            // 3. Ảnh mới
            if (!empty($img['file'])) {

                $uploaded = $this->imageService->uploadImage($img['file'], "tours/$tourId");

                $new = $this->model::create([
                    'tour_id'    => $tourId,
                    'image'      => $uploaded['url'],
                    'fileImage'  => $uploaded['filename'],
                    'path'      => $uploaded['path'],
                    'sort_order' => 0
                ]);

                $keepIds[] = $new->id;
            }
        }

        // 4. Xóa ảnh không còn trong request
        $this->model
            ->where('tour_id', $tourId)
            ->whereNotIn('id', $keepIds)
            ->get()
            ->each(fn($img) => $this->deleteImage($img->id));
    }
}
