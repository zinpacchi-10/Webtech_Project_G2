<?php
include("../Control/activecheck.php");
include("header.php");
include("receptionistsidebar.php");
require_once("../Model/db.php");

$db = new db();
$conn = $db->openConn();

$table_check = $conn->query("SHOW TABLES LIKE 'early_late_requests'");
$table_exists = $table_check && $table_check->num_rows > 0;

if($table_exists){
    $sql = "SELECT elr.id, u.name AS guest_name, rm.room_number, elr.request_type, elr.request_date, elr.status,
                   b.booking_id, b.checkin_date, b.checkout_date
            FROM early_late_requests elr
            JOIN bookings b ON elr.booking_id=b.booking_id
            JOIN users u ON elr.guest_id=u.user_id
            JOIN rooms rm ON elr.room_id=rm.room_id
            WHERE elr.status='pending'
            ORDER BY elr.request_date ASC";

    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
}
?>

<link rel="stylesheet" href="../CSS/early_late_requests.css">

<div class="main-content">
    <h1>Early Check-in / Late Check-out Requests</h1>

    <?php
    if(isset($_SESSION['earlylate_success'])){
        echo "<p class='success-message'>".$_SESSION['earlylate_success']."</p>";
        unset($_SESSION['earlylate_success']);
    }
    if(isset($_SESSION['earlylate_error'])){
        echo "<p class='error-message'>".$_SESSION['earlylate_error']."</p>";
        unset($_SESSION['earlylate_error']);
    }
    ?>

    <?php if(!$table_exists){ ?>
        <p class="error-message">early_late_requests table is missing. Run SQL/receptionist_setup.sql once in the hotelbookingsystem database.</p>
    <?php } else { ?>
    <div class="request-table-container">
        <table>
            <thead>
                <tr>
                    <th>Request ID</th>
                    <th>Booking</th>
                    <th>Guest</th>
                    <th>Room</th>
                    <th>Type</th>
                    <th>Requested Date</th>
                    <th>Current Stay</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row=$result->fetch_assoc()){ ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td>#<?php echo $row['booking_id']; ?></td>
                    <td><?php echo htmlspecialchars($row['guest_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['room_number']); ?></td>
                    <td><?php echo ucfirst(str_replace("_"," ",$row['request_type'])); ?></td>
                    <td><?php echo $row['request_date']; ?></td>
                    <td><?php echo $row['checkin_date']." to ".$row['checkout_date']; ?></td>
                    <td>
                        <form action="../Control/earlyLateController.php" method="post" class="inline-form">
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
    <?php } ?>
</div>

<?php
if($table_exists){
    $stmt->close();
}
$db->closeConn($conn);
?>
