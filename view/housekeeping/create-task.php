<?php
$content = '
<div class="container-fluid">
    <h4 class="mb-4"><i class="fas fa-plus-circle"></i> Create Housekeeping Task</h4>
    
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">New Task Assignment</h5>
                </div>
                <div class="card-body">
                    ' . (!empty($errors) ? '<div class="alert alert-danger">' . implode('<br>', $errors) . '</div>' : '') . '
                    ' . ($success ? '<div class="alert alert-success">' . $success . '</div>' : '') . '
                    
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label>Select Room *</label>
                            <select name="room_id" class="form-control" required>
                                <option value="">Select Room</option>
                                ' . implode('', array_map(function($room) {
                                    return '<option value="' . $room['room_id'] . '">' 
                                           . $room['room_number'] . ' - ' . $room['room_type'] 
                                           . ' (Status: ' . ucfirst($room['status']) . ')</option>';
                                }, $rooms)) . '
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Task Type *</label>
                            <select name="task_type" class="form-control" required>
                                <option value="cleaning">Standard Cleaning</option>
                                <option value="deep_cleaning">Deep Cleaning</option>
                                <option value="inspection">Room Inspection</option>
                                <option value="maintenance">Maintenance</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Priority *</label>
                            <select name="priority" class="form-control" required>
                                <option value="normal">Normal</option>
                                <option value="urgent">Urgent</option>
                                <option value="emergency">Emergency</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Scheduled Date *</label>
                            <input type="date" name="scheduled_date" class="form-control" value="' . date('Y-m-d') . '" required>
                        </div>
                        <div class="mb-3">
                            <label>Assign To</label>
                            <select name="assigned_to" class="form-control">
                                <option value="">Unassigned</option>
                                ' . implode('', array_map(function($staff) {
                                    return '<option value="' . $staff['user_id'] . '">' . htmlspecialchars($staff['name']) . '</option>';
                                }, $staff)) . '
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Notes / Instructions</label>
                            <textarea name="notes" class="form-control" rows="3" placeholder="Enter any specific instructions..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-success">Create Task</button>
                        <a href="index.php?controller=housekeeping&action=dashboard" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
';

require_once __DIR__ . '/../layouts/housekeeping-layout.php';
?>