<?php
include('../includes/db.php');

$startup_id = $_SESSION['startup_id'];

$query = "SELECT s.*, c.title AS challenge_title, pi.institution_name
          FROM solutions s
          JOIN challenges c ON s.challenge_id = c.challenge_id
          JOIN public_institutions pi ON c.institution_id = pi.institution_id
          WHERE s.startup_id = '$startup_id'
          ORDER BY s.submitted_at DESC";

$result = mysqli_query($conn, $query);
?>

<h4>Your Proposed Solutions</h4>

<?php if (mysqli_num_rows($result) > 0): ?>
    <?php while ($solution = mysqli_fetch_assoc($result)): ?>
        <div class="card mb-3">
            <div class="card-header">
                <strong><?php echo htmlspecialchars($solution['proposal_title']); ?></strong>
                <span class="text-muted float-end"><?php echo date('F j, Y', strtotime($solution['submitted_at'])); ?></span>
            </div>
            <div class="card-body">
    <p><strong>Challenge:</strong> <?php echo htmlspecialchars($solution['challenge_title']); ?></p>
    <p><strong>Institution:</strong> <?php echo htmlspecialchars($solution['institution_name']); ?></p>
    <p><?php echo nl2br(htmlspecialchars($solution['proposal_description'])); ?></p>

    <?php if (!empty($solution['file_attachment']) && file_exists($solution['file_attachment'])): ?>
        <a href="<?php echo htmlspecialchars($solution['file_attachment']); ?>" target="_blank" class="btn btn-sm btn-outline-primary">Download Attachment</a>
    <?php endif; ?>

    <a href="../pages/solution.php?solution_id=<?php echo $solution['solution_id']; ?>" class="btn btn-info btn-sm mt-2">View Details</a>

    <span class="badge bg-<?php 
        echo $solution['status'] === 'selected' ? 'success' :
             ($solution['status'] === 'rejected' ? 'danger' : 'secondary'); ?>">
        <?php echo ucfirst($solution['status']); ?>
    </span>
</div>

        </div>
    <?php endwhile; ?>
<?php else: ?>
    <p class="text-muted">You haven't proposed any solutions yet.</p>
<?php endif; ?>
