<?php
session_start();
require_once("../Model/db.php");

if(!isset($_SESSION["receptionist_logged_in"])){
    header("Location: receptionistlogin.php");
    exit();
}

if($_SERVER["REQUEST_METHOD"]=="POST" &&
   isset($_POST["current_password"], $_POST["new_password"], $_POST["confirm_password"])){

    $user_id = $_SESSION["receptionist_id"];
    $current_password = $_POST["current_password"];
    $new_password = $_POST["new_password"];
    $confirm_password = $_POST["confirm_password"];

    if($new_password !== $confirm_password){
        $_SESSION['password_error']="New password and confirm password do not match!";
        header("Location: ../View/changePassword.php");
        exit();
    }

    $db = new db();
    $conn = $db->openConn();

    $sql = "SELECT password_hash FROM users WHERE user_id=? AND role='receptionist'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i",$user_id);
    $stmt->execute();
    $hash = $stmt->get_result()->fetch_assoc()["password_hash"];

    if(!password_verify($current_password,$hash)){
        $_SESSION['password_error']="Current password is incorrect!";
        header("Location: ../View/changePassword.php");
        exit();
    }

    $new_hash = password_hash($new_password, PASSWORD_DEFAULT);
    $sql_update = "UPDATE users SET password_hash=? WHERE user_id=? AND role='receptionist'";
    $stmt2 = $conn->prepare($sql_update);
    $stmt2->bind_param("si",$new_hash,$user_id);
    $stmt2->execute();

    $stmt->close();
    $stmt2->close();
    $db->closeConn($conn);

    $_SESSION['password_success']="Password changed successfully!";
    header("Location: ../View/changePassword.php");
    exit();
}else{
    $_SESSION['password_error']="Invalid request!";
    header("Location: ../View/changePassword.php");
    exit();
}
?>