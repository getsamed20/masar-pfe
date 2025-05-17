<?php 
// challenges.php

// Make sure $conn and $institution are defined before including this file

$selectedChallengeId = isset($_GET['challenge_id']) ? $_GET['challenge_id'] : null;

$challenges = mysqli_query($conn, "SELECT * FROM challenges WHERE institution_id = '{$institution['institution_id']}' ORDER BY created_at DESC");
?>

<div id="challenges-section">
    <h3>Challenges</h3>

    <?php while ($challenge = mysqli_fetch_assoc($challenges)): 
        $isExpanded = ($challenge['challenge_id'] == $selectedChallengeId);
    ?>
        <div class="card mb-3 challenge-card" data-challenge-id="<?php echo $challenge['challenge_id']; ?>" style="cursor: default;">
            <div class="card-body">
                <h5><?php echo htmlspecialchars($challenge['title']); ?></h5>

                <?php if ($isExpanded): ?>
                    <p><?php echo nl2br(htmlspecialchars($challenge['description'])); ?></p>
                    <p><strong>Deadline:</strong> <?php echo htmlspecialchars($challenge['deadline']); ?></p>

                    <!-- Optional: Show any extra fields, like attachments -->
                    <?php if (!empty($challenge['file_attachment'])): ?>
                        <a href="uploads/<?php echo htmlspecialchars($challenge['file_attachment']); ?>" download>Download Attachment</a><br>
                    <?php endif; ?>

                    <!-- Link to collapse/hide details -->
                    <a href="public_institution_profile.php#challenges-section">Hide</a>

                <?php else: ?>
                    <p><?php echo htmlspecialchars(substr($challenge['description'], 0, 100)); ?>...</p>
                    <a href="?challenge_id=<?php echo $challenge['challenge_id']; ?>#challenges-section">See More</a>
                <?php endif; ?>

            </div>
        </div>
    <?php endwhile; ?>
</div>
