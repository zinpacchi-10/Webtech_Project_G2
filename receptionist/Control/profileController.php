<?php
session_start();
require_once("../Model/db.php");

if(!isset($_SESSION["receptionist_logged_in"])){
    header("Location: receptionistlogin.php");
    exit();
}

if($_SERVER["REQUEST_METHOD"]=="POST" && isset($_POST["name"], $_POST["email"], $_POST["phone"])){
    $user_id = $_SESSION["receptionist_id"];
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $phone = trim($_POST["phone"]);

    $db = new db();
    $conn = $db->openConn();

    $sql = "UPDATE users SET name=?, email=?, phone=? WHERE user_id=? AND role='receptionist'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi",$name,$email,$phone,$user_id);
    $stmt->execute();

    $stmt->close();
    $db->closeConn($conn);

    $_SESSION["profile_success"]="Profile updated successfully!";
    header("Location: ../View/profile_edit.php");
    exit();
}else{
    $_SESSION["profile_error"]="Invalid request!";
    header("Location: ../View/profile_edit.php");
    exit();
}
?>