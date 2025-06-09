<?php 
session_start();
include('db.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: admin_login.php");
    exit();
}

// Get post details from URL parameters
$postId = $_GET['post_id'] ?? null;
$postInstitutionId = $_GET['post_institution_id'] ?? null;
$postOwner = $_GET['post_owner'] ?? '';

// Function to get post content
function getPostContent($postOwner, $postId, $postInstitutionId, $conn) {
    if ($postOwner === 'startup') {
        $query = "SELECT title, content, image, reported FROM posts WHERE post_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $postId);
    } else {
        $query = "SELECT title, content, image, reported FROM posts_institution WHERE post_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $postInstitutionId);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

// Get post content
$postContent = getPostContent($postOwner, $postId, $postInstitutionId, $conn);

// Get all reports for this post
$query = "SELECT report_id, reporter_name, reason, reported_at FROM reports WHERE ";
if ($postOwner === 'startup') {
    $query .= "post_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $postId);
} else {
    $query .= "post_institution_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $postInstitutionId);
}
$stmt->execute();
$reports = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Handle actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'delete') {
        try {
            $conn->begin_transaction();
            
            // First delete all reports for this post
            $deleteReportsQuery = "DELETE FROM reports WHERE ";
            if ($postOwner === 'startup') {
                $deleteReportsQuery .= "post_id = ?";
                $stmt = $conn->prepare($deleteReportsQuery);
                $stmt->bind_param("i", $postId);
            } else {
                $deleteReportsQuery .= "post_institution_id = ?";
                $stmt = $conn->prepare($deleteReportsQuery);
                $stmt->bind_param("i", $postInstitutionId);
            }
            if (!$stmt->execute()) {
                throw new Exception("Failed to delete reports");
            }
            
            // Then delete the post
            if ($postOwner === 'startup') {
                $deleteQuery = "DELETE FROM posts WHERE post_id = ?";
                $stmt = $conn->prepare($deleteQuery);
                $stmt->bind_param("i", $postId);
            } else {
                $deleteQuery = "DELETE FROM posts_institution WHERE post_id = ?";
                $stmt = $conn->prepare($deleteQuery);
                $stmt->bind_param("i", $postInstitutionId);
            }
            if (!$stmt->execute()) {
                throw new Exception("Failed to delete post");
            }
            
            $conn->commit();
            $_SESSION['success_message'] = "Post and all associated reports have been deleted successfully.";
            header("Location: admin_reported_posts.php");
            exit();
        } catch (Exception $e) {
            $conn->rollback();
            $_SESSION['error_message'] = "Error: " . $e->getMessage();
            header("Location: report_details.php?post_id=$postId&post_institution_id=$postInstitutionId&post_owner=$postOwner");
            exit();
        }
    } elseif ($action === 'ignore') {
        try {
            $conn->begin_transaction();
            
            // Mark post as not reported
            if ($postOwner === 'startup') {
                $updateQuery = "UPDATE posts SET reported = 0 WHERE post_id = ?";
                $stmt = $conn->prepare($updateQuery);
                $stmt->bind_param("i", $postId);
            } else {
                $updateQuery = "UPDATE posts_institution SET reported = 0 WHERE post_id = ?";
                $stmt = $conn->prepare($updateQuery);
                $stmt->bind_param("i", $postInstitutionId);
            }
            if (!$stmt->execute()) {
                throw new Exception("Failed to update post status");
            }
            
            // Delete all reports for this post
            $deleteReportsQuery = "DELETE FROM reports WHERE ";
            if ($postOwner === 'startup') {
                $deleteReportsQuery .= "post_id = ?";
                $stmt = $conn->prepare($deleteReportsQuery);
                $stmt->bind_param("i", $postId);
            } else {
                $deleteReportsQuery .= "post_institution_id = ?";
                $stmt = $conn->prepare($deleteReportsQuery);
                $stmt->bind_param("i", $postInstitutionId);
            }
            if (!$stmt->execute()) {
                throw new Exception("Failed to delete reports");
            }
            
            $conn->commit();
            $_SESSION['success_message'] = "Reports have been ignored and post status updated successfully.";
            header("Location: admin_reported_posts.php");
            exit();
        } catch (Exception $e) {
            $conn->rollback();
            $_SESSION['error_message'] = "Error: " . $e->getMessage();
            header("Location: report_details.php?post_id=$postId&post_institution_id=$postInstitutionId&post_owner=$postOwner");
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Report Details</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
  <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Devanagari:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    body {
      background-color: #F2F6FF;
      font-family: 'IBM Plex Sans Devanagari', sans-serif;
    }
    .main-content {
      margin-left: 0px !important; /* Adjust if a sidebar is present in admin_navbar.php */
      padding: 20px;
    }
    .container-fluid {
      max-width: 1200px;
      margin: 0 auto;
      padding: 0; /* Remove padding as container handles it */
    }
    .page-header {
      margin-bottom: 20px;
    }
    .page-title {
      color: #0C1BA3;
      font-weight: 700;
      font-size: 28px;
      margin-bottom: 0;
    }
    .card {
      background: white;
      border: none;
      border-radius: 10px;
      box-shadow: 0px 2px 4px 0px rgba(0, 0, 0, 0.3);
      margin-bottom: 30px;
      padding: 25px; /* Adjust padding to match the card style */
    }
    .post-card {
      background: white; /* Already white, but ensure consistency */
      border-radius: 10px; /* Ensure border-radius consistency */
      box-shadow: 0px 2px 4px 0px rgba(0, 0, 0, 0.3); /* Match shadow */
      padding: 20px;
      margin-bottom: 25px;
    }
    .post-title {
      color: #0C1BA3;
      font-weight: 700; /* Match font weight */
      font-size: 20px;
      margin-bottom: 15px;
    }
    .post-content {
      color: grey; /* Match text color */
      font-size: 14px; /* Adjust font size */
      line-height: 1.6;
      margin-bottom: 15px;
    }
    .post-image {
      max-height: 350px;
      border-radius: 8px;
      margin-top: 15px;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    }
    .action-buttons {
      display: flex;
      justify-content: flex-end;
      gap: 15px;
      margin-bottom: 25px;
    }
    .btn-action {
      font-weight: 700; /* Match font weight */
      font-size: 10px; /* Match font size */
      padding: 8px 15px; /* Match padding */
      border-radius: 4px; /* Match border-radius */
      border: none;
      box-shadow: 0px 2px 2px 0px rgba(0, 0, 0, 0.3); /* Match shadow */
      transition: all 0.3s ease;
    }
    .btn-action:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    .btn-ignore {
      background-color: #666666; /* Using a darker grey for ignore to stand out */
      color: white;
    }
    .btn-delete {
      background-color: #E74C3C;
      color: white;
    }
    .table-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
    }
    .table-title {
      color: #0C1BA3;
      font-weight: 600;
      font-size: 18px;
    }
    /* DataTables specific styling for coherence */
    #reportsTable {
      width: 100%;
      border-collapse: separate;
      border-spacing: 0 8px; /* Consistent spacing */
    }
    #reportsTable thead tr {
      background-color: white;
      box-shadow: 0px 2px 4px 0px rgba(0, 0, 0, 0.3);
      border-radius: 10px;
      margin-bottom: 10px;
      display: table-row;
    }
    #reportsTable thead th {
      font-family: 'IBM Plex Sans Devanagari', sans-serif;
      font-weight: 700;
      font-size: 16px;
      color: grey;
      padding: 12px 10px;
      text-align: left;
      background-color: transparent;
      border-bottom: none;
    }
    #reportsTable tbody tr {
      background-color: white;
      box-shadow: 0px 2px 4px 0px rgba(0, 0, 0, 0.3);
      border-radius: 10px;
      margin-bottom: 8px;
      display: table-row;
    }
    #reportsTable tbody td {
      font-family: 'IBM Plex Sans Devanagari', sans-serif;
      font-weight: 500;
      font-size: 12px;
      color: grey;
      padding: 12px 10px;
      border: none;
      white-space: nowrap;
    }
    /* Remove extra space in table cells */
    #reportsTable tbody td:last-child {
      width: 1%;
      white-space: nowrap;
    }
    .alert {
      border-radius: 8px;
      padding: 15px 20px;
    }

    /* Custom DataTables controls to match the second design */
    .dataTables_wrapper .dataTables_length,
    .dataTables_wrapper .dataTables_filter,
    .dataTables_wrapper .dataTables_info,
    .dataTables_wrapper .dataTables_paginate {
        display: none; /* Hide all DataTables default controls */
    }

    .data-table-controls {
        display: none; /* Hide the custom controls as well */
    }

  </style>
