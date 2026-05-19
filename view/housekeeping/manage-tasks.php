<?php
$content = '
<div class="container-fluid">
    <h4 class="mb-4"><i class="fas fa-tasks"></i> Manage Housekeeping Tasks</h4>
    
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Pending Tasks</h5>
                </div>
                <div class="card-body">
                    ' . (count($pending_tasks) > 0 ? '
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr><th>Room</th><th>Task Type</th><th>Priority</th><th>Scheduled Date</th><th>Assigned To</th><th>Status</th><th>Action</th></tr>
                            </thead>
                            <tbody>
                                ' . implode('', array_map(function($task) {
                                    $priorityClass = $task['priority'] == 'emergency' ? 'danger' : ($task['priority'] == 'urgent' ? 'warning' : 'success');
                                    return '<tr>
                                        <td>' . $task['room_number'] . ' (' . $task['room_type'] . ')</td>
                                        <td>' . ucfirst(str_replace('_', ' ', $task['task_type'])) . '</td>
                                        <td><span class="badge bg-' . $priorityClass . '">' . ucfirst($task['priority']) . '</span></td>
                                        <td>' . date('M d, Y', strtotime($task['scheduled_date'])) . '</td>
                                        <td>' . ($task['assigned_name'] ?? 'Unassigned') . '</td>
                                        <td><span class="badge bg-warning">' . ucfirst($task['status']) . '</span></td>
                                        <td>
                                            <form method="POST" style="display:inline-block">
                                                <input type="hidden" name="task_id" value="' . $task['task_id'] . '">
                                                <select name="status" class="form-select form-select-sm" style="width:120px" onchange="this.form.submit()">
                                                    <option value="pending" ' . ($task['status'] == 'pending' ? 'selected' : '') . '>Pending</option>
                                                    <option value="in_progress" ' . ($task['status'] == 'in_progress' ? 'selected' : '') . '>In Progress</option>
                                                    <option value="completed" ' . ($task['status'] == 'completed' ? 'selected' : '') . '>Completed</option>
                                                </select>
                                                <input type="hidden" name="update_status" value="1">
                                            </form>
                                            <button class="btn btn-sm btn-info" onclick="assignTask(' . $task['task_id'] . ')">Assign</button>
                                        </td>
                                    </tr>';
                                }, $pending_tasks)) . '
                            </tbody>
                        </table>
                    </div>' : '<p class="text-muted text-center">No pending tasks.</p>') . '
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">In Progress Tasks</h5>
                </div>
                <div class="card-body">
                    ' . (count($in_progress_tasks) > 0 ? '
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr><th>Room</th><th>Task Type</th><th>Priority</th><th>Assigned To</th><th>Action</th></tr>
                            </thead>
                            <tbody>
                                ' . implode('', array_map(function($task) {
                                    return '<tr>
                                        <td>' . $task['room_number'] . ' (' . $task['room_type'] . ')</td>
                                        <td>' . ucfirst(str_replace('_', ' ', $task['task_type'])) . '</td>
                                        <td><span class="badge bg-info">' . ucfirst($task['priority']) . '</span></td>
                                        <td>' . ($task['assigned_name'] ?? 'Unassigned') . '</td>
                                        <td>
                                            <form method="POST">
                                                <input type="hidden" name="task_id" value="' . $task['task_id'] . '">
                                                <button type="submit" name="update_status" value="1" class="btn btn-sm btn-success">Mark Complete</button>
                                            </form>
                                        </td>
                                     </tr>';
                                }, $in_progress_tasks)) . '
                            </tbody>
                        </table>
                    </div>' : '<p class="text-muted text-center">No tasks in progress.</p>') . '
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function assignTask(taskId) {
    let assignedTo = prompt("Enter staff ID to assign this task to:");
    if(assignedTo) {
        $.ajax({
            url: "index.php?controller=housekeeping&action=assignTask",
            method: "POST",
            data: {task_id: taskId, assigned_to: assignedTo},
            success: function() {
                location.reload();
            }
        });
    }
}
</script>
';

require_once __DIR__ . '/../layouts/housekeeping-layout.php';
?>