<?php
session_start();
require_once("../Model/db.php");

if (!isset($_SESSION["receptionist_logged_in"])) {
    echo json_encode(["error" => "Unauthorized"]);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["query"])) {
    $query = "%" . $_GET["query"] . "%";

    $db = new db();
    $conn = $db->openConn();

    $sql = "SELECT b.booking_id, u.name AS guest_name, r.room_number, b.checkin_date, b.checkout_date, b.num_guests
            FROM bookings b
            JOIN users u ON b.guest_id = u.user_id
            JOIN rooms r ON b.room_id = r.room_id
            WHERE u.name LIKE ? OR r.room_number LIKE ? OR b.booking_id LIKE ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $query, $query, $query);
    $stmt->execute();
    $result = $stmt->get_result();

    $bookings = [];
    while($row = $result->fetch_assoc()) {
        $bookings[] = $row;
    }

    $stmt->close();
    $db->closeConn($conn);

    echo json_encode($bookings);
} else {
    echo json_encode(["error" => "Invalid request"]);
}
?>