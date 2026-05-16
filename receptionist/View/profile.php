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

$db->closeConn($conn);
?>

<link rel="stylesheet" href="../CSS/profile.css">

<div class="main-content">
    <h1>My Profile</h1>
    <div class="profile-card">
        <p><strong>Name:</strong> <?php echo $user['name']; ?></p>
        <p><strong>Email:</strong> <?php echo $user['email']; ?></p>
        <p><strong>Phone:</strong> <?php echo $user['phone']; ?></p>
        <p><strong>ID Number:</strong> <?php echo $user['id_number']; ?></p>
    </div>
</div>