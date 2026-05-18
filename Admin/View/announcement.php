<?php
session_start();
if(!isset($_SESSION['admin_id'])) { header("Location: adminlogin.php"); }
require_once("../Model/announcement_model.php");
$annModel = new AnnouncementModel();
$list = $annModel->getAllAnnouncements();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Hotel Announcements</title>
    <link rel="stylesheet" href="../CSS/announcement.css">
</head>
<body class="bodycolor">
    <?php include("header.php"); ?>
    <?php include("adminsidebar.php"); ?>

    <div class="container">
        <h2>Post Hotel News & Offers</h2>
        <?php if(isset($_GET['msg'])) echo "<p class='alert'>".$_GET['msg']."</p>"; ?>

        <div class="post-box" style="background: white; padding: 20px; border-radius: 10px; margin-bottom: 30px;">
            <form action="../Control/announcement_process.php" method="POST">
                <label>Announcement Title:</label><br>
                <input type="text" name="title" required style="width:100%; margin-bottom:10px;"><br>
                <label>Details:</label><br>
                <textarea name="content" required style="width:100%; height:100px;"></textarea><br>
                <button type="submit" name="post_news" class="btn-post">Post Announcement</button>
            </form>
        </div>

        <h3>Current Announcements</h3>
        <table border="1" width="100%" style="background: white;">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $list->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['title']; ?></td>
                    <td><?php echo $row['created_at']; ?></td>
                    <td>
                        <a href="../Control/announcement_process.php?delete_id=<?php echo $row['announcement_id']; ?>" 
                           onclick="return confirm('Delete this post?')">Remove</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>