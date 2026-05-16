<?php
include("../Control/activecheck.php");
include("header.php");
include("receptionistsidebar.php");
require_once("../Model/db.php");

$db = new db();
$conn = $db->openConn();
$today = date("Y-m-d");
$search = isset($_GET["search"]) ? trim($_GET["search"]) : "";
$like = "%".$search."%";

$sql = "SELECT b.booking_id, b.room_id, u.name AS guest_name, u.id_number, r.room_number, r.room_type,
               b.checkin_date, b.checkout_date, b.num_guests, b.created_at
        FROM bookings b
        JOIN users u ON b.guest_id = u.user_id
        JOIN rooms r ON b.room_id = r.room_id
        WHERE b.bookin_date IS NULL
          AND b.checkin_date <= ?
          AND b.checkout_date >= ?
          AND (? = '' OR u.name LIKE ? OR b.booking_id LIKE ?)
        ORDER BY b.created_at ASC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sssss", $today, $today, $search, $like, $like);
$stmt->execute();
$result = $stmt->get_result();
?>

<link rel="stylesheet" href="../CSS/checkin.css">

<div class="main-content">
    <h1>Guest Check-in</h1>

    <?php
    if(isset($_SESSION['checkin_success'])) {
        echo "<p class='success-message'>".$_SESSION['checkin_success']."</p>";
        unset($_SESSION['checkin_success']);
    }
    if(isset($_SESSION['checkin_error'])) {
        echo "<p class='error-message'>".$_SESSION['checkin_error']."</p>";
        unset($_SESSION['checkin_error']);
    }
    ?>

    <form method="get" class="search-panel">
        <input type="text" name="search" placeholder="Search by booking ID or guest name" value="<?php echo htmlspecialchars($search); ?>">
        <button type="submit">Search</button>
        <a href="checkin.php">Reset</a>
    </form>

    <div class="checkin-table-container">
        <table>
            <thead>
                <tr>
                    <th>Booking ID</th>
                    <th>Guest</th>
                    <th>ID Number</th>
                    <th>Booked Type</th>
                    <th>Dates</th>
                    <th>Room Assign</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row['booking_id']; ?></td>
                    <td><?php echo htmlspecialchars($row['guest_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['id_number']); ?></td>
                    <td><?php echo htmlspecialchars($row['room_type']); ?></td>
                    <td><?php echo $row['checkin_date']." to ".$row['checkout_date']; ?></td>
                    <td>
                        <form action="../Control/checkinController.php" method="post" class="inline-form">
                            <input type="hidden" name="booking_id" value="<?php echo $row['booking_id']; ?>">
                            <select name="room_id" required>
                                <?php
                                $room_sql = "SELECT room_id, room_number FROM rooms WHERE room_type = ? AND notes = 'available' ORDER BY room_number ASC";
                                $room_stmt = $conn->prepare($room_sql);
                                $room_stmt->bind_param("s", $row["room_type"]);
                                $room_stmt->execute();
                                $rooms = $room_stmt->get_result();
                                while($room = $rooms->fetch_assoc()) {
                                    $selected = ($room["room_id"] == $row["room_id"]) ? "selected" : "";
                                    echo "<option value='{$room['room_id']}' $selected>Room {$room['room_number']}</option>";
                                }
                                $room_stmt->close();
                                ?>
                            </select>
                            <label class="check-label">
                                <input type="checkbox" name="id_verified" value="1" required> ID verified
                            </label>
                    </td>
                    <td>
                            <button type="submit" class="btn-checkin">Check In</button>
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
