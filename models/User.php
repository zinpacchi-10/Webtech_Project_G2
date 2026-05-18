<?php
class User {
    private $conn;
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    public function login($email, $password) {
        $password_hash = md5($password);
        $sql = "SELECT * FROM users WHERE email = ? AND password_hash = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ss", $email, $password_hash);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
    
    public function getAllUsers($role = null) {
        if($role) {
            $sql = "SELECT * FROM users WHERE role = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("s", $role);
        } else {
            $sql = "SELECT * FROM users";
            $stmt = $this->conn->prepare($sql);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    public function deactivateUser($user_id) {
        $sql = "DELETE FROM users WHERE user_id = ? AND role != 'admin'";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        return $stmt->execute();
    }
    
    public function createStaff($name, $email, $password, $phone, $id_number, $role) {
        $password_hash = md5($password);
        $sql = "INSERT INTO users (name, email, password_hash, phone, id_number, role) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssssss", $name, $email, $password_hash, $phone, $id_number, $role);
        return $stmt->execute();
    }
}
?>