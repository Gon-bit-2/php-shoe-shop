<?php
class CartMiddleware
{
    public function validateAddToCartBody($data)
    {
        $variantId = $data['variant_id'] ?? null;
        $quantity = $data['quantity'] ?? null;

        // Kiểm tra variant_id
        if (empty($variantId)) {
            return 'Vui lòng chọn phiên bản sản phẩm!';
        }
        if (!is_numeric($variantId) || $variantId <= 0) {
            return 'Phiên bản sản phẩm không hợp lệ!';
        }

        // Kiểm tra quantity
        if (empty($quantity)) {
            return 'Số lượng không được để trống!';
        }
        if (!is_numeric($quantity) || $quantity <= 0) {
            return 'Số lượng phải là số nguyên dương!';
        }
        if ($quantity > 999) {
            return 'Số lượng không được vượt quá 999!';
        }

        return false; // Không có lỗi
    }

    public function validateUpdateCartBody($data)
    {
        $variantId = $data['variant_id'] ?? null;
        $quantity = $data['quantity'] ?? null;

        // Kiểm tra variant_id
        if (empty($variantId)) {
            return 'Phiên bản sản phẩm không hợp lệ!';
        }
        if (!is_numeric($variantId) || $variantId <= 0) {
            return 'Phiên bản sản phẩm không hợp lệ!';
        }

        // Kiểm tra quantity
        if (!isset($quantity) || $quantity === '') {
            return 'Số lượng không được để trống!';
        }
        if (!is_numeric($quantity) || $quantity < 0) {
            return 'Số lượng phải là số không âm!';
        }
        if ($quantity > 999) {
            return 'Số lượng không được vượt quá 999!';
        }

        return false; // Không có lỗi
    }

    public function validateRemoveFromCartBody($data)
    {
        $variantId = $data['variant_id'] ?? null;

        if (empty($variantId)) {
            return 'Phiên bản sản phẩm không hợp lệ!';
        }
        if (!is_numeric($variantId) || $variantId <= 0) {
            return 'Phiên bản sản phẩm không hợp lệ!';
        }

        return false; // Không có lỗi
    }
}
