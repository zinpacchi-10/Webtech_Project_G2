<?php
session_start();
if(!isset($_SESSION['admin_id'])) { header("Location: adminlogin.php"); }
require_once("../Model/booking_model.php");
$bookingModel = new BookingModel();
$bookings = $bookingModel->getAllBookingDetails();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Booking Management</title>
    <link rel="stylesheet" href="../CSS/booking_manage.css">
</head>
<body class="bodycolor">
    <?php include("header.php"); ?>
    <?php include("adminsidebar.php"); ?>

    <div class="container">
        <h2>Hotel Booking Overview</h2>
        
        <?php if(isset($_GET['msg'])) echo "<p class='alert'>".$_GET['msg']."</p>"; ?>

        <table border="1" width="100%" style="border-collapse: collapse; text-align: left;">
            <thead style="background-color: #f2f2f2;">
                <tr>
                    <th>Booking ID</th>
                    <th>Guest Name</th>
                    <th>Room No</th>
                    <th>Check-In</th>
                    <th>Check-Out</th>
                    <th>Total Price</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if($bookings->num_rows > 0): ?>
                    <?php while($row = $bookings->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['booking_id']; ?></td>
                        <td><?php echo $row['guest_name']; ?> (<?php echo $row['guest_email']; ?>)</td>
                        <td><?php echo $row['room_number']; ?></td>
                        <td><?php echo $row['checkin_date']; ?></td>
                        <td><?php echo $row['checkout_date']; ?></td>
                        <td>৳ <?php echo $row['total_price']; ?></td>
                        <td>
                            <a href="../Control/booking_manage_process.php?cancel_id=<?php echo $row['booking_id']; ?>" 
                               onclick="return confirm('Are you sure you want to cancel this booking?')" 
                               style="color:red">Cancel Booking</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="7" align="center">No bookings found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>