<?php
// LOCALHOST VERSION
$servname = "localhost";
$username = "root";
$password = "";
$dbname = "fantasy_db";
$socket = "/Applications/XAMPP/xamppfiles/var/mysql/mysql.sock"; // Ensure the socket path is correct

// Database connection
$conn = mysqli_connect($servname, $username, $password, $dbname, null, $socket);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
