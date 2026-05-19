<?php
session_start();
require_once "../Model/bookings.php";

if($_SERVER['REQUEST_METHOD'] == "POST"){
    if(!isset($_SESSION['user_id'])){
        header('location:../view/login.php');
        exit();
    }

    $roomId = $_POST['room_id'];
    $bookIn = $_POST['bookin_date']; 
    $bookOut = $_POST['bookout_date']; 
    $userId = $_SESSION['user_id'];

    
    if(isRoomAvailable($roomId, $bookIn, $bookOut)){
        
        $bookingData = [
            "guest_id" => $userId,
            "room_id" => $roomId,
            "bookin_date" => $bookIn,
            "bookout_date" => $bookOut
        ];

        if(createBooking($bookingData)){
          echo "<script>alert('Booking Confirmed!'); window.location='../view/guest_dashboard.php';</script>";
        } else {
            echo "Booking failed.";
        }
    } else {
        echo "<script>alert('Sorry! This room is already booked for these dates.'); window.location='../view/book_room.php?id=$roomId';</script>";
    }
}
?>
