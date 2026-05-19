<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Rain Dew Hotel</title>
    <link rel="stylesheet" href="../public/css/style1.css">
</head>
<body>
    <div class="login-card">
        <div class="logo-container">
            <img src="../public/images/logo.png" alt="Logo" class="logo-img">
        </div>
        <h2 class="welcome-text">Welcome Back</h2>
        <form action="../Controller/loginController.php" method="post">
            <div class="input-group">
                <label>Email Address</label>
                <input type="email" name="email" required>
            </div>
            <div class="input-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit" class="btn-signin">Sign In</button>
        </form>
        <div class="register-link">
            Don't have an account? <a href="registration.php">Register Now</a>
        </div>
    </div>
</body>
</html>
