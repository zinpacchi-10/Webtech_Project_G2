<?php
session_start();
if(!isset($_SESSION['admin_id'])) {
    header("Location: adminlogin.php");
}
include("header.php");
include("navbar.php");
include("adminsidebar.php");
?>

<div class="main-content">
    <h1>Welcome, <?php echo $_SESSION['admin_name']; ?>!</h1>
    <div class="stats-container">
        <div class="card">Total Rooms: <span>12</span></div>
        <div class="card">Today's Bookings: <span>5</span></div>
        <div class="card">Total Revenue: <span>৳ 50,000</span></div>
    </div>
</div>