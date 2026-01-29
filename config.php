<?php
// Set PHP timezone to IST
date_default_timezone_set('Asia/Kolkata');

// Connect to MySQL
$conn = new mysqli("localhost", "root", "", "lost_found_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set MySQL timezone to IST
$conn->query("SET time_zone = '+05:30'");

session_start();
?>
