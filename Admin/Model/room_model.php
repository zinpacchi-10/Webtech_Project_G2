<?php
require_once('db.php');

class RoomModel extends db {
    
    // নতুন রুম যোগ করা
    public function addRoom($data) {
        $conn = $this->openConn();
        $sql = "INSERT INTO rooms (room_number, price, capacity, floor, thumbnail, amidity, room_description, notes) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sidiiis s", $data['num'], $data['price'], $data['cap'], $data['floor'], $data['img'], $data['amid'], $data['desc'], $data['notes']);
        return $stmt->execute();
    }

    // সব রুমের লিস্ট আনা
    public function getAllRooms() {
        $conn = $this->openConn();
        return $conn->query("SELECT * FROM rooms ORDER BY room_id DESC");
    }

    // নির্দিষ্ট রুমের ডাটা আনা (এডিট করার জন্য)
    public function getRoomById($id) {
        $conn = $this->openConn();
        $stmt = $conn->prepare("SELECT * FROM rooms WHERE room_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // রুম আপডেট করা
    public function updateRoom($id, $data) {
        $conn = $this->openConn();
        $sql = "UPDATE rooms SET room_number=?, price=?, capacity=?, floor=?, thumbnail=?, amidity=?, room_description=?, notes=? WHERE room_id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sidiiis ss i", $data['num'], $data['price'], $data['cap'], $data['floor'], $data['img'], $data['amid'], $data['desc'], $data['notes'], $id);
        return $stmt->execute();
    }

    // রুম ডিলিট করা
    public function deleteRoom($id) {
        $conn = $this->openConn();
        $stmt = $conn->prepare("DELETE FROM rooms WHERE room_id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>