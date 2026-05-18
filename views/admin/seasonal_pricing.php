<?php include('../views/header.php'); ?>

<h1>Seasonal Pricing</h1>

<?php if(isset($_GET['msg'])): ?>
    <div class="success-msg"><?php echo $_GET['msg']; ?></div>
<?php endif; ?>

<div class="two-columns">
    <div class="column">
        <h2>Add Pricing Rule</h2>
        <form method="POST" class="admin-form">
            <div class="form-row">
                <label>Room Type:</label>
                <select name="room_type" required>
                    <option value="Standard">Standard</option>
                    <option value="Deluxe">Deluxe</option>
                    <option value="Suite">Suite</option>
                </select>
            </div>
            <div class="form-row">
                <label>Label (e.g., Eid Holiday):</label>
                <input type="text" name="label" required>
            </div>
            <div class="form-row">
                <label>Start Date:</label>
                <input type="date" name="start_date" required>
            </div>
            <div class="form-row">
                <label>End Date:</label>
                <input type="date" name="end_date" required>
            </div>
            <div class="form-row">
                <label>Price per Night ($):</label>
                <input type="number" name="price" required>
            </div>
            <div class="form-row">
                <input type="submit" name="add_pricing" value="Add Pricing Rule" class="btn-submit">
            </div>
        </form>
    </div>
    
    <div class="column">
        <h2>Existing Pricing Rules</h2>
        <table class="data-table">
            <thead>
                <tr><th>Room Type</th><th>Label</th><th>Start Date</th><th>End Date</th><th>Price</th><th>Action</th></tr>
            </thead>
            <tbody>
                <?php if(empty($pricingRules)): ?>
                <tr><td colspan="6" class="text-center">No pricing rules found</td></tr>
                <?php else: ?>
                    <?php foreach($pricingRules as $rule): ?>
                    <tr>
                        <td><?php echo $rule['room_type']; ?></td>
                        <td><?php echo $rule['label']; ?></td>
                        <td><?php echo $rule['start_date']; ?></td>
                        <td><?php echo $rule['end_date']; ?></td>
                        <td>$<?php echo $rule['price_per_night']; ?></td>
                        <td><a href="admin.php?action=seasonal_pricing&delete_id=<?php echo $rule['id']; ?>" class="btn-delete" onclick="return confirm('Delete?')">Delete</a></td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include('../views/footer.php'); ?>