<?php
include('../includes/db.php');

$email = isset($_GET['e']) ? base64_decode(urldecode($_GET['e'])) : '';
$expires = isset($_GET['t']) ? base64_decode($_GET['t']) : 0;

if (!$email || time() > $expires) {
    die("❌ Invalid or expired reset link.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_password = $_POST['password'];
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    $update_sql = "UPDATE users SET password = '$hashed_password' WHERE email = '$email'";
    if ($conn->query($update_sql)) {
        $success = "Password updated successfully. You can now <a href='login.php'>log in</a>.";
    } else {
        $error = "Error updating password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Reset Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <h2 class="mt-5">Reset Your Password</h2>
    <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
    <?php if (isset($success)) echo "<div class='alert alert-success'>$success</div>"; ?>
    <?php if (!isset($success)): ?>
    <form method="POST">
        <div class="form-floating mb-3">
            <input type="password" name="password" class="form-control" placeholder="New Password" required minlength="6">
            <label>New Password</label>
        </div>
        <button type="submit" class="btn btn-primary w-100">Update Password</button>
    </form>
    <?php endif; ?>
</div>
</body>
</html>
