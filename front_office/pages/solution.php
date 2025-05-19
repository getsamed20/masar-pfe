<?php 
session_start();

include '../includes/db.php';
include '../components/navbar.php';


if (!isset($_GET['solution_id'])) {
    echo "No solution selected.";
    exit;
}

$solution_id = intval($_GET['solution_id']);

$sql = "SELECT s.*, st.startup_name, c.title AS challenge_title, c.institution_id
        FROM solutions s
        JOIN startups st ON s.startup_id = st.startup_id
        JOIN challenges c ON s.challenge_id = c.challenge_id
        WHERE s.solution_id = $solution_id";

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) === 0) {
    echo "Solution not found.";
    exit;
}

$solution = mysqli_fetch_assoc($result);

if (isset($_POST['update_status'])) {
    $new_status = $_POST['new_status'];

    if (isset($_SESSION['role']) && $_SESSION['role'] === 'institution' && $_SESSION['institution_id'] == $solution['institution_id']) {
        $update_sql = "UPDATE solutions SET status = '$new_status' WHERE solution_id = $solution_id";
        $update_result = mysqli_query($conn, $update_sql);

        if ($update_result) {
            header("Location: " . $_SERVER['REQUEST_URI']);
            exit;
        } else {
            echo "Failed to update the status.";
        }
    } else {
        echo "You are not authorized to update the status.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Solution Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow rounded-4">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Solution for: <?= htmlspecialchars($solution['challenge_title']) ?></h4>
                <span class="badge bg-<?= 
                    $solution['status'] === 'selected' ? 'success' : 
                    ($solution['status'] === 'rejected' ? 'danger' : 
                    ($solution['status'] === 'under review' ? 'warning' : 'secondary')) ?>">
                    <?= ucfirst($solution['status']) ?>
                </span>
            </div>
            <div class="card-body">
                <h5 class="card-title text-primary"><?= htmlspecialchars($solution['proposal_title']) ?></h5>
                <p class="card-text"><?= nl2br(htmlspecialchars($solution['proposal_description'])) ?></p>

                <hr>
                <p><strong>Submitted by:</strong> <?= htmlspecialchars($solution['startup_name']) ?></p>
                <p><strong>Submitted at:</strong> <?= date('F j, Y, g:i a', strtotime($solution['submitted_at'])) ?></p>

                <?php if (!empty($solution['file_attachment'])): ?>
                    <a href="<?= htmlspecialchars($solution['file_attachment']) ?>" class="btn btn-outline-primary mt-2" target="_blank">
                        📎 View Attachment
                    </a>
                <?php endif; ?>

                <div class="mt-3 d-flex flex-wrap gap-2">
                    <?php if (
                        isset($_SESSION['role']) &&
                        $_SESSION['role'] === 'startup' &&
                        $_SESSION['startup_id'] == $solution['startup_id'] &&
                        $solution['status'] === 'pending'
                    ): ?>
                        <a href="edit_solution.php?solution_id=<?= $solution['solution_id']; ?>" class="btn btn-outline-warning btn-sm">
                            Edit Solution
                        </a>
                    <?php endif; ?>

                    <?php if (
                        isset($_SESSION['role']) &&
                        $_SESSION['role'] === 'institution' &&
                        $_SESSION['institution_id'] == $solution['institution_id']
                    ): ?>
                        <form action="" method="post" class="d-flex align-items-center gap-2 mt-2">
                            <input type="hidden" name="solution_id" value="<?= $solution['solution_id']; ?>">
                            <select name="new_status" class="form-select form-select-sm" style="width: auto;">
                                <option value="pending" <?= $solution['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                <option value="under review" <?= $solution['status'] === 'under review' ? 'selected' : ''; ?>>Under Review</option>
                                <option value="selected" <?= $solution['status'] === 'selected' ? 'selected' : ''; ?>>Selected</option>
                                <option value="rejected" <?= $solution['status'] === 'rejected' ? 'selected' : ''; ?>>Rejected</option>
                            </select>
                            <button type="submit" name="update_status" class="btn btn-sm btn-outline-info">Update</button>
                        </form>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </div>
</body>
</html>
 