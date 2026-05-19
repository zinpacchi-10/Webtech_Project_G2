<?php
$content = '
<div class="container-fluid">
    <h4 class="mb-4"><i class="fas fa-chart-line"></i> Daily Housekeeping Report</h4>
    
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h6 class="mb-0">Rooms Cleaned Today</h6>
                    <h2 class="mb-0">' . ($task_stats['completed_today'] ?? 0) . '</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <h6 class="mb-0">Dirty Rooms</h6>
                    <h2 class="mb-0">' . ($room_stats['dirty'] ?? 0) . '</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h6 class="mb-0">Pending Tasks</h6>
                    <h2 class="mb-0">' . ($task_stats['pending'] ?? 0) . '</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-secondary text-white">
                <div class="card-body">
                    <h6 class="mb-0">Open Maintenance</h6>
                    <h2 class="mb-0">' . ($maintenance_stats['open'] ?? 0) . '</h2>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Room Status Summary</h5>
                </div>
                <div class="card-body">
                    <canvas id="roomStatusChart" height="250"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Task Priority Summary</h5>
                </div>
                <div class="card-body">
                    <canvas id="priorityChart" height="250"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Recent Completed Tasks</h5>
                </div>
                <div class="card-body">
                    ' . (count($completed_tasks) > 0 ? '
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr><th>Room</th><th>Task Type</th><th>Completed At</th></tr>
                            </thead>
                            <tbody>
                                ' . implode('', array_map(function($task) {
                                    return '<tr>
                                        <td>' . $task['room_number'] . ' (' . $task['room_type'] . ')</td>
                                        <td>' . ucfirst(str_replace('_', ' ', $task['task_type'])) . '</td>
                                        <td>' . date('M d, H:i', strtotime($task['completed_at'])) . '</td>
                                      </tr>';
                                }, array_slice($completed_tasks, 0, 10))) . '
                            </tbody>
                        </table>
                    </div>' : '<p class="text-muted text-center">No completed tasks yet today.</p>') . '
                </div>
                <div class="card-footer">
                    <button class="btn btn-primary" onclick="window.print()">
                        <i class="fas fa-print"></i> Print Report
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Room Status Chart
const roomCtx = document.getElementById("roomStatusChart").getContext("2d");
new Chart(roomCtx, {
    type: "doughnut",
    data: {
        labels: ["Available", "Occupied", "Dirty", "Cleaning", "Maintenance"],
        datasets: [{
            data: [' . ($room_stats['available'] ?? 0) . ', ' . ($room_stats['occupied'] ?? 0) . ', 
                    ' . ($room_stats['dirty'] ?? 0) . ', ' . ($room_stats['cleaning'] ?? 0) . ', 
                    ' . ($room_stats['maintenance'] ?? 0) . '],
            backgroundColor: ["#28a745", "#ffc107", "#dc3545", "#17a2b8", "#6c757d"]
        }]
    }
});

// Priority Chart
const priorityCtx = document.getElementById("priorityChart").getContext("2d");
new Chart(priorityCtx, {
    type: "bar",
    data: {
        labels: ["Emergency", "Urgent", "Normal"],
        datasets: [{
            label: "Pending Tasks by Priority",
            data: [' . (($priority_stats['emergency'] ?? 0)) . ', ' . (($priority_stats['urgent'] ?? 0)) . ', ' . (($priority_stats['normal'] ?? 0)) . '],
            backgroundColor: ["#dc3545", "#fd7e14", "#28a745"]
        }]
    }
});
</script>
';

require_once __DIR__ . '/../layouts/housekeeping-layout.php';
?>