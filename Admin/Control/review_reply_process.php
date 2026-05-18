<?php
session_start();
require_once("../Model/review_model.php");

if(!isset($_SESSION['admin_id'])) {
    header("Location: ../View/adminlogin.php");
    exit();
}

if (isset($_POST['submit_reply'])) {
    $review_id = $_POST['review_id'];
    $reply = $_POST['reply'];

    $reviewModel = new ReviewModel();
    if ($reviewModel->addReply($review_id, $reply)) {
        header("Location: ../View/review_manage.php?msg=Reply sent successfully!");
    } else {
        header("Location: ../View/review_manage.php?msg=Error sending reply.");
    }
}
?>