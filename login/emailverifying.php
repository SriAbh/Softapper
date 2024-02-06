<?php
include('config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $oneTimePass = filter_var($_POST['oneTimePass'], FILTER_SANITIZE_STRING);

    // Implement your verification logic here (e.g., check against the database)
    if (isValidVerificationCode($oneTimePass)) {
        // Valid verification code
        echo "Email verified successfully! Redirecting to login page...";

        // Update the is_verified column in the database
        updateVerificationStatus($oneTimePass);

        // Redirect the user to the signup page after a short delay
        header("refresh:3;url=login.html"); // Adjust the delay and URL as needed
        exit();
    } else {
        // Invalid verification code
        echo "Invalid verification code!";
    }
}

function isValidVerificationCode($oneTimePass) {
    // Implement your verification code validation logic here
    // (e.g., query the database and check if the code matches)
    // Return true if valid, false otherwise.
    // This is just a placeholder, adapt it based on your database structure.

    // Example: Check in a hypothetical database table named 'signup'
    global $mysqli;
    $checkVerificationQuery = "SELECT * FROM signup WHERE verification_code = ?";
    $checkVerificationStmt = mysqli_prepare($mysqli, $checkVerificationQuery);
    mysqli_stmt_bind_param($checkVerificationStmt, "s", $oneTimePass);
    mysqli_stmt_execute($checkVerificationStmt);
    $verificationResult = mysqli_stmt_get_result($checkVerificationStmt);

    if ($verificationResult && mysqli_fetch_assoc($verificationResult)) {
        return true; // Valid verification code
    } else {
        return false; // Invalid verification code
    }
}

function updateVerificationStatus($oneTimePass) {
    // Update the is_verified column to 1 in the database
    global $mysqli;
    $updateQuery = "UPDATE signup SET is_verified = 1 WHERE verification_code = ?";
    $updateStmt = mysqli_prepare($mysqli, $updateQuery);
    mysqli_stmt_bind_param($updateStmt, "s", $oneTimePass);

    if (mysqli_stmt_execute($updateStmt)) {
    
    } else {
        echo "Error updating verification status: " . mysqli_stmt_error($updateStmt);
    }

    mysqli_stmt_close($updateStmt);
}

// Close database connection if applicable
mysqli_close($mysqli);
?>
