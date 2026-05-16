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

    // Update booking notes to "paid" and room to "available"
    $update_booking = "UPDATE bookings SET notes='paid' WHERE booking_id=?";
    $stmt = $conn->prepare($update_booking);
    $stmt->bind_param("i", $booking_id);
    $stmt->execute();

    // Optionally update room notes if checkout is done
    $update_room = "UPDATE rooms r
                    JOIN bookings b ON r.room_id = b.room_id
                    SET r.notes='available'
                    WHERE b.booking_id=?";
    $stmt2 = $conn->prepare($update_room);
    $stmt2->bind_param("i", $booking_id);
    $stmt2->execute();

    $stmt->close();
    $stmt2->close();
    $db->closeConn($conn);

    $_SESSION['payment_success'] = "Payment processed successfully!";
    header("Location: ../View/paymentlist.php");
    exit();
} else {
    $_SESSION['payment_error'] = "Invalid payment request!";
    header("Location: ../View/paymentlist.php");
    exit();
}
?>