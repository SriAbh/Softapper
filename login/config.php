<!-- config.php -->
<?php

$host = "127.0.0.1:3306";
$username = "u535655634_database";
$password = "Letsgo@1579";
$database = "u535655634_database";

$mysqli = new mysqli($host, $username, $password, $database);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

return $mysqli;





