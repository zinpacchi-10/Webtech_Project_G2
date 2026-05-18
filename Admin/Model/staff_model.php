<?php
require_once('db.php');

class StaffModel extends db {
    
    // নতুন স্টাফ যোগ করা
    public function addStaff($data) {
        $conn = $this->openConn();
        $sql = "INSERT INTO users (name, email, password_hash, phone, nationality, id_number, role) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssss", $data['name'], $data['email'], $data['pass'], $data['phone'], $data['nat'], $data['id'], $data['role']);
        return $stmt->execute();
    }

    // সব স্টাফদের লিস্ট আনা (Guest বাদে সবাই)
    public function getAllStaff() {
        $conn = $this->openConn();
        // শুধুমাত্র যাদের রোল 'guest' নয় তাদের নিয়ে আসা হবে
        return $conn->query("SELECT * FROM users WHERE role != 'guest' ORDER BY user_id DESC");
    }

    // নির্দিষ্ট স্টাফের ডাটা আনা (এডিট করার জন্য)
    public function getStaffById($id) {
        $conn = $this->openConn();
        $stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // স্টাফ আপডেট করা
    public function updateStaff($id, $data) {
        $conn = $this->openConn();
        $sql = "UPDATE users SET name=?, email=?, password_hash=?, phone=?, nationality=?, id_number=?, role=? WHERE user_id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssssi", $data['name'], $data['email'], $data['pass'], $data['phone'], $data['nat'], $data['id'], $data['role'], $id);
        return $stmt->execute();
    }

    // স্টাফ ডিলিট করা
    public function deleteStaff($id) {
        $conn = $this->openConn();
        $stmt = $conn->prepare("DELETE FROM users WHERE user_id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>