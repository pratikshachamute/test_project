<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "vegwow";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if (mysqli_connect_errno()) {
    echo "Connection failed: " . mysqli_connect_error();
} 


