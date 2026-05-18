<?php include('../header.php'); ?>

<h1>Guest Reviews</h1>

<?php if(isset($_GET['msg'])): ?>
    <p style="color: green;"><?php echo $_GET['msg']; ?></p>
<?php endif; ?>

<h3>Average Ratings</h3>
<ul>
    <li>Overall: <?php echo isset($avgRatings['overall']) ? round($avgRatings['overall'], 1) : 0; ?> / 5</li>
    <li>Cleanliness: <?php echo isset($avgRatings['cleanliness']) ? round($avgRatings['cleanliness'], 1) : 0; ?> / 5</li>
    <li>Service: <?php echo isset($avgRatings['service']) ? round($avgRatings['service'], 1) : 0; ?> / 5</li>
</ul>

<br>

<table border="1" cellpadding="8" style="width: 100%;">
    <tr>
        <th>Guest</th>
        <th>Rating</th>
        <th>Review</th>
        <th>Your Reply</th>
        <th>Action</th>
    </tr>
    
    <?php if(isset($reviews) && !empty($reviews)): ?>
        <?php foreach($reviews as $review): ?>
        <form method="POST">
            <input type="hidden" name="review_id" value="<?php echo $review['id']; ?>">
            <tr>
                <td><?php echo $review['guest_name']; ?></td>
                <td align="center"><?php echo $review['overall_rating']; ?>/5</td>
                <td><?php echo $review['review_text']; ?></td>
                <td>
                    <textarea name="reply" rows="2" cols="30"><?php echo $review['admin_reply']; ?></textarea>
                </td>
                <td>
                    <button type="submit" name="reply">Send Reply</button>
                </td>
            </tr>
        </form>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="5" align="center">No reviews found</td>
        </tr>
    <?php endif; ?>
</table>

<br>
<a href="../controllers/AdminController.php?action=dashboard">← Back to Dashboard</a>

<?php include('../footer.php'); ?>