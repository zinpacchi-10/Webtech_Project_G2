<?php
session_start();
require_once("../Model/db.php");

if (!isset($_SESSION["receptionist_logged_in"])) {
    header("Location: ../View/receptionistlogin.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["booking_id"], $_POST["room_id"], $_POST["id_verified"])) {
    $booking_id = intval($_POST["booking_id"]);
    $room_id = intval($_POST["room_id"]);
    $today = date("Y-m-d");

    $db = new db();
    $conn = $db->openConn();

    $sql = "SELECT b.booking_id, b.room_id AS old_room_id, b.bookin_date, b.checkout_date, r.room_type
            FROM bookings b
            JOIN rooms r ON b.room_id = r.room_id
            WHERE b.booking_id = ? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $booking_id);
    $stmt->execute();
    $booking = $stmt->get_result()->fetch_assoc();

    if (!$booking) {
        $_SESSION["checkin_error"] = "Booking not found!";
        header("Location: ../View/checkin.php");
        exit();
    }

    if (!empty($booking["bookin_date"])) {
        $_SESSION["checkin_error"] = "This guest is already checked in!";
        header("Location: ../View/checkin.php");
        exit();
    }

    $sql_room = "SELECT room_id, room_type, notes FROM rooms WHERE room_id = ? LIMIT 1";
    $stmt_room = $conn->prepare($sql_room);
    $stmt_room->bind_param("i", $room_id);
    $stmt_room->execute();
    $room = $stmt_room->get_result()->fetch_assoc();

    if (!$room || $room["room_type"] != $booking["room_type"] || strtolower($room["notes"]) != "available") {
        $_SESSION["checkin_error"] = "Selected room is not available for this booking type!";
        header("Location: ../View/checkin.php");
        exit();
    }

    $sql_conflict = "SELECT COUNT(*) AS total
                     FROM bookings
                     WHERE room_id = ? AND booking_id != ? AND bookin_date IS NOT NULL AND bookout_date IS NULL";
    $stmt_conflict = $conn->prepare($sql_conflict);
    $stmt_conflict->bind_param("ii", $room_id, $booking_id);
    $stmt_conflict->execute();
    $conflict = $stmt_conflict->get_result()->fetch_assoc()["total"];

    if ($conflict > 0) {
        $_SESSION["checkin_error"] = "Selected room is already occupied!";
        header("Location: ../View/checkin.php");
        exit();
    }

    $update_booking = "UPDATE bookings SET room_id = ?, bookin_date = ? WHERE booking_id = ?";
    $stmt_update = $conn->prepare($update_booking);
    $stmt_update->bind_param("isi", $room_id, $today, $booking_id);
    $stmt_update->execute();

    $update_new_room = "UPDATE rooms SET notes = 'occupied' WHERE room_id = ?";
    $stmt_new_room = $conn->prepare($update_new_room);
    $stmt_new_room->bind_param("i", $room_id);
    $stmt_new_room->execute();

    if ($booking["old_room_id"] != $room_id) {
        $update_old_room = "UPDATE rooms SET notes = 'available' WHERE room_id = ? AND notes != 'maintenance'";
        $stmt_old_room = $conn->prepare($update_old_room);
        $stmt_old_room->bind_param("i", $booking["old_room_id"]);
        $stmt_old_room->execute();
        $stmt_old_room->close();
    }

    $stmt->close();
    $stmt_room->close();
    $stmt_conflict->close();
    $stmt_update->close();
    $stmt_new_room->close();
    $db->closeConn($conn);

    $_SESSION["checkin_success"] = "Guest checked in successfully!";
    header("Location: ../View/checkin.php");
    exit();
} else {
    $_SESSION["checkin_error"] = "Please verify guest ID and select an available room.";
    header("Location: ../View/checkin.php");
    exit();
}
?>
