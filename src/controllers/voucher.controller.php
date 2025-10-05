<?php
require_once __DIR__ . '/../services/voucher.service.php';

class VoucherController
{
    private $voucherService;

    public function __construct($conn)
    {
        $this->voucherService = new VoucherService($conn);
    }

    // Hiển thị danh sách voucher
    public function index()
    {
        $vouchers = $this->voucherService->getAllVouchers();
        require_once __DIR__ . '/../views/admin/voucher/index.php';
    }
    public function create($errorMessage = '', $oldInput = [])
    {
        require_once __DIR__ . '/../views/admin/voucher/create.php';
    }

    // Xử lý lưu voucher mới
    public function store()
    {
        // (Chúng ta sẽ thêm validation ở bước sau)
        $result = $this->voucherService->createVoucher($_POST);

        if ($result) {
            header('Location: /shoe-shop/public/admin/vouchers');
            exit();
        } else {
            $this->create("Có lỗi xảy ra, vui lòng thử lại.", $_POST);
        }
    }
}
