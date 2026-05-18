<?php
session_start();

if (isset($_SESSION["receptionist_logged_in"])) {
    header("location:receptionistdashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Receptionist Login - Rain Dew Hotel</title>
    <link rel="stylesheet" href="../CSS/receptionistlogin.css">
</head>
<body>

    <div class="login-card">
        <div class="logo-container">
            <div class="logo-text">RD</div>
        </div>

        <h2 class="welcome-text">Receptionist Login</h2>
        <p class="sub-text">Rain Dew Hotel Front Desk</p>

        <form action="../Control/receptionistloginController.php" method="post">
            <div class="input-group">
                <label>Email Address</label>
                <input type="email" name="email" placeholder="Enter receptionist email" required>
            </div>

            <div class="input-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Enter password" required>
            </div>

            <button type="submit" class="btn-signin">Sign In</button>
        </form>
    </div>

</body>
</html>