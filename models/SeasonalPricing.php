<?php
class SeasonalPricing {
    private $conn;
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    public function getAllPricing() {
        $sql = "SELECT * FROM seasonal_pricing ORDER BY start_date DESC";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    public function addPricing($room_type, $label, $start_date, $end_date, $price) {
        $sql = "INSERT INTO seasonal_pricing (room_type, label, start_date, end_date, price_per_night) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssssi", $room_type, $label, $start_date, $end_date, $price);
        return $stmt->execute();
    }
    
    public function deletePricing($id) {
        $sql = "DELETE FROM seasonal_pricing WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>