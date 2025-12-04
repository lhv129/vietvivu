<?php

namespace App\Services\Upload;

use Illuminate\Support\Str;

class ImageService
{
    protected $baseFolder = 'images';

    /**
     * Upload 1 ảnh
     */
    public function uploadImage($file, $subFolder = null)
    {
        // Folder gốc: public/images/
        $folder = $this->baseFolder;

        // Nếu có subfolder -> public/images/countries
        if ($subFolder) {
            $folder .= '/' . trim($subFolder, '/');
        }

        // Tạo thư mục nếu chưa có
        $fullPath = public_path($folder);
        if (!file_exists($fullPath)) {
            mkdir($fullPath, 0777, true);
        }

        // Tạo tên file duy nhất
        $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();

        // Lưu file vào public
        $file->move($fullPath, $filename);

        // Tạo URL public
        $url = asset($folder . '/' . $filename);

        return [
            'filename' => $filename,
            'url'      => $url,
            'path'     => $folder . '/' . $filename, // dùng để xóa file
        ];
    }

    /**
     * Upload nhiều ảnh
     */
    public function uploadImages(array $files, $subFolder = null)
    {
        $result = [];

        foreach ($files as $file) {
            $result[] = $this->uploadImage($file, $subFolder);
        }

        return $result;
    }

    /**
     * Xóa ảnh (dùng filename hoặc url đều được)
     */
    public function deleteImage($pathOrUrl)
    {
        if (empty($pathOrUrl)) {
            return;
        }

        // Nếu truyền URL → convert về path tương đối
        if (str_starts_with($pathOrUrl, asset(''))) {
            $path = str_replace(asset('') . '/', '', $pathOrUrl);
        } else {
            // path đầy đủ rồi → giữ nguyên
            $path = $pathOrUrl;
        }

        $fullPath = public_path($path);

        if (is_file($fullPath)) {
            @unlink($fullPath);
        }
    }
}
