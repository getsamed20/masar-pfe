<div class="d-flex justify-content-between align-items-center mb-3">
    <h4>Challenges</h4>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addChallengeModal">+ Add Challenge</button>
</div>

<div class="modal fade" id="addChallengeModal" tabindex="-1" aria-labelledby="addChallengeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
    <form method="post" action="challenges/add_challenge.php" enctype="multipart/form-data">
    <div class="modal-header">
          <h5 class="modal-title" id="addChallengeModalLabel">Create New Challenge</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-2"><label>Title</label><input type="text" name="challenge_title" class="form-control" required></div>
          <div class="mb-2"><label>Description</label><textarea name="challenge_description" rows="4" class="form-control" required></textarea></div>
          <div class="mb-2"><label>Deadline</label><input type="date" name="challenge_deadline" class="form-control" required></div>
          <div class="mb-2"><label>Attach File (PDF, DOC, etc.)</label>
            <input type="file" name="challenge_file" class="form-control">
          </div>

        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Publish Challenge</button>
        </div>
      </form>
    </div>
  </div>
</div>

<hr class="my-4">
<h4 class="mb-3">My Challenges</h4>

<?php 
$challenges = mysqli_query($conn, "SELECT * FROM challenges WHERE institution_id = '{$institution['institution_id']}' ORDER BY created_at DESC");
while ($challenge = mysqli_fetch_assoc($challenges)): ?>
    <div class="card mb-3 challenge-card" 
         data-challenge-id="<?php echo $challenge['challenge_id']; ?>" 
         style="cursor: pointer;">
        <div class="card-body">
        <a href="challenge_details.php?challenge_id=<?php echo $challenge['challenge_id']; ?>">Open</a>
        <h5><?php echo $challenge['title']; ?></h5>
            <p><?php echo $challenge['description']; ?></p>
            <p><strong>Deadline:</strong> <?php echo $challenge['deadline']; ?></p>
        </div>
    </div><?php endwhile; ?>


<script>
    document.querySelectorAll('.challenge-card').forEach(card => {
        card.addEventListener('click', function (e) {
            if (e.target.closest('button') || e.target.closest('a')) return;

            const challengeId = this.dataset.challengeId;
            window.location.href = `../pages/challenge_details.php?challenge_id=${challengeId}`;
          });
    });
</script>
  </body>
  </html>