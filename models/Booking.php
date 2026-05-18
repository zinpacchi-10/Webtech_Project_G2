<?php
class Booking {
    private $conn;
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    public function getAllBookings($status = null, $start_date = null, $end_date = null) {
        $sql = "SELECT b.*, u.name as guest_name, r.room_number, r.room_type 
                FROM bookings b
                JOIN users u ON b.guest_id = u.user_id
                JOIN rooms r ON b.room_id = r.room_id
                WHERE 1=1";
        
        if($status) {
            $sql .= " AND b.status = '$status'";
        }
        if($start_date && $end_date) {
            $sql .= " AND b.checkin_date BETWEEN '$start_date' AND '$end_date'";
        }
        
        $sql .= " ORDER BY b.checkin_date DESC";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    public function getTotalRevenue($start_date = null, $end_date = null) {
        $sql = "SELECT SUM(total_price) as total FROM bookings WHERE status != 'cancelled'";
        if($start_date && $end_date) {
            $sql .= " AND checkin_date BETWEEN '$start_date' AND '$end_date'";
        }
        $result = $this->conn->query($sql);
        $data = $result->fetch_assoc();
        return $data['total'] ?? 0;
    }
    
    public function getOccupancyRate() {
        $sql = "SELECT 
                COUNT(DISTINCT CASE WHEN status = 'checked_in' THEN room_id END) as occupied,
                COUNT(DISTINCT room_id) as total_booked
                FROM bookings WHERE status IN ('confirmed', 'checked_in')";
        $result = $this->conn->query($sql);
        $data = $result->fetch_assoc();
        
        $roomModel = new Room($this->conn);
        $roomStats = $roomModel->getStats();
        
        if($roomStats['total_rooms'] > 0) {
            return ($data['occupied'] / $roomStats['total_rooms']) * 100;
        }
        return 0;
    }
}
?>