<?php

class UserRepository
{
    private $conn;
    private $table = "users";

    public function __construct($conn)
    {
        $this->conn = $conn;
    }
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
    function findUserByEmail($email)
    {
        $query = "SELECT email,password FROM " . $this->table . " WHERE email=:email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            return $stmt->fetch(PDO::FETCH_OBJ);
        }
        return false;
    }
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
}
