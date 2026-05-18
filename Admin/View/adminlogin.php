<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
    <link rel="stylesheet" href="../CSS/adminlogin.css">
</head>
<body>
    <div class="login-box">
        <h2>Hotel Admin Login</h2>
        <?php 
            if(isset($_GET['error'])) {
                echo "<p style='color:red'>".$_GET['error']."</p>";
            }
        ?>
        <form action="../Control/adminlogincheck.php" method="POST">
            <input type="email" name="email" placeholder="Admin Email" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <input type="submit" name="submit" value="Login">
        </form>
    </div>
</body>
</html>