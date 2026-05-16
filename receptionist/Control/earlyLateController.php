<?php
session_start();
require_once("../Model/db.php");

if(!isset($_SESSION["receptionist_logged_in"])){
    header("Location: receptionistlogin.php");
    exit();
}

if($_SERVER["REQUEST_METHOD"]=="POST" && isset($_POST["request_id"], $_POST["status"])){
    $request_id = intval($_POST["request_id"]);
    $status = trim($_POST["status"]);

    $valid_status = ["approved","declined"];
    if(!in_array($status,$valid_status)){
        $_SESSION['earlylate_error']="Invalid action!";
        header("Location: ../View/early_late_requests.php");
        exit();
    }

    $db = new db();
    $conn = $db->openConn();

    $sql = "UPDATE early_late_requests SET status=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si",$status,$request_id);
    $stmt->execute();

    $stmt->close();
    $db->closeConn($conn);

    $_SESSION['earlylate_success']="Request ".$status." successfully!";
    header("Location: ../View/early_late_requests.php");
    exit();
}else{
    $_SESSION['earlylate_error']="Invalid request!";
    header("Location: ../View/early_late_requests.php");
    exit();
}
?>