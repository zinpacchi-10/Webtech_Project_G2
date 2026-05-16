<?php
include("../Control/activecheck.php");
include("header.php");
include("receptionistsidebar.php");
require_once("../Model/db.php");

$db = new db();
$conn = $db->openConn();
$user_id = $_SESSION["receptionist_id"];

$sql = "SELECT name,email,phone FROM users WHERE user_id=? AND role='receptionist'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i",$user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$db->closeConn($conn);
?>

<link rel="stylesheet" href="../CSS/profile_edit.css">

<div class="main-content">
    <h1>Edit Profile</h1>

    <?php
    if(isset($_SESSION['profile_success'])){
        echo "<p class='success-message'>".$_SESSION['profile_success']."</p>";
        unset($_SESSION['profile_success']);
    }
    if(isset($_SESSION['profile_error'])){
        echo "<p class='error-message'>".$_SESSION['profile_error']."</p>";
        unset($_SESSION['profile_error']);
    }
    ?>

    <form action="../Control/profileController.php" method="post" class="profile-form">
        <div class="form-group">
            <label>Name</label>
            <input type="text" name="name" value="<?php echo $user['name']; ?>" required>
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" value="<?php echo $user['email']; ?>" required>
        </div>
        <div class="form-group">
            <label>Phone</label>
            <input type="text" name="phone" value="<?php echo $user['phone']; ?>">
        </div>
        <button type="submit" class="btn-save">Update Profile</button>
    </form>
</div>