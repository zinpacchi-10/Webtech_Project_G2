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

    // Get room_id
    $sql = "SELECT room_id FROM bookings WHERE booking_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $booking_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $booking = $result->fetch_assoc();

    if ($booking) {
        $room_id = $booking["room_id"];

        // Update booking notes & room notes
        $update_booking = "UPDATE bookings SET notes='checked_out' WHERE booking_id=?";
        $stmt = $conn->prepare($update_booking);
        $stmt->bind_param("i", $booking_id);
        $stmt->execute();

        $update_room = "UPDATE rooms SET notes='dirty' WHERE room_id=?";
        $stmt = $conn->prepare($update_room);
        $stmt->bind_param("i", $room_id);
        $stmt->execute();

        $stmt->close();
        $db->closeConn($conn);

        $_SESSION["checkout_success"] = "Guest checked out successfully!";
        header("Location: ../View/checkout.php");
        exit();
    } else {
        $_SESSION["checkout_error"] = "Booking not found!";
        header("Location: ../View/checkout.php");
        exit();
    }
} else {
    header("Location: ../View/checkout.php");
    exit();
}
?>