<?php
session_start();
if(!isset($_SESSION['admin_id'])) { header("Location: adminlogin.php"); }
require_once("../Model/room_model.php");
$roomModel = new RoomModel();
$rooms = $roomModel->getAllRooms();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Room List</title>
    <link rel="stylesheet" href="../CSS/room_manage.css">
</head>
<body>
    <?php include("header.php"); ?>
    <?php include("adminsidebar.php"); ?>

    <div class="container">
        <h2>All Hotel Rooms</h2>
        <a href="room_form.php" class="btn-add">Add New Room</a>
        
        <?php if(isset($_GET['msg'])) echo "<p class='alert'>".$_GET['msg']."</p>"; ?>

        <table border="1" width="100%">
            <thead>
                <tr>
                    <th>Room No</th>
                    <th>Price</th>
                    <th>Capacity</th>
                    <th>Floor</th>
                    <th>Image</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $rooms->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['room_number']; ?></td>
                    <td><?php echo $row['price']; ?></td>
                    <td><?php echo $row['capacity']; ?></td>
                    <td><?php echo $row['floor']; ?></td>
                    <td><img src="../uploads/room_thumbnail/<?php echo $row['thumbnail']; ?>" width="50"></td>
                    <td>
                        <a href="room_form.php?edit_id=<?php echo $row['room_id']; ?>">Edit</a> | 
                        <a href="../Control/room_process.php?delete_id=<?php echo $row['room_id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>