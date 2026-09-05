<?php
$servername = "localhost";  // or "localhost"
$username = "root";          // MySQL Workbench username
$password = "root";  // Workbench password
$dbname = "stemcelldb";      // Your database name
$port = 3307;               // Default MAMP port

$conn = new mysqli($servername, $username, $password, $dbname,$port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// echo "Connected successfully";
?>
