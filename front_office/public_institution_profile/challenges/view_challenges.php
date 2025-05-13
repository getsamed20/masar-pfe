<hr class="my-4">
<h4 class="mb-3">Challenges</h4>
<?php 
$challenges = mysqli_query($conn, "SELECT * FROM challenges WHERE institution_id = '$institution_id' ORDER BY created_at DESC");
while ($challenge = mysqli_fetch_assoc($challenges)): ?>
    <div class="card mb-3 challenge-card" 
         data-challenge-id="<?php echo $challenge['challenge_id']; ?>" 
         style="cursor: pointer;">
        <div class="card-body">
            <a href="challenge_details.php?challenge_id=<?php echo $challenge['challenge_id']; ?>" class="btn btn-sm btn-primary mb-2">View Details</a>
            <h5><?php echo htmlspecialchars($challenge['title']); ?></h5>
            <p><?php echo htmlspecialchars($challenge['description']); ?></p>
            <p><strong>Deadline:</strong> <?php echo htmlspecialchars($challenge['deadline']); ?></p>
        </div>
    </div>
<?php endwhile; ?>

<script>
    document.querySelectorAll('.challenge-card').forEach(card => {
        card.addEventListener('click', function (e) {
            if (e.target.closest('a')) return;
            const challengeId = this.dataset.challengeId;
            window.location.href = `challenge_details.php?challenge_id=${challengeId}`;
        });
    });
</script>
