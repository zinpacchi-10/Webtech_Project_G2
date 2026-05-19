<?php
    require_once 'connection.php';

   
    function fetchAllRooms(){
        $conn = dbConnection();
        $Query = "SELECT * FROM rooms";
        return mysqli_query($conn, $Query);
    }

    function getRoomById($id){
        $conn = dbConnection();
        $Query = "SELECT * FROM rooms WHERE room_id = '{$id}'";
        $result = mysqli_query($conn, $Query);
        return (mysqli_num_rows($result) == 1) ? mysqli_fetch_assoc($result) : false;
    }

    
    function isRoomAvailable($roomId, $start, $end) {
        $conn = dbConnection();
        
       
        $Query = "SELECT count(*) as total FROM bookings 
                  WHERE room_id = '{$roomId}' 
                  AND (
                    (bookin_date < '{$end}' AND bookout_date > '{$start}') 
                    OR 
                    (checkin_date < '{$end}' AND (checkout_date > '{$start}' OR checkout_date IS NULL))
                  )";
        
        $result = mysqli_query($conn, $Query);
        $row = mysqli_fetch_assoc($result);
        
        
        return ($row['total'] == 0); 
    }
?>
