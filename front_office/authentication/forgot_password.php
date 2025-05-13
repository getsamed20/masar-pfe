<?php
session_start();
include('../includes/db.php');
require '../../back_office/PHPMailer/src/Exception.php';
require '../../back_office/PHPMailer/src/PHPMailer.php';
require '../../back_office/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $expires = time() + 3600;
        $encoded_email = urlencode(base64_encode($email));
        $encoded_expires = base64_encode($expires);

        $reset_link = "http://localhost/masar_pfe/front_office/authentication/reset_password.php?e=$encoded_email&t=$encoded_expires";

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'samira.mouhammed@gmail.com';
            $mail->Password   = 'npgz ogdi klnw vcje'; 
            $mail->SMTPSecure = 'ssl';
            $mail->Port       = 465;

            $mail->setFrom('samira.mouhammed@gmail.com', 'Masar Team');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Password Reset Request';
            $mail->Body    = "<p>To reset your password, click the link below:</p><a href='$reset_link'>$reset_link</a><p>This link will expire in 1 hour.</p>";

            $mail->send();
            $success = "A password reset link has been sent to your email.";
        } catch (Exception $e) {
            $error = "Mailer Error: " . $mail->ErrorInfo;
        }
    } else {
        $error = "No account found with that email address.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container">
    <h2 class="mt-5">Forgot Password</h2>
    <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
    <?php if (isset($success)) echo "<div class='alert alert-success'>$success</div>"; ?>
    <form action="" method="POST">
        <div class="form-floating mb-3">
            <input type="email" class="form-control" name="email" id="email" placeholder="Email" required>
            <label for="email">Enter your email address</label>
        </div>
        <button type="submit" class="btn btn-primary w-100">Send Reset Link</button>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
