<?php
// models/Room.php
require_once __DIR__ . '/../config/database.php';

class Room {
    private $conn;
    private $table = "rooms";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getAllRooms() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY CAST(room_number AS UNSIGNED)";
        $result = $this->conn->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getRoomById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE room_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function getRoomsByStatus($status) {
        $query = "SELECT * FROM " . $this->table . " WHERE status = ? ORDER BY room_number";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $status);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function updateRoomStatus($room_id, $status) {
        $query = "UPDATE " . $this->table . " SET status = ? WHERE room_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("si", $status, $room_id);
        return $stmt->execute();
    }

    public function getRoomStats() {
        $query = "SELECT 
                    SUM(CASE WHEN status = 'available' THEN 1 ELSE 0 END) as available,
                    SUM(CASE WHEN status = 'occupied' THEN 1 ELSE 0 END) as occupied,
                    SUM(CASE WHEN status = 'dirty' THEN 1 ELSE 0 END) as dirty,
                    SUM(CASE WHEN status = 'cleaning' THEN 1 ELSE 0 END) as cleaning,
                    SUM(CASE WHEN status = 'inspection' THEN 1 ELSE 0 END) as inspection,
                    SUM(CASE WHEN status = 'maintenance' THEN 1 ELSE 0 END) as maintenance,
                    COUNT(*) as total
                  FROM " . $this->table;
        $result = $this->conn->query($query);
        return $result->fetch_assoc();
    }

    public function getDirtyRooms() {
        return $this->getRoomsByStatus('dirty');
    }

    public function getCleaningRooms() {
        return $this->getRoomsByStatus('cleaning');
    }

    public function getMaintenanceRooms() {
        return $this->getRoomsByStatus('maintenance');
    }
}
?>