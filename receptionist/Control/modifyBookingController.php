<?php
session_start();
require_once("../Model/db.php");

if (!isset($_SESSION["receptionist_logged_in"])) {
    header("Location: receptionistlogin.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["booking_id"], $_POST["checkin_date"], $_POST["checkout_date"])) {
    $booking_id = intval($_POST["booking_id"]);
    $new_checkin = $_POST["checkin_date"];
    $new_checkout = $_POST["checkout_date"];

    if ($new_checkout < $new_checkin) {
        $_SESSION['modify_error'] = "Check-out date cannot be earlier than check-in date!";
        header("Location: ../View/modifybooking.php");
        exit();
    }

    $db = new db();
    $conn = $db->openConn();

    // Check room availability
    $sql_room = "SELECT room_id FROM bookings WHERE booking_id=?";
    $stmt = $conn->prepare($sql_room);
    $stmt->bind_param("i", $booking_id);
    $stmt->execute();
    $booking = $stmt->get_result()->fetch_assoc();
    $room_id = $booking["room_id"];

    $sql_check = "SELECT COUNT(*) AS conflict FROM bookings 
                  WHERE room_id=? AND booking_id!=? AND 
                  ((? BETWEEN checkin_date AND checkout_date) OR 
                   (? BETWEEN checkin_date AND checkout_date) OR
                   (checkin_date BETWEEN ? AND ?) OR 
                   (checkout_date BETWEEN ? AND ?))";
    $stmt2 = $conn->prepare($sql_check);
    $stmt2->bind_param("iissssss", $room_id, $booking_id, $new_checkin, $new_checkout, $new_checkin, $new_checkout, $new_checkin, $new_checkout);
    $stmt2->execute();
    $conflict = $stmt2->get_result()->fetch_assoc()["conflict"];

    if ($conflict > 0) {
        $_SESSION['modify_error'] = "Room is not available for the selected dates!";
        header("Location: ../View/modifybooking.php");
        exit();
    }

    // Update booking
    $sql_update = "UPDATE bookings SET checkin_date=?, checkout_date=? WHERE booking_id=?";
    $stmt3 = $conn->prepare($sql_update);
    $stmt3->bind_param("ssi", $new_checkin, $new_checkout, $booking_id);
    $stmt3->execute();

    $stmt->close();
    $stmt2->close();
    $stmt3->close();
    $db->closeConn($conn);

    $_SESSION['modify_success'] = "Booking dates updated successfully!";
    header("Location: ../View/modifybooking.php");
    exit();
} else {
    $_SESSION['modify_error'] = "Invalid request!";
    header("Location: ../View/modifybooking.php");
    exit();
}
?>