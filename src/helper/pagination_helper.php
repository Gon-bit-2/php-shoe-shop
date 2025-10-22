<?php
function renderPagination($totalPages, $currentPage, $baseUrl = '')
{
    if ($totalPages <= 1) {
        return '';
    }

    $queryParams = $_GET;

    echo '<nav class="mt-8 flex justify-center">';
    echo '<ul class="inline-flex items-center -space-x-px">';

    // Nút pre
    if ($currentPage > 1) {
        $queryParams['page'] = $currentPage - 1;
        echo '<li><a href="' . $baseUrl . '?' . http_build_query($queryParams) . '" class="px-3 py-2 leading-tight text-gray-500 bg-white border border-gray-300 rounded-l-lg hover:bg-gray-100 hover:text-gray-700">&laquo; Trước</a></li>';
    }

    // Các nút số trang
    for ($i = 1; $i <= $totalPages; $i++) {
        $queryParams['page'] = $i;
        $isActive = ($i == $currentPage);
        $class = $isActive
            ? 'px-3 py-2 text-blue-600 border border-gray-300 bg-blue-50'
            : 'px-3 py-2 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700';
        echo '<li><a href="' . $baseUrl . '?' . http_build_query($queryParams) . '" class="' . $class . '">' . $i . '</a></li>';
    }

    // Nút next
    if ($currentPage < $totalPages) {
        $queryParams['page'] = $currentPage + 1;
        echo '<li><a href="' . $baseUrl . '?' . http_build_query($queryParams) . '" class="px-3 py-2 leading-tight text-gray-500 bg-white border border-gray-300 rounded-r-lg hover:bg-gray-100 hover:text-gray-700">Sau &raquo;</a></li>';
    }

    echo '</ul>';
    echo '</nav>';
}
