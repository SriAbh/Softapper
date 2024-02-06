<?php
//verify.php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';



function sendVerificationEmail($userEmail, $verificationCode)
{
    
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Your SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'enterprises.official15ss@gmail.com'; // Your SMTP username
        $mail->Password = 'rcse ngtq wwxt jqia'; // Your SMTP password
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('enterprises.official15ss@gmail.com', 'Softapper');
        $mail->addAddress($userEmail);

        $mail->isHTML(true);
        $mail->Subject = 'Verification Code';
        $mail->Body = 'Your verification code is: ' . $verificationCode;

        $mail->send();
        // You can log success or handle other logic here

    } catch (Exception $e) {
        // Log error or handle it as needed
        error_log("Error sending verification code: " . $mail->ErrorInfo);
        echo "Error sending verification code. Please try again later.";
    }
}
?>
