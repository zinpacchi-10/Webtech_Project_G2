<?php
// models/Notification.php
require_once __DIR__ . '/../config/database.php';

class Notification {
    private $conn;
    private $table = "notifications";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getUnread($user_id) {
        // Check if table exists first
        $checkTable = "SHOW TABLES LIKE 'notifications'";
        $tableExists = $this->conn->query($checkTable);
        
        if ($tableExists->num_rows == 0) {
            return []; // Table doesn't exist yet
        }
        
        $query = "SELECT * FROM " . $this->table . " 
                  WHERE user_id = ? AND is_read = 0 
                  ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            return [];
        }
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getAll($user_id, $limit = 20) {
        $checkTable = "SHOW TABLES LIKE 'notifications'";
        $tableExists = $this->conn->query($checkTable);
        
        if ($tableExists->num_rows == 0) {
            return [];
        }
        
        $query = "SELECT * FROM " . $this->table . " 
                  WHERE user_id = ? 
                  ORDER BY created_at DESC 
                  LIMIT ?";
        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            return [];
        }
        $stmt->bind_param("ii", $user_id, $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function markAsRead($notification_id) {
        $checkTable = "SHOW TABLES LIKE 'notifications'";
        $tableExists = $this->conn->query($checkTable);
        
        if ($tableExists->num_rows == 0) {
            return false;
        }
        
        $query = "UPDATE " . $this->table . " SET is_read = 1 WHERE notification_id = ?";
        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            return false;
        }
        $stmt->bind_param("i", $notification_id);
        return $stmt->execute();
    }

    public function create($user_id, $title, $message, $type = 'general') {
        $checkTable = "SHOW TABLES LIKE 'notifications'";
        $tableExists = $this->conn->query($checkTable);
        
        if ($tableExists->num_rows == 0) {
            return false;
        }
        
        $query = "INSERT INTO " . $this->table . " (user_id, title, message, type) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            return false;
        }
        $stmt->bind_param("isss", $user_id, $title, $message, $type);
        return $stmt->execute();
    }

    public function getUnreadCount($user_id) {
        $checkTable = "SHOW TABLES LIKE 'notifications'";
        $tableExists = $this->conn->query($checkTable);
        
        if ($tableExists->num_rows == 0) {
            return 0;
        }
        
        $query = "SELECT COUNT(*) as count FROM " . $this->table . " 
                  WHERE user_id = ? AND is_read = 0";
        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            return 0;
        }
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['count'] ?? 0;
    }
}
?>