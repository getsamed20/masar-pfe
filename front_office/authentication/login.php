<?php 
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include('../includes/db.php');

    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // ✅ Verify hashed password
        if (password_verify($password, $user['password'])) {

            // ✅ Check if the user is suspended (inactive)
            if ($user['status'] != 'active') {
                $error = "Your account is suspended. Please contact support.";
            } else {
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['user_id'] = $user['user_id']; 

                if ($user['role'] == 'startup') {
                    $startup_sql = "SELECT * FROM startups WHERE user_id = '".$user['user_id']."'";
                    $startup_result = $conn->query($startup_sql);
                    if ($startup_result->num_rows > 0) {
                        $startup = $startup_result->fetch_assoc();
                        $_SESSION['startup_id'] = $startup['startup_id'];
                    }
                } elseif ($user['role'] == 'institution') {
                    $institution_sql = "SELECT * FROM public_institutions WHERE user_id = '".$user['user_id']."'";
                    $institution_result = $conn->query($institution_sql);
                    if ($institution_result->num_rows > 0) {
                        $institution = $institution_result->fetch_assoc();
                        $_SESSION['institution_id'] = $institution['institution_id'];
                    }
                }

                header("Location: ../pages/home.php");
                exit();
            }
        } else {
            $error = "Invalid email or password.";
        }
    } else {
        $error = "Invalid email or password.";
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Startup Sign-In</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .error {
            color: red;
            font-size: 14px;
        }

        .hidden {
            display: none;
        }
    </style>
</head>
<body>

    <h1> Yoss test </h1>

    <div class="container">
        <h2 class="mt-5">Log In</h2>
        <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
        <form id="loginForm" action="" method="POST">
            <div class="form-floating mb-3">
                <input type="email" class="form-control" name="email" id="email" placeholder="Email" required>
                <label for="email">Email</label>
            </div>

            <div class="form-floating mb-3">
                <input type="password" class="form-control" name="password" id="password" placeholder="Password" required>
                <label for="password">Password</label>
            </div>

            <button type="submit" class="btn btn-primary w-100">Sign In</button>
        </form>

        <p class="mt-3 text-center">Don't have an account? <a href="register.php">Sign Up</a></p>
        <p class="text-center"><a href="forgot_password.php">Forgot Password?</a></p>

    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validation/1.19.3/jquery.validate.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#loginForm').validate({
                rules: {
                    email: {
                        required: true,
                        email: true
                    },
                    password: {
                        required: true,
                        minlength: 6
                    }
                },
                messages: {
                    email: {
                        required: "Please enter your email address.",
                        email: "Please enter a valid email address."
                    },
                    password: {
                        required: "Please provide a password.",
                        minlength: "Password must be at least 6 characters long."
                    }
                },
                errorClass: 'error',
                submitHandler: function (form) {
                    form.submit();
                }
            });
        });
    </script>

</body>
</html>
