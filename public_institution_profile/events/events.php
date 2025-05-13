<!-- Button to add new event -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <button class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#addEventModal">+ Add Event</button>
</div>

<!-- Add Event Modal -->
<div class="modal fade" id="addEventModal" tabindex="-1" aria-labelledby="addEventModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <form method="post" action="events/add_event.php" enctype="multipart/form-data">
        <div class="modal-header">
          <h5 class="modal-title">Create New Event</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-2"><label>Cover Photo</label><input type="file" name="event_cover" accept="image/*" class="form-control"></div>
          <div class="mb-2"><label>Title</label><input type="text" name="event_title" class="form-control" required></div>
          <div class="mb-2"><label>Description</label><textarea name="event_description" rows="4" class="form-control" required></textarea></div>
          <div class="mb-2"><label>Date</label><input type="date" name="event_date" class="form-control" required></div>
          <div class="mb-2"><label>Time</label><input type="time" name="event_time" class="form-control" required></div>
          <div class="mb-2"><label>Location</label><input type="text" name="event_location" class="form-control" required></div>
          <div class="mb-2"><label>Type</label>
            <select name="event_type" class="form-select" required>
              <option value="offline">Offline</option>
              <option value="online">Online</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Publish Event</button>
        </div>
      </form>
    </div>
  </div>
</div>

<hr class="my-4">
<h4 class="mb-3">My Events</h4>

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
