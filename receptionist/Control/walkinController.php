<?php
session_start();
require_once("../Model/db.php");

if (!isset($_SESSION["receptionist_logged_in"])) {
    header("Location: receptionistlogin.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['guest_name']);
    $email = trim($_POST['guest_email']);
    $phone = trim($_POST['guest_phone']);
    $room_id = intval($_POST['room_id']);
    $checkin_date = $_POST['checkin_date'];
    $checkout_date = $_POST['checkout_date'];
    $num_guests = intval($_POST['num_guests']);

    $db = new db();
    $conn = $db->openConn();

    // Insert guest in users table
    $role = "guest";
    $password = password_hash("guest123", PASSWORD_DEFAULT);
    $id_number = "GUEST-".time();

    $sql_user = "INSERT INTO users (name, email, password_hash, phone, role, id_number) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt_user = $conn->prepare($sql_user);
    $stmt_user->bind_param("ssssss", $name, $email, $password, $phone, $role, $id_number);
    $stmt_user->execute();
    $guest_id = $conn->insert_id;

    // Insert booking
    $sql_booking = "INSERT INTO bookings (guest_id, room_id, checkin_date, checkout_date, num_guests, notes) VALUES (?, ?, ?, ?, ?, 'checked_in')";
    $stmt_booking = $conn->prepare($sql_booking);
    $stmt_booking->bind_param("iisss", $guest_id, $room_id, $checkin_date, $checkout_date, $num_guests);
    $stmt_booking->execute();

    // Update room status
    $sql_room = "UPDATE rooms SET notes='occupied' WHERE room_id=?";
    $stmt_room = $conn->prepare($sql_room);
    $stmt_room->bind_param("i", $room_id);
    $stmt_room->execute();

    $stmt_user->close();
    $stmt_booking->close();
    $stmt_room->close();
    $db->closeConn($conn);

    $_SESSION['walkin_success'] = "Guest registered and booked successfully!";
    header("Location: ../View/walkin.php");
    exit();
}
?>