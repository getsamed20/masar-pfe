<?php
// Make sure $conn and $institution are properly initialized before this script runs
// Example:
// include 'path/to/your/db_connection.php';
// $institution = ... (fetched from session or query)

// Fetch all challenges for the current institution, ordered by newest first
$challenges = mysqli_query($conn, "SELECT * FROM challenges WHERE institution_id = '{$institution['institution_id']}' ORDER BY created_at DESC");

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>

<style>
    .challenge-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        cursor: pointer;
    }
    .challenge-header h5 {
        margin-bottom: 0;
    }
    .toggle-icon {
        transition: transform 0.3s ease;
        width: 20px;
        height: 20px;
    }
    .toggle-icon.rotated {
        transform: rotate(180deg);
    }
    .challenge-actions {
        display: flex;
        gap: 15px;
        margin-top: 10px;
        border-bottom: 1px solid #eee;
        padding-bottom: 5px;
    }
    .challenge-actions a {
        text-decoration: none;
        color: grey;
        font-weight: 500;
        padding-bottom: 5px;
        cursor: pointer;
    }
    .challenge-actions a.active,
    .challenge-actions a:hover {
        color: #0C1BA3;
        border-bottom: 2px solid #0C1BA3;
    }
    .challenge-content div {
        display: none;
        padding-top: 10px;
    }
    .challenge-content div.active {
        display: block;
    }
    .challenge-meta {
        font-size: 0.9em;
        color: #666;
    }
    .challenge-meta span {
        margin-right: 15px;
    }
    .challenge-details-container {
        display: none;
    }
    .challenge-details-container.active {
        display: block;
    }
</style>

<div id="challenges-section">
    <h3>Challenges</h3>

    <?php while ($challenge = mysqli_fetch_assoc($challenges)): ?>
        <div class="card mb-3 challenge-card" data-challenge-id="<?php echo $challenge['challenge_id']; ?>">
            <div class="card-body">
                <div class="challenge-header">
                    <div>
                        <h5><?php echo htmlspecialchars($challenge['title']); ?></h5>
                        <p class="challenge-meta">
                            <span>Deadline: <?php echo htmlspecialchars($challenge['deadline']); ?></span>
                        </p>
                    </div>
                    <img src="../pages/icons/next.png" alt="Toggle" class="toggle-icon">
                </div>

                <div class="challenge-details-container">
                    <div class="challenge-actions">
                        <a href="#" class="details-tab active" data-tab="details">Details</a>
                        <a href="#" class="submissions-tab" data-tab="submissions">Submissions</a>
                    </div>

                    <div class="challenge-content">
                        <div class="details-content active">
                            <p><?php echo nl2br(htmlspecialchars($challenge['description'])); ?></p>
                            <?php if (!empty($challenge['file_attachment'])): ?>
                                <p>
                                    <img src="../pages/icons/attachment.png" alt="Attachment Icon" style="width:16px; height:16px; vertical-align:middle; margin-right:5px;">
                                    <a href="../uploads/<?php echo htmlspecialchars($challenge['file_attachment']); ?>" download>Download Attachment</a>
                                </p>
                            <?php endif; ?>
                        </div>

                        <?php
                        $challenge_id = $challenge['challenge_id'];
                        $solutionsQuery = mysqli_query($conn, "
                            SELECT solutions.*, startups.startup_name 
                            FROM solutions
                            LEFT JOIN startups ON solutions.startup_id = startups.startup_id
                            WHERE solutions.challenge_id = '$challenge_id'
                            ORDER BY solutions.submitted_at DESC
                        ");

                        if (!$solutionsQuery) {
                            echo "<p style='color:red;'>Error fetching solutions: " . mysqli_error($conn) . "</p>";
                        }
                        ?>
                        <div class="submissions-content">
                            <?php if ($solutionsQuery && mysqli_num_rows($solutionsQuery) > 0): ?>
                                <div class="table-responsive">
                                    <table class="table table-bordered align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Startup Name</th>
                                                <th>Idea Summary</th>
                                                <th>Files</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php while ($solution = mysqli_fetch_assoc($solutionsQuery)): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($solution['startup_name'] ?? 'N/A') ?></td>
                                                    <td><?= nl2br(htmlspecialchars($solution['proposal_description'])) ?></td>
                                                    <td>
                                                        <?php if (!empty($solution['file_attachment'])): ?>
                                                            <a href="../uploads/<?= htmlspecialchars($solution['file_attachment']) ?>" download>Download</a>
                                                        <?php else: ?>
                                                            <span class="text-muted">No File</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <form action="../public_institution_profile/challenges/update_solution_status.php" method="POST" class="d-inline">
                                                            <input type="hidden" name="solution_id" value="<?= $solution['solution_id'] ?>">
                                                            <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                                                <option value="pending" <?= $solution['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                                                                <option value="selected" <?= $solution['status'] === 'selected' ? 'selected' : '' ?>>Selected</option>
                                                                <option value="rejected" <?= $solution['status'] === 'rejected' ? 'selected' : '' ?>>Rejected</option>
                                                            </select>
                                                        </form>
                                                    </td>
                                                </tr>
                                            <?php endwhile; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <p class="text-muted">No solutions submitted yet.</p>
                            <?php endif; ?>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    <?php endwhile; ?>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Challenge card toggle (show/hide details)
        document.querySelectorAll('.challenge-card').forEach(card => {
            const toggleIcon = card.querySelector('.toggle-icon');
            const detailsContainer = card.querySelector('.challenge-details-container');
            const challengeHeader = card.querySelector('.challenge-header');

            challengeHeader.addEventListener('click', () => {
                detailsContainer.classList.toggle('active');
                toggleIcon.classList.toggle('rotated');
            });

            // Tab switching logic inside each challenge card
            const tabLinks = card.querySelectorAll('.challenge-actions a');
            const contentDivs = card.querySelectorAll('.challenge-content > div');

            tabLinks.forEach(tabLink => {
                tabLink.addEventListener('click', (e) => {
                    e.preventDefault();

                    // Remove active classes
                    tabLinks.forEach(link => link.classList.remove('active'));
                    contentDivs.forEach(div => div.classList.remove('active'));

                    // Activate clicked tab and its content
                    tabLink.classList.add('active');
                    const targetTab = tabLink.dataset.tab;
                    card.querySelector(`.${targetTab}-content`).classList.add('active');
                });
            });
        });

        // Institution profile main section toggle (posts, events, challenges)
        const sectionButtons = document.querySelectorAll('.section-btn');
        const contentSections = document.querySelectorAll('.content-section');

        sectionButtons.forEach(button => {
            button.addEventListener('click', () => {
                sectionButtons.forEach(btn => btn.classList.remove('active'));
                contentSections.forEach(section => section.classList.add('d-none'));

                button.classList.add('active');

                const targetSectionId = button.getAttribute('data-section') + '-section';
                const targetSection = document.getElementById(targetSectionId);
                if (targetSection) {
                    targetSection.classList.remove('d-none');
                }
            });
        });
    });
</script>
