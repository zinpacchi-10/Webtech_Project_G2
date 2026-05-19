<?php
    require_once 'connection.php';

    function isRoomAvailable($roomId, $start, $end) {
        $conn = dbConnection();
        
       
        $Query = "SELECT * FROM bookings 
                  WHERE room_id = '{$roomId}' 
                  AND bookin_date < '{$end}' 
                  AND bookout_date > '{$start}'";
        
        $result = mysqli_query($conn, $Query);
        
       
        if(mysqli_num_rows($result) == 0){
            return true; 
        } else {
            return false;
        }
    }

    function createBooking($booking){
        $conn = dbConnection();
        $Query = "INSERT INTO bookings (guest_id, room_id, bookin_date, bookout_date, num_guests, total_price) 
                  VALUES ('{$booking['guest_id']}', '{$booking['room_id']}', '{$booking['bookin_date']}', '{$booking['bookout_date']}', 1, 0)";
        return mysqli_query($conn, $Query);
    }

    function fetchUserBookings($userId){
        $conn = dbConnection();
        $Query = "SELECT bookings.*, rooms.room_number, rooms.room_type 
                  FROM bookings 
                  JOIN rooms ON bookings.room_id = rooms.room_id 
                  WHERE bookings.guest_id = '{$userId}'";
        return mysqli_query($conn, $Query);
    }

    function getLatestBooking($userId) {
    $conn = dbConnection();
    
    $Query = "SELECT bookings.*, rooms.room_number, rooms.room_type 
              FROM bookings 
              JOIN rooms ON bookings.room_id = rooms.room_id 
              WHERE bookings.guest_id = '{$userId}' 
              ORDER BY bookings.checkin_date DESC LIMIT 1";
    $result = mysqli_query($conn, $Query);
    return mysqli_fetch_assoc($result);
}


function fetchAllUserBookings($userId){
    $conn = dbConnection();
  
    $Query = "SELECT bookings.*, rooms.room_number, rooms.room_type 
              FROM bookings 
              JOIN rooms ON bookings.room_id = rooms.room_id 
              WHERE bookings.guest_id = '{$userId}' 
              ORDER BY bookings.checkin_date DESC";
    return mysqli_query($conn, $Query);
}

function createServiceRequest($data) {
    $conn = dbConnection();
   
    $Query = "INSERT INTO service_requests (booking_id, guest_id, room_id, service_type, description) 
              VALUES ('{$data['booking_id']}', '{$data['guest_id']}', '{$data['room_id']}', '{$data['service_type']}', '{$data['description']}')";
    return mysqli_query($conn, $Query);
}


?>
