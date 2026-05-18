<?php
include("../Control/activecheck.php");
include("header.php");
include("receptionistsidebar.php");
require_once("../Model/db.php");

$db = new db();
$conn = $db->openConn();

$sql = "SELECT sr.service_request_id, u.name AS guest_name, r.room_number, sr.service_type,
               sr.description, sr.status, sr.requested_at
        FROM service_requests sr
        JOIN users u ON sr.guest_id = u.user_id
        JOIN rooms r ON sr.room_id = r.room_id
        WHERE sr.status IN ('pending','in_progress')
        ORDER BY sr.requested_at ASC";

$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
?>

<link rel="stylesheet" href="../CSS/servicerequest.css">

<div class="main-content">
    <h1>Guest Service Requests</h1>

    <?php
    if(isset($_SESSION['service_success'])){
        echo "<p class='success-message'>".$_SESSION['service_success']."</p>";
        unset($_SESSION['service_success']);
    }
    if(isset($_SESSION['service_error'])){
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
                    <th>Room</th>
                    <th>Service Type</th>
                    <th>Description</th>
                    <th>Requested</th>
                    <th>Status</th>
                    <th>Update</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()){ ?>
                <tr>
                    <td><?php echo $row['service_request_id']; ?></td>
                    <td><?php echo htmlspecialchars($row['guest_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['room_number']); ?></td>
                    <td><?php echo ucfirst(str_replace("_"," ",$row['service_type'])); ?></td>
                    <td><?php echo htmlspecialchars($row['description']); ?></td>
                    <td><?php echo $row['requested_at']; ?></td>
                    <td><span class="status-badge status-<?php echo $row['status']; ?>"><?php echo ucfirst(str_replace("_"," ",$row['status'])); ?></span></td>
                    <td>
                        <form action="../Control/updateServiceRequest.php" method="post" class="service-update-form">
                            <input type="hidden" name="request_id" value="<?php echo $row['service_request_id']; ?>">
                            <select name="status" class="service-select">
                                <option value="pending" <?php if($row['status']=='pending') echo 'selected'; ?>>Pending</option>
                                <option value="in_progress" <?php if($row['status']=='in_progress') echo 'selected'; ?>>In Progress</option>
                                <option value="completed" <?php if($row['status']=='completed') echo 'selected'; ?>>Completed</option>
                            </select>
                            <button type="submit" class="btn-update">Update</button>
                        </form>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<?php
$stmt->close();
$db->closeConn($conn);
?>
