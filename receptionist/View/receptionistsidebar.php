<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/receptionistsidebar.css">
</head>
<body>
    <div class="sidenav" id="theSideNav">
        <div class="sidebar-brand">Hotel Reception</div>

        <button type="button" class="dropdown-btn">Reception Desk <span>+</span></button>
        <div class="dropdown-container">
            <a href="receptionistdashboard.php">Dashboard</a>
            <a href="checkin.php">Guest Check-In</a>
            <a href="checkout.php">Guest Check-Out</a>
            <a href="walkin.php">Walk-In Booking</a>
            <a href="modifybooking.php">Modify Booking</a>
        </div>

        <button type="button" class="dropdown-btn">Payments <span>+</span></button>
        <div class="dropdown-container">
            <a href="paymentlist.php">Guest Payments</a>
            <a href="loyaltypoints.php">Loyalty Points</a>
        </div>

        <button type="button" class="dropdown-btn">Guest Services <span>+</span></button>
        <div class="dropdown-container">
            <a href="servicerequest.php">Service Requests</a>
            <a href="early_late_requests.php">Early / Late Requests</a>
        </div>

        <button type="button" class="dropdown-btn">Room Management <span>+</span></button>
        <div class="dropdown-container">
            <a href="room_status_board.php">Room Status Board</a>
        </div>

        <button type="button" class="dropdown-btn">Front Desk Report <span>+</span></button>
        <div class="dropdown-container">
            <a href="dailyreport.php">Daily Operation Report</a>
        </div>

        <div class="account-title">Account</div>
        <a href="profile.php">My Profile</a>
        <a href="profile_edit.php">Edit Profile</a>
        <a href="changePassword.php">Change Password</a>
        <a class="logout-link" href="../Control/receptionistlogoutController.php">Logout</a>
    </div>

    <script src="../JS/receptionistsidebar.js"></script>
</body>
</html>
