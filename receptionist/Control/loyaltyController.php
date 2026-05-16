<?php
session_start();
require_once("../Model/db.php");

if (!isset($_SESSION["receptionist_logged_in"])) {
    header("Location: receptionistlogin.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["guest_id"], $_POST["points"])) {
    $guest_id = intval($_POST["guest_id"]);
    $points = intval($_POST["points"]);

    $db = new db();
    $conn = $db->openConn();

    // Check current balance
    $sql_balance = "SELECT IFNULL(SUM(balance),0) AS total_points FROM loyalty_points WHERE guest_id=?";
    $stmt = $conn->prepare($sql_balance);
    $stmt->bind_param("i", $guest_id);
    $stmt->execute();
    $balance = $stmt->get_result()->fetch_assoc()["total_points"];

    if($points > $balance) {
        $_SESSION['loyalty_error'] = "Points to redeem exceed balance!";
        header("Location: ../View/loyaltypoints.php");
        exit();
    }

    // Deduct points
    $sql_deduct = "INSERT INTO loyalty_points (guest_id, booking_id, points_earned, points_used, balance, created_at)
                   VALUES (?, NULL, 0, ?, ? , NOW())";
    $new_balance = $balance - $points;
    $stmt2 = $conn->prepare($sql_deduct);
    $stmt2->bind_param("iii", $guest_id, $points, $new_balance);
    $stmt2->execute();

    $stmt->close();
    $stmt2->close();
    $db->closeConn($conn);

    $_SESSION['loyalty_success'] = "Points redeemed successfully!";
    header("Location: ../View/loyaltypoints.php");
    exit();
} else {
    $_SESSION['loyalty_error'] = "Invalid request!";
    header("Location: ../View/loyaltypoints.php");
    exit();
}
?>