<?php

session_start();

include '../includes/db.php';
include '../components/navbar.php';


//echo '<pre>'; print_r($_SESSION); echo '</pre>';

$role = $_SESSION['role'] ?? null;
$institution_id = $_SESSION['institution_id'] ?? null;

if (!isset($_GET['challenge_id'])) {
    header('Location: ../public_institution_profile/public_institution_profile.php');
    exit;
}
$challenge_id = intval($_GET['challenge_id']);

$getChallenge = mysqli_query($conn, "SELECT * FROM challenges WHERE challenge_id = '$challenge_id'");
if (!$getChallenge || mysqli_num_rows($getChallenge) === 0) {
    header('Location: ../public_institution_profile.php');
    exit;
}
$getChallengeMedia = mysqli_query($conn, "
    SELECT * FROM media 
    WHERE challenge_id = '$challenge_id'
");

$challenge = mysqli_fetch_assoc($getChallenge);

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>View Challenge</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<div class="container my-5">
  <h1 class="mb-4">View Challenge</h1>

  <div class="card">
    <div class="card-header">
      <h2><?= htmlspecialchars($challenge['title']) ?></h2>
    </div>
    <div class="card-body">
      <p><strong>Description:</strong></p>
      <p><?= nl2br(htmlspecialchars($challenge['description'])) ?></p>

      <p><strong>Date Created:</strong> <?= date('F j, Y', strtotime($challenge['created_at'])) ?></p>
      <p><strong>Category:</strong> <?= htmlspecialchars($challenge['category'] ?? 'Unspecified') ?></p>


      <?php if ($getChallengeMedia && mysqli_num_rows($getChallengeMedia) > 0): ?>
  <p><strong>Attached Files:</strong></p>
  <ul>
    <?php while ($media = mysqli_fetch_assoc($getChallengeMedia)): ?>
      <li>
        <a href="<?= htmlspecialchars($media['file_path']) ?>" target="_blank">
          <?= basename($media['file_path']) ?>
        </a>
      </li>
    <?php endwhile; ?>
  </ul>
<?php else: ?>
  <p class="text-muted">No file attached.</p>
<?php endif; ?>

    </div>
  </div>

  <div class="mt-3 d-flex flex-wrap gap-2">
    <?php if ($role === 'startup'): ?>
      <a href="../pages/submit_solution.php?challenge_id=<?= $challenge_id ?>" class="btn btn-success">Submit Solution</a>
    <?php endif; ?>

    <?php if ($role === 'institution' && $institution_id == $challenge['institution_id']): ?>
      <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editChallengeModal<?= $challenge['challenge_id'] ?>">Edit</button>
      <a href="../public_institution_profile/challenges/delete_challenge.php?challenge_id=<?= $challenge_id ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this challenge?');">Delete</a>
    <?php endif; ?>
  </div>

  <h3 class="mt-5">Submitted Solutions</h3>

  <?php
  $canViewAll = $role === 'institution' && $institution_id == $challenge['institution_id'];
  $canViewOwn = false;
  $solutions = false;

  if ($role === 'startup' && isset($_SESSION['startup_id'])) {
      $startup_id = $_SESSION['startup_id'];
      $getMySolution = mysqli_query($conn, "
          SELECT s.*, st.startup_name 
          FROM solutions s
          JOIN startups st ON s.startup_id = st.startup_id
          WHERE s.challenge_id = '$challenge_id' AND s.startup_id = '$startup_id'
      ");
      if ($getMySolution && mysqli_num_rows($getMySolution) > 0) {
          $canViewOwn = true;
          $solutions = $getMySolution;
      }
  }

  if ($canViewAll) {
      $solutions = mysqli_query($conn, "
          SELECT s.*, st.startup_name 
          FROM solutions s
          JOIN startups st ON s.startup_id = st.startup_id
          WHERE s.challenge_id = '$challenge_id'
          ORDER BY s.submitted_at DESC
      ");
  }
  ?>

  <?php if ($solutions && mysqli_num_rows($solutions) > 0): ?>
    <?php while ($solution = mysqli_fetch_assoc($solutions)): ?>
      <div class="card mb-3">
        <div class="card-header">
          <strong><?= htmlspecialchars($solution['proposal_title']) ?></strong>
          <span class="text-muted float-end">
            by <?= htmlspecialchars($solution['startup_name']) ?> on <?= date('F j, Y', strtotime($solution['submitted_at'])) ?>
          </span>
        </div>
        <div class="card-body">
          <p><?= nl2br(htmlspecialchars($solution['proposal_description'])) ?></p>
          <?php if (!empty($solution['file_attachment']) && file_exists($solution['file_attachment'])): ?>
            <a href="<?= htmlspecialchars($solution['file_attachment']) ?>" target="_blank" class="btn btn-sm btn-outline-primary">Download Attachment</a>
          <?php endif; ?>
          <a href="solution.php?solution_id=<?= $solution['solution_id'] ?>" class="btn btn-sm btn-primary">View Details</a>
          <span class="badge bg-<?=
            $solution['status'] === 'selected' ? 'success' :
            ($solution['status'] === 'rejected' ? 'danger' : 'secondary') ?>">
            <?= ucfirst($solution['status']) ?>
          </span>
        </div>
      </div>
    <?php endwhile; ?>
  <?php else: ?>
    <p class="text-muted">
      <?= !$role ? 'You must be logged in to view solutions.' :
          ($role === 'startup' && !$canViewOwn ? 'You haven’t submitted a solution to this challenge.' :
          ($role === 'institution' && !$canViewAll ? 'You do not have permission to view the solutions.' :
          'No solutions submitted yet.')) ?>
    </p>
  <?php endif; ?>
</div>

<?php if ($role === 'institution' && $institution_id == $challenge['institution_id']): ?>
<!-- Edit Challenge Modal -->
<div class="modal fade" id="editChallengeModal<?= $challenge['challenge_id'] ?>" tabindex="-1" aria-labelledby="editChallengeModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" action="edit_challenge.php" enctype="multipart/form-data">
        <div class="modal-header">
          <h5 class="modal-title" id="editChallengeModalLabel">Edit Challenge</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
          <input type="hidden" name="challenge_id" value="<?= $challenge['challenge_id'] ?>">

          <div class="mb-3">
            <label class="form-label">Title</label>
            <input type="text" class="form-control" name="challenge_title" value="<?= htmlspecialchars($challenge['title']) ?>" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea class="form-control" name="challenge_description" required><?= htmlspecialchars($challenge['description']) ?></textarea>
          </div>

          <div class="mb-3">
            <label class="form-label">Deadline</label>
            <input type="date" class="form-control" name="challenge_deadline" value="<?= htmlspecialchars($challenge['deadline']) ?>" required>
          </div>

<div class="mb-3">
  <label class="form-label">Category</label>
<select name="challenge_category" class="form-control" required>
  <option value="">Select Category</option>
  <option value="Operations" <?= $challenge['category'] == 'Operations' ? 'selected' : '' ?>>Operations</option>
  <option value="Design & Planning" <?= $challenge['category'] == 'Design & Planning' ? 'selected' : '' ?>>Design & Planning</option>
  <option value="Land Use & Urban Planning" <?= $challenge['category'] == 'Land Use & Urban Planning' ? 'selected' : '' ?>>Land Use & Urban Planning</option>
  <option value="Vehicles" <?= $challenge['category'] == 'Vehicles' ? 'selected' : '' ?>>Vehicles</option>
  <option value="Automated Enforcement" <?= $challenge['category'] == 'Automated Enforcement' ? 'selected' : '' ?>>Automated Enforcement</option>
  <option value="ITS & Data Utilization" <?= $challenge['category'] == 'ITS & Data Utilization' ? 'selected' : '' ?>>ITS & Data Utilization</option>
  <option value="Police Enforcement" <?= $challenge['category'] == 'Police Enforcement' ? 'selected' : '' ?>>Police Enforcement</option>
  <option value="Legislation & Regulations" <?= $challenge['category'] == 'Legislation & Regulations' ? 'selected' : '' ?>>Legislation & Regulations</option>
  <option value="Training, Awareness & Education" <?= $challenge['category'] == 'Training, Awareness & Education' ? 'selected' : '' ?>>Training, Awareness & Education</option>
  <option value="Other" <?= $challenge['category'] == 'Other' ? 'selected' : '' ?>>Other</option>
</select>

</div>


          <div class="mb-3">
            <label class="form-label">Replace Attached File (Optional)</label>
            <input type="file" class="form-control" name="challenge_file">
          </div>
        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Save Changes</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </div>
      </form>
    </div>
  </div>
</div>
<?php endif; ?>
<?php include('../components/footer.php'); ?>

</body>
</html>
