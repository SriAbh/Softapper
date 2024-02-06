<?php
include('config.php'); // Include your database configuration file
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Include the PHPMailer autoload file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    // Check if the email exists in the database
    $checkEmailQuery = "SELECT * FROM signup WHERE email = ?";
    $checkEmailStmt = mysqli_prepare($conn, $checkEmailQuery);
    mysqli_stmt_bind_param($checkEmailStmt, "s", $email);
    mysqli_stmt_execute($checkEmailStmt);
    $checkEmailResult = mysqli_stmt_get_result($checkEmailStmt);

    if (mysqli_num_rows($checkEmailResult) > 0) {
        // Email exists, generate a unique token for the reset link
        $resetToken = bin2hex(random_bytes(32));

        // Set expiration time (e.g., 1 hour from now)
        $expirationTime = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // Store the reset token and its expiration date in the database
        $updateTokenQuery = "UPDATE signup SET reset_token = ?, token_expiration = ? WHERE email = ?";
        $updateTokenStmt = mysqli_prepare($conn, $updateTokenQuery);
        mysqli_stmt_bind_param($updateTokenStmt, "sss", $resetToken, $expirationTime, $email);

        if (mysqli_stmt_execute($updateTokenStmt)) {
            // Send an email with the reset link using PHPMailer
            sendResetLinkEmail($email, $resetToken);
            echo "Reset link sent successfully. Please check your email.";
        } else {
            echo "Error updating the database";
        }

        mysqli_stmt_close($updateTokenStmt);
    } else {
        echo "Email not found in the database";
    }

    mysqli_stmt_close($checkEmailStmt);
    mysqli_close($conn);
}

function sendResetLinkEmail($email, $resetToken) {
    $mail = new PHPMailer(true);

    try {
         // Server settings
         $mail->isSMTP();
         $mail->Host = 'smtp.gmail.com'; // Your SMTP server
         $mail->SMTPAuth = true;
         $mail->Username = 'enterprises.official15ss@gmail.com'; // Your SMTP username
         $mail->Password = 'cbnu vmwd ijhg emvn '; // Your SMTP password
         $mail->SMTPSecure = 'tls';
         $mail->Port = 587;
 
         // Recipients
         $mail->setFrom('enterprises.official15ss@gmail.com', 'Softapper');
         $mail->addAddress($email);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Password Reset';
        $mail->Body = 'Click the following link to reset your password:<br> http://www.softapper.com/login/resetPassword.html?email=' . $email . '&token=' . $resetToken;

        $mail->send();
    } catch (Exception $e) {
        error_log("Error sending reset link: " . $e->getMessage());
        echo "Error sending reset link. Please try again later.";
    }
}
?>
