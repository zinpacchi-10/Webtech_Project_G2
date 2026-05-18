<?php
session_start();
@include("../Model/db.php");

if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (!empty($email) && !empty($password)) {
        $mydb = new db();
        $conn = $mydb->openConn();

        // Prepared Statement ব্যবহার করা হয়েছে SQL Injection ঠেকানোর জন্য
        $stmt = $conn->prepare("SELECT user_id, name, password_hash FROM users WHERE email = ? AND role = 'admin'");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            
            // এখানে পাসওয়ার্ড চেক করা হচ্ছে। 
            // মনে রাখবেন: আপনি ডাটাবেসে 'admin123' রেখেছেন, তাই সরাসরি চেক করা হয়েছে। 
            // পরবর্তীতে password_verify() ব্যবহার করতে হবে।
            if ($password == $user['password_hash']) {
                $_SESSION['admin_id'] = $user['user_id'];
                $_SESSION['admin_name'] = $user['name'];
                header("Location: ../View/admindashboard.php");
                exit();
            } else {
                header("Location: ../View/adminlogin.php?error=wrongpassword");
            }
        } else {
            header("Location: ../View/adminlogin.php?error=usernotfound");
        }
    } else {
        header("Location: ../View/adminlogin.php?error=emptyfields");
    }
}
?>
