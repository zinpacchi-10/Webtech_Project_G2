<?php
session_start();
require_once "../Model/users.php";

if($_SERVER['REQUEST_METHOD'] == "POST"){
    $newPass = $_POST['new_password'];
    $userId = $_SESSION['user_id'];

    if(updateUserPassword($userId, $newPass)){
        echo "<script>alert('Password Updated Successfully!'); window.location='../view/profile.php';</script>";
    } else {
        echo "Error updating password.";
    }
}
?>
