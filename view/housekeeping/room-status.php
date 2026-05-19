<?php
$content = '
<div class="container-fluid">
    <h4 class="mb-4"><i class="fas fa-bed"></i> Room Status Board</h4>
    
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-2">
                            <div class="border rounded p-3 bg-success text-white">
                                <h3 class="mb-0">' . ($room_stats['available'] ?? 0) . '</h3>
                                <small>Available</small>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="border rounded p-3 bg-warning">
                                <h3 class="mb-0">' . ($room_stats['occupied'] ?? 0) . '</h3>
                                <small>Occupied</small>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="border rounded p-3 bg-danger text-white">
                                <h3 class="mb-0">' . ($room_stats['dirty'] ?? 0) . '</h3>
                                <small>Dirty</small>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="border rounded p-3 bg-info text-white">
                                <h3 class="mb-0">' . ($room_stats['cleaning'] ?? 0) . '</h3>
                                <small>Cleaning</small>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="border rounded p-3 bg-secondary text-white">
                                <h3 class="mb-0">' . ($room_stats['maintenance'] ?? 0) . '</h3>
                                <small>Maintenance</small>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="border rounded p-3 bg-dark text-white">
                                <h3 class="mb-0">' . ($room_stats['total'] ?? 0) . '</h3>
                                <small>Total Rooms</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">All Rooms - Detailed View</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        ' . implode('', array_map(function($room) {
                            $statusClass = '';
                            $statusIcon = '';
                            $statusText = '';
                            
                            switch($room['status']) {
                                case 'available':
                                    $statusClass = 'success';
                                    $statusIcon = 'fa-check-circle';
                                    $statusText = 'Available';
                                    break;
                                case 'occupied':
                                    $statusClass = 'warning';
                                    $statusIcon = 'fa-user';
                                    $statusText = 'Occupied';
                                    break;
                                case 'dirty':
                                    $statusClass = 'danger';
                                    $statusIcon = 'fa-broom';
                                    $statusText = 'Dirty - Needs Cleaning';
                                    break;
                                case 'cleaning':
                                    $statusClass = 'info';
                                    $statusIcon = 'fa-spray-can';
                                    $statusText = 'Cleaning in Progress';
                                    break;
                                case 'maintenance':
                                    $statusClass = 'secondary';
                                    $statusIcon = 'fa-tools';
                                    $statusText = 'Maintenance';
                                    break;
                                default:
                                    $statusClass = 'dark';
                                    $statusIcon = 'fa-ban';
                                    $statusText = 'Blocked';
                            }
                            
                            return '<div class="col-md-3 col-lg-2 mb-3">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <h5 class="card-title">Room ' . $room['room_number'] . '</h5>
                                        <p class="mb-1"><small>' . $room['room_type'] . '</small></p>
                                        <p class="mb-2"><strong>₨ ' . number_format($room['price'], 2) . '</strong>/night</p>
                                        <span class="badge bg-' . $statusClass . ' p-2">
                                            <i class="fas ' . $statusIcon . '"></i> ' . $statusText . '
                                        </span>
                                    </div>
                                </div>
                            </div>';
                        }, $rooms)) . '
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Auto-refresh room status every 30 seconds
setInterval(function() {
    location.reload();
}, 30000);
</script>
';

require_once __DIR__ . '/../layouts/housekeeping-layout.php';
?>