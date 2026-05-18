<?php
include("../Control/activecheck.php");
include("header.php");
include("receptionistsidebar.php");
require_once("../Model/db.php");

$db = new db();
$conn = $db->openConn();
$available_rooms_sql = "SELECT room_id, room_number, room_type, price, capacity FROM rooms WHERE notes='available' ORDER BY room_type, room_number";
$rooms_result = $conn->query($available_rooms_sql);
?>

<link rel="stylesheet" href="../CSS/walkin.css">

<div class="main-content">
    <h1>Walk-in Booking</h1>

    <?php
    if(isset($_SESSION['walkin_success'])) {
        echo "<p class='success-message'>".$_SESSION['walkin_success']."</p>";
        unset($_SESSION['walkin_success']);
    }
    if(isset($_SESSION['walkin_error'])) {
        echo "<p class='error-message'>".$_SESSION['walkin_error']."</p>";
        unset($_SESSION['walkin_error']);
    }
    ?>

    <form action="../Control/walkinController.php" method="POST" class="walkin-form">
        <div class="form-grid">
            <div class="form-group">
                <label>Guest Name</label>
                <input type="text" name="guest_name" required>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="guest_email" required>
            </div>

            <div class="form-group">
                <label>Phone</label>
                <input type="text" name="guest_phone">
            </div>

            <div class="form-group">
                <label>Nationality</label>
                <input type="text" name="nationality">
            </div>

            <div class="form-group">
                <label>ID Number</label>
                <input type="text" name="id_number" required>
            </div>

            <div class="form-group">
                <label>Room Selection</label>
                <select name="room_id" required>
                    <option value="">Select Room</option>
                    <?php while($room = $rooms_result->fetch_assoc()) { ?>
                        <option value="<?php echo $room['room_id']; ?>">
                            <?php echo $room['room_number']." - ".$room['room_type']." - ".$room['price']." BDT/night - Capacity ".$room['capacity']; ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <div class="form-group">
                <label>Check-in Date</label>
                <input type="date" name="checkin_date" value="<?php echo date('Y-m-d'); ?>" required>
            </div>

            <div class="form-group">
                <label>Check-out Date</label>
                <input type="date" name="checkout_date" required>
            </div>

            <div class="form-group">
                <label>Number of Guests</label>
                <input type="number" name="num_guests" min="1" required>
            </div>
        </div>

        <button type="submit" class="btn-walkin">Book & Check-in</button>
    </form>
</div>

<?php
$db->closeConn($conn);
?>
