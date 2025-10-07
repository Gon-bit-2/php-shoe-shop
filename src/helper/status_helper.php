<?php
// File: src/helpers/status_helper.php

function translateOrderStatus($status)
{
    $statusMap = [
        'pending'    => 'Chờ xác nhận',
        'processing' => 'Đang xử lý',
        'shipped'    => 'Đang giao hàng',
        'completed'  => 'Đã giao thành công',
        'cancelled'  => 'Đã hủy'
    ];

    return $statusMap[$status] ?? ucfirst($status);
}

function getStatusColorClass($status)
{
    $colorMap = [
        'pending'    => 'bg-yellow-200 text-yellow-800',
        'processing' => 'bg-blue-200 text-blue-800',
        'shipped'    => 'bg-indigo-200 text-indigo-800',
        'completed'  => 'bg-green-200 text-green-800',
        'cancelled'  => 'bg-red-200 text-red-800'
    ];

    return $colorMap[$status] ?? 'bg-gray-200 text-gray-800';
}
