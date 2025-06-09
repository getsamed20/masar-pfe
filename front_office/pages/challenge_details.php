<?php

session_start();

include '../includes/db.php';
include '../components/navbar.php';


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
  <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Devanagari:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Hebrew:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
 body {
            background-color: #F2F6FF;
            font-family: 'IBM Plex Sans Devanagari', sans-serif;
            
        }
        </style>
  <style>
    .card {
      box-shadow: 0 4px 4px rgba(0, 0, 0, 0.3);
    }

    /* Style for the "View Details" button */
    .view-solution-details {
      background-color: #02FA72;
      color: #0C1BA3;
      border: 1px solid #02FA72;
      padding: 6px 12px; /* Smaller padding */
      font-size: 0.9rem; /* Smaller font size */
      border-radius: 5px;
      text-decoration: none;
      display: inline-block;
      transition: background-color 0.3s ease, color 0.3s ease;
      margin-right: 10px; /* Add some spacing between buttons */
    }

    .view-solution-details:hover {
      background-color: #02FA72; /* Keep same on hover or slight variation */
      color: #0C1BA3;
      opacity: 0.9;
    }

    /* Style for the "Download Attachment" button */
    .download-solution-attachment {
      background-color: transparent;
      color: #0C1BA3;
      border: 1px solid #0C1BA3;
      padding: 6px 12px; /* Smaller padding */
      font-size: 0.9rem; /* Smaller font size */
      border-radius: 5px;
      text-decoration: none;
      display: inline-block;
      transition: background-color 0.3s ease, color 0.3s ease;
    }

    .download-solution-attachment:hover {
      background-color: #0C1BA3;
      color: white;
    }

    .status-badge {
      padding: 0.3em 0.6em; /* Smaller padding */
      border-radius: 0.4rem; /* Slightly smaller border-radius */
      font-size: 0.8rem; /* Smaller font size */
      font-weight: bold;
      margin-left: 15px; /* Increased margin-left for state */
      display: inline-block; /* Ensure it respects margin and padding */
    }

    /* Custom styles for Edit and Delete buttons */
    .btn-edit-challenge {
        background-color: #002592;
        color: white;
        border: 1px solid #002592;
        padding: 6px 12px;
        font-size: 0.9rem;
        border-radius: 5px;
        text-decoration: none;
        display: inline-flex; /* Use flexbox for icon alignment */
        align-items: center; /* Vertically align icon and text */
        transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
    }

    .btn-edit-challenge:hover {
        background-color: #001a6b; /* Darken on hover */
        color: white;
        border-color: #001a6b;
    }

    .btn-delete-challenge {
        background-color: #BA0000;
        color: white;
        border: 1px solid #BA0000;
        padding: 6px 12px;
        font-size: 0.9rem;
        border-radius: 5px;
        text-decoration: none;
        display: inline-flex; /* Use flexbox for icon alignment */
        align-items: center; /* Vertically align icon and text */
        transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
    }

    .btn-delete-challenge:hover {
        background-color: #8c0000; /* Darken on hover */
        color: white;
        border-color: #8c0000;
    }

    .btn-edit-challenge img,
    .btn-delete-challenge img {
        margin-right: 5px; /* Space between icon and text */
        height: 16px; /* Adjust icon size as needed */
        width: 16px; /* Adjust icon size as needed */
    }
  </style>
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
      <button class="btn-edit-challenge" data-bs-toggle="modal" data-bs-target="#editChallengeModal<?= $challenge['challenge_id'] ?>">
        <img src="icons/edit.png" alt="Edit Icon"> Edit
      </button>
      <a href="../public_institution_profile/challenges/delete_challenge.php?challenge_id=<?= $challenge_id ?>" class="btn-delete-challenge" onclick="return confirm('Are you sure you want to delete this challenge?');">
        <img src="icons/trash-empty.png" alt="Delete Icon"> Delete
      </a>
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
      <?php // echo '$solutions' ?>

    <?php while ($solution = mysqli_fetch_assoc($solutions)): ?>
            <?php // echo '$solutions' ?>

      <div class="card mb-3">
        <div class="card-header d-flex justify-content-between align-items-center">
          <div>
            <strong><?= htmlspecialchars($solution['proposal_title']) ?></strong>
            <span class="text-muted">
              by <?= htmlspecialchars($solution['startup_name']) ?> on <?= date('F j, Y', strtotime($solution['submitted_at'])) ?>
            </span>
          </div>
          <?php
            $status_bg_color = '';
            $status_text_color = '';
            switch ($solution['status']) {
                case 'pending':
                    $status_bg_color = '#CCFFD0';
                    $status_text_color = '#0C1BA3';
                    break;
                case 'under review':
                    $status_bg_color = '#ADD8E6';
                    $status_text_color = '#000080';
                    break;
                case 'rejected':
                    $status_bg_color = '#FF0004';
                    $status_text_color = 'white';
                    break;
                case 'selected':
                    $status_bg_color = '#64C40C';
                    $status_text_color = 'white';
                    break;
                default:
                    $status_bg_color = '#CCCCCC';
                    $status_text_color = '#333333';
                    break;
            }
          ?>
          <span class="status-badge" style="background-color: <?= $status_bg_color ?>; color: <?= $status_text_color ?>;">
            <?= ucfirst($solution['status']) ?>
          </span>
        </div>
        <div class="card-body">
          <p><?= nl2br(htmlspecialchars($solution['proposal_description'])) ?></p>
          <a href="solution.php?solution_id=<?= $solution['solution_id'] ?>" class="view-solution-details">View Details</a>
          <?php if (!empty($solution['file_attachment']) && file_exists($solution['file_attachment'])): ?>
            <a href="<?= htmlspecialchars($solution['file_attachment']) ?>" target="_blank" class="download-solution-attachment">Download Attachment</a>
          <?php endif; ?>
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