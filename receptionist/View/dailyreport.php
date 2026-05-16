<?php
include("../Control/activecheck.php");
include("header.php");
include("receptionistsidebar.php");
require_once("../Model/db.php");

$db = new db();
$conn = $db->openConn();

$today = date("Y-m-d");

$sql_checkins = "SELECT COUNT(*) AS total_checkins FROM bookings WHERE bookin_date=?";
$stmt = $conn->prepare($sql_checkins);
$stmt->bind_param("s", $today);
$stmt->execute();
$total_checkins = $stmt->get_result()->fetch_assoc()["total_checkins"];

$sql_checkouts = "SELECT COUNT(*) AS total_checkouts FROM bookings WHERE bookout_date=?";
$stmt2 = $conn->prepare($sql_checkouts);
$stmt2->bind_param("s", $today);
$stmt2->execute();
$total_checkouts = $stmt2->get_result()->fetch_assoc()["total_checkouts"];

$sql_walkins = "SELECT COUNT(*) AS total_walkins FROM bookings WHERE DATE(created_at)=? AND bookin_date=?";
$stmt3 = $conn->prepare($sql_walkins);
$stmt3->bind_param("ss", $today, $today);
$stmt3->execute();
$total_walkins = $stmt3->get_result()->fetch_assoc()["total_walkins"];

$sql_revenue = "SELECT IFNULL(SUM(total_amount),0) AS total_revenue FROM billing WHERE payment_status='paid' AND DATE(paid_at)=?";
$stmt4 = $conn->prepare($sql_revenue);
$stmt4->bind_param("s", $today);
$stmt4->execute();
$total_revenue = $stmt4->get_result()->fetch_assoc()["total_revenue"];

$sql_rooms = "SELECT COUNT(*) AS occupied FROM rooms WHERE notes='occupied'";
$occupied = $conn->query($sql_rooms)->fetch_assoc()["occupied"];

$sql_rooms2 = "SELECT COUNT(*) AS available FROM rooms WHERE notes='available'";
$available = $conn->query($sql_rooms2)->fetch_assoc()["available"];

$sql_dirty = "SELECT COUNT(*) AS dirty FROM rooms WHERE notes='dirty'";
$dirty = $conn->query($sql_dirty)->fetch_assoc()["dirty"];

$stmt->close();
$stmt2->close();
$stmt3->close();
$stmt4->close();
$db->closeConn($conn);
?>

<link rel="stylesheet" href="../CSS/dailyreport.css">

<div class="main-content">
    <h1>Daily Operations Report (<?php echo $today; ?>)</h1>

    <div class="report-cards">
        <div class="card">
            <h3>Total Arrivals</h3>
            <p><?php echo $total_checkins; ?></p>
        </div>
        <div class="card">
            <h3>Total Departures</h3>
            <p><?php echo $total_checkouts; ?></p>
        </div>
        <div class="card">
            <h3>Walk-ins</h3>
            <p><?php echo $total_walkins; ?></p>
        </div>
        <div class="card">
            <h3>Revenue Collected</h3>
            <p><?php echo number_format($total_revenue, 2); ?> BDT</p>
        </div>
        <div class="card">
            <h3>Rooms Occupied</h3>
            <p><?php echo $occupied; ?></p>
        </div>
        <div class="card">
            <h3>Rooms Available</h3>
            <p><?php echo $available; ?></p>
        </div>
        <div class="card">
            <h3>Rooms Dirty</h3>
            <p><?php echo $dirty; ?></p>
        </div>
    </div>

    <button onclick="window.print()" class="btn-print">Print Report</button>
</div>
