<?php
require_once __DIR__ . '/../models/repositories/review.repository.php';
require_once __DIR__ . '/../models/repositories/order.repository.php';
require_once __DIR__ . '/../models/review.php';

class ReviewService
{
    private $reviewRepository;
    private $orderRepository;

    public function __construct($conn)
    {
        $this->reviewRepository = new ReviewRepository($conn);
        $this->orderRepository = new OrderRepository($conn);
    }

    /**
     * Lấy tất cả các đánh giá đã được duyệt cho một sản phẩm.
     */
    public function getApprovedReviewsForProduct($productId)
    {
        return $this->reviewRepository->findApprovedByProductId($productId);
    }

    /**
     * Kiểm tra xem người dùng có đủ điều kiện để đánh giá sản phẩm hay không.
     * Trả về một đối tượng chứa trạng thái và order_id nếu đủ điều kiện.
     */
    public function checkReviewEligibility($userId, $productId)
    {
        $orderId = $this->orderRepository->findPurchasedOrderForReview($userId, $productId);

        if ($orderId) {
            return (object)[
                'canReview' => true,
                'orderId' => $orderId
            ];
        }

        return (object)[
            'canReview' => false,
            'orderId' => null
        ];
    }

    /**
     * Tạo một đánh giá mới sau khi đã kiểm tra logic.
     */
    public function createReview($productId, $userId, $orderId, $rating, $comment)
    {
        // 1. Kiểm tra lại quyền một lần nữa để đảm bảo an toàn
        $eligibility = $this->checkReviewEligibility($userId, $productId);

        // Nếu người dùng không có quyền hoặc orderId không khớp -> thất bại
        if (!$eligibility->canReview || $eligibility->orderId != $orderId) {
            return ['success' => false, 'message' => 'Bạn không có quyền đánh giá sản phẩm này.'];
        }

        // 2. Validate dữ liệu đầu vào
        if (empty($rating) || !is_numeric($rating) || $rating < 1 || $rating > 5) {
            return ['success' => false, 'message' => 'Vui lòng chọn số sao đánh giá hợp lệ.'];
        }

        // 3. Tạo đối tượng Review và lưu
        $review = new Review();
        $review->product_id = $productId;
        $review->user_id = $userId;
        $review->order_id = $orderId;
        $review->rating = (int)$rating;
        $review->comment = htmlspecialchars(trim($comment)); // Làm sạch dữ liệu
        $review->status = 'approved';
        $isSaved = $this->reviewRepository->save($review);

        if ($isSaved) {
            return ['success' => true, 'message' => 'Gửi đánh giá thành công!'];
        } else {
            return ['success' => false, 'message' => 'Đã có lỗi xảy ra, vui lòng thử lại.'];
        }
    }
}
