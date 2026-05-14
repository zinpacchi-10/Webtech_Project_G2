<?php
if (isset($errors) && count($errors) > 0) {
    foreach ($errors as $showerror) {
        echo "<p id='denger'>" . $showerror . "</p>";
    }
}

if (isset($success) && count($success) > 0) {
    foreach ($success as $ok) {
        echo "<p id='success'>" . $ok . "</p>";
    }
}
?>