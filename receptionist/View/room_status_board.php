<?php
include("../Control/activecheck.php");
include("header.php");
include("receptionistsidebar.php");
require_once("../Model/db.php");

$db = new db();
$conn = $db->openConn();
?>

<link rel="stylesheet" href="../CSS/room_status_board.css">
<div class="main-content">
    <h1>Room Status Board</h1>
    <ul id="roomStatusBoard">
    </ul>
</div>

<script src="../JS/room_status_board.js"></script>