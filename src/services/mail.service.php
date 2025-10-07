<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../helper/status_helper.php';

class MailService
{
    // Bỏ hết các thuộc tính và hàm __construct() cũ

    public function sendOrderStatusEmail($orderDetails) // THAY ĐỔI: Nhận thẳng dữ liệu, không cần $orderId
    {
        if (!$orderDetails || !$orderDetails->status) return false;

        $order = $orderDetails->order;
        $items = $orderDetails->items;
        $status = $order->status; // Lấy trạng thái từ chính đơn hàng
        $customerEmail = $order->email;

        $subject = '';
        $bodyHeader = '';

        switch ($status) {
            case 'pending':
                $subject = 'Xác nhận đơn hàng #' . $order->id;
                $bodyHeader = '<h1>Cảm ơn bạn đã đặt hàng!</h1><p>Đơn hàng của bạn đã được tiếp nhận và đang chờ xử lý.</p>';
                break;
            case 'shipped':
                $subject = 'Thông báo: Đơn hàng #' . $order->id . ' đã được gửi đi';
                $bodyHeader = '<h1>Đơn hàng đang được giao!</h1><p>Đơn hàng của bạn đã được bàn giao cho đơn vị vận chuyển.</p>';
                break;
            case 'completed':
                $subject = 'Đơn hàng #' . $order->id . ' đã giao thành công';
                $bodyHeader = '<h1>Chúc mừng! Đơn hàng đã giao thành công!</h1><p>Cảm ơn bạn đã mua sắm tại ShoeShop.</p>';
                break;
            default:
                return false;
        }

        $mail = new PHPMailer(true);
        try {
            // Cấu hình Server (giữ nguyên)
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'vanthien7027@gmail.com';
            $mail->Password   = 'yfdy nvto nmww qitm';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;
            $mail->CharSet    = 'UTF-8';

            // Người gửi, người nhận (giữ nguyên)
            $mail->setFrom('vanthien7027@gmail.com', 'ShoeShop');
            $mail->addAddress($customerEmail, $order->customer_name);

            // Nội dung (giữ nguyên)
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $emailBody = "<h2>Xin chào " . htmlspecialchars($order->customer_name) . ",</h2>";
            $emailBody .= $bodyHeader;
            // ... (phần code tạo bảng chi tiết sản phẩm giữ nguyên) ...
            $emailBody .= "<h3>Chi tiết đơn hàng:</h3>";
            $emailBody .= "<table border='1' cellpadding='10' cellspacing='0' style='width:100%; border-collapse: collapse;'>";
            $emailBody .= "<tr style='background-color:#f2f2f2;'><th>Sản phẩm</th><th>Số lượng</th><th>Giá</th><th>Thành tiền</th></tr>";
            foreach ($items as $item) {
                $emailBody .= "<tr>";
                $emailBody .= "<td>" . htmlspecialchars($item->product_name) . "<br><small>" . htmlspecialchars($item->variant_attributes) . "</small></td>";
                $emailBody .= "<td align='center'>" . $item->quantity . "</td>";
                $emailBody .= "<td align='right'>" . number_format($item->price) . " VNĐ</td>";
                $emailBody .= "<td align='right'>" . number_format($item->price * $item->quantity) . " VNĐ</td>";
                $emailBody .= "</tr>";
            }
            $emailBody .= "</table>";
            $subtotal = $order->total_amount + $order->discount_amount;
            $emailBody .= "<p style='text-align:right;'><strong>Tạm tính:</strong> " . number_format($subtotal) . " VNĐ</p>";
            if ($order->discount_amount > 0) {
                $emailBody .= "<p style='text-align:right;'><strong>Giảm giá (" . htmlspecialchars($order->voucher_code) . "):</strong> -" . number_format($order->discount_amount) . " VNĐ</p>";
            }
            $emailBody .= "<p style='text-align:right; font-size:1.2em;'><strong>Tổng cộng: " . number_format($order->total_amount) . " VNĐ</strong></p>";
            $emailBody .= "<p>Chúng tôi sẽ tiếp tục cập nhật trạng thái đơn hàng cho bạn. Cảm ơn!</p>";
            $mail->Body = $emailBody;

            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Mailer Error: {$mail->ErrorInfo}");
            return false;
        }
    }
}
