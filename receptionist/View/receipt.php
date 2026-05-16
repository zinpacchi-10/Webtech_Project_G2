<?php
include("../Control/activecheck.php");
include("header.php");
include("receptionistsidebar.php");
require_once("../Model/db.php");

if(!isset($_GET['booking_id'])){
    echo "Invalid request";
    exit();
}

$booking_id = intval($_GET['booking_id']);

$db = new db();
$conn = $db->openConn();

$sql = "SELECT b.booking_id, u.name AS guest_name, u.email, r.room_number, r.room_type, b.checkin_date, b.checkout_date, b.total_price, b.notes
        FROM bookings b
        JOIN users u ON b.guest_id = u.user_id
        JOIN rooms r ON b.room_id = r.room_id
        WHERE b.booking_id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i",$booking_id);
$stmt->execute();
$result = $stmt->get_result();
$booking = $result->fetch_assoc();

$db->closeConn($conn);
?>

<link rel="stylesheet" href="../CSS/receipt.css">

<div class="main-content">
    <h1>Payment Receipt</h1>
    <?php if($booking){ ?>
    <div class="receipt-card">
        <p><strong>Booking ID:</strong> <?php echo $booking['booking_id']; ?></p>
        <p><strong>Guest Name:</strong> <?php echo $booking['guest_name']; ?></p>
        <p><strong>Email:</strong> <?php echo $booking['email']; ?></p>
        <p><strong>Room:</strong> <?php echo $booking['room_number']." (".$booking['room_type'].")"; ?></p>
        <p><strong>Check-in:</strong> <?php echo $booking['checkin_date']; ?></p>
        <p><strong>Check-out:</strong> <?php echo $booking['checkout_date']; ?></p>
        <p><strong>Total Paid:</strong> <?php echo $booking['total_price']; ?> BDT</p>
        <p><strong>Status:</strong> <?php echo ucfirst($booking['notes']); ?></p>
    </div>
    <button onclick="window.print()" class="btn-print">Print Receipt</button>
    <?php } else { ?>
        <p>Booking not found.</p>
    <?php } ?>
</div>