<?php
    require_once 'connection.php';

    function registerUser($user){
        $conn = dbConnection();
  
        $Query = "INSERT INTO users (name, email, password_hash, phone, nationality, id_number, role) 
                  VALUES ('{$user['name']}', '{$user['email']}', '{$user['password']}', '{$user['phone']}', '{$user['nationality']}', '{$user['id_number']}', '{$user['role']}')";
        return mysqli_query($conn, $Query);
    }

    function loginUser($user){
        $conn = dbConnection();
        $Query = "SELECT * FROM users WHERE email = '{$user['email']}'";
        $result = mysqli_query($conn, $Query);
        if(mysqli_num_rows($result) == 1){
            return mysqli_fetch_assoc($result); 
        } else {
            return false;
        }
    }

    function getUserDetails($userId) {
    $conn = dbConnection();
    $Query = "SELECT * FROM users WHERE user_id = '{$userId}'";
    $result = mysqli_query($conn, $Query);
    return mysqli_fetch_assoc($result);
}

function updateUserPassword($userId, $newPassword) {
    $conn = dbConnection();
    // In a real project, use password_hash() here
    $Query = "UPDATE users SET password_hash = '{$newPassword}' WHERE user_id = '{$userId}'";
    return mysqli_query($conn, $Query);
}


?>
