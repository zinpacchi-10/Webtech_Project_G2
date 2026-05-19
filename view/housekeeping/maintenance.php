<?php
$content = '
<div class="container-fluid">
    <h4 class="mb-4"><i class="fas fa-tools"></i> Maintenance Reports</h4>
    
    <div class="row">
        <div class="col-md-5">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-plus-circle"></i> Report New Issue</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label>Room *</label>
                            <select name="room_id" class="form-control" required>
                                <option value="">Select Room</option>
                                ' . implode('', array_map(function($room) {
                                    return '<option value="' . $room['room_id'] . '">' 
                                           . $room['room_number'] . ' - ' . $room['room_type'] . '</option>';
                                }, $rooms)) . '
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Severity *</label>
                            <select name="severity" class="form-control" required>
                                <option value="low">Low</option>
                                <option value="medium">Medium</option>
                                <option value="high">High</option>
                                <option value="critical">Critical</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Description *</label>
                            <textarea name="description" class="form-control" rows="3" required placeholder="Describe the issue in detail..."></textarea>
                        </div>
                        <button type="submit" name="create_report" class="btn btn-danger">Report Issue</button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-7">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-exclamation-triangle"></i> Open Maintenance Issues</h5>
                </div>
                <div class="card-body">
                    ' . (count($open_reports) > 0 ? '
                    <div class="list-group">
                        ' . implode('', array_map(function($report) {
                            $severityClass = $report['severity'] == 'critical' ? 'danger' : ($report['severity'] == 'high' ? 'warning' : ($report['severity'] == 'medium' ? 'info' : 'secondary'));
                            return '<div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>Room ' . $report['room_number'] . '</strong>
                                        <span class="badge bg-' . $severityClass . ' ms-2">' . ucfirst($report['severity']) . '</span>
                                        <p class="mb-0 mt-1">' . htmlspecialchars($report['description']) . '</p>
                                        <small class="text-muted">Reported: ' . date('M d, H:i', strtotime($report['reported_at'])) . '</small>
                                    </div>
                                    <form method="POST">
                                        <input type="hidden" name="report_id" value="' . $report['report_id'] . '">
                                        <select name="status" class="form-select form-select-sm" style="width:130px" onchange="this.form.submit()">
                                            <option value="open" ' . ($report['status'] == 'open' ? 'selected' : '') . '>Open</option>
                                            <option value="in_progress" ' . ($report['status'] == 'in_progress' ? 'selected' : '') . '>In Progress</option>
                                            <option value="resolved" ' . ($report['status'] == 'resolved' ? 'selected' : '') . '>Resolved</option>
                                        </select>
                                        <input type="hidden" name="update_status" value="1">
                                    </form>
                                </div>
                            </div>';
                        }, $open_reports)) . '
                    </div>' : '<p class="text-muted text-center">No open maintenance issues.</p>') . '
                </div>
            </div>
        </div>
    </div>
</div>
';

require_once __DIR__ . '/../layouts/housekeeping-layout.php';
?>