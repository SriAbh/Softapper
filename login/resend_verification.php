<?php
include('config.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['email'])) {
    $email = filter_var($_GET['email'], FILTER_SANITIZE_EMAIL);

    // Check if the user exists in the database
    $checkUserQuery = "SELECT * FROM signup WHERE email = ?";
    $checkUserStmt = mysqli_prepare($conn, $checkUserQuery);
    mysqli_stmt_bind_param($checkUserStmt, "s", $email);
    mysqli_stmt_execute($checkUserStmt);
    $userResult = mysqli_stmt_get_result($checkUserStmt);

    if ($userResult && $user = mysqli_fetch_assoc($userResult)) {
        // Check if the email is not verified
        if ($user['is_verified'] == 0) {
            // Resend the verification email
            include('verify.php');
            sendVerificationEmail($email, $user['verification_code']);
            echo "Verification Code resent successfully. Please check your email.";
             // Redirect to the login page after resending the verification email
             header("Location: verification.html");
             exit();
        } else {
            echo "Email is already verified.";
        }
    } else {
        echo "User not found!";
    }

    mysqli_stmt_close($checkUserStmt);
}

mysqli_close($conn);
?>
