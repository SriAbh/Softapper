



<?php
// Replace these values with your actual database credentials
$servername = "127.0.0.1:3306";
$username = "u535655634_database";
$password = "Letsgo@1579";
$dbname = "u535655634_database";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve user inputs
    $name = $_POST["name"];
    $email = $_POST["email"];
    $rating1 = $_POST["rate-1"];
    $rating2 = $_POST["rate-2"];
    $rating3 = $_POST["rate-3"];
    $rating4 = $_POST["rate-4"]; // replace with actual input names
    $experience1 = $_POST["experience1"];
    $experience2 = $_POST["experience2"];
    $experience3 = $_POST["experience3"];
    $experience4 = $_POST["experience4"]; // replace with actual input names

    // Insert data into the database
    $sql = "INSERT INTO feedback (name, email,rating1,rating2,rating3,rating4, experience1,experience2,experience3,experience4)
            VALUES ('$name', '$email','$rating1','$rating2','$rating3','$rating4', '$experience1','$experience2','$experience3','$experience4')";

    if ($conn->query($sql) === TRUE) {
        echo "<script type='text/javascript'> 
                        alert('Thank You for Your Feedback'); 
                        window.location.href = '/index.html';
                      </script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Close the database connection
$conn->close();
?>
