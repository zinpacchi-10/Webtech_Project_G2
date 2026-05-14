<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION["username"]) || empty($_SESSION["role"]) || $_SESSION["role"] != "receptionist") {
    header("location: ../View/adminlogin.php?login=required");
    exit();
}
?>