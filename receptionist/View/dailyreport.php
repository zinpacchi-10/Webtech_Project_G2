<?php
include("../Control/activecheck.php");
include("header.php");
include("receptionistsidebar.php");
require_once("../Model/db.php");

$db = new db();
$conn = $db->openConn();

$today = date("Y-m-d");

// Total check-ins today
$sql_checkins = "SELECT COUNT(*) AS total_checkins FROM bookings WHERE checkin_date=?";
$stmt = $conn->prepare($sql_checkins);
$stmt->bind_param("s", $today);
$stmt->execute();
$total_checkins = $stmt->get_result()->fetch_assoc()["total_checkins"];

// Total check-outs today
$sql_checkouts = "SELECT COUNT(*) AS total_checkouts FROM bookings WHERE checkout_date=?";
$stmt2 = $conn->prepare($sql_checkouts);
$stmt2->bind_param("s", $today);
$stmt2->execute();
$total_checkouts = $stmt2->get_result()->fetch_assoc()["total_checkouts"];

// Total walk-ins today
$sql_walkins = "SELECT COUNT(*) AS total_walkins FROM bookings WHERE notes='checked_in' AND created_at LIKE CONCAT(?, '%')";
$stmt3 = $conn->prepare($sql_walkins);
$stmt3->bind_param("s", $today);
$stmt3->execute();
$total_walkins = $stmt3->get_result()->fetch_assoc()["total_walkins"];

// Total revenue today (paid bookings)
$sql_revenue = "SELECT IFNULL(SUM(total_price),0) AS total_revenue FROM bookings WHERE notes='paid' AND checkin_date=?";
$stmt4 = $conn->prepare($sql_revenue);
$stmt4->bind_param("s", $today);
$stmt4->execute();
$total_revenue = $stmt4->get_result()->fetch_assoc()["total_revenue"];

// Rooms currently occupied vs available
$sql_rooms = "SELECT COUNT(*) AS occupied FROM rooms WHERE notes='occupied'";
$occupied = $conn->query($sql_rooms)->fetch_assoc()["occupied"];

$sql_rooms2 = "SELECT COUNT(*) AS available FROM rooms WHERE notes='available'";
$available = $conn->query($sql_rooms2)->fetch_assoc()["available"];

$db->closeConn($conn);
?>

<link rel="stylesheet" href="../CSS/dailyreport.css">

<div class="main-content">
    <h1>Daily Operations Report (<?php echo $today; ?>)</h1>

    <div class="report-cards">
        <div class="card">
            <h3>Total Check-ins</h3>
            <p><?php echo $total_checkins; ?></p>
        </div>
        <div class="card">
            <h3>Total Check-outs</h3>
            <p><?php echo $total_checkouts; ?></p>
        </div>
        <div class="card">
            <h3>Total Walk-ins</h3>
            <p><?php echo $total_walkins; ?></p>
        </div>
        <div class="card">
            <h3>Total Revenue</h3>
            <p><?php echo $total_revenue; ?> BDT</p>
        </div>
        <div class="card">
            <h3>Rooms Occupied</h3>
            <p><?php echo $occupied; ?></p>
        </div>
        <div class="card">
            <h3>Rooms Available</h3>
            <p><?php echo $available; ?></p>
        </div>
    </div>
</div>