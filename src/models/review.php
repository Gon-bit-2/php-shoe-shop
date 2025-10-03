<?php
class Review
{
    public $id;
    public $product_id;
    public $user_id;
    public $order_id;
    public $rating;
    public $comment;
    public $status;
    public $created_at;

    // Thuộc tính này sẽ được lấy từ việc JOIN bảng users
    public $fullname;
}
