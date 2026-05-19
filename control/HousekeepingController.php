<?php
// controllers/HousekeepingController.php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Room.php';
require_once __DIR__ . '/../models/Booking.php';
require_once __DIR__ . '/../models/HousekeepingTask.php';
require_once __DIR__ . '/../models/MaintenanceReport.php';
require_once __DIR__ . '/../models/Notification.php';

class HousekeepingController {
    private $userModel;
    private $roomModel;
    private $bookingModel;
    private $taskModel;
    private $maintenanceModel;
    private $notificationModel;

    public function __construct() {
        $this->userModel = new User();
        $this->roomModel = new Room();
        $this->bookingModel = new Booking();
        $this->taskModel = new HousekeepingTask();
        $this->maintenanceModel = new MaintenanceReport();
        $this->notificationModel = new Notification();
    }

    public function dashboard() {
        requireHousekeeping();
        
        $room_stats = $this->roomModel->getRoomStats();
        $task_stats = $this->taskModel->getTaskStats();
        $priority_stats = $this->taskModel->getTasksByPriority();
        $maintenance_stats = $this->maintenanceModel->getMaintenanceStats();
        $today_tasks = $this->taskModel->getTodayTasks();
        $open_maintenance = $this->maintenanceModel->getOpenReports();
        $today_checkouts = $this->bookingModel->getTodayCheckouts();
        $tomorrow_checkouts = $this->bookingModel->getTomorrowCheckouts();
        $today_checkins = $this->bookingModel->getTodayCheckins();
        $unread_count = $this->notificationModel->getUnreadCount($_SESSION['user_id']);
        $notifications = $this->notificationModel->getUnread($_SESSION['user_id']);
        
        extract(array(
            'room_stats' => $room_stats,
            'task_stats' => $task_stats,
            'priority_stats' => $priority_stats,
            'maintenance_stats' => $maintenance_stats,
            'today_tasks' => $today_tasks,
            'open_maintenance' => $open_maintenance,
            'today_checkouts' => $today_checkouts,
            'tomorrow_checkouts' => $tomorrow_checkouts,
            'today_checkins' => $today_checkins,
            'unread_count' => $unread_count,
            'notifications' => $notifications
        ));
        
        include __DIR__ . '/../views/housekeeping/dashboard.php';
    }

    public function roomStatus() {
        requireHousekeeping();
        
        $rooms = $this->roomModel->getAllRooms();
        $room_stats = $this->roomModel->getRoomStats();
        
        extract(array(
            'rooms' => $rooms,
            'room_stats' => $room_stats
        ));
        
        include __DIR__ . '/../views/housekeeping/room-status.php';
    }

    public function createTask() {
        requireHousekeeping();
        
        $errors = [];
        $success = false;
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $room_id = $_POST['room_id'] ?? '';
            $task_type = $_POST['task_type'] ?? '';
            $priority = $_POST['priority'] ?? 'normal';
            $scheduled_date = $_POST['scheduled_date'] ?? date('Y-m-d');
            $notes = $_POST['notes'] ?? '';
            $assigned_to = $_POST['assigned_to'] ?? null;
            
            if (empty($room_id)) {
                $errors[] = "Please select a room";
            }
            if (empty($task_type)) {
                $errors[] = "Please select task type";
            }
            
            if (empty($errors)) {
                $result = $this->taskModel->createTask($room_id, $task_type, $priority, $scheduled_date, $notes, $assigned_to);
                if ($result) {
                    $success = "Task created successfully!";
                    // Create notification for assigned staff
                    if ($assigned_to) {
                        $this->notificationModel->create($assigned_to, "New Task Assigned", "You have been assigned a $task_type task for Room #$room_id", 'task');
                    }
                } else {
                    $errors[] = "Failed to create task";
                }
            }
        }
        
        $rooms = $this->roomModel->getDirtyRooms();
        $staff = $this->userModel->getStaffList();
        
        extract(array(
            'rooms' => $rooms,
            'staff' => $staff,
            'errors' => $errors,
            'success' => $success
        ));
        
