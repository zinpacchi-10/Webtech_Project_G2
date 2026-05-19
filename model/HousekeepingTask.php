<?php
// models/HousekeepingTask.php
require_once __DIR__ . '/../config/database.php';

class HousekeepingTask {
    private $conn;
    private $table = "housekeeping_tasks";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getAllTasks($status = null) {
        $query = "SELECT t.*, r.room_number, r.room_type, u.name as assigned_name
                  FROM " . $this->table . " t
                  JOIN rooms r ON t.room_id = r.room_id
                  LEFT JOIN users u ON t.assigned_to = u.user_id";
        
        if ($status) {
            $query .= " WHERE t.status = '" . $status . "'";
        }
        
        $query .= " ORDER BY 
                    CASE t.priority 
                      WHEN 'emergency' THEN 1 
                      WHEN 'urgent' THEN 2 
                      WHEN 'normal' THEN 3 
                    END,
                    t.scheduled_date ASC";
        
        $result = $this->conn->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getTodayTasks() {
        $query = "SELECT t.*, r.room_number, r.room_type, u.name as assigned_name
                  FROM " . $this->table . " t
                  JOIN rooms r ON t.room_id = r.room_id
                  LEFT JOIN users u ON t.assigned_to = u.user_id
                  WHERE t.scheduled_date = CURDATE()
                  ORDER BY 
                    CASE t.priority 
                      WHEN 'emergency' THEN 1 
                      WHEN 'urgent' THEN 2 
                      WHEN 'normal' THEN 3 
                    END";
        $result = $this->conn->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getPendingTasks() {
        return $this->getAllTasks('pending');
    }

    public function getInProgressTasks() {
        return $this->getAllTasks('in_progress');
    }

    public function getTaskById($id) {
        $query = "SELECT t.*, r.room_number, r.room_type 
                  FROM " . $this->table . " t
                  JOIN rooms r ON t.room_id = r.room_id
                  WHERE t.task_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function createTask($room_id, $task_type, $priority, $scheduled_date, $notes = null, $assigned_to = null) {
        $query = "INSERT INTO " . $this->table . " 
                  (room_id, assigned_to, task_type, priority, status, notes, scheduled_date) 
                  VALUES (?, ?, ?, ?, 'pending', ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("iissss", $room_id, $assigned_to, $task_type, $priority, $notes, $scheduled_date);
        
        if ($stmt->execute()) {
            $task_id = $this->conn->insert_id;
            // Update room status to cleaning if task is cleaning
            if ($task_type == 'cleaning' || $task_type == 'deep_cleaning') {
                $roomModel = new Room();
                $roomModel->updateRoomStatus($room_id, 'cleaning');
            }
            return $task_id;
        }
        return false;
    }

    public function updateTaskStatus($task_id, $status, $completed_at = null) {
        $query = "UPDATE " . $this->table . " SET status = ?";
        if ($status == 'completed') {
            $query .= ", completed_at = NOW()";
        }
        $query .= " WHERE task_id = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("si", $status, $task_id);
        return $stmt->execute();
    }

    public function assignTask($task_id, $assigned_to) {
        $query = "UPDATE " . $this->table . " SET assigned_to = ? WHERE task_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $assigned_to, $task_id);
        return $stmt->execute();
    }

    public function getTaskStats() {
        $query = "SELECT 
                    SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
                    SUM(CASE WHEN status = 'in_progress' THEN 1 ELSE 0 END) as in_progress,
                    SUM(CASE WHEN status = 'completed' AND DATE(completed_at) = CURDATE() THEN 1 ELSE 0 END) as completed_today,
                    COUNT(*) as total
                  FROM " . $this->table;
        $result = $this->conn->query($query);
        return $result->fetch_assoc();
    }

    public function getTasksByPriority() {
        $query = "SELECT 
                    SUM(CASE WHEN priority = 'emergency' AND status != 'completed' THEN 1 ELSE 0 END) as emergency,
                    SUM(CASE WHEN priority = 'urgent' AND status != 'completed' THEN 1 ELSE 0 END) as urgent,
                    SUM(CASE WHEN priority = 'normal' AND status != 'completed' THEN 1 ELSE 0 END) as normal
                  FROM " . $this->table;
        $result = $this->conn->query($query);
        return $result->fetch_assoc();
    }
}
?>