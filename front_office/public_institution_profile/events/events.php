<?php 
$events = mysqli_query($conn, "SELECT * FROM events WHERE institution_id = '{$institution['institution_id']}' ORDER BY created_at DESC");
while ($event = mysqli_fetch_assoc($events)): ?>
  <div class="card mb-4 shadow-sm">
    <?php if (!empty($event['cover_image'])): ?>
      <img src="../<?php echo htmlspecialchars($event['cover_image']); ?>" class="card-img-top" style="max-height: 300px; object-fit: cover;" alt="Event Cover">
    <?php endif; ?>
    <div class="card-body">
      <h5 class="card-title"><?php echo htmlspecialchars($event['title']); ?></h5>
      <p class="card-text"><?php echo nl2br(htmlspecialchars($event['description'])); ?></p>
      <p class="mb-1"><strong>Date:</strong> <?php echo $event['date']; ?> at <?php echo $event['time']; ?></p>
      <p class="mb-1"><strong>Location:</strong> <?php echo htmlspecialchars($event['location']); ?></p>
      <p><strong>Type:</strong> <?php echo ucfirst($event['event_type']); ?></p>

      <div class="d-flex justify-content-end gap-2 mt-3">
        <a href="events/delete_event.php?event_id=<?php echo $event['event_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this event?');">Delete</a>
      </div>
    </div>
  </div>

<?php endwhile; ?>
