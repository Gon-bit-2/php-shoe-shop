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
        $voucher->code = strtoupper(trim($data['code'])); // Viết hoa mã cho thống nhất
        $voucher->type = $data['type'];
        $voucher->value = $data['value'];
        $voucher->min_spend = $data['min_spend'] ?? 0;
        $voucher->quantity = $data['quantity'];
        $voucher->starts_at = $data['starts_at'] ?: null; // Chấp nhận giá trị rỗng
        $voucher->expires_at = $data['expires_at'] ?: null; // Chấp nhận giá trị rỗng
        $voucher->is_active = isset($data['is_active']) ? 1 : 0;

        return $this->voucherRepository->save($voucher);
    }
    public function getAllVouchers()
    {
        return $this->voucherRepository->findAll();
    }
}
