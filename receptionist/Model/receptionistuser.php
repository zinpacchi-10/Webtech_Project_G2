<?php
require_once "db.php";

function loginReceptionist($user_data)
{
    $db = new db();
    $conn = $db->openConn();

    $email = $user_data["email"];
    $role = "receptionist";

    $sql = "SELECT user_id, name, email, password_hash, role 
            FROM users 
            WHERE email = ? AND role = ? 
            LIMIT 1";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        return false;
    }

    $stmt->bind_param("ss", $email, $role);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        $stmt->close();
        $db->closeConn($conn);
        return $user;
    } else {
        $stmt->close();
        $db->closeConn($conn);
        return false;
    }
}
?>