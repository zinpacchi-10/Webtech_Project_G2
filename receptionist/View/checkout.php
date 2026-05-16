<?php
include("../Control/activecheck.php");
include("header.php");
include("receptionistsidebar.php");
require_once("../Model/db.php");

$db = new db();
$conn = $db->openConn();

// Today or past checked-in bookings ready for checkout
$sql = "SELECT b.booking_id, u.name AS guest_name, r.room_number, b.checkin_date, b.checkout_date
        FROM bookings b
        JOIN users u ON b.guest_id = u.user_id
        JOIN rooms r ON b.room_id = r.room_id
        WHERE b.notes='checked_in' AND r.notes='occupied'";

$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
?>

<link rel="stylesheet" href="../CSS/checkout.css">

<div class="main-content">
    <h1>Guest Check-out</h1>

    <div class="checkout-table-container">
        <table>
            <thead>
                <tr>
                    <th>Booking ID</th>
                    <th>Guest Name</th>
                    <th>Room Number</th>
                    <th>Check-in Date</th>
                    <th>Check-out Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row['booking_id']; ?></td>
                    <td><?php echo $row['guest_name']; ?></td>
                    <td><?php echo $row['room_number']; ?></td>
                    <td><?php echo $row['checkin_date']; ?></td>
                    <td><?php echo $row['checkout_date']; ?></td>
                    <td>
                        <form action="../Control/checkoutController.php" method="post">
                            <input type="hidden" name="booking_id" value="<?php echo $row['booking_id']; ?>">
                            <button type="submit" class="btn-checkout">Check Out</button>
                        </form>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>