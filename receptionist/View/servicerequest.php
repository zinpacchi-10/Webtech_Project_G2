<?php
include("../Control/activecheck.php");
include("header.php");
include("receptionistsidebar.php");
require_once("../Model/db.php");

$db = new db();
$conn = $db->openConn();

// Fetch all pending/in-progress service requests for occupied rooms
$sql = "SELECT sr.id, u.name AS guest_name, r.room_number, sr.service_type, sr.description, sr.status, sr.requested_at
        FROM service_requests sr
        JOIN users u ON sr.guest_id = u.user_id
        JOIN rooms r ON sr.room_id = r.room_id
        WHERE sr.status IN ('pending','in_progress')";

$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
?>

<link rel="stylesheet" href="../CSS/servicerequest.css">

<div class="main-content">
    <h1>Guest Service Requests</h1>

    <?php
    if(isset($_SESSION['service_success'])) {
        echo "<p class='success-message'>".$_SESSION['service_success']."</p>";
        unset($_SESSION['service_success']);
    }
    if(isset($_SESSION['service_error'])) {
        echo "<p class='error-message'>".$_SESSION['service_error']."</p>";
        unset($_SESSION['service_error']);
    }
    ?>

    <div class="service-table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Guest Name</th>
                    <th>Room Number</th>
                    <th>Service Type</th>
                    <th>Description</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['guest_name']; ?></td>
                    <td><?php echo $row['room_number']; ?></td>
                    <td><?php echo $row['service_type']; ?></td>
                    <td><?php echo $row['description']; ?></td>
                    <td><?php echo ucfirst(str_replace("_"," ",$row['status'])); ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>