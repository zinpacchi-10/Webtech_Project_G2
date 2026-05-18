<?php include('../header.php'); ?>

<h1>Admin Dashboard</h1>

<div class="stats-grid">
    <div class="stat-card">
        <h3>Total Rooms</h3>
        <div class="number"><?php echo isset($roomStats['total_rooms']) ? $roomStats['total_rooms'] : 0; ?></div>
    </div>
    <div class="stat-card">
        <h3>Available Rooms</h3>
        <div class="number"><?php echo isset($roomStats['available_rooms']) ? $roomStats['available_rooms'] : 0; ?></div>
    </div>
    <div class="stat-card">
        <h3>Occupied Rooms</h3>
        <div class="number"><?php echo isset($roomStats['occupied_rooms']) ? $roomStats['occupied_rooms'] : 0; ?></div>
    </div>
    <div class="stat-card">
        <h3>Occupancy Rate</h3>
        <div class="number"><?php echo isset($occupancyRate) ? round($occupancyRate, 1) : 0; ?>%</div>
    </div>
    <div class="stat-card">
        <h3>Total Revenue</h3>
        <div class="number">$<?php echo isset($totalRevenue) ? number_format($totalRevenue) : 0; ?></div>
    </div>
    <div class="stat-card">
        <h3>Pending Reviews</h3>
        <div class="number"><?php echo isset($pendingReviews) ? $pendingReviews : 0; ?></div>
    </div>
</div>

<div class="recent-bookings">
    <h2>Recent Bookings</h2>
    <?php if(isset($recentBookings) && !empty($recentBookings)): ?>
    <table class="data-table">
        <thead>
            <tr>
                <th>Guest Name</th>
                <th>Room Number</th>
                <th>Room Type</th>
                <th>Check In</th>
                <th>Check Out</th>
                <th>Total Price</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($recentBookings as $booking): ?>
            <tr>
                <td><?php echo isset($booking['guest_name']) ? $booking['guest_name'] : 'N/A'; ?></td>
                <td><?php echo isset($booking['room_number']) ? $booking['room_number'] : 'N/A'; ?></td>
                <td><?php echo isset($booking['room_type']) ? $booking['room_type'] : 'N/A'; ?></td>
                <td><?php echo isset($booking['checkin_date']) ? $booking['checkin_date'] : 'N/A'; ?></td>
                <td><?php echo isset($booking['checkout_date']) ? $booking['checkout_date'] : 'N/A'; ?></td>
                <td>$<?php echo isset($booking['total_price']) ? number_format($booking['total_price']) : 0; ?></td>
                <td class="status-<?php echo isset($booking['status']) ? $booking['status'] : 'pending'; ?>">
                    <?php echo isset($booking['status']) ? $booking['status'] : 'pending'; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php else: ?>
        <div class="no-data">No bookings found.</div>
    <?php endif; ?>
</div>

<div class="quick-links">
    <h2>Quick Actions</h2>
    <div class="links-grid">
        <a href="../controllers/AdminController.php?action=rooms" class="quick-link">Manage Rooms</a>
        <a href="../controllers/AdminController.php?action=add_room" class="quick-link">Add New Room</a>
        <a href="../controllers/AdminController.php?action=bookings" class="quick-link">View All Bookings</a>
        <a href="../controllers/AdminController.php?action=reviews" class="quick-link">Manage Reviews</a>
        <a href="../controllers/AdminController.php?action=seasonal_pricing" class="quick-link">Seasonal Pricing</a>
        <a href="../controllers/AdminController.php?action=users" class="quick-link">Manage Users</a>
    </div>
</div>

<?php include('../footer.php'); ?>