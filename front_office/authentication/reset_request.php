<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include('../includes/db.php');
    $email = $_POST['email'];
    
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $token = bin2hex(random_bytes(50)); 
        
        $update_sql = "UPDATE users SET reset_token = '$token', token_expiry = DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE email = '$email'";
        $conn->query($update_sql);

        $reset_link = "http://localhost/masar-pfe/reset_password.php?token=" . $token;
        
        require 'PHPMailer/src/Exception.php';
        require 'PHPMailer/src/PHPMailer.php';
        require 'PHPMailer/src/SMTP.php';
        
        use PHPMailer\PHPMailer\PHPMailer;
        use PHPMailer\PHPMailer\Exception;

        $mail = new PHPMailer(true);
        
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'samira.mouhammed@gmail.com'; 
            $mail->Password = 'your_smtp_password';
            $mail->SMTPSecure = 'ssl';
            $mail->Port = 465;

            $mail->setFrom('samira.mouhammed@gmail.com', 'Masar Team');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Password Reset Request';
            $mail->Body = "Click on the following link to reset your password: <a href='$reset_link'>$reset_link</a>";

            $mail->send();
            $message = "A reset link has been sent to your email address.";
        } catch (Exception $e) {
            $error = "Mailer Error: " . $mail->ErrorInfo;
        }
    } else {
        $error = "No user found with that email.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
</head>
<body>
    <form method="POST" action="">
        <label for="email">Enter your email:</label>
        <input type="email" name="email" required>
        <button type="submit">Send Reset Link</button>
    </form>

    <?php if (isset($message)) echo "<div>$message</div>"; ?>
    <?php if (isset($error)) echo "<div>$error</div>"; ?>
</body>
</html>
