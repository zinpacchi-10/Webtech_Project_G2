<?php
require_once('db.php');

class ReviewModel extends db {
    
    // সব রিভিউ আনা (JOIN ব্যবহার করে গেস্টের নাম এবং রুম নম্বর আনা হয়েছে)
    public function getAllReviews() {
        $conn = $this->openConn();
        $sql = "SELECT rv.review_id, rv.rating, rv.comment, rv.review_date, rv.admin_reply, 
                u.name as guest_name, r.room_number 
                FROM reviews rv, users u, rooms r 
                WHERE rv.user_id = u.user_id AND rv.room_id = r.room_id 
                ORDER BY rv.review_date DESC";
        return $conn->query($sql);
    }

    // অ্যাডমিনের রিপ্লাই সেভ করা
    public function addReply($review_id, $reply) {
        $conn = $this->openConn();
        $stmt = $conn->prepare("UPDATE reviews SET admin_reply = ? WHERE review_id = ?");
        $stmt->bind_param("si", $reply, $review_id);
        return $stmt->execute();
    }
}
?>
