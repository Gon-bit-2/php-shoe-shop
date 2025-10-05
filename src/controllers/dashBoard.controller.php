<?php
require_once __DIR__ . '/../services/dashboard.service.php';

class DashboardController
{
    private $dashboardService;
    public function __construct($conn)
    {
        // Constructor có thể dùng sau này nếu cần kết nối DB
        $this->dashboardService = new DashboardService($conn);
    }

    public function index()
    { // Gọi service để lấy toàn bộ dữ liệu
        $dashboardData = $this->dashboardService->getDashboardData();
        // Chỉ cần nạp file view của dashboard
        require_once __DIR__ . '/../views/admin/dashBoards/dashboard.php';
    }
}
