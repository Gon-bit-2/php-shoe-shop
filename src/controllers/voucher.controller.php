<?php
require_once __DIR__ . '/../services/voucher.service.php';

class VoucherController
{
    private $voucherService;

    public function __construct($conn)
    {
        $this->voucherService = new VoucherService($conn);
    }

    public function index()
    {
        $vouchers = $this->voucherService->getAllVouchers();
        require_once __DIR__ . '/../views/admin/voucher/index.php';
    }
    public function create($errorMessage = '', $oldInput = [])
    {
        require_once __DIR__ . '/../views/admin/voucher/create.php';
    }

    public function store()
    {
        $result = $this->voucherService->createVoucher($_POST);

        if ($result) {
            header('Location: /shoe-shop/public/admin/vouchers');
            exit();
        } else {
            $this->create("Có lỗi xảy ra, vui lòng thử lại.", $_POST);
        }
    }
    public function edit($id, $errorMessage = '', $oldInput = [])
    {
        $voucher = $this->voucherService->getVoucherById($id);
        if (!$voucher) {
            http_response_code(404);
            echo "404 Not Found - Voucher không tồn tại.";
            exit();
        }
        require_once __DIR__ . '/../views/admin/voucher/edit.php';
    }

    public function update($id)
    {
        $result = $this->voucherService->updateVoucher($id, $_POST);

        if ($result) {
            header('Location: /shoe-shop/public/admin/vouchers');
            exit();
        } else {
            $this->edit($id, "Có lỗi xảy ra, vui lòng thử lại.", $_POST);
        }
    }
}
