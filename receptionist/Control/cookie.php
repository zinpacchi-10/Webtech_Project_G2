<?php
if (isset($_COOKIE["uname"])) {
    echo " " . htmlspecialchars($_COOKIE["uname"]);
} elseif (isset($_SESSION["name"])) {
    echo " " . htmlspecialchars($_SESSION["name"]);
}
?>