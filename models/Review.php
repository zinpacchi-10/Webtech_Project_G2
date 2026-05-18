<?php
class Review {
    private $conn;
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    public function getAllReviews() {
        $sql = "SELECT r.*, u.name as guest_name 
                FROM reviews r
                JOIN users u ON r.guest_id = u.user_id
                ORDER BY r.created_at DESC";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    
    public function getPendingReviews() {
        $sql = "SELECT COUNT(*) as pending FROM reviews WHERE admin_reply IS NULL OR admin_reply = ''";
        $result = $this->conn->query($sql);
        $data = $result->fetch_assoc();
        return $data['pending'];
    }
    
    public function addReply($review_id, $reply) {
        $sql = "UPDATE reviews SET admin_reply = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("si", $reply, $review_id);
        return $stmt->execute();
    }
    
    public function getAverageRatings() {
        $sql = "SELECT 
                AVG(overall_rating) as overall,
                AVG(cleanliness_rating) as cleanliness,
                AVG(service_rating) as service
                FROM reviews";
        $result = $this->conn->query($sql);
        return $result->fetch_assoc();
    }
}
?>