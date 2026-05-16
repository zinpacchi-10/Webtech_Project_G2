<?php
session_start();
require_once("../Model/db.php");

if (!isset($_SESSION["receptionist_logged_in"])) {
    header("Location: receptionistlogin.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["booking_id"])) {
    $booking_id = intval($_POST["booking_id"]);

    $db = new db();
    $conn = $db->openConn();

    // Get room_id for this booking
    $sql = "SELECT room_id FROM bookings WHERE booking_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $booking_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $booking = $result->fetch_assoc();

    if ($booking) {
        $room_id = $booking["room_id"];

        // Update booking status and room notes/status
        $update_booking = "UPDATE bookings SET notes='checked_in' WHERE booking_id=?";
        $stmt = $conn->prepare($update_booking);
        $stmt->bind_param("i", $booking_id);
        $stmt->execute();

        $update_room = "UPDATE rooms SET notes='occupied' WHERE room_id=?";
        $stmt = $conn->prepare($update_room);
        $stmt->bind_param("i", $room_id);
        $stmt->execute();

        $stmt->close();
        $db->closeConn($conn);

        $_SESSION["checkin_success"] = "Guest checked in successfully!";
        header("Location: ../View/checkin.php");
        exit();
    } else {
        $_SESSION["checkin_error"] = "Booking not found!";
        header("Location: ../View/checkin.php");
        exit();
    }
} else {
    header("Location: ../View/checkin.php");
    exit();
}
?>