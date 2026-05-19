<?php
session_start();
if (!isset($_SESSION['username'])) { header('Location: login.php'); exit(); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome – Rain Dew Hotel</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family: Arial, sans-serif; background:#f4f4f4; color:#333; }
        
        /* Header */
        .main-header { 
            background: white; height: 80px; padding: 0 50px; 
            box-shadow: 0 2px 5px rgba(0,0,0,0.1); position: fixed; 
            top: 0; width: 100%; z-index: 100; 
            display: flex; justify-content: space-between; align-items: center; 
        }
        .logo-left img { height: 60px; }
        .header-actions { display: flex; align-items: center; gap: 20px; }
        .profile-link { text-decoration: none; color: #333; font-weight: bold; display: flex; align-items: center; gap: 10px; }
        .logout-btn { color: red; text-decoration: none; font-weight: bold; }
        
       
        .hero-section { 
            background-color: #2c3e50; 
            background-image: url('../public/images/room1.jpg'); 
            background-size: cover; 
            background-position: center; 
            color: white; 
            text-align: center; 
            padding: 150px 20px; 
        }
        .hero-section h1 { font-size: 50px; margin-bottom: 10px; }
        .btn-book { background-color: #27ae60; color: white; padding: 15px 30px; text-decoration: none; font-size: 20px; font-weight: bold; border-radius: 5px; }
        
        
        .features-container { text-align: center; padding: 50px 20px; }
        .section-title { font-size: 30px; color: #2c3e50; margin-bottom: 40px; }
        
        .feature-box { 
            background: white; 
            width: 70%; 
            margin: 0 auto 20px auto; 
            padding: 20px; 
            border: 1px solid #ddd; 
            border-radius: 10px; 
            display: flex;      
            align-items: center; 
            text-align: left;     
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        
        .feature-box img { 
            width: 200px;       
            height: 150px;      
            border-radius: 10px; 
            object-fit: cover; 
            margin-right: 25px;  
            flex-shrink: 0;     
        }
        
        .feature-text {
            flex: 1;           
        }
        
        .feature-text h3 { 
            font-size: 22px; 
            color: #2c3e50; 
            margin-bottom: 10px; 
        }
        
        .feature-text p { 
            font-size: 16px; 
            color: #666; 
            line-height: 1.6; 
        }

        .footer { background: #2c3e50; color: white; text-align: center; padding: 30px; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="main-header">
        <div class="logo-left"><img src="../public/images/logo.png" alt="Logo"></div>
        <div class="header-actions">
            <a href="profile.php" class="profile-link"> My Account (<?php echo $_SESSION['username']; ?>)</a>
            <a href="../Controller/logoutController.php" class="logout-btn">Logout</a>
        </div>
    </div>

    <div class="hero-section">
        <h1>Rain Dew Hotel</h1>
        <p>Where Luxury Meets Comfort</p><br>
        <a href="all_rooms.php" class="btn-book">Book a Room Now</a>
    </div>

    <div class="features-container">
        <h2 class="section-title">Our Special Features</h2>
        
        <!-- Feature 1 -->
        <div class="feature-box">
            <img src="../public/images/room1.jpg" alt="Rooms">
            <div class="feature-text">
                <h3>Premium Suites</h3>
                <p>Experience the finest luxury with our meticulously designed rooms, ensuring a peaceful and royal stay for you and your loved ones.</p>
            </div>
        </div>

        <!-- Feature 2 -->
        <div class="feature-box">
            <img src="../public/images/room1.jpg" alt="WiFi">
            <div class="feature-text">
                <h3>High‑Speed Connectivity</h3>
                <p>Stay connected with the world using our complimentary high‑speed WiFi available in every corner of the hotel.</p>
            </div>
        </div>

        <!-- Feature 3 -->
        <div class="feature-box">
            <img src="../public/images/room1.jpg" alt="Dining">
            <div class="feature-text">
                <h3>Gourmet Dining</h3>
                <p>Enjoy a world‑class culinary experience with our 24/7 room service and exquisite multi‑cuisine restaurant.</p>
            </div>
        </div>
    </div>

    <div class="footer">
        <p>&copy; <?php echo date('Y'); ?> Rain Dew Hotel. All Rights Reserved.</p>
    </div>
</body>
</html>
