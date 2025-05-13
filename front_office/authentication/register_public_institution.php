<?php  
session_start();
include('../includes/db.php');
//echo '<pre>'; print_r($_SESSION); echo '</pre>';

$error = "";
$registrationSuccess = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $institution_name = trim($_POST['institution_name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];
    $unique_identifier = trim($_POST['unique_identifier']);

    if ($password !== $confirmPassword) {
        $error = "Passwords do not match.";
    } else {
        $file = $_FILES['commercial_register'];
        $allowed_ext = 'pdf';
        $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        $new_filename = uniqid('register_', true) . '.' . $file_ext;
        $target_dir = "../uploads/";
        $target_file = $target_dir . $new_filename;

=        $stmt = $conn->prepare("SELECT * FROM pending_accounts WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $check_result = $stmt->get_result();

        if ($check_result->num_rows === 0 && $file['error'] === 0 && $file_ext === $allowed_ext) {
            if (move_uploaded_file($file["tmp_name"], $target_file)) {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                $insert_stmt = $conn->prepare("INSERT INTO pending_accounts 
                    (role, name, email, password, unique_identifier, commercial_register) 
                    VALUES (?, ?, ?, ?, ?, ?)");
                $role = "institution";
                $insert_stmt->bind_param("ssssss", $role, $institution_name, $email, $hashed_password, $unique_identifier, $new_filename);

                if ($insert_stmt->execute()) {
                    $_SESSION['email'] = $email;
                    $registrationSuccess = true;
                } else {
                    $error = "Database insertion failed.";
                }
            } else {
                $error = "Failed to upload the file.";
            }
        } else {
            $error = "Please ensure the email is unique and the file is a valid PDF.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Public Institution Sign-Up</title>
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
        <h2 class="text-center mb-4">Public Institution Sign-Up</h2>
        <?php if (!empty($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
        <form id="signupForm" method="POST" enctype="multipart/form-data">
            
            <!-- Step 1 -->
            <div id="step1">
                <div class="mb-3">
                    <label class="form-label">Institution Name</label>
                    <input type="text" name="institution_name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" id="password" class="form-control" required minlength="6">
                </div>
                <div class="mb-3">
                    <label class="form-label">Confirm Password</label>
                    <input type="password" name="confirmPassword" class="form-control" required>
                </div>
                <button type="button" class="btn btn-primary w-100 next">Next</button>
                <p class="mt-3 text-center">Already have an account? <a href="login.php">Log In</a></p>
            </div>

            <!-- Step 2 -->
            <div id="step2" class="hidden">
                <div class="mb-3">
                    <label class="form-label">Unique Identifier</label>
                    <input type="text" name="unique_identifier" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Commercial Register (PDF only)</label>
                    <input type="file" name="commercial_register" accept=".pdf" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Submit</button>
                <button type="button" class="btn btn-secondary prev w-100 mt-2">Back</button>
            </div>

            <!-- Step 3 -->
            <div id="step3" class="hidden">
                <div class="alert alert-info">
                    <h4 class="alert-heading">Account Under Verification</h4>
                    <p>Your account is currently under verification. Once approved, you will be able to access the platform.</p>
                    <p>You will receive an email notification within the next 1 to 24 hours.</p>
                    <hr>
                    <p class="mb-0">Thank you for your patience!</p>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js"></script>

<script>
$(document).ready(function () {
    const step1 = $("#step1");
    const step2 = $("#step2");
    const step3 = $("#step3");

    $(".next").click(function () {
        if ($("#signupForm").valid()) {
            step1.addClass("hidden");
            step2.removeClass("hidden");
        }
    });

    $(".prev").click(function () {
        step2.addClass("hidden");
        step1.removeClass("hidden");
    });

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
    $(function () {
        $("#signupForm").hide();
        $("#step1, #step2").addClass("hidden");
        $("#step3").removeClass("hidden");
        setTimeout(function () {
            window.location.href = '../authentication/login.php'; 
        }, 10000); // 10 seconds
    });
    <?php endif; ?>
});
</script>

</body>
</html>
