<?php
function dbConnection(){
    $hostName  = "localhost";
    $userName  = "root";
    $password  = "";
    $dbName = "hotelbookingsystem"; 

    $conn = mysqli_connect($hostName, $userName, $password, $dbName);
    if($conn){
        return $conn;
    } else{
        die("Connection failed: " . mysqli_connect_error());
    }
}
?>
