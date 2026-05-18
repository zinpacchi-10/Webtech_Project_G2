<?php
session_start();
if(!isset($_SESSION['admin_id'])) { header("Location: adminlogin.php"); }
require_once("../Model/review_model.php");
$reviewModel = new ReviewModel();
$reviews = $reviewModel->getAllReviews();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Review Management</title>
    <link rel="stylesheet" href="../CSS/review_manage.css">
</head>
<body class="bodycolor">
    <?php include("header.php"); ?>
    <?php include("adminsidebar.php"); ?>

    <div class="container">
        <h2>Guest Reviews & Feedback</h2>
        <?php if(isset($_GET['msg'])) echo "<p class='alert'>".$_GET['msg']."</p>"; ?>

        <div class="review-list">
            <?php while($row = $reviews->fetch_assoc()): ?>
                <div class="review-card" style="background: white; padding: 15px; margin-bottom: 20px; border-radius: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                    <div class="review-header">
                        <strong><?php echo $row['guest_name']; ?></strong> 
                        <span class="rating">⭐ <?php echo $row['rating']; ?>/5</span>
                        <small>Room: <?php echo $row['room_number']; ?> | <?php echo $row['review_date']; ?></small>
                    </div>
                    <p class="comment">"<?php echo $row['comment']; ?>"</p>
                    
                    <div class="admin-reply-section" style="background: #f9f9f9; padding: 10px; border-left: 4px solid #2c3e50;">
                        <strong>Admin Reply:</strong> 
                        <p><?php echo $row['admin_reply'] ? $row['admin_reply'] : "No reply yet."; ?></p>
                        
                        <form action="../Control/review_reply_process.php" method="POST">
                            <input type="hidden" name="review_id" value="<?php echo $row['review_id']; ?>">
                            <textarea name="reply" placeholder="Write your reply..." required></textarea><br>
                            <button type="submit" name="submit_reply">Send Reply</button>
                        </form>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>
</html>