<?php
class OrderMiddleware
{
    public function validateCheckoutBody($data)
    {
        $customerName = trim($data['customer_name'] ?? '');
        $customerPhone = trim($data['customer_phone'] ?? '');
        $customerAddress = trim($data['customer_address'] ?? '');
        $paymentMethod = trim($data['payment_method'] ?? '');

        // Kiểm tra tên khách hàng
        if (empty($customerName)) {
            return 'Tên người nhận không được để trống!';
        }
        if (strlen($customerName) < 2) {
            return 'Tên người nhận phải có ít nhất 2 ký tự!';
        }
        if (strlen($customerName) > 100) {
            return 'Tên người nhận không được quá 100 ký tự!';
        }

        // Kiểm tra số điện thoại
        if (empty($customerPhone)) {
            return 'Số điện thoại không được để trống!';
        }
        // Định dạng số điện thoại Việt Nam: 10-11 số, bắt đầu bằng 0
        if (!preg_match('/^0[0-9]{9,10}$/', $customerPhone)) {
            return 'Số điện thoại không đúng định dạng (10-11 số, bắt đầu bằng 0)!';
        }

        // Kiểm tra địa chỉ
        if (empty($customerAddress)) {
            return 'Địa chỉ giao hàng không được để trống!';
        }
        if (strlen($customerAddress) < 10) {
            return 'Địa chỉ giao hàng phải có ít nhất 10 ký tự!';
        }
        if (strlen($customerAddress) > 500) {
            return 'Địa chỉ giao hàng không được quá 500 ký tự!';
        }

        // Kiểm tra phương thức thanh toán
        if (empty($paymentMethod)) {
            return 'Vui lòng chọn phương thức thanh toán!';
        }
        if (!in_array($paymentMethod, ['cod', 'bank_transfer'])) {
            return 'Phương thức thanh toán không hợp lệ!';
        }

        return false; // Không có lỗi
    }
}
