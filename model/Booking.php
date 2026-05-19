<?php
// models/Booking.php
require_once __DIR__ . '/../config/database.php';

class Booking {
    private $conn;
    private $table = "bookings";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getTodayCheckouts() {
        $query = "SELECT b.*, u.name as guest_name, r.room_number, r.room_type
                  FROM " . $this->table . " b
                  JOIN users u ON b.guest_id = u.user_id
                  JOIN rooms r ON b.room_id = r.room_id
                  WHERE b.checkout_date = CURDATE() 
                  AND b.status = 'checked_in'
                  ORDER BY r.room_number ASC";
        $result = $this->conn->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getTomorrowCheckouts() {
        $query = "SELECT b.*, u.name as guest_name, r.room_number, r.room_type
                  FROM " . $this->table . " b
                  JOIN users u ON b.guest_id = u.user_id
                  JOIN rooms r ON b.room_id = r.room_id
                  WHERE b.checkout_date = DATE_ADD(CURDATE(), INTERVAL 1 DAY)
                  AND b.status = 'checked_in'
                  ORDER BY r.room_number ASC";
        $result = $this->conn->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getTodayCheckins() {
        $query = "SELECT b.*, u.name as guest_name, r.room_number, r.room_type
                  FROM " . $this->table . " b
                  JOIN users u ON b.guest_id = u.user_id
                  JOIN rooms r ON b.room_id = r.room_id
                  WHERE b.checkin_date = CURDATE() 
                  AND b.status = 'confirmed'
                  ORDER BY r.room_number ASC";
        $result = $this->conn->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>