<?php
session_start();

include '../includes/db.php';


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
include '../components/navbar.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Solution Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .card {
            box-shadow: 0 4px 4px rgba(0, 0, 0, 0.3); /* Apply box-shadow to the card */
        }

        .card-header {
            border-bottom: none !important; /* Remove the line in card header */
            background-color: #f8f9fa; /* Light background for header */
            color: #212529; /* Darker text for header */
            padding-bottom: 15px; /* Add some padding to the bottom */
        }

        .card-title {
            color: #0C1BA3 !important; /* Specific color for card title */
            font-size: 1.75rem; /* Make title larger */
            margin-bottom: 1rem;
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
            margin-top: 10px; /* Spacing from above content */
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
        }

        /* Custom status badge colors */
        .status-badge.pending {
            background-color: #CCFFD0;
            color: #0C1BA3;
        }
        .status-badge.under-review {
            background-color: #ADD8E6;
            color: #000080;
        }
        .status-badge.rejected {
            background-color: #FF0004;
            color: white;
        }
        .status-badge.selected {
            background-color: #64C40C;
            color: white;
        }

        /* Edit solution button style (renamed from btn-edit-challenge for clarity) */
        .btn-edit-solution {
            background-color: #002592;
            color: white;
            border: 1px solid #002592;
            padding: 6px 12px;
            font-size: 0.9rem;
            border-radius: 5px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
        }
        .btn-edit-solution:hover {
            background-color: #001a6b;
            color: white;
            border-color: #001a6b;
        }

        /* Style for the Update Status button */
        .btn-update-status {
            background-color: #02FA72; /* Using the same green as 'View Details' for consistency */
            color: #0C1BA3; /* Using the same blue as 'View Details' for consistency */
            border: 1px solid #02FA72;
            padding: 6px 12px;
            font-size: 0.9rem;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block; /* Not inline-flex as no icon */
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .btn-update-status:hover {
            background-color: #02FA72;
            color: #0C1BA3;
            opacity: 0.9;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow rounded-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Solution for: <?= htmlspecialchars($solution['challenge_title']) ?></h4>
                <?php
                    $status_class = str_replace(' ', '-', $solution['status']); // Convert 'under review' to 'under-review' for class
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
                <h5 class="card-title"><?= htmlspecialchars($solution['proposal_title']) ?></h5>
                <p class="card-text"><?= nl2br(htmlspecialchars($solution['proposal_description'])) ?></p>

                <hr>
                <p><strong>Submitted by:</strong> <?= htmlspecialchars($solution['startup_name']) ?></p>
                <p><strong>Submitted at:</strong> <?= date('F j, Y, g:i a', strtotime($solution['submitted_at'])) ?></p>

                <?php if (!empty($solution['file_attachment'])): ?>
                    <a href="<?= htmlspecialchars($solution['file_attachment']) ?>" class="download-solution-attachment" target="_blank">
                        Download Attachment
                    </a>
                <?php endif; ?>

                <div class="mt-3 d-flex flex-wrap gap-2">
                    <?php if (
                        isset($_SESSION['role']) &&
                        $_SESSION['role'] === 'startup' &&
                        $_SESSION['startup_id'] == $solution['startup_id'] &&
                        $solution['status'] === 'pending'
                    ): ?>
                        <a href="edit_solution.php?solution_id=<?= $solution['solution_id']; ?>" class="btn-edit-solution">
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
                            <button type="submit" name="update_status" class="btn-update-status">Update Status</button>
                        </form>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </div>
</body>
</html>