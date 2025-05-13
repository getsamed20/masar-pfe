<?php

include 'db.php';

$admin_email = '';

$user_id = $_SESSION['user_id'];
$sql = "SELECT email FROM users WHERE user_id = $user_id";
$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $admin_email = $row['email'];
}
?>

<style>
  .nav-item {display: block;}
</style>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="dashboard.php">Admin Panel</a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto">
        <li class="nav-item">
          <a class="nav-link" href="manage_accounts.php">Manage Accounts</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="upload_documents.php">Upload Documents</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="admin_publish_success.php">Publish Stories</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="manage_pending_accounts.php">Manage Pending Accounts</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="admin_reported_posts.php">Reported Content</a>
        </li>
      </ul>
      <span class="navbar-text text-white me-3">
        <?php echo htmlspecialchars($admin_email); ?>
      </span>
      <a href="logout.php" class="btn btn-outline-light btn-sm">Logout</a>
    </div>
  </div>
</nav>
