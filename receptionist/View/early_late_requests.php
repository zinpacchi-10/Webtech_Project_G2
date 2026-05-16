<?php
include("../Control/activecheck.php");
include("header.php");
include("receptionistsidebar.php");
require_once("../Model/db.php");

$db = new db();
$conn = $db->openConn();

$sql = "SELECT r.id, u.name AS guest_name, r.room_id, rm.room_number, r.request_type, r.request_date, r.status 
        FROM early_late_requests r
        JOIN users u ON r.guest_id=u.user_id
        JOIN rooms rm ON r.room_id=rm.room_id
        WHERE r.status='pending'";

$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
?>

<link rel="stylesheet" href="../CSS/early_late_requests.css">

<div class="main-content">
    <h1>Early Check-in / Late Check-out Requests</h1>
    <div class="request-table-container">
        <table>
            <thead>
                <tr>
                    <th>Request ID</th>
                    <th>Guest Name</th>
                    <th>Room Number</th>
                    <th>Request Type</th>
                    <th>Request Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row=$result->fetch_assoc()){ ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['guest_name']; ?></td>
                    <td><?php echo $row['room_number']; ?></td>
                    <td><?php echo ucfirst(str_replace("_"," ",$row['request_type'])); ?></td>
                    <td><?php echo $row['request_date']; ?></td>
                    <td><?php echo ucfirst($row['status']); ?></td>
                    <td>
                        <form action="../Control/earlyLateController.php" method="post">
                            <input type="hidden" name="request_id" value="<?php echo $row['id']; ?>">
                            <select name="status" required>
                                <option value="approved">Approve</option>
                                <option value="declined">Decline</option>
                            </select>
                            <button type="submit" class="btn-action">Update</button>
                        </form>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>