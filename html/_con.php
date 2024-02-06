<?php
// Database credentials
$servername = "127.0.0.1:3306";
$username = "u535655634_database";
$password = "Letsgo@1579";
$dbname = "u535655634_database";

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $fname = $_POST["fname"];
    $lname = $_POST["lname"];
    $email = $_POST["email"];
     $work = $_POST["work"];
    $mobile = $_POST["mobile"];
    $city = $_POST["city"];

    // Insert data into the database
    $sql = "INSERT INTO Details (fname, lname, email, work,  mobile, city) 
            VALUES ('$fname', '$lname', '$email', '$work',  '$mobile', '$city')";

    if ($conn->query($sql) === TRUE) {
         echo "<script type='text/javascript'> 
                        alert('Thank You for Contact Us,We will reach u soon.'); 
                        window.location.href = '/index.html';
                      </script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Close the database connection
$conn->close();
?>
