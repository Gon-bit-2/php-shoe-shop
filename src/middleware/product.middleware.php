<?php
class ProductMiddleware
{
    public function validateProductBody($data)
    {
        $name = trim($data['name'] ?? '');
        $price = $data['price'] ?? '';
        $is_active = $data['is_active'] ?? '';
        $description = $data['description'] ?? '';
        $stock = $data['stock'] ?? '';
        $categories = $data['categories'] ?? '';

        if (empty($name)) {
            return 'Tên sản phẩm không được để trống!';
        }
        if (empty($price)) {
            return 'Giá sản phẩm không được để trống!';
        }
        if (!is_numeric($price) || $price <= 0) {
            return 'Giá sản phẩm phải là một số lớn hơn 0!';
        }
        if (empty($stock)) {
            return 'Số lượng sản phẩm không được để trống!';
        }
        if (!is_numeric($stock) || $stock <= 0) {
            return 'Số lượng sản phẩm phải là một số lớn hơn 0!';
        }
        if (empty($description)) {
            return 'Mô tả sản phẩm không được để trống!';
        }
        if (empty($categories)) {
            return 'Danh mục sản phẩm không được để trống!';
        }
        return false;
    }
}
