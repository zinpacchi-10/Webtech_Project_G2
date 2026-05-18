<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["receptionist_logged_in"]) || $_SESSION["receptionist_role"] != "receptionist") {
    header("location:receptionistlogin.php");
    exit();
}
?>