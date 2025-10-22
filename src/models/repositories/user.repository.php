<?php

class UserRepository
{
    private $conn;
    private $table = "users";

    public function __construct($conn)
    {
        $this->conn = $conn;
    }
    /**
     * Tìm kiếm email đã tồn tại
     * @param string $email
     */
    function findByEmail($email)
    {
        $query = "SELECT id FROM " . $this->table . " WHERE email=:email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            return [
                'message' => "Email đã tồn tại",
                'status' => true
            ];
        }
        return false;
    }
    /**
     * Tìm kiếm email
     * @param string $email
     */
    function findUserByEmail($email)
    {
        // Lấy tất cả các cột
        $query = "SELECT * FROM " . $this->table . " WHERE email=:email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            // Trả về một đối tượng User hoàn chỉnh
            $stmt->setFetchMode(PDO::FETCH_CLASS, 'User');
            return $stmt->fetch();
        }
        return false;
    }
    /**
     * Lưu user
     * @param User $user
     */
    function save(User $user)
    {
        $query = "INSERT INTO " . $this->table . "(fullname,email,password,role_id,created_at) VALUES (:fullname,:email,:password,:role_id,:created_at)";
        $stmt = $this->conn->prepare($query);

        //clean data
        $user->fullname = htmlspecialchars($user->fullname);
        $user->email = htmlspecialchars($user->email);
        $user->password = $user->password;
        $user->role_id = 2;
        $user->created_at = date('Y-m-d H:i:s');
        //
        $stmt->bindParam(":fullname", $user->fullname);
        $stmt->bindParam(":email", $user->email);
        $stmt->bindParam(":password", $user->password);
        $stmt->bindParam(":role_id", $user->role_id);
        $stmt->bindParam(":created_at", $user->created_at);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    /**
     * Lấy tất cả các user
     *
     */
    public function findAll()
    {
        $query = "SELECT u.id, u.fullname, u.email, u.role_id, u.is_active, r.name as role_name
                  FROM " . $this->table . " u
                  JOIN roles r ON u.role_id = r.id
                  ORDER BY u.created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    /**
     * Lấy user theo id
     * @param int $id
     */
    public function findById($id)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':id' => $id]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'User');
        return $stmt->fetch();
    }
    /**
     * Cập nhật user
     * @param User $user
     */
    public function update(User $user)
    {
        if (property_exists($user, 'password') && !empty($user->password) && strlen($user->password) < 60) {
            $user->password = password_hash($user->password, PASSWORD_DEFAULT);
        }
        // Chỉ thêm password vào câu query nếu có
        $query = "UPDATE " . $this->table . "
                  SET fullname = :fullname, email = :email, role_id = :role_id, is_active = :is_active"
            . (!empty($user->password) ? ", password = :password" : "") .
            " WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $params = [
            ':id' => $user->id,
            ':fullname' => $user->fullname,
            ':email' => $user->email,
            ':role_id' => $user->role_id,
            ':is_active' => $user->is_active
        ];

        if (!empty($user->password)) {
            $params[':password'] = $user->password;
        }

        return $stmt->execute($params);
    }
    /**
     * Lưu token reset password
     * @param int $userId
     * @param string $token
     * @param string $expiresAt
     */
    public function saveResetToken($userId, $token, $expiresAt)
    {
        $query = "UPDATE " . $this->table . " SET reset_token = :token, reset_token_expires_at = :expires_at WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":token", $token);
        $stmt->bindParam(":expires_at", date('Y-m-d H:i:s', strtotime($expiresAt)));
        $stmt->bindParam(":id", $userId);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    /**
     * Tìm kiếm user theo token reset password
     * @param string $token
     */
    public function findByResetToken($token)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE reset_token = :token AND reset_token_expires_at > NOW() LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":token", $token);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $stmt->setFetchMode(PDO::FETCH_CLASS, 'User');
            return $stmt->fetch();
        }
        return false;
    }

    /**
     * Reset password
     * @param int $userId
     * @param string $newPassword
     */
    public function resetPassword($userId, $newPassword)
    {
        $query = "UPDATE " . $this->table . " SET password = :password, reset_token = NULL, reset_token_expires_at = NULL WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt->bindParam(":password", $hashedPassword);
        $stmt->bindParam(":id", $userId);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
