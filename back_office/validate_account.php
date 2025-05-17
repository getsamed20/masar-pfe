<?php
session_start();
include 'db.php';
include 'send_mail.php';  

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: admin_login.php");
    exit();
}

if (!isset($_GET['id'])) {
    die("Invalid request.");
}

$pending_id = intval($_GET['id']);

// Fetch pending account
$query = "SELECT * FROM pending_accounts WHERE id = $pending_id";
$result = mysqli_query($conn, $query);
if (!$result || mysqli_num_rows($result) === 0) {
    die("Pending account not found.");
}

$pending = mysqli_fetch_assoc($result);

// Extract data from pending account
$email = mysqli_real_escape_string($conn, $pending['email']);
$hashed_password = $pending['password'];
$role = mysqli_real_escape_string($conn, $pending['role']);
$status = 'active';
$created_at = date('Y-m-d H:i:s');

$name = mysqli_real_escape_string($conn, $pending['name']);
$identifier = mysqli_real_escape_string($conn, $pending['unique_identifier']);
$register_path = mysqli_real_escape_string($conn, $pending['commercial_register']);
$logo = mysqli_real_escape_string($conn, $pending['logo']);

// Insert into users table
$insert_user = "INSERT INTO users (email, password, role, status, created_at) 
                VALUES ('$email', '$hashed_password', '$role', '$status', '$created_at')";
mysqli_query($conn, $insert_user);
$new_user_id = mysqli_insert_id($conn);

// Insert into specific table based on role
if ($role === 'startup') {
    $insert_startup = "INSERT INTO startups (user_id, startup_name, unique_identifier, commercial_register, logo) 
                       VALUES ($new_user_id, '$name', '$identifier', '$register_path', '$logo')";
    mysqli_query($conn, $insert_startup);

} elseif ($role === 'institution') {
    $insert_institution = "INSERT INTO public_institutions (user_id, institution_name, unique_identifier, commercial_register, logo) 
                           VALUES ($new_user_id, '$name', '$identifier', '$register_path', '$logo')";
    mysqli_query($conn, $insert_institution);
}

// Send confirmation email
sendAccountStatusEmail($email, $name, $role, 1);

// Remove from pending accounts
$delete_query = "DELETE FROM pending_accounts WHERE id = $pending_id";
mysqli_query($conn, $delete_query);

header("Location: manage_pending_accounts.php?success=1");
exit();
?>
