<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\DB;
use App\Services\Upload\ImageService;
use App\Repositories\CountryRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CountryService extends BaseService
{
    protected $imageService;

    public function repository()
    {
        return CountryRepository::class;
    }

    public function __construct(ImageService $imageService)
    {
        parent::__construct();
        $this->imageService = $imageService;
    }

    public function create(array $data)
    {
        DB::beginTransaction();

        $uploadedImagePath = null;

        try {

            // Upload ảnh trước
            if (!empty($data['image'])) {

                $imageData = $this->imageService->uploadImage($data['image'], 'countries');

                $uploadedImagePath = $imageData['path']; // dùng để rollback nếu lỗi

                $data['image']     = $imageData['url'];
                $data['fileImage'] = $imageData['filename'];
            }

            $result = parent::create($data);

            DB::commit();
            return $result;
        } catch (\Throwable $e) {

            DB::rollBack();

            // Xóa ảnh nếu đã upload nhưng DB lỗi
            if ($uploadedImagePath) {
                $this->imageService->deleteImage($uploadedImagePath);
            }

            throw $e;
        }
    }

    public function update($id, array $data)
    {
        DB::beginTransaction();

        try {

            // Lấy model từ DB
            $model = $this->repository->findOneById($id);

            if (!$model) {
                throw new ModelNotFoundException("ID bản ghi không tồn tại, vui lòng kiểm tra lại");
            }

            $oldImagePath = null;

            // Nếu có ảnh mới
            if (!empty($data['image'])) {

                // Lưu lại đường dẫn ảnh cũ để xóa
                if (!empty($model->fileImage)) {
                    $oldImagePath = 'images/countries/' . $model->fileImage;
                }

                // Upload ảnh mới
                $imageData = $this->imageService->uploadImage($data['image'], 'countries');

                $data['image']     = $imageData['url'];
                $data['fileImage'] = $imageData['filename'];
            }

            // Update DB
            $updated = $this->repository->update($model, $data);

            DB::commit();

            // Sau khi commit thành công thì mới được xóa ảnh cũ
            if ($oldImagePath) {
                $this->imageService->deleteImage($oldImagePath);
            }

            return $updated;
        } catch (\Throwable $e) {

            DB::rollBack();

            // Nếu upload ảnh mới mà lỗi DB → rollback xóa ảnh mới
            if (!empty($data['fileImage'])) {
                $this->imageService->deleteImage('images/countries/' . $data['fileImage']);
            }

            throw $e;
        }
    }

    public function delete($id)
    {
        DB::beginTransaction();

        try {

            // Lấy model từ DB
            $model = $this->repository->findOneById($id);

            if (!$model) {
                throw new \Exception("ID bản ghi không tồn tại, vui lòng kiểm tra lại");
            }

            // Lưu lại đường dẫn ảnh cũ để xóa sau khi commit
            $oldImagePath = null;

            if (!empty($model->fileImage)) {
                $oldImagePath = 'images/countries/' . $model->fileImage;
            }

            // Xóa bản ghi
            $this->repository->delete($model);

            DB::commit();

            // Sau commit mới được xóa file thực tế
            if ($oldImagePath) {
                $this->imageService->deleteImage($oldImagePath);
            }

            return true;
        } catch (\Throwable $e) {

            DB::rollBack();
            throw $e;
        }
    }
}
