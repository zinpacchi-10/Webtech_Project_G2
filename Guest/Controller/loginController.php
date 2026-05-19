<?php
session_start();
require_once "../Model/users.php";

function loginController(){
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $user_data = ["email" => $email];
    $status = loginUser($user_data);

    if($status && $password === $status['password_hash']){ 
      
        $_SESSION['user_id'] = $status['user_id']; 
        $_SESSION['username'] = $status['name'];
        $_SESSION['user_role'] = $status['role'];
        $_SESSION['email'] = $status['email'];
        $_SESSION['password'] = $status['password_hash'];
        $_SESSION['logged_in'] = true;

        header('location:../view/guest_dashboard.php');

        exit();
    } else {
        echo "<script>alert('Invalid Email or Password'); window.location='../view/login.php';</script>";
    }
}

if($_SERVER['REQUEST_METHOD'] == "POST"){
    loginController();
} else {
    echo "Invalid Request";
}
?>
