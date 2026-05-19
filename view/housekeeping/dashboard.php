<?php
$content = '
<div class="container-fluid">
    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card card-stats bg-danger text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Dirty Rooms</h6>
                            <h2 class="mb-0">' . ($room_stats['dirty'] ?? 0) . '</h2>
                        </div>
                        <i class="fas fa-broom fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-stats bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Cleaning In Progress</h6>
                            <h2 class="mb-0">' . ($room_stats['cleaning'] ?? 0) . '</h2>
                        </div>
                        <i class="fas fa-spray-can fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-stats bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Pending Tasks</h6>
                            <h2 class="mb-0">' . ($task_stats['pending'] ?? 0) . '</h2>
                        </div>
                        <i class="fas fa-tasks fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-stats bg-secondary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Open Maintenance</h6>
                            <h2 class="mb-0">' . ($maintenance_stats['open'] ?? 0) . '</h2>
                        </div>
                        <i class="fas fa-tools fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Today\'s Tasks -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-calendar-day"></i> Today\'s Tasks</h5>
                </div>
                <div class="card-body">';
if (count($today_tasks) > 0) {
    $content .= '<div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr><th>Room</th><th>Task Type</th><th>Priority</th><th>Status</th><th>Action</th></tr>
                            </thead>
                            <tbody>';
    foreach ($today_tasks as $task) {
        $priorityClass = ($task['priority'] == 'emergency') ? 'danger' : (($task['priority'] == 'urgent') ? 'warning' : 'success');
        $statusClass = ($task['status'] == 'pending') ? 'warning' : (($task['status'] == 'in_progress') ? 'info' : 'success');
        $content .= '<tr>
                            <td>' . $task['room_number'] . ' (' . $task['room_type'] . ')</td>
                            <td>' . ucfirst(str_replace('_', ' ', $task['task_type'])) . '</td>
                            <td><span class="badge bg-' . $priorityClass . '">' . ucfirst($task['priority']) . '</span></td>
                            <td><span class="badge bg-' . $statusClass . '">' . ucfirst($task['status']) . '</span></td>
                            <td><a href="index.php?controller=housekeeping&action=manageTasks" class="btn btn-sm btn-primary">Update</a></td>
                          </tr>';
    }
    $content .= '              </tbody>
                        </table>
                    </div>';
} else {
    $content .= '<p class="text-muted text-center">No tasks scheduled for today.</p>';
}
$content .= '</div>
            </div>
        </div>
        
        <!-- Open Maintenance Issues -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-exclamation-triangle"></i> Open Maintenance Issues</h5>
                </div>
                <div class="card-body">';
if (count($open_maintenance) > 0) {
    $content .= '<div class="list-group">';
    foreach ($open_maintenance as $issue) {
        $severityClass = ($issue['severity'] == 'critical') ? 'danger' : (($issue['severity'] == 'high') ? 'warning' : (($issue['severity'] == 'medium') ? 'info' : 'secondary'));
        $content .= '<div class="list-group-item">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <strong>Room ' . $issue['room_number'] . '</strong>
                                        <span class="badge bg-' . $severityClass . ' ms-2">' . ucfirst($issue['severity']) . '</span>
                                        <p class="mb-0 small">' . htmlspecialchars(substr($issue['description'], 0, 50)) . '...</p>
                                    </div>
                                    <a href="index.php?controller=housekeeping&action=maintenance" class="btn btn-sm btn-outline-primary">View</a>
                                </div>
                            </div>';
    }
    $content .= '</div>';
} else {
    $content .= '<p class="text-muted text-center">No open maintenance issues.</p>';
}
$content .= '</div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Today\'s Check-outs (Priority Cleaning) -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-sign-out-alt"></i> Today\'s Check-outs (Priority Cleaning)</h5>
                </div>
                <div class="card-body">';
if (count($today_checkouts) > 0) {
    $content .= '<div class="list-group">';
    foreach ($today_checkouts as $checkout) {
        $content .= '<div class="list-group-item list-group-item-warning">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <strong>Room ' . $checkout['room_number'] . '</strong><br>
                                        <small>' . $checkout['room_type'] . ' | Guest: ' . $checkout['guest_name'] . '</small>
                                    </div>
                                    <a href="index.php?controller=housekeeping&action=createTask" class="btn btn-sm btn-success">Create Cleaning Task</a>
                                </div>
                            </div>';
    }
    $content .= '</div>';
} else {
    $content .= '<p class="text-muted text-center">No check-outs today.</p>';
}
$content .= '</div>
            </div>
        </div>
        
        <!-- Tomorrow\'s Check-ins -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-sign-in-alt"></i> Tomorrow\'s Check-ins (Need Ready Rooms)</h5>
                </div>
                <div class="card-body">';
if (count($today_checkins) > 0) {
    $content .= '<div class="list-group">';
    foreach ($today_checkins as $checkin) {
        $content .= '<div class="list-group-item list-group-item-info">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <strong>Room ' . $checkin['room_number'] . '</strong><br>
                                        <small>' . $checkin['room_type'] . ' | Guest: ' . $checkin['guest_name'] . '</small>
                                    </div>
                                    <span class="badge bg-warning">Needs to be ready</span>
                                </div>
                            </div>';
    }
    $content .= '</div>';
} else {
    $content .= '<p class="text-muted text-center">No check-ins scheduled for tomorrow.</p>';
}
$content .= '</div>
            </div>
        </div>
    </div>
</div>

<script>
// Auto-refresh dashboard stats every 30 seconds
setInterval(function() {
    $.ajax({
        url: "index.php?controller=housekeeping&action=getDashboardStats",
        method: "GET",
        dataType: "json",
        success: function(data) {
            if(data && data.data) {
                $(".card-stats .h2").eq(0).text(data.data.dirty_rooms);
                $(".card-stats .h2").eq(1).text(data.data.cleaning_rooms);
                $(".card-stats .h2").eq(2).text(data.data.pending_tasks);
                $(".card-stats .h2").eq(3).text(data.data.open_maintenance);
            }
        }
    });
}, 30000);
</script>
';

require_once __DIR__ . '/../layouts/housekeeping-layout.php';
?>