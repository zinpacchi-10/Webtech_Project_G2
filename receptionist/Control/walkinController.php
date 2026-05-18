<?php
session_start();
require_once("../Model/db.php");

if (!isset($_SESSION["receptionist_logged_in"])) {
    header("Location: ../View/receptionistlogin.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['guest_name']);
    $email = trim($_POST['guest_email']);
    $phone = trim($_POST['guest_phone']);
    $nationality = trim($_POST['nationality']);
    $id_number = trim($_POST['id_number']);
    $room_id = intval($_POST['room_id']);
    $checkin_date = $_POST['checkin_date'];
    $checkout_date = $_POST['checkout_date'];
    $num_guests = intval($_POST['num_guests']);

    if ($name == "" || $email == "" || $id_number == "" || $room_id <= 0 || $checkin_date == "" || $checkout_date == "" || $num_guests <= 0) {
        $_SESSION['walkin_error'] = "Please fill all required guest and booking fields.";
        header("Location: ../View/walkin.php");
        exit();
    }

    if ($checkout_date <= $checkin_date) {
        $_SESSION['walkin_error'] = "Check-out date must be after check-in date.";
        header("Location: ../View/walkin.php");
        exit();
    }

    $db = new db();
    $conn = $db->openConn();

    $sql_room = "SELECT room_id, price, capacity, notes FROM rooms WHERE room_id = ? LIMIT 1";
    $stmt_room = $conn->prepare($sql_room);
    $stmt_room->bind_param("i", $room_id);
    $stmt_room->execute();
    $room = $stmt_room->get_result()->fetch_assoc();

    if (!$room || strtolower($room["notes"]) != "available") {
        $_SESSION['walkin_error'] = "Selected room is not available.";
        header("Location: ../View/walkin.php");
        exit();
    }

    if (!empty($room["capacity"]) && $num_guests > intval($room["capacity"])) {
        $_SESSION['walkin_error'] = "Guest count exceeds selected room capacity.";
        header("Location: ../View/walkin.php");
        exit();
    }

    $sql_conflict = "SELECT COUNT(*) AS total FROM bookings
                     WHERE room_id = ? AND bookout_date IS NULL AND
                     ((checkin_date < ? AND checkout_date > ?) OR (bookin_date IS NOT NULL AND bookout_date IS NULL))";
    $stmt_conflict = $conn->prepare($sql_conflict);
    $stmt_conflict->bind_param("iss", $room_id, $checkout_date, $checkin_date);
    $stmt_conflict->execute();
    $conflict = intval($stmt_conflict->get_result()->fetch_assoc()["total"]);

    if ($conflict > 0) {
        $_SESSION['walkin_error'] = "Selected room has a booking conflict.";
        header("Location: ../View/walkin.php");
        exit();
    }

    $sql_guest = "SELECT user_id FROM users WHERE email = ? AND role = 'guest' LIMIT 1";
    $stmt_guest = $conn->prepare($sql_guest);
    $stmt_guest->bind_param("s", $email);
    $stmt_guest->execute();
    $guest = $stmt_guest->get_result()->fetch_assoc();

    if ($guest) {
        $guest_id = intval($guest["user_id"]);
        $sql_update_guest = "UPDATE users SET name=?, phone=?, nationality=?, id_number=? WHERE user_id=? AND role='guest'";
        $stmt_update_guest = $conn->prepare($sql_update_guest);
        $stmt_update_guest->bind_param("ssssi", $name, $phone, $nationality, $id_number, $guest_id);
        $stmt_update_guest->execute();
        $stmt_update_guest->close();
    } else {
        $role = "guest";
        $password = password_hash("guest123", PASSWORD_DEFAULT);
        $sql_user = "INSERT INTO users (name, email, password_hash, phone, nationality, id_number, role)
                     VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt_user = $conn->prepare($sql_user);
        $stmt_user->bind_param("sssssss", $name, $email, $password, $phone, $nationality, $id_number, $role);
        $stmt_user->execute();
        $guest_id = $conn->insert_id;
        $stmt_user->close();
    }

    $date1 = new DateTime($checkin_date);
    $date2 = new DateTime($checkout_date);
    $nights = max(1, $date1->diff($date2)->days);
    $total_price = $nights * floatval($room["price"]);
    $today = date("Y-m-d");

    $sql_booking = "INSERT INTO bookings
                    (guest_id, room_id, checkin_date, checkout_date, num_guests, total_price, bookin_date)
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt_booking = $conn->prepare($sql_booking);
    $stmt_booking->bind_param("iissids", $guest_id, $room_id, $checkin_date, $checkout_date, $num_guests, $total_price, $today);
    $stmt_booking->execute();

    $sql_room_update = "UPDATE rooms SET notes='occupied' WHERE room_id=?";
    $stmt_room_update = $conn->prepare($sql_room_update);
    $stmt_room_update->bind_param("i", $room_id);
    $stmt_room_update->execute();

    $stmt_room->close();
    $stmt_conflict->close();
    $stmt_guest->close();
    $stmt_booking->close();
    $stmt_room_update->close();
    $db->closeConn($conn);

    $_SESSION['walkin_success'] = "Walk-in guest registered, booked, and checked in successfully!";
    header("Location: ../View/walkin.php");
    exit();
}
?>
