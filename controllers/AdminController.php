<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
// ... rest of the code
require_once('../config/database.php');
require_once('../models/User.php');
require_once('../models/Room.php');
require_once('../models/Booking.php');
require_once('../models/Review.php');
require_once('../models/SeasonalPricing.php');

class AdminController {
    private $db;
    private $userModel;
    private $roomModel;
    private $bookingModel;
    private $reviewModel;
    private $pricingModel;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->userModel = new User($this->db);
        $this->roomModel = new Room($this->db);
        $this->bookingModel = new Booking($this->db);
        $this->reviewModel = new Review($this->db);
        $this->pricingModel = new SeasonalPricing($this->db);
    }

    public function dashboard() {
        $roomStats = $this->roomModel->getStats();
        $totalRevenue = $this->bookingModel->getTotalRevenue();
        $occupancyRate = $this->bookingModel->getOccupancyRate();
        $pendingReviews = $this->reviewModel->getPendingReviews();
        $recentBookings = $this->bookingModel->getAllBookings(null, null, null);
        $recentBookings = array_slice($recentBookings, 0, 5);
        
        include('../views/admin/dashboard.php');
    }

    public function rooms() {
        $rooms = $this->roomModel->getAllRooms();
        include('../views/admin/rooms.php');
    }
    
    public function addRoom() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $result = $this->roomModel->addRoom(
                $_POST['room_number'],
                $_POST['room_type'],
                $_POST['price'],
                $_POST['capacity'],
                $_POST['floor'],
                $_POST['description']
            );
            if($result) {
                header("Location: admin.php?action=rooms&msg=Room added successfully");
                exit();
            } else {
                $error = "Failed to add room";
                include('../views/admin/add_room.php');
            }
        } else {
            include('../views/admin/add_room.php');
        }
    }
    
    public function editRoom($room_id) {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $result = $this->roomModel->updateRoom(
                $room_id,
                $_POST['room_number'],
                $_POST['room_type'],
                $_POST['price'],
                $_POST['capacity'],
                $_POST['floor'],
                $_POST['description'],
                $_POST['status']
            );
            if($result) {
                header("Location: admin.php?action=rooms&msg=Room updated successfully");
                exit();
            } else {
                $error = "Failed to update room";
                $room = $this->roomModel->getRoomById($room_id);
                include('../views/admin/edit_room.php');
            }
        } else {
            $room = $this->roomModel->getRoomById($room_id);
            include('../views/admin/edit_room.php');
        }
    }
    
    public function deleteRoom($room_id) {
        $result = $this->roomModel->deleteRoom($room_id);
        header("Location: admin.php?action=rooms&msg=" . ($result ? "Room deleted" : "Delete failed"));
        exit();
    }
    
    public function seasonalPricing() {
        if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_pricing'])) {
            $this->pricingModel->addPricing(
                $_POST['room_type'],
                $_POST['label'],
                $_POST['start_date'],
                $_POST['end_date'],
                $_POST['price']
            );
            header("Location: admin.php?action=seasonal_pricing&msg=Pricing added");
            exit();
        }
        
        if(isset($_GET['delete_id'])) {
            $this->pricingModel->deletePricing($_GET['delete_id']);
            header("Location: admin.php?action=seasonal_pricing&msg=Pricing deleted");
            exit();
        }
        
        $pricingRules = $this->pricingModel->getAllPricing();
        include('../views/admin/seasonal_pricing.php');
    }
    
    public function bookings() {
        $status = $_GET['status'] ?? null;
        $start_date = $_GET['start_date'] ?? null;
        $end_date = $_GET['end_date'] ?? null;
        $bookings = $this->bookingModel->getAllBookings($status, $start_date, $end_date);
        include('../views/admin/bookings.php');
    }
    
    public function reviews() {
        if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['reply'])) {
            $this->reviewModel->addReply($_POST['review_id'], $_POST['reply']);
            header("Location: admin.php?action=reviews&msg=Reply added");
            exit();
        }
        
        $reviews = $this->reviewModel->getAllReviews();
        $avgRatings = $this->reviewModel->getAverageRatings();
        include('../views/admin/reviews.php');
    }
    
    public function users() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            if(isset($_POST['deactivate'])) {
                $this->userModel->deactivateUser($_POST['user_id']);
                header("Location: admin.php?action=users&msg=User deactivated");
                exit();
            }
            if(isset($_POST['add_staff'])) {
                $this->userModel->createStaff(
                    $_POST['name'],
                    $_POST['email'],
                    $_POST['password'],
                    $_POST['phone'],
                    $_POST['id_number'],
                    $_POST['role']
                );
                header("Location: admin.php?action=users&msg=Staff added");
                exit();
            }
        }
        
        $guests = $this->userModel->getAllUsers('guest');
        $receptionists = $this->userModel->getAllUsers('receptionist');
        $housekeeping = $this->userModel->getAllUsers('housekeeping');
        include('../views/admin/users.php');
    }
    
    public function roomStatusAjax() {
        include('../views/admin/room_status_ajax.php');
    }
}

// Check if admin is logged in
if(!isset($_SESSION['admin_id'])) {
    header("Location: ../views/login.php");
    exit();
}

// Routing
$controller = new AdminController();
$action = isset($_GET['action']) ? $_GET['action'] : 'dashboard';

switch($action) {
    case 'dashboard':
        $controller->dashboard();
        break;
    case 'rooms':
        $controller->rooms();
        break;
    case 'add_room':
        $controller->addRoom();
        break;
    case 'edit_room':
        $controller->editRoom($_GET['id'] ?? 0);
        break;
    case 'delete_room':
        $controller->deleteRoom($_GET['id'] ?? 0);
        break;
    case 'seasonal_pricing':
        $controller->seasonalPricing();
        break;
    case 'bookings':
        $controller->bookings();
        break;
    case 'reviews':
        $controller->reviews();
        break;
    case 'users':
        $controller->users();
        break;
    case 'room_status_ajax':
        $controller->roomStatusAjax();
        break;
    case 'room_status_ajax':
    $controller->roomStatusAjax();
        break;
    default:
        $controller->dashboard();
        break;
}
?>
