<?php
include("../Control/activecheck.php");
include("header.php");
include("receptionistsidebar.php");
?>

<div class="main-content">
    <h1>Receptionist Dashboard</h1>
    <div class="dashboard-cards">
        <div class="card">
            <h3>Today's Check-ins</h3>
            <p>0</p>
        </div>
        <div class="card">
            <h3>Today's Check-outs</h3>
            <p>0</p>
        </div>
        <div class="card">
            <h3>Currently Checked-in Guests</h3>
            <p>0</p>
        </div>
        <div class="card">
            <h3>Available Rooms</h3>
            <p>0</p>
        </div>
    </div>
</div>

<?php include("footer.php"); ?>