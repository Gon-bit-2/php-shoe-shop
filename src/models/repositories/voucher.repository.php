<?php
require_once __DIR__ . '/../voucher.php';

class VoucherRepository
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }
    public function save(Voucher $voucher)
    {
        $query = "INSERT INTO vouchers (code, type, value, min_spend, quantity, starts_at, expires_at, is_active)
                  VALUES (:code, :type, :value, :min_spend, :quantity, :starts_at, :expires_at, :is_active)";
        $stmt = $this->conn->prepare($query);

        // Chuyển đổi ngày tháng về đúng định dạng Y-m-d H:i:s cho MySQL
        $starts_at_formatted = !empty($voucher->starts_at) ? date('Y-m-d H:i:s', strtotime($voucher->starts_at)) : null;
        $expires_at_formatted = !empty($voucher->expires_at) ? date('Y-m-d H:i:s', strtotime($voucher->expires_at)) : null;
        $stmt->bindParam(":code", $voucher->code);
        $stmt->bindParam(":type", $voucher->type);
        $stmt->bindParam(":value", $voucher->value);
        $stmt->bindParam(":min_spend", $voucher->min_spend);
        $stmt->bindParam(":quantity", $voucher->quantity);
        $stmt->bindParam(":starts_at", $starts_at_formatted);
        $stmt->bindParam(":expires_at", $expires_at_formatted);
        $stmt->bindParam(":is_active", $voucher->is_active);
        return $stmt->execute();
    }
    public function findAll()
    {
        $query = "SELECT * FROM vouchers ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS, 'Voucher');
    }
}