</head>
<body>
  <?php include('admin_navbar.php'); ?>

  <div class="main-content">
    <div class="container-fluid py-5"> <div class="page-header">
        <h1 class="page-title">Report Details</h1>
      </div>

      <?php if (isset($_SESSION['error_message'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['error_message'] ?></div>
        <?php unset($_SESSION['error_message']); ?>
      <?php endif; ?>

      <?php if (isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success"><?= $_SESSION['success_message'] ?></div>
        <?php unset($_SESSION['success_message']); ?>
      <?php endif; ?>

      <div class="card">
        <?php if ($postContent): ?>
          <div class="post-card">
            <h3 class="post-title"><?= htmlspecialchars($postContent['title']) ?></h3>
            <p class="post-content"><?= htmlspecialchars($postContent['content']) ?></p>
            <?php if ($postContent['image']): ?>
              <img src="<?= htmlspecialchars($postContent['image']) ?>" alt="Post image" class="post-image img-fluid">
            <?php endif; ?>
          </div>
        <?php else: ?>
          <div class="alert alert-warning">Post content not found. It may have been deleted.</div>
        <?php endif; ?>

        <div class="action-buttons">
          <form method="post">
            <input type="hidden" name="action" value="ignore">
            <button type="submit" class="btn-action btn-ignore">Ignore Reports</button>
          </form>
          <form method="post">
            <input type="hidden" name="action" value="delete">
            <button type="submit" class="btn-action btn-delete">Delete Post</button>
          </form>
        </div>

        <div class="table-header">
          <h4 class="table-title">Reports List</h4>
        </div>
        
        <table id="reportsTable" class="table">
          <thead>
            <tr>
              <th>Reported By</th>
              <th>Reason</th>
              <th>Reported At</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($reports as $report): ?>
              <tr>
                <td><?= htmlspecialchars($report['reporter_name']) ?></td>
                <td><?= htmlspecialchars($report['reason']) ?></td>
                <td><?= htmlspecialchars($report['reported_at']) ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
  <script>
    $(document).ready(function() {
      // Initialize DataTable without search, pagination, or info
      $('#reportsTable').DataTable({
        responsive: true,
        order: [[2, 'desc']],
        searching: false,    // Disable search
        paging: false,       // Disable pagination
        info: false,         // Disable info display
        lengthChange: false, // Disable length change
      });
    });
  </script>
</body>
</html>