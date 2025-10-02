<?php
class ProductMiddleware
{
    public function validateProductBody($data)
    {
        $name = trim($data['name'] ?? '');
        $description = $data['description'] ?? '';
        $categories = $data['categories'] ?? [];

        // --- BẮT ĐẦU THAY ĐỔI ---
        $variants = $data['variants'] ?? []; // Lấy mảng các biến thể

        if (empty($name)) {
            return 'Tên sản phẩm không được để trống!';
        }

        if (empty($description)) {
            return 'Mô tả sản phẩm không được để trống!';
        }

        if (empty($categories)) {
            return 'Bạn phải chọn ít nhất một danh mục cho sản phẩm!';
        }

        // Kiểm tra mới: Phải có ít nhất một biến thể được tạo
        if (empty($variants)) {
            return 'Bạn phải tạo ít nhất một biến thể (Size/Màu) cho sản phẩm!';
        }

        // (Tùy chọn) Kiểm tra chi tiết từng biến thể
        foreach ($variants as $index => $variant) {
            $price = $variant['price'] ?? '';
            $stock = $variant['stock'] ?? '';
            if (empty($price) || !is_numeric($price) || $price < 0) {
                return "Giá của biến thể thứ " . ($index + 1) . " không hợp lệ!";
            }
            if (empty($stock) || !is_numeric($stock) || $stock < 0) {
                return "Tồn kho của biến thể thứ " . ($index + 1) . " không hợp lệ!";
            }
        }
        // --- KẾT THÚC THAY ĐỔI ---

        return false; // Trả về false nếu không có lỗi
    }
}
