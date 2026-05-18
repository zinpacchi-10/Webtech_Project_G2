<?php
session_start();
require_once("../Model/announcement_model.php");

if(!isset($_SESSION['admin_id'])) {
    header("Location: ../View/adminlogin.php");
    exit();
}

$annModel = new AnnouncementModel();

if (isset($_POST['post_news'])) {
    $title = $_POST['title'];
    $content = $_POST['content'];

    if ($annModel->postAnnouncement($title, $content)) {
        header("Location: ../View/announcement.php?msg=Announcement posted successfully!");
    } else {
        header("Location: ../View/announcement.php?msg=Error posting announcement.");
    }
}

if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    if ($annModel->deleteAnnouncement($id)) {
        header("Location: ../View/announcement.php?msg=Removed successfully!");
    } else {
        header("Location: ../View/announcement.php?msg=Error removing.");
    }
}
?>