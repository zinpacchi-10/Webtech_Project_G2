<?php include('../header.php'); ?>

<h1>Manage Rooms</h1>

<?php if(isset($_GET['msg'])): ?>
    <p style="color: green;"><?php echo $_GET['msg']; ?></p>
<?php endif; ?>

<p>
    <a href="../controllers/AdminController.php?action=add_room" style="background: green; color: white; padding: 8px 15px; text-decoration: none;">+ Add New Room</a>
</p>

<br>

<table border="1" cellpadding="10" style="width: 100%; background: white;">
    <thead>
        <tr>
            <th>Room #</th>
            <th>Type</th>
            <th>Price</th>
            <th>Capacity</th>
            <th>Floor</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if(isset($rooms) && !empty($rooms)): ?>
            <?php foreach($rooms as $room): ?>
            <tr>
                <td><?php echo $room['room_number']; ?></td>
                <td><?php echo $room['room_type']; ?></td>
                <td>$<?php echo $room['price']; ?></td>
                <td><?php echo $room['capacity']; ?></td>
                <td><?php echo $room['floor']; ?></td>
                <td><?php echo $room['status']; ?></td>
                <td>
                    <a href="../controllers/AdminController.php?action=edit_room&id=<?php echo $room['room_id']; ?>" style="background: blue; color: white; padding: 5px 10px; text-decoration: none;">Edit</a>
                    <a href="../controllers/AdminController.php?action=delete_room&id=<?php echo $room['room_id']; ?>" style="background: red; color: white; padding: 5px 10px; text-decoration: none;" onclick="return confirm('Delete this room?')">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="7" align="center">No rooms found</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<br>
<a href="../controllers/AdminController.php?action=dashboard" style="background: #666; color: white; padding: 8px 15px; text-decoration: none;">← Back to Dashboard</a>

<?php include('../footer.php'); ?>