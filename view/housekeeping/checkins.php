<?php
$content = '
<div class="container-fluid">
    <h4 class="mb-4"><i class="fas fa-sign-in-alt"></i> Upcoming Check-ins</h4>
    
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Tomorrow\'s Check-ins (Rooms Must Be Ready)</h5>
                </div>
                <div class="card-body">
                    ' . (count($today_checkins) > 0 ? '
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr><th>Room Number</th><th>Room Type</th><th>Guest Name</th><th>Status</th><th>Action</th></tr>
                            </thead>
                            <tbody>
                                ' . implode('', array_map(function($checkin) {
                                    return '<tr>
                                        <td>' . $checkin['room_number'] . '</td>
                                        <td>' . $checkin['room_type'] . '</td>
                                        <td>' . htmlspecialchars($checkin['guest_name']) . '</td>
                                        <td><span class="badge bg-warning">Needs to be ready</span></td>
                                        <td>
                                            <a href="index.php?controller=housekeeping&action=roomStatus" class="btn btn-sm btn-primary">Check Room Status</a>
                                        </td>
                                     </tr>';
                                }, $today_checkins)) . '
                            </tbody>
                        </table>
                    </div>' : '<p class="text-muted text-center">No check-ins scheduled for tomorrow.</p>') . '
                </div>
            </div>
        </div>
    </div>
</div>
';

require_once __DIR__ . '/../layouts/housekeeping-layout.php';
?>