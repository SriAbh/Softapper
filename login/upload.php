<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
    // Directory where uploaded images will be saved
    $target_dir = "uploads/";

    // Get the uploaded file information
    $target_file = $target_dir . basename($_FILES["my_image"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if the image file is a actual image or fake image
    if (isset($_POST["submit"])) {
        // Check if a file was uploaded
        if (!empty($_FILES["my_image"]["tmp_name"])) {
            $check = getimagesize($_FILES["my_image"]["tmp_name"]);
            if ($check !== false) {
                $uploadOk = 1;
            } else {
                $uploadOk = 0;
            }
        } else {
            echo '<script>alert("Please choose a file to upload."); window.location.href = "dashboard.php";</script>';
            exit();
        }
    }

    // Check if file already exists
    if (file_exists($target_file)) {
        echo '<script>alert("Sorry, file already exists."); window.location.href = "dashboard.php";</script>';
        exit();
    }

    // Check file size
    if ($_FILES["my_image"]["size"] > 500000) {
        echo '<script>alert("Sorry, your file is too large."); window.location.href = "dashboard.php";</script>';
        exit();
    }

    // Allow certain file formats
    $allowed_types = array("jpg", "jpeg", "png", "gif");
    if (!in_array($imageFileType, $allowed_types)) {
        echo '<script>alert("Sorry, only JPG, JPEG, PNG, and GIF files are allowed."); window.location.href = "dashboard.php";</script>';
        exit();
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo '<script>alert("Sorry, your file was not uploaded."); window.location.href = "dashboard.php";</script>';
        exit();
    } else {
        // Move the uploaded file to the specified directory
        if (move_uploaded_file($_FILES["my_image"]["tmp_name"], $target_file)) {
            // Update the user's profile image URL in the database
            $mysqli = require __DIR__ . "/config.php";
            $user_id = $_SESSION["user_id"];
            $image_url = $target_file;

            $sql = "UPDATE signup SET image_url = ? WHERE id = ?";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("si", $image_url, $user_id);
            $stmt->execute();
            
            //echo '<script>alert("The file ' . htmlspecialchars(basename($_FILES["my_image"]["name"])) . ' has been uploaded."); window.location.href = "dashboard.php";</script>';
            header("Location: dashboard.php");
            exit();
           
        } else {
            echo '<script>alert("Sorry, there was an error uploading your file."); window.location.href = "dashboard.php";</script>';
            exit();
        }
    }
}
?>
