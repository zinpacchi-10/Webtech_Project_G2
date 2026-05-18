<?php
session_start();
if(!isset($_SESSION['admin_id'])) { header("Location: adminlogin.php"); }
require_once("../Model/staff_model.php");
$staffModel = new StaffModel();
$staffs = $staffModel->getAllStaff();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Staff List</title>
    <link rel="stylesheet" href="../CSS/staff_manage.css">
</head>
<body class="bodycolor">
    <?php include("header.php"); ?>
    <?php include("adminsidebar.php"); ?>

    <div class="container">
        <h2>Hotel Staff Directory</h2>
        <a href="staff_form.php" class="btn-add">Add New Staff</a>
        
        <?php if(isset($_GET['msg'])) echo "<p class='alert'>".$_GET['msg']."</p>"; ?>

        <table border="1" width="100%">
            <thead style="background-color: #f2f2f2;">
                <tr align="left">
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Phone</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $staffs->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['user_id']; ?></td>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td><strong><?php echo ucfirst($row['role']); ?></strong></td>
                    <td><?php echo $row['phone']; ?></td>
                    <td>
                        <a href="staff_form.php?edit_id=<?php echo $row['user_id']; ?>">Edit</a> | 
                        <?php if($row['user_id'] != $_SESSION['admin_id']): ?>
                            <a href="../Control/staff_process.php?delete_id=<?php echo $row['user_id']; ?>" onclick="return confirm('Remove this staff member?')">Delete</a>
                        <?php else: ?>
                            <span style="color:gray">Own Account</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>