        include __DIR__ . '/../views/housekeeping/create-task.php';
    }

    public function manageTasks() {
        requireHousekeeping();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
            $task_id = $_POST['task_id'];
            $status = $_POST['status'];
            
            $this->taskModel->updateTaskStatus($task_id, $status);
            
            // If task completed, update room status
            if ($status == 'completed') {
                $task = $this->taskModel->getTaskById($task_id);
                if ($task) {
                    $this->roomModel->updateRoomStatus($task['room_id'], 'available');
                    $this->notificationModel->create($_SESSION['user_id'], "Task Completed", "Task for Room #{$task['room_number']} has been completed", 'task');
                }
            }
            
            header("Location: index.php?controller=housekeeping&action=manageTasks&success=updated");
            exit();
        }
        
        $pending_tasks = $this->taskModel->getPendingTasks();
        $in_progress_tasks = $this->taskModel->getInProgressTasks();
        $today_tasks = $this->taskModel->getTodayTasks();
        $staff = $this->userModel->getStaffList();
        
        extract(array(
            'pending_tasks' => $pending_tasks,
            'in_progress_tasks' => $in_progress_tasks,
            'today_tasks' => $today_tasks,
            'staff' => $staff
        ));
        
        include __DIR__ . '/../views/housekeeping/manage-tasks.php';
    }

    public function assignTask() {
        requireHousekeeping();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $task_id = $_POST['task_id'];
            $assigned_to = $_POST['assigned_to'];
            
            $this->taskModel->assignTask($task_id, $assigned_to);
            
            // Create notification
            $this->notificationModel->create($assigned_to, "Task Assigned", "You have been assigned a new task", 'task');
            
            header("Location: index.php?controller=housekeeping&action=manageTasks&success=assigned");
            exit();
        }
    }

    public function maintenance() {
        requireHousekeeping();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['create_report'])) {
                $room_id = $_POST['room_id'];
                $description = $_POST['description'];
                $severity = $_POST['severity'];
                $reported_by = $_SESSION['user_id'];
                
                $this->maintenanceModel->createReport($room_id, $reported_by, $description, $severity);
                header("Location: index.php?controller=housekeeping&action=maintenance&success=reported");
                exit();
            } elseif (isset($_POST['update_status'])) {
                $report_id = $_POST['report_id'];
                $status = $_POST['status'];
                $resolution_notes = $_POST['resolution_notes'] ?? null;
                
                $this->maintenanceModel->updateReportStatus($report_id, $status, $resolution_notes);
                header("Location: index.php?controller=housekeeping&action=maintenance&success=updated");
                exit();
            }
        }
        
        $open_reports = $this->maintenanceModel->getOpenReports();
        $in_progress_reports = $this->maintenanceModel->getInProgressReports();
        $rooms = $this->roomModel->getAllRooms();
        
        extract(array(
            'open_reports' => $open_reports,
            'in_progress_reports' => $in_progress_reports,
            'rooms' => $rooms
        ));
        
        include __DIR__ . '/../views/housekeeping/maintenance.php';
    }

    public function checkouts() {
        requireHousekeeping();
        
        $today_checkouts = $this->bookingModel->getTodayCheckouts();
        $tomorrow_checkouts = $this->bookingModel->getTomorrowCheckouts();
        
        extract(array(
            'today_checkouts' => $today_checkouts,
            'tomorrow_checkouts' => $tomorrow_checkouts
        ));
        
        include __DIR__ . '/../views/housekeeping/checkouts.php';
    }

    public function checkins() {
        requireHousekeeping();
        
        $today_checkins = $this->bookingModel->getTodayCheckins();
        
        extract(array(
            'today_checkins' => $today_checkins
        ));
        
        include __DIR__ . '/../views/housekeeping/checkins.php';
    }

    public function report() {
        requireHousekeeping();
        
        $room_stats = $this->roomModel->getRoomStats();
        $task_stats = $this->taskModel->getTaskStats();
        $completed_tasks = $this->taskModel->getAllTasks('completed');
        $maintenance_stats = $this->maintenanceModel->getMaintenanceStats();
        
        extract(array(
            'room_stats' => $room_stats,
            'task_stats' => $task_stats,
            'completed_tasks' => $completed_tasks,
            'maintenance_stats' => $maintenance_stats
        ));
        
        include __DIR__ . '/../views/housekeeping/report.php';
    }

    public function updateRoomStatus() {
        requireHousekeeping();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $room_id = $_POST['room_id'];
            $status = $_POST['status'];
            
            $result = $this->roomModel->updateRoomStatus($room_id, $status);
            
            header('Content-Type: application/json');
            echo json_encode(['success' => $result]);
            exit();
        }
    }

    public function getDashboardStats() {
        requireHousekeeping();
        
        $room_stats = $this->roomModel->getRoomStats();
        $task_stats = $this->taskModel->getTaskStats();
        $maintenance_stats = $this->maintenanceModel->getMaintenanceStats();
        
        $stats = [
            'dirty_rooms' => $room_stats['dirty'] ?? 0,
            'cleaning_rooms' => $room_stats['cleaning'] ?? 0,
            'pending_tasks' => $task_stats['pending'] ?? 0,
            'open_maintenance' => $maintenance_stats['open'] ?? 0,
            'completed_today' => $task_stats['completed_today'] ?? 0
        ];
        
        header('Content-Type: application/json');
        echo json_encode($stats);
        exit();
    }
}
?>