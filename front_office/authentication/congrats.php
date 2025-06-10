<?php
session_start();
include('../includes/db.php');

$error = "";
$registrationSuccess = true; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $role = isset($_POST['role']) ? $_POST['role'] : '';
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];
    $unique_identifier = $_POST['unique_identifier'];

    if (!isset($_POST['termsCheckbox'])) {
        $error = "You must accept the Terms & Conditions.";
    } elseif ($password !== $confirmPassword) {
        $error = "Passwords do not match.";
    } else {
        $commercial_register = $_FILES['commercial_register']['name'];
        $target_dir = "../uploads/";
        $commercial_register_target = $target_dir . basename($commercial_register);
        $commercial_register_type = strtolower(pathinfo($commercial_register_target, PATHINFO_EXTENSION));

        $logo = $_FILES['logo']['name'];
        $logo_tmp = $_FILES['logo']['tmp_name'];
        $logo_ext = strtolower(pathinfo($logo, PATHINFO_EXTENSION));
        $allowed_image_types = ['jpg', 'jpeg', 'png'];
        $logo_file = "";

        // Check if logo file is uploaded and valid
        if (empty($logo) || $_FILES['logo']['error'] != 0 || !in_array($logo_ext, $allowed_image_types)) {
            $error = "Please upload a valid logo (JPG, JPEG, PNG).";
        } else {
            $check_email = mysqli_query($conn, "SELECT * FROM pending_accounts WHERE email = '$email'");
            $check_users = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");

            if (mysqli_num_rows($check_email) === 0 && mysqli_num_rows($check_users) === 0
                && $_FILES['commercial_register']['error'] == 0 && $commercial_register_type == "pdf") {

                if (move_uploaded_file($_FILES["commercial_register"]["tmp_name"], $commercial_register_target)) {
                    // Logo upload
                    $logo_file = uniqid() . "." . $logo_ext;
                    $logo_target = $target_dir . $logo_file;
                    move_uploaded_file($logo_tmp, $logo_target);


                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                    $insert_pending = mysqli_query($conn,
                        "INSERT INTO pending_accounts (name, email, password, unique_identifier, commercial_register, role, logo)
                         VALUES ('$name', '$email', '$hashed_password', '$unique_identifier', '$commercial_register', '$role', '$logo_file')");

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
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registration Complete</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">


    <style>
        body {
            margin: 0;
            padding: 0;
            background: url('../images/auth_bg.png') no-repeat center center fixed;
            background-size: cover;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: flex-end; /* Keeps the container to the right on large screens */
        }

        .register-container {
            width: 45%; /* Slightly smaller width on larger screens */
            background-color: rgba(255, 255, 255, 0.95);
            padding: 20px; /* Slightly reduced padding */
            box-shadow: -4px 0 15px rgba(0,0,0,0.1);
            height: 100vh;
            overflow-y: auto;
            border-radius: 60px 0 0 60px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Adjusted form-wrapper to emulate register.php's content centering */
        .form-wrapper {
            width: 100%;
            max-width: 400px; /* Adjust max-width as needed to match register.php's form width */
            padding: 20px;
        }

        .center {
            text-align: center;
        }

        .error {
            color: red;
            font-size: 13px; /* Slightly smaller error font */
        }
        .hidden { display: none; }

        /* Stepper Styles */
        .stepper-wrapper {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            margin-bottom: 20px;
            position: relative;
        }

        .stepper-item {
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            flex: 1;
        }

        .stepper-item::before {
            content: '';
            position: absolute;
            top: 15px; /* Adjust to align with circle center */
            left: -50%;
            width: 100%;
            height: 2px;
            background-color: #e0e0e0;
            z-index: 0;
        }

        .stepper-item:first-child::before {
            content: none;
        }

        .stepper-counter {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background-color: #e0e0e0;
            color: white;
            font-size: 14px;
            font-weight: bold;
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1;
            margin-bottom: 5px;
        }

        .stepper-item.active .stepper-counter {
            background-color: #0C1BA3; /* Your brand blue */
        }

        .stepper-item.completed .stepper-counter {
            background-color: #28a745; /* Green for completed */
        }

        .stepper-label {
            font-size: 12px;
            color: #6c757d;
            text-align: center;
        }

        .stepper-item.active .stepper-label {
            color: #0C1BA3;
            font-weight: bold;
        }

        /* Media Query for Smaller Screens */
        @media (max-width: 768px) {
            body {
                justify-content: center; /* Center the content on smaller screens */
            }

            .register-container {
                width: 100%; /* Take full width on smaller screens */
                border-radius: 0; /* Remove border-radius for full width on small screens */
                box-shadow: none; /* Remove shadow on small screens for cleaner look */
                padding: 15px;
            }

            .form-wrapper {
                max-width: 100%;
                padding: 0 15px;
            }
        }
    </style>
</head>
<body>

<div class="register-container">
    <div class="form-wrapper"> <div class="center mb-4">
            <img src="../images/masar-logo.png" alt="Masar Logo" class="mb-3">
            <div class="stepper-wrapper">
                <div class="stepper-item completed">
                    <div class="stepper-counter">1</div>
                    <div class="stepper-label">Account Details</div>
                </div>
                <div class="stepper-item completed">
                    <div class="stepper-counter">2</div>
                    <div class="stepper-label">Review</div>
                </div>
                <div class="stepper-item active">
                    <div class="stepper-counter">3</div>
                    <div class="stepper-label">Completion</div>
                </div>
            </div>
        </div>
        <div class="center mb-4">
            <img src="../images/ok.png">
            <h1 style="color: #414141;">Congratulations!</h1>
            <p style="color: #818181;">Your account has been approved. You can now access the platform and start exploring opportunities.</p>
            <p class="mt-3 text-center">
                <a href="login.php" class="btn btn-primary">Go to Login</a>
            </p>
        </div>
    </div>
</div>

</body>
</html>