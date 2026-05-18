<?php
session_start();
if(!isset($_SESSION['admin_id'])) { header("Location: adminlogin.php"); }
require_once("../Model/staff_model.php");
$staffModel = new StaffModel();

$staff = null;
if (isset($_GET['edit_id'])) {
    $staff = $staffModel->getStaffById($_GET['edit_id']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Staff</title>
    <link rel="stylesheet" href="../CSS/staff_manage.css">
</head>
<body class="bodycolor">
    <?php include("header.php"); ?>
    <?php include("adminsidebar.php"); ?>

    <div class="container">
        <h2><?php echo $staff ? "Edit Staff Info" : "Add New Staff Member"; ?></h2>
        
        <?php if(isset($_GET['msg'])) echo "<p class='alert'>".$_GET['msg']."</p>"; ?>

        <form action="../Control/staff_process.php" method="POST">
            <input type="hidden" name="user_id" value="<?php echo $staff['user_id'] ?? ''; ?>">

            <div class="form-group">
                <label>Full Name:</label>
                <input type="text" name="name" value="<?php echo $staff['name'] ?? ''; ?>" required>
            </div>

            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" value="<?php echo $staff['email'] ?? ''; ?>" required>
            </div>

            <div class="form-group">
                <label>Password:</label>
                <input type="password" name="password" placeholder="<?php echo $staff ? 'Leave blank to keep current password' : 'Enter password'; ?>">
            </div>

            <div class="form-group">
                <label>Phone Number:</label>
                <input type="text" name="phone" value="<?php echo $staff['phone'] ?? ''; ?>">
            </div>

            <div class="form-group">
                <label>Nationality:</label>
                <input type="text" name="nationality" value="<?php echo $staff['nationality'] ?? ''; ?>">
            </div>

            <div class="form-group">
                <label>ID Number (NID/Passport):</label>
                <input type="text" name="id_number" value="<?php echo $staff['id_number'] ?? ''; ?>" required>
            </div>

            <div class="form-group">
                <label>Role:</label>
                <select name="role" required>
                    <option value="admin" <?php echo (isset($staff) && $staff['role'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
                    <option value="receptionist" <?php echo (isset($staff) && $staff['role'] == 'receptionist') ? 'selected' : ''; ?>>Receptionist</option>
                    <option value="housekeeping" <?php echo (isset($staff) && $staff['role'] == 'housekeeping') ? 'selected' : ''; ?>>Housekeeping</option>
                </select>
            </div>

            <button type="submit" name="save_staff">Save Staff Member</button>
            <a href="staff_list.php">Back to List</a>
        </form>
    </div>
</body>
</html>