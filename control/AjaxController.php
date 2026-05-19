<?php
// controllers/AjaxController.php
require_once __DIR__ . '/../models/Room.php';
require_once __DIR__ . '/../models/HousekeepingTask.php';
require_once __DIR__ . '/../models/MaintenanceReport.php';

class AjaxController {
    private $roomModel;
    private $taskModel;
    private $maintenanceModel;

    public function __construct() {
        $this->roomModel = new Room();
        $this->taskModel = new HousekeepingTask();
        $this->maintenanceModel = new MaintenanceReport();
    }

    public function getRoomStatus() {
        $rooms = $this->roomModel->getAllRooms();
        header('Content-Type: application/json');
        echo json_encode($rooms);
        exit();
    }

    public function updateRoomStatus() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $room_id = $_POST['room_id'] ?? 0;
            $status = $_POST['status'] ?? '';
            
            $result = $this->roomModel->updateRoomStatus($room_id, $status);
            
            header('Content-Type: application/json');
            echo json_encode(['success' => $result]);
            exit();
        }
    }

    public function getTaskDetails() {
        $task_id = $_GET['task_id'] ?? 0;
        $task = $this->taskModel->getTaskById($task_id);
        
        header('Content-Type: application/json');
        echo json_encode($task);
        exit();
    }

    public function getMaintenanceDetails() {
        $report_id = $_GET['report_id'] ?? 0;
        $report = $this->maintenanceModel->getReportById($report_id);
        
        header('Content-Type: application/json');
        echo json_encode($report);
        exit();
    }

    public function getDashboardStats() {
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