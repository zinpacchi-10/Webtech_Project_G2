<?php
require_once('db.php');

class BookingModel extends db {
    
    // সব বুকিংয়ের বিস্তারিত লিস্ট (JOIN query ব্যবহার করে)
    public function getAllBookingDetails() {
        $conn = $this->openConn();
        // bookings, users এবং rooms টেবিলকে একসাথে যুক্ত করা হয়েছে
        $sql = "SELECT b.booking_id, b.checkin_date, b.checkout_date, b.total_price, 
                u.name as guest_name, u.email as guest_email, 
                r.room_number, r.room_type 
                FROM bookings b, users u, rooms r 
                WHERE b.guest_id = u.user_id AND b.room_id = r.room_id 
                ORDER BY b.created_at DESC";
        return $conn->query($sql);
    }

    // মোট আয় হিসাব করা
    public function getTotalRevenue() {
        $conn = $this->openConn();
        $result = $conn->query("SELECT SUM(total_price) as total FROM bookings");
        $row = $result->fetch_assoc();
        return $row['total'] ?? 0;
    }

    // বর্তমানে কতজন গেস্ট আছে (Active Bookings)
    public function getActiveBookingsCount() {
        $conn = $this->openConn();
        $today = date('Y-m-d');
        $sql = "SELECT COUNT(*) as count FROM bookings WHERE checkout_date >= '$today'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        return $row['count'] ?? 0;
    }

    // নির্দিষ্ট বুকিং ডিলিট করা
    public function deleteBooking($id) {
        $conn = $this->openConn();
        $stmt = $conn->prepare("DELETE FROM bookings WHERE booking_id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>