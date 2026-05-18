<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Hotel Booking System</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    
    <!-- <li><a href="../controllers/AdminController.php?action=room_status_ajax">Room Status (AJAX)</a></li> -->
</head>
<body>
    <div class="admin-wrapper">
        <div class="sidebar">
            <h3>Hotel Admin</h3>
            <ul>
                <!-- এই লিংক গুলো ঠিক করুন -->
                <li><a href="../controllers/AdminController.php?action=dashboard">Dashboard</a></li>
                <li><a href="../controllers/AdminController.php?action=rooms">Manage Rooms</a></li>
                <li><a href="../controllers/AdminController.php?action=seasonal_pricing">Seasonal Pricing</a></li>
                <li><a href="../controllers/AdminController.php?action=bookings">All Bookings</a></li>
                <li><a href="../controllers/AdminController.php?action=reviews">Guest Reviews</a></li>
                <li><a href="../controllers/AdminController.php?action=users">Manage Users</a></li>
                <li><a href="../controllers/AuthController.php?action=logout" onclick="return confirm('Logout?')">Logout</a></li>
            </ul>
        </div>
        <div class="main-content">
            <div class="top-bar">
                <span>Welcome, <?php echo $_SESSION['admin_name'] ?? 'Admin'; ?></span>
                <a href="../controllers/AuthController.php?action=logout" class="logout-btn">Logout</a>
            </div>
            <div class="content">

