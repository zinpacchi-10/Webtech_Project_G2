<?php
session_start();
require_once("../Model/db.php");

if (!isset($_SESSION["receptionist_logged_in"])) {
    header("Location: ../View/receptionistlogin.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["booking_id"], $_POST["payment_method"])) {
    $booking_id = intval($_POST["booking_id"]);
    $payment_method = trim($_POST["payment_method"]);
    $points_to_use = isset($_POST["points_used"]) ? intval($_POST["points_used"]) : 0;

    $valid_methods = ["cash", "card", "mobile_banking", "bank_transfer"];
    if (!in_array($payment_method, $valid_methods)) {
        $_SESSION['payment_error'] = "Please select a valid payment method.";
        header("Location: ../View/paymentlist.php");
        exit();
    }

    $db = new db();
    $conn = $db->openConn();

    $sql = "SELECT b.booking_id, b.guest_id, b.total_price, u.name
            FROM bookings b
            JOIN users u ON b.guest_id = u.user_id
            WHERE b.booking_id = ? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $booking_id);
    $stmt->execute();
    $booking = $stmt->get_result()->fetch_assoc();

    if (!$booking) {
        $_SESSION['payment_error'] = "Booking not found!";
        header("Location: ../View/paymentlist.php");
        exit();
    }

    $guest_id = intval($booking["guest_id"]);
    $base_amount = floatval($booking["total_price"]);

    $sql_extras = "SELECT COUNT(*) AS completed_services
                   FROM service_requests
                   WHERE booking_id = ? AND status = 'completed'";
    $stmt_extras = $conn->prepare($sql_extras);
    $stmt_extras->bind_param("i", $booking_id);
    $stmt_extras->execute();
    $completed_services = intval($stmt_extras->get_result()->fetch_assoc()["completed_services"]);
    $extras_amount = $completed_services * 200;

    $sql_balance = "SELECT IFNULL(balance,0) AS balance
                    FROM loyalty_points
                    WHERE guest_id = ?
                    ORDER BY loyalty_id DESC
                    LIMIT 1";
    $stmt_balance = $conn->prepare($sql_balance);
    $stmt_balance->bind_param("i", $guest_id);
    $stmt_balance->execute();
    $balance_row = $stmt_balance->get_result()->fetch_assoc();
    $available_points = $balance_row ? intval($balance_row["balance"]) : 0;

    if ($points_to_use < 0) {
        $points_to_use = 0;
    }

    if ($points_to_use > $available_points) {
        $_SESSION['payment_error'] = "Redeem points cannot exceed guest balance.";
        header("Location: ../View/paymentlist.php");
        exit();
    }

    $discount_amount = min($points_to_use, $base_amount + $extras_amount);
    $total_amount = ($base_amount + $extras_amount) - $discount_amount;
    $receipt_path = "../View/receipt.php?booking_id=".$booking_id;

    $sql_existing = "SELECT billing_id FROM billing WHERE booking_id = ? LIMIT 1";
    $stmt_existing = $conn->prepare($sql_existing);
    $stmt_existing->bind_param("i", $booking_id);
    $stmt_existing->execute();
    $existing = $stmt_existing->get_result()->fetch_assoc();

    if ($existing) {
        $sql_bill = "UPDATE billing
                     SET base_amount=?, extras_amount=?, discount_amount=?, total_amount=?, payment_method=?,
                         payment_status='paid', paid_at=NOW(), receipt_path=?
                     WHERE booking_id=?";
        $stmt_bill = $conn->prepare($sql_bill);
        $stmt_bill->bind_param("ddddssi", $base_amount, $extras_amount, $discount_amount, $total_amount, $payment_method, $receipt_path, $booking_id);
        $stmt_bill->execute();
    } else {
        $sql_bill = "INSERT INTO billing
                     (booking_id, guest_id, base_amount, extras_amount, discount_amount, total_amount, payment_method, payment_status, paid_at, receipt_path)
                     VALUES (?, ?, ?, ?, ?, ?, ?, 'paid', NOW(), ?)";
        $stmt_bill = $conn->prepare($sql_bill);
        $stmt_bill->bind_param("iiddddss", $booking_id, $guest_id, $base_amount, $extras_amount, $discount_amount, $total_amount, $payment_method, $receipt_path);
        $stmt_bill->execute();
    }

    if ($points_to_use > 0) {
        $new_balance = $available_points - $points_to_use;
        $sql_points = "INSERT INTO loyalty_points (guest_id, booking_id, points_earned, points_used, balance, created_at)
                       VALUES (?, ?, 0, ?, ?, NOW())";
        $stmt_points = $conn->prepare($sql_points);
        $stmt_points->bind_param("iiii", $guest_id, $booking_id, $points_to_use, $new_balance);
        $stmt_points->execute();
        $stmt_points->close();
    }

    $stmt->close();
    $stmt_extras->close();
    $stmt_balance->close();
    $stmt_existing->close();
    $stmt_bill->close();
    $db->closeConn($conn);

    $_SESSION['payment_success'] = "Payment processed successfully! Receipt is ready.";
    header("Location: ../View/receipt.php?booking_id=".$booking_id);
    exit();
} else {
    $_SESSION['payment_error'] = "Invalid payment request!";
    header("Location: ../View/paymentlist.php");
    exit();
}
?>
