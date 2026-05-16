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

$sql = "SELECT b.booking_id, u.name AS guest_name, u.email, r.room_number, r.room_type,
               b.checkin_date, b.checkout_date, b.total_price,
               bl.base_amount, bl.extras_amount, bl.discount_amount, bl.total_amount,
               bl.payment_method, bl.payment_status, bl.paid_at
        FROM bookings b
        JOIN users u ON b.guest_id = u.user_id
        JOIN rooms r ON b.room_id = r.room_id
        LEFT JOIN billing bl ON b.booking_id = bl.booking_id
        WHERE b.booking_id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i",$booking_id);
$stmt->execute();
$result = $stmt->get_result();
$booking = $result->fetch_assoc();

$stmt->close();
$db->closeConn($conn);
?>

<link rel="stylesheet" href="../CSS/receipt.css">

<div class="main-content">
    <h1>Payment Receipt</h1>
    <?php if($booking){ ?>
    <div class="receipt-card">
        <div class="receipt-head">
            <div>
                <h2>Hotel Booking Management System</h2>
                <p>Receipt #<?php echo $booking['booking_id']; ?></p>
            </div>
            <span class="status-badge status-<?php echo $booking['payment_status']; ?>"><?php echo ucfirst($booking['payment_status'] ? $booking['payment_status'] : 'pending'); ?></span>
        </div>

        <div class="receipt-grid">
            <p><strong>Guest:</strong> <?php echo htmlspecialchars($booking['guest_name']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($booking['email']); ?></p>
            <p><strong>Room:</strong> <?php echo htmlspecialchars($booking['room_number']." (".$booking['room_type'].")"); ?></p>
            <p><strong>Stay:</strong> <?php echo $booking['checkin_date']." to ".$booking['checkout_date']; ?></p>
            <p><strong>Payment Method:</strong> <?php echo ucfirst(str_replace("_"," ",$booking['payment_method'])); ?></p>
            <p><strong>Paid At:</strong> <?php echo $booking['paid_at']; ?></p>
        </div>

        <div class="receipt-lines">
            <p><span>Base Amount</span><strong><?php echo number_format($booking['base_amount'] ? $booking['base_amount'] : $booking['total_price'], 2); ?> BDT</strong></p>
            <p><span>Service Extras</span><strong><?php echo number_format($booking['extras_amount'], 2); ?> BDT</strong></p>
            <p><span>Loyalty Discount</span><strong><?php echo number_format($booking['discount_amount'], 2); ?> BDT</strong></p>
            <p class="total"><span>Total Paid</span><strong><?php echo number_format($booking['total_amount'] ? $booking['total_amount'] : $booking['total_price'], 2); ?> BDT</strong></p>
        </div>
    </div>
    <button onclick="window.print()" class="btn-print">Print Receipt</button>
    <?php } else { ?>
        <p>Booking not found.</p>
    <?php } ?>
</div>
