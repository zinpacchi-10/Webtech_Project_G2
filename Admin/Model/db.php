<?php
class db {
    private $servername = "localhost";
    private $username = "root";
    private $password = "";
    private $dbname = "hotelbookingsystem1";
    public $conn;

    public function openConn() {
        // MySqli connection
        $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
        return $this->conn;
    }

    // এখানে আমরা সব SQL কুয়েরি ফাংশন লিখবো যাতে View ফাইল পরিষ্কার থাকে
}
?>