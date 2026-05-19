<?php
    session_start();
    require_once '../Model/rooms.php';
    if(!isset($_SESSION['username'])){ header('location:login.php'); exit(); }

    if(isset($_GET['id'])){
        $room = getRoomById($_GET['id']);
    } else {
        header('location:all_rooms.php');
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Select Dates - Rain Dew Hotel</title>
    <link rel="stylesheet" href="../public/css/style1.css">
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .booking-card { background: white; padding: 30px; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.2); width: 400px; }
        .booking-card h2 { text-align: center; color: #333; margin-bottom: 20px; }
        .input-group { margin-bottom: 15px; }
        .input-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .input-group input { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box; }
        .btn-confirm { width: 100%; padding: 12px; background: #27ae60; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; font-weight: bold; }
        .back-link { display: block; text-align: center; margin-top: 15px; text-decoration: none; color: #666; font-size: 14px; }
    </style>
</head>
<body>
    <div class="booking-card">
        <h2>Booking Room <?php echo $room['room_number']; ?></h2>
        <form action="../Controller/bookingController.php" method="POST">
            <input type="hidden" name="room_id" value="<?php echo $room['room_id']; ?>">
            
            <div class="input-group">
                <label>Booking Start Date</label>
                <input type="date" name="bookin_date" required>
            </div>
            <div class="input-group">
                <label>Booking End Date</label>
                <input type="date" name="bookout_date" required>
            </div>
            
            <button type="submit" class="btn-confirm">Confirm My Booking</button>
            <a href="room_details.php?id=<?php echo $room['room_id']; ?>" class="back-link">← Back to Details</a>
        </form>
    </div>
</body>
</html>
