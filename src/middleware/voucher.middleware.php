<?php
class VoucherMiddleware
{
    public function validateVoucherBody($data)
    {
        $code = trim($data['code'] ?? '');
        $type = trim($data['type'] ?? '');
        $value = trim($data['value'] ?? '');
        $quantity = trim($data['quantity'] ?? '');
        $minSpend = trim($data['min_spend'] ?? '0');
        $startsAt = trim($data['starts_at'] ?? '');
        $expiresAt = trim($data['expires_at'] ?? '');

        // Kiểm tra mã voucher
        if (empty($code)) {
            return 'Mã voucher không được để trống!';
        }
        if (strlen($code) < 3 || strlen($code) > 20) {
            return 'Mã voucher phải từ 3-20 ký tự!';
        }
        if (!preg_match('/^[A-Z0-9]+$/', $code)) {
            return 'Mã voucher chỉ được chứa chữ in hoa và số, không có khoảng trắng!';
        }

        // Kiểm tra loại voucher
        if (empty($type)) {
            return 'Vui lòng chọn loại voucher!';
        }
        if (!in_array($type, ['percent', 'fixed'])) {
            return 'Loại voucher không hợp lệ!';
        }

        // Kiểm tra giá trị
        if (empty($value) || !is_numeric($value) || $value <= 0) {
            return 'Giá trị voucher phải là số dương!';
        }
        if ($type === 'percent' && $value > 100) {
            return 'Giá trị phần trăm không được vượt quá 100%!';
        }

        // Kiểm tra số lượng
        if (empty($quantity) || !is_numeric($quantity) || $quantity <= 0) {
            return 'Số lượng voucher phải là số nguyên dương!';
        }

        //check giá trị đơn hàng tối thiểu
        if (!is_numeric($minSpend) || $minSpend < 0) {
            return 'Giá trị đơn hàng tối thiểu không hợp lệ!';
        }

        //check ngày bắt đầu và hết hạn
        if (!empty($startsAt) && !$this->isValidDateTime($startsAt)) {
            return 'Ngày bắt đầu không hợp lệ!';
        }
        if (!empty($expiresAt) && !$this->isValidDateTime($expiresAt)) {
            return 'Ngày hết hạn không hợp lệ!';
        }
        if (!empty($startsAt) && !empty($expiresAt)) {
            if (strtotime($expiresAt) < strtotime($startsAt)) {
                return 'Ngày hết hạn phải sau ngày bắt đầu!';
            }
        }

        return false; // Không có lỗi
    }

    //check ngày bắt đầu và hết hạn
    private function isValidDateTime($datetime)
    {
        // Thử validate với định dạng datetime-local (Y-m-d\TH:i)
        $d = DateTime::createFromFormat('Y-m-d\TH:i', $datetime);
        if ($d && $d->format('Y-m-d\TH:i') === $datetime) {
            return true;
        }

        // Thử validate với định dạng date only (Y-m-d)
        $d = DateTime::createFromFormat('Y-m-d', $datetime);
        if ($d && $d->format('Y-m-d') === $datetime) {
            return true;
        }

        // Thử validate với datetime có giây (Y-m-d\TH:i:s)
        $d = DateTime::createFromFormat('Y-m-d\TH:i:s', $datetime);
        if ($d && $d->format('Y-m-d\TH:i:s') === $datetime) {
            return true;
        }

        return false;
    }
}
