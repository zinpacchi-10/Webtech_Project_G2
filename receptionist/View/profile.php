<?php
include("../Control/activecheck.php");
include("header.php");
include("receptionistsidebar.php");
require_once("../Model/db.php");

$db = new db();
$conn = $db->openConn();
$receptionist_id = $_SESSION["receptionist_id"];

$sql = "SELECT name, email, phone, id_number FROM users WHERE user_id=? AND role='receptionist'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $receptionist_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$stmt->close();
$db->closeConn($conn);
?>

<link rel="stylesheet" href="../CSS/profile.css">

<div class="main-content">
    <h1>My Profile</h1>

    <div class="profile-card">
        <div class="profile-top">
            <div class="profile-avatar"><?php echo strtoupper(substr($user['name'], 0, 1)); ?></div>
            <div>
                <h2><?php echo htmlspecialchars($user['name']); ?></h2>
                <p>Reception Desk Panel</p>
            </div>
        </div>

        <div class="profile-grid">
            <div class="profile-field">
                <span>Email</span>
                <strong><?php echo htmlspecialchars($user['email']); ?></strong>
            </div>
            <div class="profile-field">
                <span>Phone</span>
                <strong><?php echo htmlspecialchars($user['phone']); ?></strong>
            </div>
            <div class="profile-field">
                <span>ID Number</span>
                <strong><?php echo htmlspecialchars($user['id_number']); ?></strong>
            </div>
        </div>
    </div>
</div>
