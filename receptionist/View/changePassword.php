<?php
include("../Control/activecheck.php");
include("header.php");
include("receptionistsidebar.php");
require_once("../Model/db.php");
?>

<link rel="stylesheet" href="../CSS/changePassword.css">

<div class="main-content">
    <h1>Change Password</h1>

    <?php
    if(isset($_SESSION['password_success'])){
        echo "<p class='success-message'>".$_SESSION['password_success']."</p>";
        unset($_SESSION['password_success']);
    }
    if(isset($_SESSION['password_error'])){
        echo "<p class='error-message'>".$_SESSION['password_error']."</p>";
        unset($_SESSION['password_error']);
    }
    ?>

    <form action="../Control/changePasswordController.php" method="post" class="change-password-form">
        <div class="form-group">
            <label>Current Password</label>
            <input type="password" name="current_password" required>
        </div>
        <div class="form-group">
            <label>New Password</label>
            <input type="password" name="new_password" required>
        </div>
        <div class="form-group">
            <label>Confirm New Password</label>
            <input type="password" name="confirm_password" required>
        </div>
        <button type="submit" class="btn-save">Change Password</button>
    </form>
</div>