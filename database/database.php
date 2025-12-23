<?php
$servername = "127.0.0.1";   // use 127.0.0.1 instead of localhost
$username   = "root";        // default XAMPP MySQL user
$password   = "";            // no password by default
$dbname     = "attapp_db";
$port       = 3306;          // check in XAMPP -> my.ini for your port number

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}


?>
