<?php
session_start();
require_once "../Model/receptionistuser.php";

function receptionistLoginController()
{
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    if ($email == "" || $password == "") {
        echo "<script>alert('Email and Password are required'); window.location='../View/receptionistlogin.php';</script>";
        exit();
    }

    $user_data = ["email" => $email];
    $status = loginReceptionist($user_data);

    if ($status && password_verify($password, $status["password_hash"])) {

        $_SESSION["receptionist_id"] = $status["user_id"];
        $_SESSION["receptionist_name"] = $status["name"];
        $_SESSION["receptionist_email"] = $status["email"];
        $_SESSION["receptionist_role"] = $status["role"];
        $_SESSION["receptionist_logged_in"] = true;

        header("location:../View/receptionistdashboard.php");
        exit();

    } else {
        echo "<script>alert('Invalid Receptionist Email or Password'); window.location='../View/receptionistlogin.php';</script>";
        exit();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    receptionistLoginController();
} else {
    echo "Invalid Request";
}
?>