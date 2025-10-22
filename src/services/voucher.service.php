<?php
require_once __DIR__ . '/../models/repositories/voucher.repository.php';

class VoucherService
{
    private $voucherRepository;

    public function __construct($conn)
    {
        $this->voucherRepository = new VoucherRepository($conn);
    }
    public function createVoucher($data)
    {
        $voucher = new Voucher();
        $voucher->code = strtoupper(trim($data['code']));
        $voucher->type = $data['type'];
        $voucher->value = $data['value'];
        $voucher->min_spend = $data['min_spend'] ?? 0;
        $voucher->quantity = $data['quantity'];
        $voucher->starts_at = $data['starts_at'] ?: null;
        $voucher->expires_at = $data['expires_at'] ?: null;
        $voucher->is_active = isset($data['is_active']) ? 1 : 0;

        return $this->voucherRepository->save($voucher);
    }
    public function getAllVouchers()
    {
        return $this->voucherRepository->findAll();
    }
    public function validateAndApplyVoucher($code, $cartTotal)
    {
        $voucher = $this->voucherRepository->findByCode(strtoupper($code));

        if (!$voucher) {
            return ['success' => false, 'message' => 'Mã giảm giá không tồn tại.'];
        }
        if (!$voucher->is_active) {
            return ['success' => false, 'message' => 'Mã giảm giá này đã bị vô hiệu hóa.'];
        }

        if ($voucher->used_count >= $voucher->quantity) {
            return ['success' => false, 'message' => 'Mã giảm giá đã được sử dụng hết.'];
        }

        $now = new DateTime();
        if ($voucher->starts_at && new DateTime($voucher->starts_at) > $now) {
            return ['success' => false, 'message' => 'Mã giảm giá chưa đến ngày có hiệu lực.'];
        }
        if ($voucher->expires_at && new DateTime($voucher->expires_at) < $now) {
            return ['success' => false, 'message' => 'Mã giảm giá đã hết hạn.'];
        }

        if ($cartTotal < $voucher->min_spend) {
            return ['success' => false, 'message' => 'Đơn hàng của bạn chưa đạt giá trị tối thiểu (' . number_format($voucher->min_spend) . ' VNĐ) để áp dụng mã này.'];
        }

        $discountAmount = 0;
        if ($voucher->type == 'percent') {
            $discountAmount = ($cartTotal * $voucher->value) / 100;
        } else {
            $discountAmount = $voucher->value;
        }

        if ($discountAmount > $cartTotal) {
            $discountAmount = $cartTotal;
        }

        return [
            'success' => true,
            'voucher_code' => $voucher->code,
            'discount_amount' => $discountAmount
        ];
    }
    public function getVoucherById($id)
    {
        return $this->voucherRepository->findById($id);
    }
    public function getActiveVouchers()
    {
        return $this->voucherRepository->findActiveVouchers();
    }
    public function updateVoucher($id, $data)
    {
        $voucher = $this->getVoucherById($id);
        if (!$voucher) {
            return false;
        }

        $voucher->code = strtoupper(trim($data['code']));
        $voucher->type = $data['type'];
        $voucher->value = $data['value'];
        $voucher->min_spend = $data['min_spend'] ?? 0;
        $voucher->quantity = $data['quantity'];
        $voucher->starts_at = $data['starts_at'] ?: null;
        $voucher->expires_at = $data['expires_at'] ?: null;
        $voucher->is_active = isset($data['is_active']) ? 1 : 0;

        return $this->voucherRepository->update($voucher);
    }
}
