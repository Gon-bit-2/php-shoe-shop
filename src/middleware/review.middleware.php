<?php
class ReviewMiddleware
{
    public function validateReviewBody($data)
    {
        $rating = $data['rating'] ?? null;
        $comment = trim($data['comment'] ?? '');
        $orderId = $data['order_id'] ?? null;

        //check rating
        if (empty($rating)) {
            return 'Vui lòng chọn số sao đánh giá!';
        }
        if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
            return 'Đánh giá phải từ 1 đến 5 sao!';
        }

        //check comment
        if (empty($comment)) {
            return 'Nội dung đánh giá không được để trống!';
        }
        if (strlen($comment) < 10) {
            return 'Nội dung đánh giá phải có ít nhất 10 ký tự!';
        }
        if (strlen($comment) > 1000) {
            return 'Nội dung đánh giá không được quá 1000 ký tự!';
        }

        if (empty($orderId)) {
            return 'Thông tin đơn hàng không hợp lệ!';
        }
        if (!is_numeric($orderId) || $orderId <= 0) {
            return 'Thông tin đơn hàng không hợp lệ!';
        }

        return false;
    }
}
