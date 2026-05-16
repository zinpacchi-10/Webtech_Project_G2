<?php
include("../Control/activecheck.php");
include("header.php");
include("receptionistsidebar.php");
require_once("../Model/db.php");

$db = new db();
$conn = $db->openConn();

$sql = "SELECT b.booking_id, u.name AS guest_name, r.room_number, b.checkin_date, b.checkout_date, b.num_guests,
               IF(b.bookin_date IS NULL, 'Pending', IF(b.bookout_date IS NULL, 'Checked In', 'Checked Out')) AS booking_status
        FROM bookings b
        JOIN users u ON b.guest_id = u.user_id
        JOIN rooms r ON b.room_id = r.room_id
        WHERE b.bookout_date IS NULL
        ORDER BY b.checkin_date ASC";

$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
?>

<link rel="stylesheet" href="../CSS/modifybooking.css">

<div class="main-content">
    <h1>Modify Booking</h1>

    <?php
    if(isset($_SESSION['modify_success'])) {
        echo "<p class='success-message'>".$_SESSION['modify_success']."</p>";
        unset($_SESSION['modify_success']);
    }
    if(isset($_SESSION['modify_error'])) {
        echo "<p class='error-message'>".$_SESSION['modify_error']."</p>";
        unset($_SESSION['modify_error']);
    }
    ?>

    <div class="modify-table-container">
        <table>
            <thead>
                <tr>
                    <th>Booking ID</th>
                    <th>Guest</th>
                    <th>Room</th>
                    <th>Current Dates</th>
                    <th>Guests</th>
                    <th>Status</th>
                    <th>New Dates</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row['booking_id']; ?></td>
                    <td><?php echo htmlspecialchars($row['guest_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['room_number']); ?></td>
                    <td><?php echo $row['checkin_date']." to ".$row['checkout_date']; ?></td>
                    <td><?php echo $row['num_guests']; ?></td>
                    <td><span class="status-badge"><?php echo $row['booking_status']; ?></span></td>
                    <td>
                        <form action="../Control/modifyBookingController.php" method="post" class="modify-form">
                            <input type="hidden" name="booking_id" value="<?php echo $row['booking_id']; ?>">
                            <input type="date" name="checkin_date" required>
                            <input type="date" name="checkout_date" required>
                            <button type="submit" class="btn-modify">Update</button>
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
