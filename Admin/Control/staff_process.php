<?php
session_start();
require_once("../Model/staff_model.php");

if(!isset($_SESSION['admin_id'])) {
    header("Location: ../View/adminlogin.php");
    exit();
}

$staffModel = new StaffModel();
$message = "";

// স্টাফ যোগ করা বা আপডেট করার প্রসেস
if (isset($_POST['save_staff'])) {
    $id = $_POST['user_id']; 
    
    $data = [
        'name'  => $_POST['name'],
        'email' => $_POST['email'],
        'pass'  => $_POST['password'],
        'phone' => $_POST['phone'],
        'nat'   => $_POST['nationality'],
        'id'    => $_POST['id_number'],
        'role'  => $_POST['role']
    ];

    if (empty($id)) {
        if ($staffModel->addStaff($data)) {
            $message = "Staff added successfully!";
        } else {
            $message = "Error adding staff.";
        }
    } else {
        // পাসওয়ার্ড যদি ফাঁকা থাকে, তবে পুরনো পাসওয়ার্ডটিই রাখা হবে
        if(empty($data['pass'])) {
            $currentStaff = $staffModel->getStaffById($id);
            $data['pass'] = $currentStaff['password_hash'];
        }

        if ($staffModel->updateStaff($id, $data)) {
            $message = "Staff updated successfully!";
        } else {
            $message = "Error updating staff.";
        }
    }
    header("Location: ../View/staff_form.php?msg=" . $message);
}

// স্টাফ ডিলিট করার প্রসেস
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    
    // অ্যাডমিন নিজেকে ডিলিট করতে পারবে না তার জন্য চেক
    if($id == $_SESSION['admin_id']) {
        header("Location: ../View/staff_list.php?msg=You cannot delete yourself!");
    } else {
        if ($staffModel->deleteStaff($id)) {
            header("Location: ../View/staff_list.php?msg=Staff removed successfully!");
        } else {
            header("Location: ../View/staff_list.php?msg=Error removing staff.");
        }
    }
}
?>