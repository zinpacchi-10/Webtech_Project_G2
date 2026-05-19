<?php
// views/auth/profile.php
$content = '
<div class="container-fluid">
    <h4 class="mb-4"><i class="fas fa-user-circle"></i> My Profile</h4>
    
    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Profile Information</h5>
                </div>
                <div class="card-body">
                    ' . (!empty($errors) ? '<div class="alert alert-danger">' . implode('<br>', $errors) . '</div>' : '') . '
                    ' . (!empty($success) ? '<div class="alert alert-success">' . $success . '</div>' : '') . '
                    
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label>Full Name</label>
                            <input type="text" name="name" class="form-control" value="' . htmlspecialchars($user['name'] ?? '') . '" required>
                        </div>
                        <div class="mb-3">
                            <label>Email</label>
                            <input type="email" class="form-control" value="' . htmlspecialchars($user['email'] ?? '') . '" readonly disabled>
                        </div>
                        <div class="mb-3">
                            <label>Phone Number</label>
                            <input type="text" name="phone" class="form-control" value="' . htmlspecialchars($user['phone'] ?? '') . '">
                        </div>
                        <div class="mb-3">
                            <label>Role</label>
                            <input type="text" class="form-control" value="Housekeeping Supervisor" readonly disabled>
                        </div>
                        <div class="mb-3">
                            <label>Member Since</label>
                            <input type="text" class="form-control" value="' . (isset($user['created_at']) ? date('F d, Y', strtotime($user['created_at'])) : 'N/A') . '" readonly disabled>
                        </div>
                        <button type="submit" class="btn btn-success">Update Profile</button>
                        <a href="index.php?controller=housekeeping&action=dashboard" class="btn btn-secondary">Back to Dashboard</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
';

require_once __DIR__ . '/../layouts/housekeeping-layout.php';
?>