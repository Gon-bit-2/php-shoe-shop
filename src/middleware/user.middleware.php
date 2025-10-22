<?php
class UserMiddleware
{
    public function validateUserEditBody($data)
    {
        $fullname = trim($data['fullname'] ?? '');
        $email = trim($data['email'] ?? '');
        $roleId = $data['role_id'] ?? '';

        // Kiểm tra họ tên
        if (empty($fullname)) {
            return 'Họ và tên không được để trống!';
        }
        if (strlen($fullname) < 2) {
            return 'Họ và tên phải có ít nhất 2 ký tự!';
        }
        if (strlen($fullname) > 100) {
            return 'Họ và tên không được quá 100 ký tự!';
        }

        // Kiểm tra email
        if (empty($email)) {
            return 'Email không được để trống!';
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return 'Email không đúng định dạng!';
        }

        // Kiểm tra role_id
        if (empty($roleId)) {
            return 'Vui lòng chọn vai trò!';
        }
        if (!in_array($roleId, ['1', '2', 1, 2])) {
            return 'Vai trò không hợp lệ!';
        }

        return false; // Không có lỗi
    }

    public function validateProfileUpdateBody($data)
    {
        $fullname = trim($data['fullname'] ?? '');
        $newPassword = trim($data['new_password'] ?? '');
        $currentPassword = trim($data['current_password'] ?? '');
        $confirmPassword = trim($data['confirm_password'] ?? '');

        //check họ tên
        if (empty($fullname)) {
            return 'Họ và tên không được để trống!';
        }
        if (strlen($fullname) < 2) {
            return 'Họ và tên phải có ít nhất 2 ký tự!';
        }
        if (strlen($fullname) > 100) {
            return 'Họ và tên không được quá 100 ký tự!';
        }

        //check mật khẩu
        if (!empty($newPassword)) {
            if (empty($currentPassword)) {
                return 'Vui lòng nhập mật khẩu hiện tại!';
            }
            if (strlen(str_replace(' ', '', $newPassword)) < 6) {
                return 'Mật khẩu mới phải có ít nhất 6 ký tự!';
            }
            if ($newPassword !== $confirmPassword) {
                return 'Mật khẩu xác nhận không khớp!';
            }
        }

        return false;
    }
}
