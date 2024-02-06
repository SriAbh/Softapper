<?php
include('config.php');


session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['signup'])) {
        // Validate and sanitize form data
        $name = filter_var($_POST['Name'], FILTER_SANITIZE_STRING);
        $email = filter_var($_POST['Email'], FILTER_SANITIZE_EMAIL);
        $phone = filter_var($_POST['phone'], FILTER_SANITIZE_STRING);
        $birthday = filter_var($_POST['birthday'], FILTER_SANITIZE_STRING);
        $password = filter_var($_POST['fpassword'], FILTER_SANITIZE_STRING);
        $confirmPassword = filter_var($_POST['spassword'], FILTER_SANITIZE_STRING);
        $verificationCode = generateVerificationCode();

        // Check if passwords match
        if ($password !== $confirmPassword) {
            echo "Passwords do not match!";
        } else {
            // Check if the email already exists in the database
            $checkEmailQuery = "SELECT * FROM signup WHERE email = ?";
            $checkEmailStmt = mysqli_prepare($mysqli, $checkEmailQuery);
            mysqli_stmt_bind_param($checkEmailStmt, "s", $email);
            mysqli_stmt_execute($checkEmailStmt);
            $checkEmailResult = mysqli_stmt_get_result($checkEmailStmt);

            if (mysqli_num_rows($checkEmailResult) > 0) {
                // Email is already in use
                echo "<script>alert('Email is already exist!');</script>";
                echo "<script>window.location.replace('login.html');</script>";
                exit();
            } else {
                // Hash the password before storing it in the database
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                // Insert user into the database with unverified status
                $insertQuery = "INSERT INTO signup (name, email, phone, birthday, password, verification_code, is_verified) VALUES (?, ?, ?, ?, ?, ?, 0)";
                $insertStmt = mysqli_prepare($mysqli, $insertQuery);
                mysqli_stmt_bind_param($insertStmt, "ssssss", $name, $email, $phone, $birthday, $hashedPassword, $verificationCode);

                if (mysqli_stmt_execute($insertStmt)) {
                    // Send verification email
                    include('verify.php');
                    sendVerificationEmail($email, $verificationCode);

                    // Signup successful
                    header("Location: verification.html");
                    exit();
                } else {
                    // Signup failed
                    echo "Error: " . mysqli_stmt_error($insertStmt);
                }

                mysqli_stmt_close($insertStmt);
            }

            mysqli_stmt_close($checkEmailStmt);
        }
    } elseif (isset($_POST['login'])) {
        // Handle login logic
        $email = filter_var($_POST['Email'], FILTER_SANITIZE_EMAIL);
        $password = $_POST['Password'];
    
        // Validate and sanitize the input
        if (empty($email) || empty($password)) {
            echo "Email and password are required!";
            exit();
        }
    
        // Check if the user exists in the database
        $checkUserQuery = "SELECT * FROM signup WHERE email = ?";
        $checkUserStmt = mysqli_prepare($mysqli, $checkUserQuery);
        mysqli_stmt_bind_param($checkUserStmt, "s", $email);
        mysqli_stmt_execute($checkUserStmt);
        $userResult = mysqli_stmt_get_result($checkUserStmt);

        if ($userResult && $user = mysqli_fetch_assoc($userResult)) {
            // Check if the email is verified
            if ($user['is_verified'] == 1) {
                // Verify the password using password_verify
                if (password_verify($password, $user['password'])) {
                    // Password is correct, proceed with login
                    $_SESSION["user_id"] = $user["id"];
                    header("Location: dashboard.php");
                    exit;
                } else {
                    // Password is incorrect
                   echo '<script>alert("Incorrect password!"); window.location.href = "login.html";</script>';
            exit;
                }
            } else {
                // Email is not verified
                echo "Email not verified. Click <a href='resend_verification.php?email={$email}'>here</a> to resend the verification code.";
                // Optionally, you can provide a link to resend the verification email
            }
        } else {
            // User does not exist
             echo '<script>alert("User not found!"); window.location.href = "login.html";</script>';
    exit;
        }
    
        mysqli_stmt_close($checkUserStmt);
    }
}

mysqli_close($mysqli);

function generateVerificationCode() {
    // Generate a random 6-digit code
    return sprintf('%06d', mt_rand(0, 999999));
}
?>
