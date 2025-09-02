<?php
// includes/db_connect.php

error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = 'localhost'; // Your database host
$user = 'root'; // Your database username
$password = ''; // Your database password
$database = 'two_wheeler_rental'; // Your database name

// Create a new mysqli connection
$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// echo "Connected successfully"; // For testing, remove in production
?>
