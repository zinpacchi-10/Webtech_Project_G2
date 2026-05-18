<?php
session_start();
require_once("../Model/booking_model.php");

if(!isset($_SESSION['admin_id'])) {
    header("Location: ../View/adminlogin.php");
    exit();
}

$bookingModel = new BookingModel();

// বুকিং ডিলিট করার প্রসেস
if (isset($_GET['cancel_id'])) {
    $id = $_GET['cancel_id'];
    if ($bookingModel->deleteBooking($id)) {
        header("Location: ../View/booking_list.php?msg=Booking cancelled successfully!");
    } else {
        header("Location: ../View/booking_list.php?msg=Error cancelling booking.");
    }
}
?>