<?php
class Room {
    private $conn;
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    public function getAllRooms() {
        $sql = "SELECT * FROM rooms ORDER BY room_number";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    public function getRoomById($room_id) {
        $sql = "SELECT * FROM rooms WHERE room_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $room_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
    
    public function addRoom($room_number, $room_type, $price, $capacity, $floor, $description) {
        $sql = "INSERT INTO rooms (room_number, room_type, price, capacity, floor, room_description) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssiiis", $room_number, $room_type, $price, $capacity, $floor, $description);
        return $stmt->execute();
    }
    
    public function updateRoom($room_id, $room_number, $room_type, $price, $capacity, $floor, $description, $status) {
        $sql = "UPDATE rooms SET room_number=?, room_type=?, price=?, capacity=?, floor=?, room_description=?, status=? WHERE room_id=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssiiissi", $room_number, $room_type, $price, $capacity, $floor, $description, $status, $room_id);
        return $stmt->execute();
    }
    
    public function deleteRoom($room_id) {
        $sql = "DELETE FROM rooms WHERE room_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $room_id);
        return $stmt->execute();
    }
    
    public function updateRoomStatus($room_id, $status) {
        $sql = "UPDATE rooms SET status = ? WHERE room_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("si", $status, $room_id);
        return $stmt->execute();
    }
    
    public function getStats() {
        $sql = "SELECT 
                COUNT(*) as total_rooms,
                SUM(CASE WHEN status = 'available' THEN 1 ELSE 0 END) as available_rooms,
                SUM(CASE WHEN status = 'occupied' THEN 1 ELSE 0 END) as occupied_rooms
                FROM rooms";
        $result = $this->conn->query($sql);
        return $result->fetch_assoc();
    }
}
?>