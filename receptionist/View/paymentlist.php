<?php
include("../Control/activecheck.php");
include("header.php");
include("receptionistsidebar.php");
require_once("../Model/db.php");

$db = new db();
$conn = $db->openConn();
$search = isset($_GET["search"]) ? trim($_GET["search"]) : "";
$like = "%".$search."%";

$sql = "SELECT b.booking_id, b.guest_id, u.name AS guest_name, r.room_number, b.checkin_date, b.checkout_date,
               b.total_price, IFNULL(bl.payment_status, 'pending') AS payment_status,
               IFNULL(bl.extras_amount, 0) AS saved_extras, IFNULL(bl.discount_amount, 0) AS saved_discount,
               IFNULL(bl.total_amount, b.total_price) AS saved_total
        FROM bookings b
        JOIN users u ON b.guest_id = u.user_id
        JOIN rooms r ON b.room_id = r.room_id
        LEFT JOIN billing bl ON b.booking_id = bl.booking_id
        WHERE b.bookin_date IS NOT NULL
          AND (? = '' OR u.name LIKE ? OR b.booking_id LIKE ? OR r.room_number LIKE ?)
        ORDER BY b.checkin_date DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $search, $like, $like, $like);
$stmt->execute();
$result = $stmt->get_result();
?>

<link rel="stylesheet" href="../CSS/payment.css">

<div class="main-content">
    <h1>Guest Payments</h1>

    <?php
    if(isset($_SESSION['payment_success'])) {
        echo "<p class='success-message'>".$_SESSION['payment_success']."</p>";
        unset($_SESSION['payment_success']);
    }
    if(isset($_SESSION['payment_error'])) {
        echo "<p class='error-message'>".$_SESSION['payment_error']."</p>";
        unset($_SESSION['payment_error']);
    }
    ?>

    <form method="get" class="search-panel">
        <input type="text" name="search" placeholder="Search booking, guest, or room" value="<?php echo htmlspecialchars($search); ?>">
        <button type="submit">Search</button>
        <a href="paymentlist.php">Reset</a>
    </form>

    <div class="payment-table-container">
        <table>
            <thead>
                <tr>
                    <th>Booking</th>
                    <th>Guest</th>
                    <th>Room</th>
                    <th>Base</th>
                    <th>Extras</th>
                    <th>Points</th>
                    <th>Total</th>
                    <th>Payment</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()) { ?>
                <?php
                    $service_sql = "SELECT COUNT(*) AS completed_services FROM service_requests WHERE booking_id=? AND status='completed'";
                    $service_stmt = $conn->prepare($service_sql);
                    $service_stmt->bind_param("i", $row["booking_id"]);
                    $service_stmt->execute();
                    $completed_services = intval($service_stmt->get_result()->fetch_assoc()["completed_services"]);
                    $extras_amount = $completed_services * 200;
                    $service_stmt->close();

                    $points_sql = "SELECT IFNULL(balance,0) AS balance FROM loyalty_points WHERE guest_id=? ORDER BY loyalty_id DESC LIMIT 1";
                    $points_stmt = $conn->prepare($points_sql);
                    $points_stmt->bind_param("i", $row["guest_id"]);
                    $points_stmt->execute();
                    $points_row = $points_stmt->get_result()->fetch_assoc();
                    $points_balance = $points_row ? intval($points_row["balance"]) : 0;
                    $points_stmt->close();

                    $total_due = floatval($row["total_price"]) + $extras_amount;
                ?>
                <tr>
                    <td>#<?php echo $row['booking_id']; ?></td>
                    <td><?php echo htmlspecialchars($row['guest_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['room_number']); ?></td>
                    <td><?php echo number_format($row['total_price'], 2); ?></td>
                    <td><?php echo number_format($extras_amount, 2); ?></td>
                    <td><?php echo $points_balance; ?></td>
                    <td><?php echo number_format($total_due, 2); ?> BDT</td>
                    <td>
                        <?php if($row["payment_status"] == "paid") { ?>
                            <a class="btn-receipt" href="receipt.php?booking_id=<?php echo $row['booking_id']; ?>">Receipt</a>
                        <?php } else { ?>
                        <form action="../Control/paymentController.php" method="post" class="payment-form">
                            <input type="hidden" name="booking_id" value="<?php echo $row['booking_id']; ?>">
                            <select name="payment_method" required>
                                <option value="">Method</option>
                                <option value="cash">Cash</option>
                                <option value="card">Card</option>
                                <option value="mobile_banking">Mobile Banking</option>
                                <option value="bank_transfer">Bank Transfer</option>
                            </select>
                            <input type="number" name="points_used" min="0" max="<?php echo $points_balance; ?>" value="0" title="Redeem points">
                            <button type="submit" class="btn-pay">Pay</button>
                        </form>
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
