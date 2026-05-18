<?php
session_start();
if(!isset($_SESSION['admin_id'])) { header("Location: adminlogin.php"); }
require_once("../Model/booking_model.php");
$bookingModel = new BookingModel();

$totalRevenue = $bookingModel->getTotalRevenue();
$activeBookings = $bookingModel->getActiveBookingsCount();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Financial Report</title>
    <link rel="stylesheet" href="../CSS/report.css">
    <style>
        .report-grid { display: flex; gap: 20px; margin-bottom: 30px; }
        .report-card { 
            background: white; padding: 20px; border-radius: 10px; 
            box-shadow: 0 4px 8px rgba(0,0,0,0.1); flex: 1; text-align: center;
        }
        .report-card h3 { color: #555; margin-bottom: 10px; }
        .report-card p { font-size: 24px; font-weight: bold; color: #2c3e50; }
    </style>
</head>
<body class="bodycolor">
    <?php include("header.php"); ?>
    <?php include("adminsidebar.php"); ?>

    <div class="container">
        <h2>Financial & Occupancy Report</h2>
        <p>Real-time business analytics for your hotel.</p>

        <div class="report-grid">
            <div class="report-card">
                <h3>Total Revenue</h3>
                <p>৳ <?php echo number_format($totalRevenue, 2); ?></p>
            </div>
            <div class="report-card">
                <h3>Active Guests</h3>
                <p><?php echo $activeBookings; ?> Guests</p>
            </div>
            <div class="report-card">
                <h3>Total Rooms</h3>
                <p>12 Rooms</p> <!-- static as per your 123.txt -->
            </div>
        </div>

        <div class="details-section" style="background: white; padding: 20px; border-radius: 10px;">
            <h3>Revenue Analysis</h3>
            <p>The total revenue is calculated based on all successful bookings in the system. 
               Currently, you have <b><?php echo $activeBookings; ?></b> active bookings for today.</p>
            <hr>
            <a href="booking_list.php" class="btn">View All Bookings</a>
        </div>
    </div>
</body>
</html>