<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - Rain Dew Hotel</title>
    <link rel="stylesheet" href="../public/css/style1.css">
</head>
<body>
    <div class="login-card">
        <div class="logo-container">
            <img src="../public/images/logo.png" alt="Logo" class="logo-img">
        </div>
        <h2 class="welcome-text">Create Account</h2>
        <form action="../Controller/regController.php" method="post">
            <div class="input-group">
                <label>Full Name</label>
                <input type="text" name="fullname" required>
            </div>
            <div class="input-group">
                <label>Email Address</label>
                <input type="email" name="email" required>
            </div>
            <div class="input-group">
                <label>Phone Number</label>
                <input type="text" name="phone" required>
            </div>
            <div class="input-group">
                <label>Nationality</label>
                <input type="text" name="nationality" required>
            </div>
            <div class="input-group">
                <label>ID/Passport Number</label>
                <input type="text" name="id_number" required>
            </div>
            
            <div class="input-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit" class="btn-signin">Register Now</button>
        </form>
        <div class="register-link">
            Already have an account? <a href="login.php">Login Here</a>
        </div>
    </div>
</body>
</html>
