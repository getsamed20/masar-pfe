<?php

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

if(isset($_POST['submit'])){
$email=$_POST['email']; 
$code=mt_rand(9999,99999);


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

            $mail->Body = "
                <h3 style='color:green;'>Your account has been verified</h3>
                <p>Hey <strong>$name</strong>,</p>
                <p>you have registered to masar we need you to verify your mail  </p>
            ";

        $mail->send();
    } catch (Exception $e) {
        error_log("Mailer Error: " . $mail->ErrorInfo);
    }
}
?>