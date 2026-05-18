<?php
require_once('db.php');

class AnnouncementModel extends db {
    
    // নতুন অ্যানাউন্সমেন্ট পোস্ট করা
    public function postAnnouncement($title, $content) {
        $conn = $this->openConn();
        $stmt = $conn->prepare("INSERT INTO announcements (title, content) VALUES (?, ?)");
        $stmt->bind_param("ss", $title, $content);
        return $stmt->execute();
    }

    // সব অ্যানাউন্সমেন্ট দেখা
    public function getAllAnnouncements() {
        $conn = $this->openConn();
        return $conn->query("SELECT * FROM announcements ORDER BY created_at DESC");
    }

    // ডিলিট করা
    public function deleteAnnouncement($id) {
        $conn = $this->openConn();
        $stmt = $conn->prepare("DELETE FROM announcements WHERE announcement_id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>

