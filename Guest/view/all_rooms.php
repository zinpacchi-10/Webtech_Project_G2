<?php
    session_start();
    require_once '../Model/rooms.php'; 
    if(!isset($_SESSION['username'])){ header('location:login.php'); exit(); }
?> 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Our Rooms - Rain Dew Hotel</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Arial, sans-serif; background-color: #f4f4f4; color: #333; }

        /* HEADER FIX: Ensures items are on opposite ends */
        .main-header { 
            background-color: white; height: 80px; padding: 0 50px; 
            box-shadow: 0 2px 10px rgba(0,0,0,0.1); position: fixed; 
            top: 0; width: 100%; z-index: 1000; 
            display: flex; justify-content: space-between; align-items: center; 
        }
        .header-logo img { height: 60px; }
        .header-actions { display: flex; align-items: center; gap: 20px; }
        .profile-link { text-decoration: none; color: #333; font-weight: bold; display: flex; align-items: center; gap: 10px; }
        .logout-btn { color: red; text-decoration: none; font-weight: bold; }

        .content { margin-top: 120px; text-align: center; padding: 0 50px 50px 50px; }
        
        /* BACK BUTTON FIX: Highly visible link */
        .back-nav { text-align: left; margin-bottom: 20px; }
        .btn-back { text-decoration: none; color: #666; font-weight: bold; font-size: 16px; transition: 0.3s; }
        .btn-back:hover { color: #000; }

        h2 { font-size: 32px; color: #2c3e50; margin-bottom: 30px; }
        .room-list { display: flex; flex-wrap: wrap; justify-content: center; gap: 25px; }
        .room-card { background: white; width: 300px; border: 1px solid #ddd; border-radius: 15px; overflow: hidden; text-align: left; box-shadow: 0 4px 8px rgba(0,0,0,0.05); transition: 0.3s; }
        .room-card:hover { transform: translateY(-5px); box-shadow: 0 8px 16px rgba(0,0,0,0.1); }
        .room-card img { width: 100%; height: 200px; object-fit: cover; border-bottom: 1px solid #eee; }
        .room-details { padding: 20px; }
        .room-number-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; }
        .room-number { font-size: 18px; font-weight: bold; color: #2c3e50; }
        .room-floor { font-size: 12px; color: #888; background: #eee; padding: 2px 8px; border-radius: 10px; }
        .price-text { color: #27ae60; font-weight: bold; font-size: 20px; margin-bottom: 10px; }
        .description { font-size: 14px; color: #666; line-height: 1.5; margin-bottom: 20px; height: 42px; overflow: hidden; }
        .btn-details { display: block; background: #2c3e50; color: white; text-align: center; padding: 12px; text-decoration: none; border-radius: 8px; font-weight: 600; transition: 0.3s; }
        .btn-details:hover { background: #34495e; }
    </style>
</head>
<body>

    <div class="main-header">
        <div class="header-logo">
            <img src="../public/images/logo.png" alt="Logo">
        </div>
        <div class="header-actions">
            <a href="profile.php" class="profile-link">👤 My Account (<?php echo $_SESSION['username']; ?>)</a>
            <a href="../Controller/logoutController.php" class="logout-btn">Logout</a>
        </div>
    </div>

    <div class="content">
        <!-- BACK BUTTON HERE -->
        <div class="back-nav">
            <a href="guest_dashboard.php" class="btn-back">← Back to Dashboard</a>
        </div>

        <h2>Our Luxury Rooms</h2>

        <div class="room-list">
            <?php 
                $rooms = fetchAllRooms(); 
                if(mysqli_num_rows($rooms) > 0) {
                    while($row = mysqli_fetch_assoc($rooms)) { 
            ?>
                <div class="room-card">
                    <img src="../public/images/<?php echo $row['thumbnail'] ? $row['thumbnail'] : 'room1.jpg'; ?>" alt="Room">
                    <div class="room-details">
                        <div class="room-number-row">
                            <span class="room-number">Room <?php echo $row['room_number']; ?></span> 
                            <span class="room-floor">Floor <?php echo $row['floor']; ?></span>
                        </div>
                        <div class="price-text">৳ <?php echo $row['price']; ?> / night</div>
                        <p class="description">
                            <?php echo (strlen($row['room_description']) > 60) ? substr($row['room_description'], 0, 60) . '...' : $row['room_description']; ?>
                        </p>
                        <a href="room_details.php?id=<?php echo $row['room_id']; ?>" class="btn-details">View Details</a>
                    </div>
                </div>
            <?php 
                    } 
                } else {
                    echo "<p>No rooms available at the moment.</p>";
                }
            ?>
        </div>
    </div>
</body>
</html>
