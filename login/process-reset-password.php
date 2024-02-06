<?php

$token = $_POST["token"];


$token_hash = hash("sha256", $token);

$mysqli = require __DIR__ . "/config.php";

$sql = "SELECT * FROM signup
        WHERE reset_token = ?";

$stmt = $mysqli->prepare($sql);

$stmt->bind_param("s", $token_hash);

$stmt->execute();

$result = $stmt->get_result();

$user = $result->fetch_assoc();

if ($user === null) {
    die("Token not found");
}

if (strtotime($user["token_expiration"]) <= time()) {
    die("Token has expired");
}

if ($_POST["fpassword"] !== $_POST["spassword"]) {
    die("Passwords must match");
}

// Hash the password before storing it in the database
$hashedpassword = password_hash($_POST["fpassword"], PASSWORD_DEFAULT);

// Retrieve the old hashed password from the database
$oldPasswordHash = $user["password"];

// Check if the new password is different from the old password
if (password_verify($_POST["fpassword"], $oldPasswordHash)) {
    echo "<script>alert('Please choose a new password that is different from your old password.');
    window.location.href='setNewpass.php?token=$token';</script>";
    exit();
}

$sql = "UPDATE signup 
           SET password = ?,
            reset_token = NULL,
            token_expiration = NULL
        WHERE id = ?";

$stmt = $mysqli->prepare($sql);

$stmt->bind_param("ss", $hashedpassword, $user["id"]);

$stmt->execute();

echo "<script>alert('Password Change Successfully. You can now login.');
 window.location.href='login.html';</script>";
exit();

// Close the database connection

