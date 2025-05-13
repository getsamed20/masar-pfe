<?php

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


function sendAccountStatusEmail($toEmail, $name, $role, $validated) {
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'samira.mouhammed@gmail.com';
        $mail->Password   = 'ixpk ekst zoqg ompq';
        $mail->SMTPSecure = 'ssl';
        $mail->Port       = 465;

        $mail->setFrom('samira.mouhammed@gmail.com', 'Masar Team');
        $mail->addAddress($toEmail);

        $mail->isHTML(true);
        $mail->Subject = 'Masar Account Request Status';

        if ($validated === 1) {  
            $mail->Body = "
                <h3 style='color:green;'>Your account has been verified ✅</h3>
<p>Hey <strong>$name</strong>,</p>
<p>Welcome to <strong>Masar</strong> as <strong>$role</strong>! We're thrilled to have you with us.</p>
<p>Your account is now active. To get the most out of Masar, please <a href='http://localhost/masar/front_office/authentication/insert_info.php'><strong>click here to complete your profile</strong></a> by adding your logo, bio, and contact information.</p>
<p>Let’s shape the future together!</p>

            ";
        } else {  
            $mail->Body = "
                <h3 style='color:red;'>Your account has been rejected</h3>
                <p>Hi <strong>$name</strong>,</p>
                <p>We’ve reviewed your <strong>$role</strong> registration request, and unfortunately, it was not approved.</p>
                <p>If you believe this is a mistake or need more info, feel free to reach out.</p>
                <p>We encourage you to apply again in the future!</p>
            ";
        }

        $mail->send();
    } catch (Exception $e) {
        error_log("Mailer Error: " . $mail->ErrorInfo);
    }
}

?>