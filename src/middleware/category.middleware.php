<?php
class CategoryMiddleware
{
    public function validateCategoryBody($data, $file = null)
    {
        $name = trim($data['name'] ?? '');

        // Kiểm tra tên danh mục
        if (empty($name)) {
            return 'Tên danh mục không được để trống!';
        }
        if (strlen($name) < 2) {
            return 'Tên danh mục phải có ít nhất 2 ký tự!';
        }
        if (strlen($name) > 100) {
            return 'Tên danh mục không được quá 100 ký tự!';
        }

        //file upload
        if ($file && isset($file['error']) && $file['error'] !== UPLOAD_ERR_NO_FILE) {
            if ($file['error'] !== UPLOAD_ERR_OK) {
                return 'Có lỗi xảy ra khi upload ảnh!';
            }

            //check định dạng file
            $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
            $fileType = $file['type'];
            if (!in_array($fileType, $allowedTypes)) {
                return 'Chỉ chấp nhận file ảnh định dạng JPG, PNG, GIF!';
            }

            //check kích thước file
            $maxSize = 2 * 1024 * 1024; // 2MB
            if ($file['size'] > $maxSize) {
                return 'Kích thước ảnh không được vượt quá 2MB!';
            }
        }

        return false;
    }
}
