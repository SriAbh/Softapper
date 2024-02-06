
<?php
session_start();

$mysqli = require __DIR__ . "/config.php";

$user = null;

if (isset($_SESSION["user_id"])) {
    $sql = "SELECT * FROM signup WHERE id = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $_SESSION["user_id"]);
    $stmt->execute();

    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
}

// Handle profile picture upload
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
    include 'upload.php'; // Include the script to handle profile picture upload

    // Refresh user data to get updated information
    $result = $mysqli->query("SELECT * FROM signup WHERE id = {$_SESSION["user_id"]}");
    $user = $result->fetch_assoc();
}

// Handle profile picture removal
if (isset($_POST["remove_profile"]) && !empty($user["image_url"])) {
    // Remove the profile picture from the server
    unlink($user["image_url"]);

    // Update the database to remove the profile picture URL
    $sql = "UPDATE signup SET image_url = NULL WHERE id = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $_SESSION["user_id"]);
    $stmt->execute();

    // Refresh the user data to reflect changes
    $result = $mysqli->query("SELECT * FROM signup WHERE id = {$_SESSION["user_id"]}");
    $user = $result->fetch_assoc();
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
        /* Add some styles for the profile picture */
        .profile-pic {
            width: 150px; /* Adjust the size as needed */
            height: 150px;
            border-radius: 50%; /* Make it a circle */
            object-fit: cover; /* Prevent image distortion */
        }

        /* Hide the upload form if a profile picture exists */
        .upload-form {
            display: <?= empty($user['image_url']) ? 'block' : 'none' ?>;
        }
    </style>
</head>
<body>
    <h1>Welcome to the Dashboard, <?= isset($user) ? htmlspecialchars($user["name"]) : "Guest" ?>!</h1>

    <?php if (isset($user)): ?>
        <!-- Display dashboard content for authenticated users -->
        <p>This is your personalized dashboard content.</p>
        
        <!-- Display Profile Image -->
        <?php if (!empty($user['image_url'])): ?>
            <img src="<?= $user['image_url'] ?>" alt="Profile Image" class="profile-pic">
            <form action="" method="post">
                <input type="submit" name="remove_profile" value="Remove Profile">
            </form>
        <?php endif; ?>

        <!-- Display user data -->
        <p>Name: <?= htmlspecialchars($user["name"]) ?></p>
        <p>Email: <?= $user["email"] ?></p>
        <p>Mobile No: <?= $user["phone"] ?></p>
        <p>Date of Birth: <?= date('d-m-Y', strtotime($user["birthday"])) ?></p>

        <!-- Add more attributes as needed -->

        <!-- For now, display a message indicating the dashboard is under process -->
        <p>The dashboard is currently under construction. Check back later for more features!</p>

        <!-- Display the upload form only if a profile picture doesn't exist -->
        <div class="upload-form">
            <form action="upload.php" method="post" enctype="multipart/form-data">
                <input type="file" name="my_image">
                <input type="submit" name="submit" value="Upload Profile">
            </form>
        </div>

        <p><a href="login.html">Log out</a></p>
    <?php else: ?>
        <!-- Redirect to login if not logged in -->
        <p>Please <a href="login.html">log in</a> to access the dashboard.</p>
    <?php endif; ?>
</body>
</html>
