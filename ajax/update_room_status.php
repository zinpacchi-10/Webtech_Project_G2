<?php
session_start();
header('Content-Type: application/json');

// Check if admin is logged in
if(!isset($_SESSION['admin_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit();
}

require_once('../config/database.php');
require_once('../models/Room.php');

// Check if request is AJAX
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $database = new Database();
    $db = $database->getConnection();
    $roomModel = new Room($db);
    
    $room_id = $_POST['room_id'] ?? 0;
    $status = $_POST['status'] ?? '';
    
    // Validate status
    $allowed_status = ['available', 'occupied', 'maintenance', 'dirty'];
    
    if(!$room_id || !in_array($status, $allowed_status)) {
        echo json_encode(['success' => false, 'message' => 'Invalid data']);
        exit();
    }
    
    // Update room status
    $result = $roomModel->updateRoomStatus($room_id, $status);
    
    if($result) {
        echo json_encode(['success' => true, 'message' => 'Room status updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update room status']);
    }
    exit();
}

// For GET request - fetch room status
if($_SERVER['REQUEST_METHOD'] == 'GET') {
    $database = new Database();
    $db = $database->getConnection();
    $roomModel = new Room($db);
    
    $rooms = $roomModel->getAllRooms();
    
    $roomData = [];
    foreach($rooms as $room) {
        $roomData[] = [
            'room_id' => $room['room_id'],
            'room_number' => $room['room_number'],
            'room_type' => $room['room_type'],
            'status' => $room['status'],
            'price' => $room['price']
        ];
    }
    
    echo json_encode(['success' => true, 'rooms' => $roomData]);
    exit();
}
?>ajax