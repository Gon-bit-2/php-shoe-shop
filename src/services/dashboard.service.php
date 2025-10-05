<?php
require_once __DIR__ . '/../models/repositories/order.repository.php';

class DashboardService
{
    private $orderRepository;

    public function __construct($conn)
    {
        $this->orderRepository = new OrderRepository($conn);
    }

    /**
     * Lấy tất cả dữ liệu cần thiết cho trang dashboard.
     */
    public function getDashboardData()
    {
        $stats = $this->orderRepository->getDashboardStats();
        $topProducts = $this->orderRepository->getTopSellingProducts(5); // Lấy top 5 sản phẩm

        return (object)[
            'stats' => $stats,
            'topProducts' => $topProducts
        ];
    }
}
