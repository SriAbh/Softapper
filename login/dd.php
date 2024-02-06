<?php
// Enable error reporting for debugging purposes
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start the session
session_start();

// Include necessary files and configurations
require_once 'setup.php';
$mysqli = require __DIR__ . "/config.php"; // Adjust the path as needed

// Handle Google OAuth2 authentication
if (isset($_GET['code'])) {
    try {
        $token = $google->fetchAccessTokenWithAuthCode($_GET['code']);
        $_SESSION['token'] = $token;

        if (!isset($token["error"])) {
            $google->setAccessToken($token['access_token']);
            $service = new Google_Service_Oauth2($google);

            $data = $service->userinfo->get();

            $_SESSION['name'] = $data['name'];
            $_SESSION['src'] = $data['picture'];
            $_SESSION['email'] = $data['email'];
            $_SESSION['phone'] = $data['phone'] ?? null; // Check if 'phone' is set in the data
        }
    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
        exit;
    }
}

// Fetch user data from the database
$user = null;

if (isset($_SESSION["user_id"])) {
    $sql = "SELECT * FROM signup WHERE id = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $_SESSION["user_id"]);
    $stmt->execute();

    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
}

// Handle profile picture actions
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["submit"])) {
        include 'upload.php'; // Include the script to handle profile picture upload
    } elseif (isset($_POST["remove_profile"]) && !empty($user["image_url"])) {
        unlink($user["image_url"]); // Remove the profile picture from the server

        // Update the database to remove the profile picture URL
        $sql = "UPDATE signup SET image_url = NULL WHERE id = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("i", $_SESSION["user_id"]);
        $stmt->execute();

        // Refresh the user data to reflect changes
        $result = $mysqli->query("SELECT * FROM signup WHERE id = {$_SESSION["user_id"]}");
        $user = $result->fetch_assoc();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
    <style>
        .profile-pic {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
        }

        .upload-form {
            display: <?= empty($user['image_url']) ? 'block' : 'none' ?>;
        }
    </style>
</head>
<body>
    <!-- Your HTML content -->
    <h1>Welcome to the Dashboard</h1>
    <p>This is your personalized dashboard content.</p>

    <div class="container">
        <div class="card" >
            <img class="card-img-top profile-pic" src="<?= $_SESSION['src'] ?>" alt="Profile Image">

            <div class="card-body">
                <h5 class="card-title"><?= $_SESSION['name'] ?></h5>
                <span><?= $_SESSION['email'] ?></span><br>
                <p>The dashboard is currently under construction. Check back later for more features!</p>
                <a href="logout.php" class="btn btn-primary">Logout</a>
            </div>
        </div>
    </div>
    
</body>
</html>