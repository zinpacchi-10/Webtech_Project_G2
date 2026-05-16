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

    if ($new_checkout <= $new_checkin) {
        $_SESSION['modify_error'] = "Check-out date must be after check-in date!";
        header("Location: ../View/modifybooking.php");
        exit();
    }

    $db = new db();
    $conn = $db->openConn();

    $sql_room = "SELECT room_id, total_price, checkin_date, checkout_date FROM bookings WHERE booking_id=?";
    $stmt = $conn->prepare($sql_room);
    $stmt->bind_param("i", $booking_id);
    $stmt->execute();
    $booking = $stmt->get_result()->fetch_assoc();

    if (!$booking) {
        $_SESSION['modify_error'] = "Booking not found!";
        header("Location: ../View/modifybooking.php");
        exit();
    }

    $room_id = $booking["room_id"];

    $sql_conflict = "SELECT COUNT(*) AS conflict FROM bookings
                     WHERE room_id=? AND booking_id!=? AND bookout_date IS NULL AND
                     (checkin_date < ? AND checkout_date > ?)";

    $stmt2 = $conn->prepare($sql_conflict);
    $stmt2->bind_param("iiss", $room_id, $booking_id, $new_checkout, $new_checkin);
    $stmt2->execute();
    $conflict = $stmt2->get_result()->fetch_assoc()["conflict"];

    if ($conflict > 0) {
        $_SESSION['modify_error'] = "Room is not available for the selected dates!";
        header("Location: ../View/modifybooking.php");
        exit();
    }

    $sql_price = "SELECT price FROM rooms WHERE room_id=? LIMIT 1";
    $stmt_price = $conn->prepare($sql_price);
    $stmt_price->bind_param("i", $room_id);
    $stmt_price->execute();
    $room = $stmt_price->get_result()->fetch_assoc();

    $date1 = new DateTime($new_checkin);
    $date2 = new DateTime($new_checkout);
    $nights = max(1, $date1->diff($date2)->days);
    $total_price = $nights * floatval($room["price"]);

    $sql_update = "UPDATE bookings SET checkin_date=?, checkout_date=?, total_price=? WHERE booking_id=?";
    $stmt3 = $conn->prepare($sql_update);
    $stmt3->bind_param("ssdi", $new_checkin, $new_checkout, $total_price, $booking_id);
    $stmt3->execute();

    $sql_bill = "UPDATE billing SET base_amount=?, total_amount=(? + extras_amount - discount_amount), payment_status='pending', paid_at=NULL WHERE booking_id=? AND payment_status='pending'";
    $stmt_bill = $conn->prepare($sql_bill);
    $stmt_bill->bind_param("ddi", $total_price, $total_price, $booking_id);
    $stmt_bill->execute();

    $stmt->close();
    $stmt2->close();
    $stmt_price->close();
    $stmt3->close();
    $stmt_bill->close();
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
