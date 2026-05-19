<?php
// models/MaintenanceReport.php
require_once __DIR__ . '/../config/database.php';

class MaintenanceReport {
    private $conn;
    private $table = "maintenance_reports";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getAllReports($status = null) {
        $query = "SELECT m.*, r.room_number, r.room_type, u.name as reported_by_name
                  FROM " . $this->table . " m
                  JOIN rooms r ON m.room_id = r.room_id
                  JOIN users u ON m.reported_by = u.user_id";
        
        if ($status) {
            $query .= " WHERE m.status = '$status'";
        } else {
            $query .= " WHERE m.status IN ('open', 'in_progress')";
        }
        
        $query .= " ORDER BY 
                    CASE m.severity 
                      WHEN 'critical' THEN 1 
                      WHEN 'high' THEN 2 
                      WHEN 'medium' THEN 3 
                      WHEN 'low' THEN 4 
                    END,
                    m.reported_at ASC";
        
        $result = $this->conn->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getOpenReports() {
        return $this->getAllReports('open');
    }

    public function getInProgressReports() {
        return $this->getAllReports('in_progress');
    }

    public function getReportById($id) {
        $query = "SELECT m.*, r.room_number, r.room_type, u.name as reported_by_name
                  FROM " . $this->table . " m
                  JOIN rooms r ON m.room_id = r.room_id
                  JOIN users u ON m.reported_by = u.user_id
                  WHERE m.report_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function createReport($room_id, $reported_by, $description, $severity) {
        $query = "INSERT INTO " . $this->table . " 
                  (room_id, reported_by, description, severity, status) 
                  VALUES (?, ?, ?, ?, 'open')";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("iiss", $room_id, $reported_by, $description, $severity);
        
        if ($stmt->execute()) {
            // Update room status to maintenance
            $roomModel = new Room();
            $roomModel->updateRoomStatus($room_id, 'maintenance');
            return $this->conn->insert_id;
        }
        return false;
    }

    public function updateReportStatus($report_id, $status, $resolution_notes = null) {
        $query = "UPDATE " . $this->table . " SET status = ?";
        if ($status == 'resolved' || $status == 'closed') {
            $query .= ", resolved_at = NOW()";
        }
        if ($resolution_notes) {
            $query .= ", resolution_notes = ?";
            $stmt = $this->conn->prepare($query . " WHERE report_id = ?");
            $stmt->bind_param("ssi", $status, $resolution_notes, $report_id);
        } else {
            $stmt = $this->conn->prepare($query . " WHERE report_id = ?");
            $stmt->bind_param("si", $status, $report_id);
        }
        
        if ($stmt->execute()) {
            // If resolved, update room status back to dirty for cleaning
            if ($status == 'resolved' || $status == 'closed') {
                $report = $this->getReportById($report_id);
                if ($report) {
                    $roomModel = new Room();
                    $roomModel->updateRoomStatus($report['room_id'], 'dirty');
                }
            }
            return true;
        }
        return false;
    }

    public function getMaintenanceStats() {
        $query = "SELECT 
                    SUM(CASE WHEN status IN ('open', 'in_progress') THEN 1 ELSE 0 END) as open,
                    SUM(CASE WHEN severity = 'critical' AND status != 'resolved' THEN 1 ELSE 0 END) as critical,
                    SUM(CASE WHEN severity = 'high' AND status != 'resolved' THEN 1 ELSE 0 END) as high,
                    COUNT(*) as total
                  FROM " . $this->table;
        $result = $this->conn->query($query);
        return $result->fetch_assoc();
    }
}
?>