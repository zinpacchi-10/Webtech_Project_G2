<?php
include("../Control/activecheck.php");
include("header.php");
include("receptionistsidebar.php");
require_once("../Model/db.php");

$db = new db();
$conn = $db->openConn();

$sql = "SELECT u.user_id, u.name, u.email,
               IFNULL((SELECT lp.balance FROM loyalty_points lp WHERE lp.guest_id = u.user_id ORDER BY lp.loyalty_id DESC LIMIT 1),0) AS points_balance
        FROM users u
        WHERE u.role='guest'
        ORDER BY u.name ASC";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
?>

<link rel="stylesheet" href="../CSS/loyaltypoints.css">

<div class="main-content">
    <h1>Loyalty Points Redemption</h1>

    <?php
    if(isset($_SESSION['loyalty_success'])) {
        echo "<p class='success-message'>".$_SESSION['loyalty_success']."</p>";
        unset($_SESSION['loyalty_success']);
    }
    if(isset($_SESSION['loyalty_error'])) {
        echo "<p class='error-message'>".$_SESSION['loyalty_error']."</p>";
        unset($_SESSION['loyalty_error']);
    }
    ?>

    <div class="loyalty-table-container">
        <table>
            <thead>
                <tr>
                    <th>Guest Name</th>
                    <th>Email</th>
                    <th>Current Balance</th>
                    <th>Manual Redeem</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td><?php echo $row['points_balance']; ?></td>
                    <td>
                        <form action="../Control/loyaltyController.php" method="post" class="inline-form">
                            <input type="hidden" name="guest_id" value="<?php echo $row['user_id']; ?>">
                            <input type="number" name="points" min="1" max="<?php echo $row['points_balance']; ?>" required>
                            <button type="submit" class="btn-apply">Apply</button>
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
