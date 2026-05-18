<?php
include("../Control/activecheck.php");
include("header.php");
include("receptionistsidebar.php");
require_once("../Model/db.php");

$db = new db();
$conn = $db->openConn();
$search = isset($_GET["search"]) ? trim($_GET["search"]) : "";
$like = "%".$search."%";

$sql = "SELECT b.booking_id, u.name AS guest_name, r.room_number, b.checkin_date, b.checkout_date,
               IFNULL(bl.payment_status, 'pending') AS payment_status, IFNULL(bl.total_amount, b.total_price) AS bill_total
        FROM bookings b
        JOIN users u ON b.guest_id = u.user_id
        JOIN rooms r ON b.room_id = r.room_id
        LEFT JOIN billing bl ON b.booking_id = bl.booking_id
        WHERE b.bookin_date IS NOT NULL AND b.bookout_date IS NULL
          AND (? = '' OR u.name LIKE ? OR r.room_number LIKE ?)
        ORDER BY b.checkout_date ASC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $search, $like, $like);
$stmt->execute();
$result = $stmt->get_result();
?>

<link rel="stylesheet" href="../CSS/checkout.css">

<div class="main-content">
    <h1>Guest Check-out</h1>

    <?php
    if(isset($_SESSION['checkout_success'])) {
        echo "<p class='success-message'>".$_SESSION['checkout_success']."</p>";
        unset($_SESSION['checkout_success']);
    }
    if(isset($_SESSION['checkout_error'])) {
        echo "<p class='error-message'>".$_SESSION['checkout_error']."</p>";
        unset($_SESSION['checkout_error']);
    }
    ?>

    <form method="get" class="search-panel">
        <input type="text" name="search" placeholder="Search by room number or guest name" value="<?php echo htmlspecialchars($search); ?>">
        <button type="submit">Search</button>
        <a href="checkout.php">Reset</a>
    </form>

    <div class="checkout-table-container">
        <table>
            <thead>
                <tr>
                    <th>Booking ID</th>
                    <th>Guest Name</th>
                    <th>Room</th>
                    <th>Stay Dates</th>
                    <th>Bill</th>
                    <th>Payment</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row['booking_id']; ?></td>
                    <td><?php echo htmlspecialchars($row['guest_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['room_number']); ?></td>
                    <td><?php echo $row['checkin_date']." to ".$row['checkout_date']; ?></td>
                    <td><?php echo number_format($row['bill_total'], 2); ?> BDT</td>
                    <td><span class="status-badge status-<?php echo $row['payment_status']; ?>"><?php echo ucfirst($row['payment_status']); ?></span></td>
                    <td>
                        <?php if($row['payment_status'] == 'paid') { ?>
                        <form action="../Control/checkoutController.php" method="post" class="inline-form">
                            <input type="hidden" name="booking_id" value="<?php echo $row['booking_id']; ?>">
                            <label class="check-label">
                                <input type="checkbox" name="charges_settled" value="1" required> Settled
                            </label>
                            <button type="submit" class="btn-checkout">Check Out</button>
                        </form>
                        <?php } else { ?>
                            <a class="btn-pay-link" href="paymentlist.php?search=<?php echo $row['booking_id']; ?>">Go to Payment</a>
                        <?php } ?>
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
