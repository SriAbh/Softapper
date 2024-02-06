<?php

$email = $_POST["Email"];

$mysqli = require __DIR__ . "/config.php";

// Check if the email exists in the database
$checkEmailQuery = "SELECT * FROM signup WHERE email = ?";
$checkEmailStmt = $mysqli->prepare($checkEmailQuery);
$checkEmailStmt->bind_param("s", $email);
$checkEmailStmt->execute();
$checkEmailResult = $checkEmailStmt->get_result();

if ($checkEmailResult->num_rows > 0) {

    // Email exists, proceed with password reset

    $token = bin2hex(random_bytes(16));
    $token_hash = hash("sha256", $token);
    $expiry = date("Y-m-d H:i:s", time() + 60 * 30);

    $updateQuery = "UPDATE signup
                    SET reset_token = ?,
                        token_expiration = ?
                    WHERE email = ?";
    $stmt = $mysqli->prepare($updateQuery);
    $stmt->bind_param("sss", $token_hash, $expiry, $email);
    $stmt->execute();

    if ($mysqli->affected_rows) {

        $mail = require __DIR__ . "/mailer.php";

        $mail->setFrom('enterprises.official15ss@gmail.com', 'Softapper');
        $mail->addAddress($email);
        $mail->Subject = "Reset Password Link";
        $mail->Body = <<<END

        Click <a href="http://www.softapper.com/login/setNewpass.php?token=$token">here</a> 
        to reset your password.

        END;

        try {

            $mail->send();

        } catch (Exception $e) {

            echo "Message could not be sent. Mailer error: {$mail->ErrorInfo}";
            header("refresh:3;url=login.html");
            exit();

        }

        // echo 'Reset Link Sent, please check your Email inbox.';
         echo '<script>alert("Reset Link Sent, please check your Email inbox.");</script>';
         echo '<script>window.location.href = "login.html";</script>';
         // header("refresh:3;url=login.html");

       // header("refresh:3;url=login.html");
        exit();

    } else {

        echo "Error updating database.";
        header("refresh:3;url=login.html");
        exit();

    }

} else {

    // Email does not exist, handle accordingly
    //echo "Email not found in the database.";
    //header("refresh:3;url=login.html");
    
         echo '<script>
    alert("User doesn\'t Exist.");
    window.location.href = "login.html";
</script>';

    exit();

}
?>
