<?php
session_start();
require_once("../Model/room_model.php");

if(!isset($_SESSION['admin_id'])) {
    header("Location: ../View/adminlogin.php");
    exit();
}

$roomModel = new RoomModel();
$message = "";

// রুম যোগ করা বা আপডেট করার প্রসেস
if (isset($_POST['save_room'])) {
    $id = $_POST['room_id']; // যদি ফাঁকা থাকে তবে নতুন রুম, থাকলে আপডেট
    
    // ইমেজ আপলোড লজিক
    $thumbnail = $_POST['old_img'];
    if (!empty($_FILES['thumbnail']['name'])) {
        $target_dir = "../uploads/room_thumbnail/";
        $thumbnail = time() . "_" . $_FILES['thumbnail']['name']; // ইউনিক নাম দেওয়া হয়েছে
        move_uploaded_file($_FILES['thumbnail']['tmp_name'], $target_dir . $thumbnail);
    }

    $data = [
        'num'  => $_POST['room_number'],
        'price' => $_POST['price'],
        'cap'   => $_POST['capacity'],
        'floor' => $_POST['floor'],
        'img'   => $thumbnail,
        'amid'  => $_POST['amidity'],
        'desc'  => $_POST['room_description'],
        'notes' => $_POST['notes']
    ];

    if (empty($id)) {
        if ($roomModel->addRoom($data)) {
            $message = "Room added successfully!";
        } else {
            $message = "Error adding room.";
        }
    } else {
        if ($roomModel->updateRoom($id, $data)) {
            $message = "Room updated successfully!";
        } else {
            $message = "Error updating room.";
        }
    }
    header("Location: ../View/room_form.php?msg=" . $message);
}

// রুম ডিলিট করার প্রসেস
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    if ($roomModel->deleteRoom($id)) {
        header("Location: ../View/room_list.php?msg=Room deleted!");
    } else {
        header("Location: ../View/room_list.php?msg=Error deleting room.");
    }
}
?>