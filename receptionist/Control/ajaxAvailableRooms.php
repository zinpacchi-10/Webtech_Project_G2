<?php
session_start();
require_once("../Model/db.php");

if (!isset($_SESSION["receptionist_logged_in"])) {
    echo json_encode(["error" => "Unauthorized"]);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["checkin_date"], $_GET["checkout_date"], $_GET["room_type"])) {
    $checkin_date = $_GET["checkin_date"];
    $checkout_date = $_GET["checkout_date"];
    $room_type = $_GET["room_type"];

    $db = new db();
    $conn = $db->openConn();

    $sql = "SELECT room_id, room_number 
            FROM rooms r
            WHERE r.room_type = ? AND r.notes = 'available' AND r.room_id NOT IN (
                SELECT b.room_id FROM bookings b 
                WHERE (b.checkin_date BETWEEN ? AND ?) 
                   OR (b.checkout_date BETWEEN ? AND ?)
            )";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $room_type, $checkin_date, $checkout_date, $checkin_date, $checkout_date);
    $stmt->execute();
    $result = $stmt->get_result();

    $rooms = [];
    while ($row = $result->fetch_assoc()) {
        $rooms[] = $row;
    }

    $stmt->close();
    $db->closeConn($conn);

    echo json_encode($rooms);
} else {
    echo json_encode(["error" => "Invalid request"]);
}
?>