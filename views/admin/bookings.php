<?php include('../views/header.php'); ?>

<h1>All Bookings</h1>

<?php if(isset($_GET['msg'])): ?>
    <div class="success-msg"><?php echo $_GET['msg']; ?></div>
<?php endif; ?>

<form method="GET" class="filter-form">
    <input type="hidden" name="action" value="bookings">
    <select name="status">
        <option value="">All Status</option>
        <option value="confirmed">Confirmed</option>
        <option value="checked_in">Checked In</option>
        <option value="checked_out">Checked Out</option>
        <option value="cancelled">Cancelled</option>
    </select>
    <input type="date" name="start_date" placeholder="Start Date">
    <input type="date" name="end_date" placeholder="End Date">
    <button type="submit" class="btn-filter">Filter</button>
    <a href="admin.php?action=bookings" class="btn-reset">Reset</a>
</form>

<table class="data-table">
    <thead>
        <tr><th>Guest</th><th>Room</th><th>Check In</th><th>Check Out</th><th>Guests</th><th>Total</th><th>Status</th></tr>
    </thead>
    <tbody>
        <?php if(empty($bookings)): ?>
            <tr><td colspan="7" class="text-center">No bookings found</td></tr>
        <?php else: ?>
            <?php foreach($bookings as $booking): ?>
            <tr>
                <td><?php echo $booking['guest_name']; ?></td>
                <td><?php echo $booking['room_number'] . ' - ' . $booking['room_type']; ?></td>
                <td><?php echo $booking['checkin_date']; ?></td>
                <td><?php echo $booking['checkout_date']; ?></td>
                <td><?php echo $booking['num_guests']; ?></td>
                <td>$<?php echo number_format($booking['total_price']); ?></td>
                <td class="status-<?php echo $booking['status']; ?>"><?php echo $booking['status']; ?></td>
            </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>

<?php include('../views/footer.php'); ?>