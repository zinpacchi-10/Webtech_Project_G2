<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Hotel Booking System</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <h2>Hotel Admin Login</h2>
            <?php if(isset($error)): ?>
                <div class="error-msg"><?php echo $error; ?></div>
            <?php endif; ?>
            <form method="POST" action="../controllers/AuthController.php?action=login">
                <div class="input-group">
                    <label>Email</label>
                    <input type="email" name="email" required>
                </div>
                <div class="input-group">
                    <label>Password</label>
                    <input type="password" name="password" required>
                </div>
                <button type="submit" class="btn-login">Login</button>
            </form>
            <p class="demo-info">Demo: admin@hotel.com / admin123</p>
        </div>
    </div>
</body>
</html>