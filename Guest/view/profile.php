<?php
    session_start();
    require_once '../Model/users.php'; 
    require_once '../Model/bookings.php';
    if(!isset($_SESSION['user_id'])){ header('location:login.php'); exit(); }
    $user = getUserDetails($_SESSION['user_id']);
    $bookings = fetchAllUserBookings($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Account - Rain Dew Hotel</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f4f7f6; margin: 0; }
        .main-header { background: white; height: 70px; display: flex; justify-content: space-between; align-items: center; padding: 0 40px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .container { max-width: 800px; margin: 40px auto; padding: 20px; }
        .section { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); margin-bottom: 30px; }
        h2 { color: #2c3e50; border-bottom: 2px solid #eee; padding-bottom: 10px; margin-bottom: 20px; }
        .info-row { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #f9f9f9; }
        .booking-item { background: #f9f9f9; padding: 15px; border-radius: 8px; margin-bottom: 10px; border-left: 5px solid #3498db; }
        .btn-service { background: #27ae60; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block; margin-top: 10px; }
        .password-form input { width: 100%; padding: 10px; margin: 10px 0; box-sizing: border-box; }
        .btn-save { background: #2c3e50; color: white; padding: 10px 20px; border: none; cursor: pointer; width: 100%; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="main-header">
        <img src="../public/images/logo.png" height="50">
        <a href="guest_dashboard.php" style="text-decoration:none; color:#333; font-weight:bold;">← Back to Dashboard</a>
    </div>

    <div class="container">
        <div class="section">
            <h2>My Personal Information</h2>
            <div class="info-row"><strong>Name:</strong> <span><?php echo $user['name']; ?></span></div>
            <div class="info-row"><strong>Email:</strong> <span><?php echo $user['email']; ?></span></div>
            <div class="info-row"><strong>Phone:</strong> <span><?php echo $user['phone']; ?></span></div>
            <div class="info-row"><strong>Nationality:</strong> <span><?php echo $user['nationality']; ?></span></div>
            <div class="info-row"><strong>ID:</strong> <span><?php echo $user['id_number']; ?></span></div>
        </div>

        <div class="section">
            <h2>My Bookings & Stays</h2>
            <?php if(mysqli_num_rows($bookings) > 0): ?>
                <?php while($row = mysqli_fetch_assoc($bookings)): ?>
                    <div class="booking-item">
                        <strong>Room <?php echo $row['room_number']; ?> (<?php echo $row['room_type']; ?>)</strong><br>
                      
                        Booking Date: <?php echo $row['bookin_date']; ?> | Check-out Date: <?php echo $row['bookout_date']; ?>
                        <br>
                        <?php 
                            $today = date('Y-m-d');
                            if($today >= $row['checkin_date'] && $today <= $row['checkout_date']) {
                                echo '<a href="request_service.php?booking_id='.$row['booking_id'].'&room_id='.$row['room_id'].'" class="btn-service">Request Room Service</a>';
                            }
                        ?>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No bookings found.</p>
            <?php endif; ?>
        </div>

        <div class="section">
            <h2>Security</h2>
            <form action="../Controller/updatePasswordController.php" method="POST" class="password-form">
                <label>New Password</label>
                <input type="password" name="new_password" required>
                <button type="submit" class="btn-save">Update Password</button>
            </form>
        </div>
    </div>
</body>
</html>
