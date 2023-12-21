<?php

// Database configuration
$hostname = "localhost";   
$database = "commentfiltering"; 
$username = "root";          
$password = "";             

// Create a database connection
$conn = new mysqli($hostname, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8");

?>
