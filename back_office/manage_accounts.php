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

$sql_users = "SELECT user_id, email, role, status FROM users WHERE role IN ('startup', 'institution')";
$result_users = mysqli_query($conn, $sql_users);

$users = [];
while ($row = mysqli_fetch_assoc($result_users)) {
    $users[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

  <style>
    #roleFilter {
      max-width: 200px;
    }

    body{
      background-color:#F2F6FF;
    }
  </style>
</head>
<body>
  
<?php include('admin_navbar.php'); ?>
<div class="container py-5">
  <h2 class="mb-4">Admin Dashboard</h2>

  <div class="alert alert-info">
    Logged in as: <strong><?php echo htmlspecialchars($admin_email); ?></strong>
  </div>

  <h3>Manage User Accounts</h3>

  <div class="row mb-4">
  <div class="col-md-3">
    <label for="roleFilter" class="form-label">Filter by Role:</label>
    <select id="roleFilter" class="form-select">
      <option value="">All</option>
      <option value="startup">Startups</option>
      <option value="institution">Institutions</option>
    </select>
  </div>

  <div class="col-md-3">
    <label class="form-label d-block">Filter by Status:</label>
    <div class="form-check form-check-inline">
      <input class="form-check-input status-filter" type="checkbox" id="activeCheckbox" value="active" checked>
      <label class="form-check-label" for="activeCheckbox">Active</label>
    </div>
    <div class="form-check form-check-inline">
      <input class="form-check-input status-filter" type="checkbox" id="inactiveCheckbox" value="inactive" checked>
      <label class="form-check-label" for="inactiveCheckbox">Inactive</label>
    </div>
  </div>
</div>


  <table id="userTable" class="table table-bordered table-striped">
    <thead>
      <tr>
        <th>Email</th>
        <th>Role</th>
        <th>Status</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($users as $user) { ?>
        <tr>
          <td><?php echo htmlspecialchars($user['email']); ?></td>
          <td><?php echo htmlspecialchars($user['role']); ?></td>
          <td><?php echo htmlspecialchars($user['status']); ?></td>
          <td>
            <?php if ($user['status'] === 'active') { ?>
              <a href="change_status.php?user_id=<?php echo $user['user_id']; ?>&status=inactive" class="btn btn-warning" onclick="return confirm('Are you sure you want to deactivate this account?');">Deactivate</a>
            <?php } else { ?>
              <a href="change_status.php?user_id=<?php echo $user['user_id']; ?>&status=active" class="btn btn-success" onclick="return confirm('Are you sure you want to activate this account?');">Activate</a>
            <?php } ?>
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
    const table = $('#userTable').DataTable();

    $('#roleFilter').on('change', function () {
      const role = this.value;
      if (role) {
        table.column(1).search('^' + role + '$', true, false).draw();
      } else {
        table.column(1).search('').draw();
      }
    });

    const statusColumnIndex = 2;
    function updateStatusFilter() {
      let filters = [];

      $('.status-filter:checked').each(function () {
        filters.push($(this).val());
      });

      if (filters.length > 0) {
        const regex = '^(' + filters.join('|') + ')$';
        table.column(statusColumnIndex).search(regex, true, false).draw();
      } else {
        table.column(statusColumnIndex).search('ImpossibleToMatchString').draw(); 
      }
    }

    $('.status-filter').on('change', updateStatusFilter);
    updateStatusFilter();
  });
</script>
</body>
</html>
