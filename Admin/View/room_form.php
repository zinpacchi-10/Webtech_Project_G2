<?php
session_start();
if(!isset($_SESSION['admin_id'])) { header("Location: adminlogin.php"); }
require_once("../Model/room_model.php");
$roomModel = new RoomModel();

// এডিট মোড চেক করা
$room = null;
if (isset($_GET['edit_id'])) {
    $room = $roomModel->getRoomById($_GET['edit_id']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Room</title>
    <link rel="stylesheet" href="../CSS/room_manage.css">
</head>
<body>
    <?php include("header.php"); ?>
    <?php include("adminsidebar.php"); ?>

    <div class="container">
        <h2><?php echo $room ? "Edit Room" : "Add New Room"; ?></h2>
        
        <?php if(isset($_GET['msg'])) echo "<p class='alert'>".$_GET['msg']."</p>"; ?>

        <form action="../Control/room_process.php" method="POST" enctype="multipart/form-data">
            <!-- Hidden ID for Update -->
            <input type="hidden" name="room_id" value="<?php echo $room['room_id'] ?? ''; ?>">
            <input type="hidden" name="old_img" value="<?php echo $room['thumbnail'] ?? ''; ?>">

            <div class="form-group">
                <label>Room Number:</label>
                <input type="text" name="room_number" value="<?php echo $room['room_number'] ?? ''; ?>" required>
            </div>

            <div class="form-group">
                <label>Price (BDT):</label>
                <input type="number" name="price" value="<?php echo $room['price'] ?? ''; ?>" required>
            </div>

            <div class="form-group">
                <label>Capacity (Persons):</label>
                <input type="number" name="capacity" value="<?php echo $room['capacity'] ?? ''; ?>">
            </div>

            <div class="form-group">
                <label>Floor:</label>
                <input type="number" name="floor" value="<?php echo $room['floor'] ?? ''; ?>">
            </div>

            <div class="form-group">
                <label>Thumbnail Image:</label>
                <input type="file" name="thumbnail">
                <?php if($room) echo "<br><img src='../uploads/room_thumbnail/".$room['thumbnail']."' width='100'>"; ?>
            </div>

            <div class="form-group">
                <label>Amenities (e.g. AC, WiFi):</label>
                <input type="text" name="amidity" value="<?php echo $room['amidity'] ?? ''; ?>">
            </div>

            <div class="form-group">
                <label>Room Description:</label>
                <textarea name="room_description"><?php echo $room['room_description'] ?? ''; ?></textarea>
            </div>

            <div class="form-group">
                <label>Internal Notes:</label>
                <textarea name="notes"><?php echo $room['notes'] ?? ''; ?></textarea>
            </div>

            <button type="submit" name="save_room">Save Room Data</button>
            <a href="room_list.php">Back to List</a>
        </form>
    </div>
</body>
</html>