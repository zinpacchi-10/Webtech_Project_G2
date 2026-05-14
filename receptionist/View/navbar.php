<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/navbar.css">
</head>
<body>
    <div class="nav-wrapper">
        <div class="topnav" id="theTopNav">
            <a href="javascript:void(0);" class="icon" onclick="openNav()" id="hamburger">&#9776;</a>

            <a href="../View/adminhomepage.php">Dashboard</a>
            <a href="../View/checkin.php">Check-In</a>
            <a href="../View/checkout.php">Check-Out</a>
            <a href="../View/walkin.php">Walk-In Booking</a>

            <a id="logout" href="../Control/adminlogout.php">Logout</a>
            <a id="user">Welcome<?php include('../Control/cookie.php'); ?></a>
        </div>
    </div>

    <script src="../JS/receptionistsidebar.js"></script>
</body>
</html>