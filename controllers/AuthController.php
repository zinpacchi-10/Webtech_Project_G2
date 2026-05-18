<?php
session_start();
require_once('../config/database.php');
require_once('../models/User.php');

class AuthController {
    private $userModel;
    
    public function __construct() {
        $database = new Database();
        $db = $database->getConnection();
        $this->userModel = new User($db);
    }
    
    public function login() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];
            
            $user = $this->userModel->login($email, $password);
            
            if($user && $user['role'] == 'admin') {
                $_SESSION['admin_id'] = $user['user_id'];
                $_SESSION['admin_name'] = $user['name'];
                $_SESSION['admin_email'] = $user['email'];
                $_SESSION['admin_role'] = $user['role'];
                header("Location: ../views/admin/dashboard.php");
                exit();
            } else {
                $error = "Invalid email or password!";
                include('../views/login.php');
            }
        } else {
            include('../views/login.php');
        }
    }
    
    public function logout() {
        session_destroy();
        header("Location: ../views/login.php");
        exit();
    }
}

if(isset($_GET['action'])) {
    $controller = new AuthController();
    if($_GET['action'] == 'login') {
        $controller->login();
    } elseif($_GET['action'] == 'logout') {
        $controller->logout();
    }
}
?>