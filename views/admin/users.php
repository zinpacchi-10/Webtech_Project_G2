<?php include('../header.php'); ?>

<h1>Manage Users</h1>

<?php if(isset($_GET['msg'])): ?>
    <p style="color: green;"><?php echo $_GET['msg']; ?></p>
<?php endif; ?>

<!-- Add Staff Form -->
<fieldset>
    <legend>Add New Staff Member</legend>
    <form method="POST">
        Name: <input type="text" name="name" required><br><br>
        Email: <input type="email" name="email" required><br><br>
        Password: <input type="password" name="password" required><br><br>
        Phone: <input type="text" name="phone"><br><br>
        ID Number: <input type="text" name="id_number" required><br><br>
        Role: 
        <select name="role">
            <option value="receptionist">Receptionist</option>
            <option value="housekeeping">Housekeeping Supervisor</option>
        </select><br><br>
        <input type="submit" name="add_staff" value="Add Staff">
    </form>
</fieldset>

<br>

<!-- Receptionists -->
<fieldset>
    <legend>Receptionists</legend>
    <?php if(isset($receptionists) && !empty($receptionists)): ?>
        <ul>
            <?php foreach($receptionists as $user): ?>
                <li>
                    <?php echo $user['name']; ?> - <?php echo $user['email']; ?>
                    <form method="POST" style="display: inline;">
                        <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">
                        <button type="submit" name="deactivate">Remove</button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No receptionists found.</p>
    <?php endif; ?>
</fieldset>

<br>

<!-- Housekeeping -->
<fieldset>
    <legend>Housekeeping Staff</legend>
    <?php if(isset($housekeeping) && !empty($housekeeping)): ?>
        <ul>
            <?php foreach($housekeeping as $user): ?>
                <li>
                    <?php echo $user['name']; ?> - <?php echo $user['email']; ?>
                    <form method="POST" style="display: inline;">
                        <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">
                        <button type="submit" name="deactivate">Remove</button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No housekeeping staff found.</p>
    <?php endif; ?>
</fieldset>

<br>

<!-- Guests -->
<fieldset>
    <legend>Guest Accounts</legend>
    <?php if(isset($guests) && !empty($guests)): ?>
        <table border="1" cellpadding="5">
            <tr><th>Name</th><th>Email</th><th>Phone</th><th>Action</th></tr>
            <?php foreach($guests as $user): ?>
                <tr>
                    <td><?php echo $user['name']; ?></td>
                    <td><?php echo $user['email']; ?></td>
                    <td><?php echo isset($user['phone']) ? $user['phone'] : 'N/A'; ?></td>
                    <td>
                        <form method="POST">
                            <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">
                            <button type="submit" name="deactivate">Deactivate</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>No guest accounts found.</p>
    <?php endif; ?>
</fieldset>

<br>
<a href="../controllers/AdminController.php?action=dashboard">← Back to Dashboard</a>

<?php include('../footer.php'); ?>