<?php
include("../Control/activecheck.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Receptionist Dashboard</title>
</head>
<body>

    <h2>Welcome, <?php echo $_SESSION["receptionist_name"]; ?></h2>
    <p>Receptionist Dashboard will be completed in next step.</p>

    <a href="../Control/receptionistlogoutController.php">Logout</a>

</body>
</html>