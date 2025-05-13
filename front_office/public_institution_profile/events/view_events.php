<!-- Display Events -->
<hr class="my-4">
<h4 class="mb-3">Events</h4>

<?php 

$events = mysqli_query($conn, "SELECT * FROM events WHERE institution_id = '$institution_id' ORDER BY created_at DESC");
while ($event = mysqli_fetch_assoc($events)): ?>
    <div class="card mb-4 shadow-sm">
        <?php if (!empty($event['cover_image'])): ?>
            <img src="<?php echo $event['cover_image']; ?>" class="card-img-top" alt="Event Cover" style="max-height: 300px; object-fit: cover;">
        <?php endif; ?>
        <div class="card-body">
            <h5 class="card-title"><?php echo $event['title']; ?></h5>
            <p class="card-text"><?php echo $event['description']; ?></p>
            <p class="mb-1"><strong>Date:</strong> <?php echo $event['date']; ?> at <?php echo $event['time']; ?></p>
            <p class="mb-1"><strong>Location:</strong> <?php echo $event['location']; ?></p>
            <p><strong>Type:</strong> <?php echo ucfirst($event['event_type']); ?></p>
        </div>
    </div>
<?php endwhile; ?>
