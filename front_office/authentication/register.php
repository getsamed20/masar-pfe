<?php 
session_start();
include('../includes/db.php');

$error = "";
$registrationSuccess = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $role = $_POST['role'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];
    $unique_identifier = $_POST['unique_identifier'];

    if ($password !== $confirmPassword) {
        $error = "Passwords do not match.";
    } else {
        $commercial_register = $_FILES['commercial_register']['name'];
        $target_dir = "../uploads/";
        $target_file = $target_dir . basename($commercial_register);
        $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $check_email = mysqli_query($conn, "SELECT * FROM pending_accounts WHERE email = '$email'");
        $check_users = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");

        if (mysqli_num_rows($check_email) === 0 && mysqli_num_rows($check_users) === 0 && $_FILES['commercial_register']['error'] == 0 && $file_type == "pdf") {
            if (move_uploaded_file($_FILES["commercial_register"]["tmp_name"], $target_file)) {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                $insert_pending = mysqli_query($conn, 
                    "INSERT INTO pending_accounts (name, email, password, unique_identifier, commercial_register, role) 
                    VALUES ('$name', '$email', '$hashed_password', '$unique_identifier', '$commercial_register', '$role')");

                if ($insert_pending) {
                    $registrationSuccess = true; 
                } else {
                    $error = "Something went wrong while saving to pending accounts.";
                }
            } else {
                $error = "Failed to upload the PDF.";
            }
        } else {
            $error = "Email already exists or invalid file.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register Account</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .hidden { display: none; }
        .error { color: red; font-size: 14px; }
    </style>
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card p-4 shadow">
        <h2 class="text-center mb-4">Register</h2>
        <?php if (!empty($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
        <form id="signupForm" method="POST" enctype="multipart/form-data">
            <div id="step1">
                <div class="mb-3">
                    <label class="form-label">Select Role</label>
                    <select name="role" class="form-select" required>
                        <option value="">-- Choose Role --</option>
                        <option value="startup">Startup</option>
                        <option value="institution">Public Institution</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" id="password" class="form-control" minlength="6" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Confirm Password</label>
                    <input type="password" name="confirmPassword" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Unique Identifier</label>
                    <input type="text" name="unique_identifier" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Commercial Register (PDF only)</label>
                    <input type="file" name="commercial_register" accept=".pdf" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Register</button>
                <p class="mt-3 text-center">Already have an account? <a href="login.php">Log In</a></p>
            </div>

            <div id="step2" class="hidden text-center">
                <h3 class="mb-3">Registration Successful!</h3>
                <p>Thank you for signing up! Your account is under review.</p>
                <p>You will receive a confirmation email within 1 to 24 hours.</p>
                <p>Once verified, you’ll be able to access all the platform features.</p>
            </div>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js"></script>
<script>
$(document).ready(function () {
    $("#signupForm").validate({
        rules: {
            confirmPassword: {
                equalTo: "#password"
            }
        },
        messages: {
            confirmPassword: "Passwords don't match."
        },
        errorClass: "error"
    });

    <?php if ($registrationSuccess): ?>
        $("#step1").addClass("hidden");
        $("#step2").removeClass("hidden");

        setTimeout(function () {
            window.location.href = '../authentication/login.php'; 
        }, 50000);
    <?php endif; ?>
});
</script>

</body>
</html>
