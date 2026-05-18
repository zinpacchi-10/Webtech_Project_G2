<?php
session_start();
require_once("../Model/db.php");

if (!isset($_SESSION["receptionist_logged_in"])) {
    echo json_encode(["error" => "Unauthorized"]);
    exit();
}

$db = new db();
$conn = $db->openConn();

$sql = "SELECT room_number, room_type, notes FROM rooms";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

$rooms = [];
while($row = $result->fetch_assoc()){
    $rooms[] = $row;
}

$stmt->close();
$db->closeConn($conn);
echo json_encode($rooms);
?>