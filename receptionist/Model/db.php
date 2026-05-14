<?php
class db
{
    function openConn()
    {
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "hotelbookingsystem";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Database connection failed");
        }

        $conn->set_charset("utf8mb4");

        return $conn;
    }

    function closeConn($conn)
    {
        $conn->close();
    }

    function cleanData($data)
    {
        return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
    }
}
?>