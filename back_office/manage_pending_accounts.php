<?php 
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
  header("Location: admin_login.php");
  exit();
}

$admin_email = '';
$user_id = $_SESSION['user_id'];
$sql = "SELECT email FROM users WHERE user_id = $user_id";
$result = mysqli_query($conn, $sql);
if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $admin_email = $row['email'];
}

$sql_pending = "SELECT * FROM pending_accounts WHERE validated = 0";
$result_pending = mysqli_query($conn, $sql_pending);

$pending_accounts = [];
while ($row = mysqli_fetch_assoc($result_pending)) {
    $pending_accounts[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Pending Accounts - Admin</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
</head>
<body>
<?php include('admin_navbar.php'); ?>
<div class="container py-5">
  <h2 class="mb-4">Pending Account Validations</h2>

  <div class="alert alert-info">
    Logged in as: <strong><?php echo htmlspecialchars($admin_email); ?></strong>
  </div>

  <div class="mb-3">
    <label for="roleFilter" class="form-label">Filter by Role:</label>
    <select id="roleFilter" class="form-select" style="max-width: 300px;">
      <option value="">All</option>
      <option value="startup">Startup</option>
      <option value="institution">Institution</option>
    </select>
  </div>

  <table id="pendingTable" class="table table-bordered table-striped">
    <thead>
      <tr>
        <th>ID</th>
        <th>Role</th>
        <th>Name</th>
        <th>Email</th>
        <th>Unique Identifier</th>
        <th>Commercial Register</th>
        <th>Submitted At</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($pending_accounts as $account) { ?>
        <tr data-role="<?php echo $account['role']; ?>">
          <td><?php echo $account['id']; ?></td>
          <td><?php echo $account['role']; ?></td>
          <td><?php echo $account['name']; ?></td>
          <td><?php echo $account['email']; ?></td>
          <td><?php echo $account['unique_identifier']; ?></td>
          <td><a href="<?php echo $account['commercial_register']; ?>" target="_blank">View File</a></td>
          <td><?php echo $account['created_at']; ?></td>
          <td>
            <a href="validate_account.php?id=<?php echo $account['id']; ?>" class="btn btn-success btn-sm" onclick="return confirm('Are you sure you want to validate account?');">Validate</a>
            <a href="reject_account.php?id=<?php echo $account['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to reject account?');">Reject</a>
          </td>
        </tr>
      <?php } ?>
    </tbody>
  </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
  $(document).ready(function () {
    var table = $('#pendingTable').DataTable();

    $('#roleFilter').on('change', function () {
      var selectedRole = $(this).val();
      table.rows().every(function () {
        var role = $(this.node()).data('role');
        if (!selectedRole || role === selectedRole) {
          $(this.node()).show();
        } else {
          $(this.node()).hide();
        }
      });
    });
  });
</script>
</body>
</html>
