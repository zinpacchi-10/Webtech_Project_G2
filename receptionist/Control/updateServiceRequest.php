<?php
session_start();
require_once("../Model/db.php");

if (!isset($_SESSION["receptionist_logged_in"])) {
    header("Location: receptionistlogin.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["request_id"], $_POST["status"])) {
    $request_id = intval($_POST["request_id"]);
    $status = trim($_POST["status"]);

    // Only allow valid statuses
    $valid_status = ["pending", "in_progress", "completed"];
    if (!in_array($status, $valid_status)) {
        $_SESSION['service_error'] = "Invalid status!";
        header("Location: ../View/servicerequest.php");
        exit();
    }

    $db = new db();
    $conn = $db->openConn();

    $sql = "UPDATE service_requests SET status=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $status, $request_id);
    $stmt->execute();

    $stmt->close();
    $db->closeConn($conn);

    $_SESSION['service_success'] = "Service request updated successfully!";
    header("Location: ../View/servicerequest.php");
    exit();
} else {
    $_SESSION['service_error'] = "Invalid request!";
    header("Location: ../View/servicerequest.php");
    exit();
}
?>