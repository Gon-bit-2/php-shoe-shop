<?php
class DashboardController
{
    public function __construct($conn)
    {
        // Constructor có thể dùng sau này nếu cần kết nối DB
    }

    public function index()
    {
        // Chỉ cần nạp file view của dashboard
        require_once __DIR__ . '/../views/admin/dashBoards/dashboard.php';
    }
}
