<?php include('../views/header.php'); ?>

<h1>Add New Room</h1>

<?php if(isset($error)): ?>
    <div class="error-msg"><?php echo $error; ?></div>
<?php endif; ?>

<form method="POST" class="admin-form">
    <div class="form-row">
        <label>Room Number:</label>
        <input type="text" name="room_number" required>
    </div>
    <div class="form-row">
        <label>Room Type:</label>
        <select name="room_type" required>
            <option value="Standard">Standard</option>
            <option value="Deluxe">Deluxe</option>
            <option value="Suite">Suite</option>
        </select>
    </div>
    <div class="form-row">
        <label>Price per Night ($):</label>
        <input type="number" name="price" required>
    </div>
    <div class="form-row">
        <label>Capacity:</label>
        <input type="number" name="capacity" required>
    </div>
    <div class="form-row">
        <label>Floor:</label>
        <input type="number" name="floor" required>
    </div>
    <div class="form-row">
        <label>Description:</label>
        <textarea name="description" rows="3"></textarea>
    </div>
    <div class="form-row">
        <input type="submit" value="Add Room" class="btn-submit">
        <a href="admin.php?action=rooms" class="btn-back">Back</a>
    </div>
</form>
<a href="../controllers/AdminController.php?action=rooms" class="btn-back">Back</a>

<?php include('../views/footer.php'); ?>