<?php
// controllers/AuthController.php
require_once __DIR__ . '/../models/User.php';

class AuthController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            
            $errors = [];
            
            if (empty($email)) {
                $errors[] = "Email is required";
            }
            if (empty($password)) {
                $errors[] = "Password is required";
            }
            
            if (empty($errors)) {
                $user = $this->userModel->login($email, $password);
                if ($user) {
                    $_SESSION['user_id'] = $user['user_id'];
                    $_SESSION['user_name'] = $user['name'];
                    $_SESSION['user_email'] = $user['email'];
                    $_SESSION['role'] = $user['role'];
                    
                    header("Location: index.php?controller=housekeeping&action=dashboard");
                    exit();
                } else {
                    $errors[] = "Invalid email or password";
                }
            }
            
            $data = ['errors' => $errors];
            include __DIR__ . '/../views/auth/login.php';
        } else {
            include __DIR__ . '/../views/auth/login.php';
        }
    }

    public function logout() {
        session_destroy();
        header("Location: index.php?controller=auth&action=login");
        exit();
    }

    public function profile() {
        requireHousekeeping();
        
        $user_id = $_SESSION['user_id'];
        $user = $this->userModel->getHousekeepingById($user_id);
        
        if (!$user) {
            $user = [
                'name' => $_SESSION['user_name'] ?? '',
                'email' => $_SESSION['user_email'] ?? '',
                'phone' => '',
                'created_at' => date('Y-m-d H:i:s')
            ];
        }
        
        $errors = [];
        $success = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $phone = $_POST['phone'] ?? '';
            
            if (empty($name)) {
                $errors[] = "Name is required";
            }
            
            if (empty($errors)) {
                if ($this->userModel->updateProfile($user_id, $name, $phone)) {
                    $_SESSION['user_name'] = $name;
                    $success = "Profile updated successfully";
                    $user['name'] = $name;
                    $user['phone'] = $phone;
                } else {
                    $errors[] = "Failed to update profile";
                }
            }
        }
        
        include __DIR__ . '/../views/auth/profile.php';
    }
}
?>