<?php 
session_start();
include 'db.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    $email = mysqli_real_escape_string($conn, $email); 
    $sql1 = "SELECT user_id, password, role FROM users WHERE email = '$email'";
    $result1 = mysqli_query($conn, $sql1); 

    if ($result1 && mysqli_num_rows($result1) > 0) {
        $row = mysqli_fetch_assoc($result1);

        if ($password === $row['password']) { 
            $_SESSION["user_id"] = $row['user_id'];
            $_SESSION["role"] = $row['role'];

            if ($row['role'] == "admin") {
                $user_id = $row['user_id'];
                $sql2 = "SELECT admin_id FROM admins WHERE user_id = '$user_id'";
                $result2 = mysqli_query($conn, $sql2);

                if ($result2 && mysqli_num_rows($result2) > 0) {
                    $admin_row = mysqli_fetch_assoc($result2);
                    $_SESSION["admin_id"] = $admin_row['admin_id'];
                }

                header("Location: manage_accounts.php");
                exit();
            } 

        } else {
            $error = "Incorrect password.";
        }
    } else {
        $error = "No account found with that email.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <h2 class="text-center mb-4">Login</h2>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form action="" method="post">
                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                
                <div class="mb-3">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Login</button>
                </div>
            </form>

        </div>
    </div>
</div>
</body>
</html>
