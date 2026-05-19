<?php
require_once "../Model/users.php";

if($_SERVER['REQUEST_METHOD'] == "POST"){
    $user = [
        "name" => $_POST['fullname'],
        "email" => $_POST['email'],
        "phone" => $_POST['phone'],
        "nationality" => $_POST['nationality'],
        "id_number" => $_POST['id_number'],
        "role" => "guest", 
        "password" => $_POST['password']
    ];

    if(registerUser($user)){
        echo "<script>alert('Registration Successful!'); window.location='../view/login.php';</script>";
    } else {
        echo "Registration Failed.";
    }
}
?>
