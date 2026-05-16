<?php
include("../Control/activecheck.php");
include("header.php");
include("receptionistsidebar.php");
require_once("../Model/db.php");

$db = new db();
$conn = $db->openConn();
$today = date("Y-m-d");

$sql_checkins = "SELECT COUNT(*) AS total_checkins FROM bookings WHERE checkin_date = ?";
$stmt = $conn->prepare($sql_checkins);
$stmt->bind_param("s", $today);
$stmt->execute();
$result = $stmt->get_result();
$checkins = $result->fetch_assoc()["total_checkins"];

$sql_checkouts = "SELECT COUNT(*) AS total_checkouts FROM bookings WHERE checkout_date = ?";
$stmt = $conn->prepare($sql_checkouts);
$stmt->bind_param("s", $today);
$stmt->execute();
$result = $stmt->get_result();
$checkouts = $result->fetch_assoc()["total_checkouts"];

$sql_checkedin = "SELECT COUNT(*) AS currently_checkedin FROM bookings WHERE checkin_date <= ? AND checkout_date >= ?";
$stmt = $conn->prepare($sql_checkedin);
$stmt->bind_param("ss", $today, $today);
$stmt->execute();
$result = $stmt->get_result();
$checkedin = $result->fetch_assoc()["currently_checkedin"];

$sql_available = "SELECT COUNT(*) AS available_rooms FROM rooms WHERE notes = 'available'";
$result = $conn->query($sql_available);
$available_rooms = $result->fetch_assoc()["available_rooms"];

$db->closeConn($conn);
?>

<link rel="stylesheet" href="../CSS/receptionistdashboard.css">

<div class="main-content">
    <h1>Receptionist Dashboard</h1>

    <div class="dashboard-cards">
        <div class="card card-blue">
            <h3>Today's Check-ins</h3>
            <p><?php echo $checkins; ?></p>
        </div>
        <div class="card card-red">
            <h3>Today's Check-outs</h3>
            <p><?php echo $checkouts; ?></p>
        </div>
        <div class="card card-green">
            <h3>Currently Checked-in Guests</h3>
            <p><?php echo $checkedin; ?></p>
        </div>
        <div class="card card-yellow">
            <h3>Available Rooms</h3>
            <p><?php echo $available_rooms; ?></p>
        </div>
    </div>

    <h2>Today’s Check-in List</h2>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Booking ID</th>
                    <th>Guest Name</th>
                    <th>Room Number</th>
                    <th>Check-in Date</th>
                    <th>Check-out Date</th>
                    <th>Number of Guests</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $db = new db();
                $conn = $db->openConn();
                $sql = "SELECT b.booking_id, u.name AS guest_name, r.room_number, b.checkin_date, b.checkout_date, b.num_guests
                        FROM bookings b
                        JOIN users u ON b.guest_id = u.user_id
                        JOIN rooms r ON b.room_id = r.room_id
                        WHERE b.checkin_date = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("s", $today);
                $stmt->execute();
                $result = $stmt->get_result();

                while($row = $result->fetch_assoc()){
                    echo "<tr>
                        <td>{$row['booking_id']}</td>
                        <td>{$row['guest_name']}</td>
                        <td>{$row['room_number']}</td>
                        <td>{$row['checkin_date']}</td>
                        <td>{$row['checkout_date']}</td>
                        <td>{$row['num_guests']}</td>
                    </tr>";
                }

                $stmt->close();
                $db->closeConn($conn);
                ?>
            </tbody>
        </table>
    </div>
</div>