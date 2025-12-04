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
                'image'     => $uploaded['url'],       // URL tuyệt đối
                'fileImage' => $uploaded['filename'],  // tên file
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
}
