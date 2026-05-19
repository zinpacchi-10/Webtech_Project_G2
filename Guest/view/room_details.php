<?php
    session_start();
    require_once '../Model/rooms.php'; 

 
    if(!isset($_SESSION['username'])){
        header('location:login.php');
        exit();
    }

   
    if(isset($_GET['id'])){
        $roomId = $_GET['id'];
        $room = getRoomById($roomId);
    } else {
        $room = false;
    }

   
    if(!$room){
        header('location:all_rooms.php');
        exit();
    }
?> 

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Room Details - Rain Dew Hotel</title>
    <style>
        * { box-sizing:border-box; margin:0; padding:0; }
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background-color:#f0f2f5;
            color:#333;
        }

        .main-header {
            background:#fff;
            width:100%;
            position:fixed;
            top:0; left:0;
            height:120px;
            display:flex;
            justify-content:space-between;
            align-items:center;
            padding:0 40px;
            box-shadow:0 2px 10px rgba(0,0,0,.1);
            z-index:1000;
        }

        .header-logo img {
            height:120px;
            width:auto;
            display:block;
        }

        .header-user {
            font-size:1.1em;
            display:flex;
            align-items:center;
        }

        .logout-btn {
            color:red;
            text-decoration:none;
            font-weight:bold;
            margin-left:15px;
        }

        .content-wrapper {
            width:100%;
            max-width:1000px;
            margin:140px auto 20px;
            padding:0 20px;
        }

        .room-details-card {
            background:#fff;
            width:100%;
            border-radius:20px;
            overflow:hidden;
            box-shadow:0 10px 30px rgba(0,0,0,.1);
            border:1px solid #eee;
        }

        .hero-image {
            width:100%;
            height:450px;
            object-fit:cover;
            display:block;
        }

        .details-body { padding:40px; }

        .title-row {
            display:flex;
            justify-content:space-between;
            align-items:center;
            margin-bottom:25px;
        }

        .room-title {
            font-size:32px;
            color:#2c3e50;
            margin:0;
        }

        .price-tag {
            font-size:28px;
            color:#27ae60;
            font-weight:bold;
        }

        .info-grid {
            display:flex;
            gap:20px;
            margin-bottom:40px;
        }

        .info-box {
            background:#f9f9f9;
            padding:20px;
            border-radius:12px;
            flex:1;
            text-align:center;
            border:1px solid #eef0f2;
        }

        .info-box strong {
            display:block;
            color:#999;
            font-size:12px;
            text-transform:uppercase;
            margin-bottom:8px;
        }

        .info-box span {
            font-size:18px;
            font-weight:bold;
            color:#333;
        }

        .gallery-container {
            display:flex;
            gap:15px;
            overflow-x:auto;
            padding-bottom:10px;
            margin-bottom:30px;
        }

        .gallery-container img {
            width:180px;
            height:120px;
            object-fit:cover;
            border-radius:10px;
            border:1px solid #ddd;
        }

        .description-area {
            background:#fcfcfc;
            padding:25px;
            border-radius:12px;
            border-left:6px solid #2c3e50;
            color:#555;
            line-height:1.6;
        }
    </style>
</head>
<body>

    <div class="main-header">
        <div class="header-logo">
            <img src="../public/images/logo.png" alt="Logo">
        </div>
        <div class="header-user">
            Welcome, <strong><?php echo $_SESSION['username']; ?></strong>
            <a href="../Controller/logoutController.php" class="logout-btn">Logout</a>
        </div>
    </div>

    <div class="content-wrapper">
        <a href="all_rooms.php"
           style="text-decoration:none; color:#666; font-weight:bold; margin-bottom:20px; display:block;">
            
        </a>

        <div class="room-details-card">
            <img src="../public/images/<?php echo $room['thumbnail']; ?>" class="hero-image" alt="Room Image">

            <div class="details-body">
                <div class="title-row">
                    <h1 class="room-title">
                        Room <?php echo $room['room_number']; ?>
                        (<?php echo $room['room_type']; ?>)
                    </h1>
                    <div class="price-tag">৳ <?php echo $room['price']; ?> / night</div>
                </div>

                <div class="info-grid">
                    <div class="info-box">
                        <strong>Capacity</strong>
                        <span><?php echo $room['capacity']; ?> Persons</span>
                    </div>
                    <div class="info-box">
                        <strong>Floor</strong>
                        <span><?php echo $room['floor']; ?></span>
                    </div>
                    <div class="info-box">
                        <strong>Amenities</strong>
                        <span><?php echo $room['amidity']; ?></span>
                    </div>
                </div>

                <h3 style="margin-bottom:15px;">Photo Gallery</h3>
                <div class="gallery-container">
                    <?php
                    $imageList = explode(',', $room['images']);
                    foreach($imageList as $imgName) {
                        $trimmedImg = trim($imgName);
                        if(!empty($trimmedImg)) {
                            echo '<img src="../public/images/'.$trimmedImg.'" alt="Room">';
                        }
                    }
                    ?>
                </div>

                <div class="description-area">
                    <h3 style="margin-top:0;">About this Room</h3>
                    <p><?php echo $room['room_description']; ?></p>
                </div>

                <div style="margin-top:20px; font-style:italic; color:#999; font-size:13px;">
                    Staff Notes: <?php echo $room['notes']; ?>
                </div>

                <div style="text-align: center; margin-top: 40px; border-top: 1px solid #eee; padding-top: 20px;">
                    <a href="book_room.php?id=<?php echo $room['room_id']; ?>" 
                       style="background: #27ae60; color: white; padding: 15px 40px; text-decoration: none; border-radius: 30px; font-weight: bold; font-size: 18px; display: inline-block; box-shadow: 0 4px 10px rgba(39, 174, 96, 0.3);">
                        Book This Room Now
                    </a>
                </div>
                

            </div>
        </div>
    </div>

</body>
</html>
