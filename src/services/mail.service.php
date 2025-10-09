<?php


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

require_once __DIR__ . '/../helper/status_helper.php';

class MailService
{

    /**
     * Gửi email thông báo trạng thái đơn hàng
     * @param mixed $orderDetails
     * @return bool
     */
    // src/services/mail.service.php

    public function sendOrderStatusEmail($orderDetails)
    {
        if (!$orderDetails || !$orderDetails->status) return false;

        $order = $orderDetails->order;
        $items = $orderDetails->items;
        $status = $order->status;
        $customerEmail = $order->email;

        $subject = '';
        $bodyHeader = '';

        // ... (Phần switch-case giữ nguyên, không thay đổi)
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
            case 'cancelled':
                $subject = 'Đơn hàng #' . $order->id . ' đã bị hủy';
                $bodyHeader = '<h1>Đơn hàng đã bị hủy!</h1><p>Đơn hàng của bạn đã bị hủy. Vui lòng liên hệ với chúng tôi để biết thêm chi tiết.</p>';
                break;
            default:
                return false;
        }


        $mail = new PHPMailer(true);
        try {
            // --- PHẦN CẤU HÌNH GIỮ NGUYÊN ---
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = $_ENV['GMAIL_USERNAME'];
            $mail->Password   = $_ENV['GMAIL_APP_PASSWORD'];
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;
            $mail->CharSet    = 'UTF-8';
            $mail->setFrom($_ENV['GMAIL_USERNAME'], 'ShoeShop');
            $mail->addAddress($customerEmail, $order->customer_name);
            $mail->isHTML(true);
            $mail->Subject = $subject;

            $emailBody = "<h2>Xin chào " . htmlspecialchars($order->customer_name) . ",</h2>";
            $emailBody .= $bodyHeader;
            $emailBody .= "<h3>Chi tiết đơn hàng:</h3>";
            $emailBody .= "<table border='1' cellpadding='10' cellspacing='0' style='width:100%; border-collapse: collapse;'>";
            $emailBody .= "<tr style='background-color:#f2f2f2;'><th>Sản phẩm</th><th>Ảnh</th><th>Số lượng</th><th>Giá</th><th>Thành tiền</th></tr>";

            foreach ($items as $index => $item) {
                // 1. Chuyển đổi đường dẫn web thành đường dẫn vật lý trên server
                // Ví dụ: /shoe-shop/public/images/products/abc.jpg -> D:/xampp/htdocs/shoe-shop/public/images/products/abc.jpg
                $imagePath = realpath(__DIR__ . '/../../public' . str_replace('/shoe-shop/public', '', $item->image_url));

                // 2. Đính kèm ảnh vào email và tạo một ID duy nhất (cid) cho nó
                if ($imagePath && file_exists($imagePath)) {
                    $cid = 'image' . $index; // Tạo cid duy nhất cho mỗi ảnh, ví dụ 'image0', 'image1'
                    $mail->addEmbeddedImage($imagePath, $cid, basename($imagePath));
                } else {
                    $cid = null;
                }

                $emailBody .= "<tr>";
                $emailBody .= "<td>" . htmlspecialchars($item->product_name) . "<br><small>" . htmlspecialchars($item->variant_attributes) . "</small></td>";

                // 3. Sử dụng cid trong thẻ <img>
                if ($cid) {
                    $emailBody .= "<td align='center'><img src='cid:" . $cid . "' alt='" . htmlspecialchars($item->product_name) . "' style='width:100px; height:100px;'></td>";
                } else {
                    $emailBody .= "<td>(không có ảnh)</td>"; // Hiển thị nếu ảnh không tồn tại
                }

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
    /**
     * Gửi email thông báo đặt lại mật khẩu
     * @param mixed $recipientEmail
     * @param mixed $recipientName
     * @param mixed $token
     * @return bool
     */
    public function sendPasswordResetEmail($recipientEmail, $recipientName, $token)
    {
        // Link này trỏ đến trang reset mật khẩu trên web của bạn
        $resetLink = "http://" . $_SERVER['HTTP_HOST'] . "/shoe-shop/public/reset-password?token=" . $token;

        $mail = new PHPMailer(true);

        try {
            // Cấu hình server (giữ nguyên)
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = $_ENV['GMAIL_USERNAME'];
            $mail->Password   = $_ENV['GMAIL_APP_PASSWORD'];
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;
            $mail->CharSet    = 'UTF-8';

            // Người gửi, người nhận
            $mail->setFrom($_ENV['GMAIL_USERNAME'], 'ShoeShop');
            $mail->addAddress($recipientEmail, $recipientName);

            // Nội dung
            $mail->isHTML(true);
            $mail->Subject = 'Yêu cầu đặt lại mật khẩu tài khoản ShoeShop';
            $mail->Body    = "
                <h1>Yêu cầu đặt lại mật khẩu</h1>
                <p>Xin chào " . htmlspecialchars($recipientName) . ",</p>
                <p>Chúng tôi nhận được yêu cầu đặt lại mật khẩu cho tài khoản của bạn. Vui lòng nhấp vào liên kết bên dưới để tạo mật khẩu mới. Liên kết này sẽ hết hạn sau 1 giờ.</p>
                <p><a href='" . $resetLink . "' style='background-color:#007bff; color:white; padding:10px 20px; text-decoration:none; border-radius:5px;'>Đặt lại mật khẩu</a></p>
                <p>Nếu bạn không yêu cầu đặt lại mật khẩu, vui lòng bỏ qua email này.</p>
            ";

            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Mailer Error: {$mail->ErrorInfo}");
            return false;
        }
    }
}
