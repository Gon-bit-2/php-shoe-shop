<?php
require_once __DIR__ . '/../models/repositories/order.repository.php';

class DashboardService
{
    private $orderRepository;

    public function __construct($conn)
    {
        $this->orderRepository = new OrderRepository($conn);
    }

    public function getDashboardData()
    {
        $stats = $this->orderRepository->getDashboardStats();
        $topProducts = $this->orderRepository->getTopSellingProducts(4); //4sp
        return (object)[
            'stats' => $stats,
            'topProducts' => $topProducts
        ];
    }
}
