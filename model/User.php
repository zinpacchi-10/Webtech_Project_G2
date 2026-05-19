<?php
// models/User.php
require_once __DIR__ . '/../config/database.php';

class User {
    private $conn;
    private $table = "users";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function login($email, $password) {
        $query = "SELECT * FROM " . $this->table . " WHERE email = ? AND role = 'housekeeping'";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            // PLAIN TEXT password comparison (temporary fix)
            if ($password === $user['password_hash']) {
                return $user;
            }
        }
        return false;
    }

    public function getHousekeepingById($id) {
        $query = "SELECT user_id, name, email, phone, role, created_at 
                  FROM " . $this->table . " 
                  WHERE user_id = ? AND role = 'housekeeping'";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function updateProfile($user_id, $name, $phone) {
        $query = "UPDATE " . $this->table . " SET name = ?, phone = ? WHERE user_id = ? AND role = 'housekeeping'";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ssi", $name, $phone, $user_id);
        return $stmt->execute();
    }

    public function getStaffList() {
        $query = "SELECT user_id, name, email, phone, role FROM " . $this->table . " 
                  WHERE role = 'housekeeping' ORDER BY name";
        $result = $this->conn->query($query);
        if ($result) {
            return $result->fetch_all(MYSQLI_ASSOC);
        }
        return [];
    }
}
?>