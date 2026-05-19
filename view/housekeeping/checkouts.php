<?php
$content = '
<div class="container-fluid">
    <h4 class="mb-4"><i class="fas fa-sign-out-alt"></i> Upcoming Check-outs</h4>
    
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">Today\'s Check-outs (Priority Cleaning)</h5>
                </div>
                <div class="card-body">
                    ' . (count($today_checkouts) > 0 ? '
                    <div class="list-group">
                        ' . implode('', array_map(function($checkout) {
                            return '<div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>Room ' . $checkout['room_number'] . '</strong><br>
                                        <small>' . $checkout['room_type'] . ' | Guest: ' . $checkout['guest_name'] . '</small>
                                    </div>
                                    <a href="index.php?controller=housekeeping&action=createTask&room_id=' . $checkout['room_id'] . '" class="btn btn-sm btn-success">
                                        <i class="fas fa-broom"></i> Create Cleaning Task
                                    </a>
                                </div>
                            </div>';
                        }, $today_checkouts)) . '
                    </div>' : '<p class="text-muted text-center">No check-outs today.</p>') . '
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Tomorrow\'s Check-outs</h5>
                </div>
                <div class="card-body">
                    ' . (count($tomorrow_checkouts) > 0 ? '
                    <div class="list-group">
                        ' . implode('', array_map(function($checkout) {
                            return '<div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>Room ' . $checkout['room_number'] . '</strong><br>
                                        <small>' . $checkout['room_type'] . ' | Guest: ' . $checkout['guest_name'] . '</small>
                                    </div>
                                    <span class="badge bg-secondary">Plan cleaning for tomorrow</span>
                                </div>
                            </div>';
                        }, $tomorrow_checkouts)) . '
                    </div>' : '<p class="text-muted text-center">No check-outs tomorrow.</p>') . '
                </div>
            </div>
        </div>
    </div>
</div>
';

require_once __DIR__ . '/../layouts/housekeeping-layout.php';
?>