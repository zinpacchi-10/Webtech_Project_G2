<?php include('../views/header.php'); ?>

<h1>Edit Room</h1>

<?php if(isset($error)): ?>
    <div class="error-msg"><?php echo $error; ?></div>
<?php endif; ?>

<?php if(isset($room) && $room): ?>

<form method="POST" class="admin-form">
    <div class="form-row">
        <label>Room Number:</label>
        <input type="text" name="room_number" value="<?php echo htmlspecialchars($room['room_number']); ?>" required>
    </div>
    
    <div class="form-row">
        <label>Room Type:</label>
        <select name="room_type" required>
            <option value="Standard" <?php echo ($room['room_type'] == 'Standard') ? 'selected' : ''; ?>>Standard</option>
            <option value="Deluxe" <?php echo ($room['room_type'] == 'Deluxe') ? 'selected' : ''; ?>>Deluxe</option>
            <option value="Suite" <?php echo ($room['room_type'] == 'Suite') ? 'selected' : ''; ?>>Suite</option>
        </select>
    </div>
    
    <div class="form-row">
        <label>Price per Night ($):</label>
        <input type="number" name="price" value="<?php echo $room['price']; ?>" required>
    </div>
    
    <div class="form-row">
        <label>Capacity:</label>
        <input type="number" name="capacity" value="<?php echo $room['capacity']; ?>" required>
    </div>
    
    <div class="form-row">
        <label>Floor:</label>
        <input type="number" name="floor" value="<?php echo $room['floor']; ?>" required>
    </div>
    
    <div class="form-row">
        <label>Status:</label>
        <select name="status">
            <option value="available" <?php echo ($room['status'] == 'available') ? 'selected' : ''; ?>>Available</option>
            <option value="occupied" <?php echo ($room['status'] == 'occupied') ? 'selected' : ''; ?>>Occupied</option>
            <option value="maintenance" <?php echo ($room['status'] == 'maintenance') ? 'selected' : ''; ?>>Maintenance</option>
            <option value="dirty" <?php echo ($room['status'] == 'dirty') ? 'selected' : ''; ?>>Dirty</option>
        </select>
    </div>
    
    <div class="form-row">
        <label>Description:</label>
        <textarea name="description" rows="3"><?php echo htmlspecialchars($room['room_description']); ?></textarea>
    </div>
    
    <div class="form-row">
        <input type="submit" value="Update Room" class="btn-submit">
        <a href="admin.php?action=rooms" class="btn-back">Back to Rooms</a>
    </div>
</form>

<?php else: ?>
    <div class="error-msg">Room not found!</div>
    <a href="admin.php?action=rooms" class="btn-back">Back to Rooms</a>
<?php endif; ?>

<a href="../controllers/AdminController.php?action=rooms" class="btn-back">Back to Rooms</a>

<?php include('../footer.php'); ?>