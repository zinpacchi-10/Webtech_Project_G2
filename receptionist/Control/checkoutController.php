<?php
session_start();
require_once("../Model/db.php");

if (!isset($_SESSION["receptionist_logged_in"])) {
    header("Location: ../View/receptionistlogin.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["booking_id"], $_POST["charges_settled"])) {
    $booking_id = intval($_POST["booking_id"]);
    $today = date("Y-m-d");

    $db = new db();
    $conn = $db->openConn();

    $sql = "SELECT b.room_id, bl.payment_status
            FROM bookings b
            LEFT JOIN billing bl ON b.booking_id = bl.booking_id
            WHERE b.booking_id = ? AND b.bookin_date IS NOT NULL AND b.bookout_date IS NULL
            LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $booking_id);
    $stmt->execute();
    $booking = $stmt->get_result()->fetch_assoc();

    if (!$booking) {
        $_SESSION["checkout_error"] = "Active checked-in booking not found!";
        header("Location: ../View/checkout.php");
        exit();
    }

    if ($booking["payment_status"] != "paid") {
        $_SESSION["checkout_error"] = "Please settle the guest bill before checkout.";
        header("Location: ../View/checkout.php");
        exit();
    }

    $update_booking = "UPDATE bookings SET bookout_date = ? WHERE booking_id = ?";
    $stmt_update = $conn->prepare($update_booking);
    $stmt_update->bind_param("si", $today, $booking_id);
    $stmt_update->execute();

    $update_room = "UPDATE rooms SET notes='dirty' WHERE room_id=?";
    $stmt_room = $conn->prepare($update_room);
    $stmt_room->bind_param("i", $booking["room_id"]);
    $stmt_room->execute();

    $stmt->close();
    $stmt_update->close();
    $stmt_room->close();
    $db->closeConn($conn);

    $_SESSION["checkout_success"] = "Guest checked out successfully. Room marked dirty for housekeeping.";
    header("Location: ../View/checkout.php");
    exit();
} else {
    $_SESSION["checkout_error"] = "Please confirm all charges are settled.";
    header("Location: ../View/checkout.php");
    exit();
}
?>
