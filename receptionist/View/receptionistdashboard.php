<?php
include("../Control/activecheck.php");
include("header.php");
include("receptionistsidebar.php");
require_once("../Model/db.php");

$db = new db();
$conn = $db->openConn();
$today = date("Y-m-d");

$sql_checkins = "SELECT COUNT(*) AS total_checkins FROM bookings WHERE checkin_date = ? AND bookin_date IS NULL";
$stmt = $conn->prepare($sql_checkins);
$stmt->bind_param("s", $today);
$stmt->execute();
$total_checkins = $stmt->get_result()->fetch_assoc()["total_checkins"];

$sql_checkouts = "SELECT COUNT(*) AS total_checkouts FROM bookings WHERE checkout_date = ? AND bookin_date IS NOT NULL AND bookout_date IS NULL";
$stmt = $conn->prepare($sql_checkouts);
$stmt->bind_param("s", $today);
$stmt->execute();
$total_checkouts = $stmt->get_result()->fetch_assoc()["total_checkouts"];

$sql_checkedin = "SELECT COUNT(*) AS currently_checkedin FROM bookings WHERE bookin_date IS NOT NULL AND bookout_date IS NULL";
$stmt = $conn->prepare($sql_checkedin);
$stmt->execute();
$checkedin = $stmt->get_result()->fetch_assoc()["currently_checkedin"];

$sql_available = "SELECT COUNT(*) AS available_rooms FROM rooms WHERE notes = 'available'";
$stmt = $conn->prepare($sql_available);
$stmt->execute();
$available_rooms = $stmt->get_result()->fetch_assoc()["available_rooms"];

$sql_available_type = "SELECT room_type, COUNT(*) AS total FROM rooms WHERE notes='available' GROUP BY room_type ORDER BY room_type";
$available_by_type = $conn->query($sql_available_type);
?>

<link rel="stylesheet" href="../CSS/receptionistdashboard.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="main-content">
    <h1>Receptionist Dashboard</h1>

    <div class="dashboard-cards">
        <div class="card card-blue">
            <h3>Expected Check-ins</h3>
            <p><?php echo $total_checkins; ?></p>
        </div>
        <div class="card card-red">
            <h3>Expected Check-outs</h3>
            <p><?php echo $total_checkouts; ?></p>
        </div>
        <div class="card card-green">
            <h3>Currently Checked-in</h3>
            <p><?php echo $checkedin; ?></p>
        </div>
        <div class="card card-yellow">
            <h3>Available Rooms</h3>
            <p><?php echo $available_rooms; ?></p>
        </div>
    </div>

    <div class="dashboard-grid">
        <div class="chart-container">
            <canvas id="summaryChart"></canvas>
        </div>

        <div class="type-panel">
            <h2>Available Rooms by Type</h2>
            <?php while($type = $available_by_type->fetch_assoc()) { ?>
                <div class="type-row">
                    <span><?php echo htmlspecialchars($type["room_type"]); ?></span>
                    <strong><?php echo $type["total"]; ?></strong>
                </div>
            <?php } ?>
        </div>
    </div>

    <h2>Today's Check-in List</h2>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Booking ID</th>
                    <th>Guest Name</th>
                    <th>Room Type</th>
                    <th>Room Number</th>
                    <th>Check-in</th>
                    <th>Check-out</th>
                    <th>Guests</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT b.booking_id, u.name AS guest_name, r.room_number, r.room_type, b.checkin_date, b.checkout_date, b.num_guests
                        FROM bookings b
                        JOIN users u ON b.guest_id = u.user_id
                        JOIN rooms r ON b.room_id = r.room_id
                        WHERE b.checkin_date = ? AND b.bookin_date IS NULL
                        ORDER BY b.created_at ASC";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("s", $today);
                $stmt->execute();
                $result = $stmt->get_result();
                while($row = $result->fetch_assoc()){
                    echo "<tr>
                        <td>{$row['booking_id']}</td>
                        <td>".htmlspecialchars($row['guest_name'])."</td>
                        <td>".htmlspecialchars($row['room_type'])."</td>
                        <td>".htmlspecialchars($row['room_number'])."</td>
                        <td>{$row['checkin_date']}</td>
                        <td>{$row['checkout_date']}</td>
                        <td>{$row['num_guests']}</td>
                    </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<script>
var ctx = document.getElementById('summaryChart').getContext('2d');
var summaryChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Check-ins','Check-outs','In-house','Available'],
        datasets: [{
            label: 'Today Summary',
            data: [<?php echo $total_checkins; ?>, <?php echo $total_checkouts; ?>, <?php echo $checkedin; ?>, <?php echo $available_rooms; ?>],
            backgroundColor: ['#2563eb','#ef4444','#10b981','#f59e0b'],
            borderRadius: 8
        }]
    },
    options: {
        responsive:true,
        plugins:{legend:{display:false}},
        scales:{y:{beginAtZero:true}}
    }
});
</script>

<?php
$stmt->close();
$db->closeConn($conn);
?>
